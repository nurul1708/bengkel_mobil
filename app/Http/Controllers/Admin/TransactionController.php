<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ClientTransactionReadyMail;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\Transaction;
use App\Models\TransactionSparepart;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class TransactionController extends Controller
{
    private function getMidtransEnabledPayments(): array
    {
        return [
            'qris',
            'gopay',
            'shopeepay',
            'bca_va',
            'bni_va',
            'bri_va',
            'permata_va',
            'other_va',
            'echannel',
            'credit_card',
            'cstore',
            'akulaku',
        ];
    }

    private function buildMidtransItemDetails(Transaction $trx): array
    {
        $itemDetails = [[
            'id' => 'SERVICE-' . ($trx->service_id ?? $trx->id),
            'price' => (int) $trx->total_service,
            'quantity' => 1,
            'name' => Str::limit('Jasa ' . ($trx->service->service_name ?? 'Service'), 50, ''),
        ]];

        foreach (($trx->items ?? []) as $index => $item) {
            $itemDetails[] = [
                'id' => 'SP-' . ($item['sparepart_id'] ?? $index + 1),
                'price' => (int) ($item['harga_beli'] ?? 0),
                'quantity' => (int) ($item['jumlah_beli'] ?? 0),
                'name' => Str::limit($item['sparepart_name'] ?? ('Sparepart ' . ($index + 1)), 50, ''),
            ];
        }

        return $itemDetails;
    }

    private function resolveMidtransPaymentMethod(array $payload): string
    {
        $paymentType = $payload['payment_type'] ?? 'midtrans';

        if ($paymentType === 'bank_transfer') {
            $bank = strtolower($payload['va_numbers'][0]['bank'] ?? '');

            if ($bank !== '') {
                return $bank . '_va';
            }

            return !empty($payload['permata_va_number']) ? 'permata_va' : 'other_va';
        }

        if ($paymentType === 'echannel') {
            return 'mandiri_bill';
        }

        if ($paymentType === 'cstore') {
            return strtolower($payload['store'] ?? 'cstore');
        }

        return strtolower($paymentType);
    }

    private function sendTransactionReadyEmail(Transaction $transaction): void
    {
        $transaction->loadMissing(['booking.user', 'service']);

        $client = $transaction->booking?->user;
        $bookingStatus = $transaction->booking?->status;

        if (!$client?->email || !in_array($bookingStatus, ['completed', 'paid'], true)) {
            return;
        }

        try {
            Mail::to($client->email)->send(new ClientTransactionReadyMail($transaction));
        } catch (Throwable $e) {
            Log::error('Failed sending transaction ready email', [
                'transaction_id' => $transaction->id,
                'booking_id' => $transaction->booking_id,
                'email' => $client->email,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function midtransIsConfigured(): bool
    {
        return filled(config('midtrans.server_key')) && filled(config('midtrans.client_key'));
    }

    private function formatMidtransNetworkError(Throwable $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'cURL error 28') || str_contains(strtolower($message), 'resolving timed out')) {
            return 'Koneksi ke server Midtrans timeout. Cek internet atau DNS lalu coba lagi.';
        }

        if (str_contains(strtolower($message), 'ssl') || str_contains(strtolower($message), 'certificate')) {
            return 'Koneksi aman ke Midtrans gagal. Periksa SSL/certificate atau jaringan Anda.';
        }

        return $message;
    }

    private function getMidtransOrderId(Transaction $trx): string
    {
        return 'ADM-TRX-' . $trx->id . '-' . now()->format('YmdHis');
    }

    private function mapMidtransMethodForLegacySchema(string $paymentMethod): string
    {
        return $paymentMethod === 'qris' ? 'qris' : 'transfer';
    }

    private function persistMidtransPayment(Transaction $trx, array $payload, bool $isPaid, string $paymentMethod): void
    {
        try {
            $payment = Payment::firstOrNew([
                'transaction_id' => $trx->id,
                'payment_method' => $paymentMethod,
            ]);
            $payment->payment_date = now()->toDateString();
            $payment->amount_paid = (float) ($payload['gross_amount'] ?? $trx->grand_total);
            $payment->payment_status = $isPaid ? 'paid' : 'unpaid';
            $payment->save();
        } catch (QueryException $e) {
            if (!str_contains(strtolower($e->getMessage()), 'payment_method')) {
                throw $e;
            }

            $fallbackMethod = $this->mapMidtransMethodForLegacySchema($paymentMethod);

            Log::warning('Admin Midtrans payment method fallback applied', [
                'transaction_id' => $trx->id,
                'original_method' => $paymentMethod,
                'fallback_method' => $fallbackMethod,
                'message' => $e->getMessage(),
            ]);

            $payment = Payment::firstOrNew([
                'transaction_id' => $trx->id,
                'payment_method' => $fallbackMethod,
            ]);
            $payment->payment_date = now()->toDateString();
            $payment->amount_paid = (float) ($payload['gross_amount'] ?? $trx->grand_total);
            $payment->payment_status = $isPaid ? 'paid' : 'unpaid';
            $payment->save();
        }
    }

    private function createMidtransSnapTransaction(Transaction $trx): array
    {
        $orderId = $this->getMidtransOrderId($trx);
        $customer = $trx->booking?->user;

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $trx->grand_total,
            ],
            'customer_details' => [
                'first_name' => $customer->name ?? 'Customer',
                'email' => $customer->email ?? 'customer@example.com',
                'phone' => $customer->phone ?? '',
            ],
            'item_details' => $this->buildMidtransItemDetails($trx),
            'enabled_payments' => $this->getMidtransEnabledPayments(),
        ];

        Log::info('Admin Midtrans snap request', [
            'transaction_id' => $trx->id,
            'order_id' => $orderId,
            'gross_amount' => (int) $trx->grand_total,
            'item_count' => count($payload['item_details']),
            'payload' => $payload,
        ]);

        try {
            $response = Http::withBasicAuth(config('midtrans.server_key'), '')
                ->timeout(30)
                ->connectTimeout(10)
                ->acceptJson()
                ->post(config('midtrans.snap_url') . '/transactions', $payload);
        } catch (Throwable $e) {
            Log::error('Admin Midtrans snap network failed', [
                'transaction_id' => $trx->id,
                'message' => $e->getMessage(),
            ]);

            throw new \RuntimeException($this->formatMidtransNetworkError($e));
        }

        if (!$response->successful()) {
            $responseBody = $response->json();
            $midtransMessage = $responseBody['status_message']
                ?? ($responseBody['error_messages'][0] ?? null)
                ?? 'Gagal membuat transaksi Midtrans.';

            Log::error('Admin Midtrans snap transaction failed', [
                'transaction_id' => $trx->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException($midtransMessage);
        }

        $responseBody = $response->json();
        $responseBody['order_id'] = $orderId;

        Log::info('Admin Midtrans snap response', [
            'transaction_id' => $trx->id,
            'order_id' => $orderId,
            'status' => $response->status(),
            'response' => $responseBody,
        ]);

        return $responseBody;
    }

    private function fetchMidtransQrImageDataUrl(?string $qrUrl): ?string
    {
        if (!$qrUrl) {
            return null;
        }

        try {
            $response = Http::withBasicAuth(config('midtrans.server_key'), '')
                ->timeout(20)
                ->get($qrUrl);

            if (!$response->successful()) {
                return null;
            }

            $body = $response->body();
            if (!$body) {
                return null;
            }

            return 'data:' . ($response->header('Content-Type', 'image/png')) . ';base64,' . base64_encode($body);
        } catch (Throwable $e) {
            Log::warning('Admin failed fetching Midtrans QR image', [
                'url' => $qrUrl,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function getMidtransTransactionStatus(string $orderId): array
    {
        try {
            $response = Http::withBasicAuth(config('midtrans.server_key'), '')
                ->timeout(20)
                ->acceptJson()
                ->get(config('midtrans.api_url') . '/v2/' . $orderId . '/status');
        } catch (Throwable $e) {
            Log::error('Admin Midtrans status network failed', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            throw new \RuntimeException($this->formatMidtransNetworkError($e));
        }

        if (!$response->successful()) {
            Log::error('Admin Midtrans status check failed', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Gagal memeriksa status pembayaran Midtrans.');
        }

        return $response->json();
    }

    private function syncMidtransPayment(Transaction $trx, array $payload): void
    {
        $transactionStatus = $payload['transaction_status'] ?? 'pending';
        $fraudStatus = $payload['fraud_status'] ?? null;
        $isPaid = $transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && $fraudStatus === 'accept');
        $paymentMethod = $this->resolveMidtransPaymentMethod($payload);

        $this->persistMidtransPayment($trx, $payload, $isPaid, $paymentMethod);

        if ($isPaid) {
            $trx->update(['status' => 'paid']);
            $trx->booking?->update(['status' => 'paid']);
            return;
        }

        $trx->update(['status' => 'pending']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $payments = Payment::with(['transaction.booking.user', 'transaction.service'])
            ->latest('id')
            ->get();
        $search = trim((string) $request->query('search'));

        $transaksi = Transaction::with(['booking.user', 'booking.vehicle', 'booking.service', 'service', 'payments'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('id', 'like', '%' . $search . '%')
                    ->orWhereHas('booking', function ($bookingQuery) use ($search) {
                        $bookingQuery->where('id', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', '%' . $search . '%');
                            })
                            ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                                $vehicleQuery->where('brand', 'like', '%' . $search . '%')
                                    ->orWhere('model', 'like', '%' . $search . '%')
                                    ->orWhere('license_plate', 'like', '%' . $search . '%');
                            });
                    })
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('service_name', 'like', '%' . $search . '%');
                    });
            })
            ->latest('id')
            ->get();

        return view('transaksi.index', compact('transaksi', 'search'), [
            'payments' => $payments,
            'title' => 'Transaksi'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
$bookings = Booking::with('user', 'vehicle', 'service', 'mekanik')->get();
        $transaksi = Transaction::with('booking')->get();
        $customer = User::with('vehicle')->where('role', 'customer')->get();
        $userkasir = User::where('role', 'kasir')->get();
        $usermekanik = User::where('role', 'mekanik')->get();
        $services = Service::all();
        $spareparts = Sparepart::all();

$selectedBooking = null;
        if ($request->has('booking_id')) {
            $selectedBooking = Booking::with('user', 'vehicle', 'service', 'mekanik')->find($request->booking_id);
        }

        return view('transaksi.create', compact('transaksi', 'customer', 'userkasir', 'usermekanik', 'services', 'spareparts', 'bookings', 'selectedBooking'), [
            'title' => 'Transaksi'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'id_service' => 'required|exists:services,id',
            'id_mekanik' => 'nullable|exists:users,id',
            'id_kasir' => 'nullable|exists:users,id',
            'total_service' => 'required|numeric',
            'total_sparepart' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'items' => 'nullable|array',
            'items.*.sparepart_id' => 'nullable|exists:spareparts,id',
            'items.*.harga_beli' => 'nullable|numeric|min:0',
            'items.*.jumlah_beli' => 'nullable|integer|min:1',
        ]);

        try {
            $t = DB::transaction(function () use ($request) {
                $items = collect($request->input('items', []))
                    ->filter(fn ($item) => !empty($item['sparepart_id']))
                    ->map(function ($item) {
                    $sparepart = Sparepart::lockForUpdate()->findOrFail($item['sparepart_id']);
                    $qty = (int) $item['jumlah_beli'];

                    if ($sparepart->stock < $qty) {
                        throw new \RuntimeException(
                            'Stok sparepart "' . $sparepart->name . '" tidak cukup. Sisa stok: ' . $sparepart->stock . '.'
                        );
                    }

                    $sparepart->decrement('stock', $qty);

                    return [
                        'sparepart_id' => $sparepart->id,
                        'sparepart_name' => $sparepart->name,
                        'harga_beli' => $item['harga_beli'],
                        'jumlah_beli' => $qty,
                        'subtotal' => $item['harga_beli'] * $qty,
                    ];
                })->values()->toArray();

                $transaction = Transaction::create([
                    'booking_id' => $request->booking_id,
                    'service_id' => $request->id_service,
                    'mekanik_id' => $request->id_mekanik,
                    'kasir_id' => $request->id_kasir,
                    'status' => 'pending',
                    'total_service' => $request->total_service,
                    'total_sparepart' => $request->total_sparepart,
                    'grand_total' => $request->grand_total,
                    'items' => $items,
                ]);

                foreach ($items as $item) {
                    TransactionSparepart::create([
                        'transaction_id' => $transaction->id,
                        'sparepart_id' => $item['sparepart_id'],
                        'qty' => $item['jumlah_beli'],
                        'price' => $item['harga_beli'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }

                return $transaction;
            });
        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors(['items' => $e->getMessage()]);
        }

        $this->sendTransactionReadyEmail($t);

        return redirect('/admin/transaksi/' . $t->id)
            ->with('success', 'Transaksi berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trx = Transaction::with(['booking.user', 'booking.vehicle', 'service', 'mekanik', 'kasir', 'transactionSpareparts.sparepart'])->findOrFail($id);
        return view('transaksi.show', compact('trx'), [
            'title' => 'Transaksi',
            'midtransEnabled' => $this->midtransIsConfigured(),
            'midtransClientKey' => config('midtrans.client_key'),
            'midtransSnapJsUrl' => config('midtrans.snap_js_url'),
        ]);
    }

    public function invoice($id)
    {
        $trx = Transaction::with([
            'booking.user',
            'booking.vehicle',
            'service',
            'mekanik',
            'kasir',
            'transactionSpareparts.sparepart',
        ])->findOrFail($id);

        if ($trx->status !== 'paid') {
            return redirect('/admin/transaksi/' . $trx->id)
                ->with('error', 'Invoice hanya tersedia untuk transaksi yang sudah lunas.');
        }

        $payment = Payment::where('transaction_id', $trx->id)
            ->latest('payment_date')
            ->latest('id')
            ->first();

        return view('transaksi.invoice', [
            'trx' => $trx,
            'payment' => $payment,
            'title' => 'Transaksi',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function bayar(Request $request, $id)
    {
        $trx = Transaction::findOrFail($id);

        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,midtrans',
        ]);

        if ($request->payment_method === 'midtrans') {
            return redirect('/admin/transaksi/' . $trx->id)
                ->with('error', 'Pembayaran Midtrans admin diproses melalui popup checkout. Silakan gunakan tombol checkout Midtrans.');
        }

        // Determine payment status
        $paymentStatus = 'unpaid';
        $transactionStatus = 'pending';
        if ($request->amount_paid >= $trx->grand_total) {
            $paymentStatus = 'paid';
            $transactionStatus = 'paid';
        } elseif ($request->amount_paid > 0) {
            $paymentStatus = 'partial';
            $transactionStatus = 'partial';
        }

        // Create payment record
        Payment::create([
            'transaction_id' => $trx->id,
            'payment_date' => now(),
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'payment_status' => $paymentStatus
        ]);

        // If fully paid, mark booking as paid
        if ($transactionStatus == 'paid') {
            $booking = Booking::find($trx->booking_id);
            $booking->update([
                'status' => 'paid'
            ]);
        }

        // Update transaction status
        $trx->update([
            'status' => $transactionStatus
        ]);

        if ($transactionStatus === 'paid') {
            return redirect('/admin/transaksi/' . $trx->id . '/invoice')
                ->with('success', 'Pembayaran berhasil dan invoice sudah dibuat.');
        }

        return redirect('/admin/transaksi/' . $trx->id)
            ->with('success', 'Pembayaran berhasil!');
    }

    public function midtransSnap($id)
    {
        $trx = Transaction::with(['booking.user', 'service', 'transactionSpareparts.sparepart'])->findOrFail($id);

        if (!$this->midtransIsConfigured()) {
            return response()->json(['message' => 'Midtrans belum dikonfigurasi di file .env.'], 422);
        }

        try {
            $transaction = $this->createMidtransSnapTransaction($trx);

            Log::info('Admin Midtrans snap parsed response', [
                'transaction_id' => $trx->id,
                'order_id' => $transaction['order_id'] ?? null,
                'redirect_url' => $transaction['redirect_url'] ?? null,
            ]);

            return response()->json([
                'order_id' => $transaction['order_id'] ?? null,
                'snap_token' => $transaction['token'] ?? null,
                'redirect_url' => $transaction['redirect_url'] ?? null,
                'message' => 'Checkout Midtrans berhasil dibuat.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function midtransCheck(Request $request, $id)
    {
        $trx = Transaction::with('booking')->findOrFail($id);

        $validated = $request->validate([
            'order_id' => 'required|string',
        ]);

        if (!str_starts_with($validated['order_id'], 'ADM-TRX-' . $trx->id . '-')) {
            return response()->json(['message' => 'Order Midtrans tidak cocok.'], 422);
        }

        $payload = $this->getMidtransTransactionStatus($validated['order_id']);

        Log::info('Admin Midtrans status payload', [
            'transaction_id' => $trx->id,
            'order_id' => $validated['order_id'],
            'payload' => $payload,
        ]);

        $this->syncMidtransPayment($trx, $payload);

        return response()->json([
            'message' => 'Status pembayaran diperbarui.',
            'status' => $trx->fresh()->status,
            'transaction_status' => $payload['transaction_status'] ?? null,
        ]);
    }
}
