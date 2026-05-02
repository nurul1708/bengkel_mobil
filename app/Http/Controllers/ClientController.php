<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Testimonial;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ClientController extends Controller
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

    private function getFirstPaidTransactionForClient(int $clientId): ?Transaction
{
    return Transaction::with(['booking.service', 'testimonial'])
        // Specify transactions.status to avoid ambiguity
        ->where('transactions.status', 'paid') 
        ->whereHas('booking', function ($query) use ($clientId) {
            $query->where('user_id', $clientId);
        })
        ->whereHas('booking', function ($query) {
            // whereHas naturally scopes to the 'bookings' table, so this is fine,
            // but being explicit (bookings.status) is even safer.
            $query->whereIn('status', ['paid', 'completed']);
        })
        ->join('bookings', 'transactions.booking_id', '=', 'bookings.id')
        ->orderBy('bookings.booking_date')
        ->orderBy('bookings.booking_time')
        ->orderBy('transactions.id')
        ->select('transactions.*') // This ensures you only get Transaction model data
        ->first();
}

    private function formatMidtransNetworkError(Throwable $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'cURL error 28') || str_contains(strtolower($message), 'resolving timed out')) {
            return 'Koneksi ke server Midtrans timeout. Cek internet atau DNS perangkat/server Anda, lalu coba lagi.';
        }

        if (str_contains(strtolower($message), 'ssl') || str_contains(strtolower($message), 'certificate')) {
            return 'Koneksi aman ke Midtrans gagal. Periksa SSL/certificate atau jaringan Anda.';
        }

        return $message;
    }

    private function midtransIsConfigured(): bool
    {
        return filled(config('midtrans.server_key')) && filled(config('midtrans.client_key'));
    }

    private function getMidtransOrderId(Transaction $trx): string
    {
        return 'TRX-' . $trx->id . '-' . now()->format('YmdHis');
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

            Log::warning('Client Midtrans payment method fallback applied', [
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

    private function createMidtransSnapTransaction(Transaction $trx, $client): array
    {
        $orderId = $this->getMidtransOrderId($trx);

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $trx->grand_total,
            ],
            'customer_details' => [
                'first_name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
            ],
            'item_details' => $this->buildMidtransItemDetails($trx),
            'enabled_payments' => $this->getMidtransEnabledPayments(),
        ];

        try {
            $response = Http::withBasicAuth(config('midtrans.server_key'), '')
                ->timeout(30)
                ->connectTimeout(10)
                ->acceptJson()
                ->post(config('midtrans.snap_url') . '/transactions', $payload);
        } catch (Throwable $e) {
            Log::error('Midtrans snap network failed', [
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

            Log::error('Midtrans snap transaction failed', [
                'transaction_id' => $trx->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException($midtransMessage);
        }

        $responseBody = $response->json();
        $responseBody['order_id'] = $orderId;

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
            Log::warning('Failed fetching Midtrans QR image', [
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
            Log::error('Midtrans status network failed', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            throw new \RuntimeException($this->formatMidtransNetworkError($e));
        }

        if (!$response->successful()) {
            Log::error('Midtrans status check failed', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            abort(500, 'Gagal memeriksa status pembayaran Midtrans.');
        }

        return $response->json();
    }

    private function isMidtransSignatureValid(array $payload): bool
    {
        $signatureKey = $payload['signature_key'] ?? null;

        if (!$signatureKey) {
            return false;
        }

        $expected = hash(
            'sha512',
            ($payload['order_id'] ?? '')
            . ($payload['status_code'] ?? '')
            . ($payload['gross_amount'] ?? '')
            . config('midtrans.server_key')
        );

        return hash_equals($expected, $signatureKey);
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

    // TAMPIL PROFILE
    public function show()
    {
        $client = auth('client')->user();

        if (!$client) {
            return redirect()->route('client.loginForm');
        }

        return view('client.profile', [
            'data'  => $client,
            'title' => 'Profile',
        ]);
    }

    public function history()
    {
        $client = auth('client')->user();

        if (!$client) {
            return redirect()->route('client.loginForm');
        }

        return view('client.history', [
            'bookings' => Booking::with(['service', 'vehicle', 'transaction'])
                ->where('user_id', $client->id)
                ->latest('id')
                ->get(),
            'title' => 'Profile',
        ]);
    }

    public function transactions()
    {
        $client = auth('client')->user();

        if (!$client) {
            return redirect()->route('client.loginForm');
        }

        $eligibleTestimonialTransaction = $this->getFirstPaidTransactionForClient($client->id);
        $clientTestimonial = Testimonial::with(['reviewer'])
            ->where('user_id', $client->id)
            ->latest('id')
            ->first();

        return view('client.transactions', [
            'paymentBookings' => Booking::with([
                'service',
                'vehicle',
                'transaction.service',
                'transaction.transactionSpareparts.sparepart',
            ])
                ->where('user_id', $client->id)
                ->where('status', 'completed')
                ->latest('id')
                ->get(),
            'payments' => Payment::with(['transaction.booking', 'transaction.service'])
                ->whereHas('transaction.booking', function ($query) use ($client) {
                    $query->where('user_id', $client->id);
                })
                ->latest('id')
                ->get(),
            'eligibleTestimonialTransaction' => $eligibleTestimonialTransaction,
            'clientTestimonial' => $clientTestimonial,
            'title' => 'Profile',
        ]);
    }

    public function testimonialStore(Request $request, $id)
    {
        $client = auth('client')->user();

        if (!$client) {
            return redirect()->route('client.loginForm');
        }

        $eligibleTransaction = $this->getFirstPaidTransactionForClient($client->id);

        if (!$eligibleTransaction || (int) $eligibleTransaction->id !== (int) $id) {
            return redirect()->route('client.transactions.index')
                ->with('error', 'Testimonial hanya bisa dikirim untuk service pertama yang sudah lunas.');
        }

        if (Testimonial::where('transaction_id', $eligibleTransaction->id)->exists()) {
            return redirect()->route('client.transactions.index')
                ->with('error', 'Testimonial untuk service pertama Anda sudah pernah dikirim.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        Testimonial::create([
            'user_id' => $client->id,
            'booking_id' => $eligibleTransaction->booking_id,
            'transaction_id' => $eligibleTransaction->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => 'pending',
        ]);

        return redirect()->route('client.transactions.index')
            ->with('success', 'Rating dan komentar berhasil dikirim. Sekarang menunggu review admin.');
    }

    public function paymentShow($id)
    {
        $client = auth('client')->user();

        if (!$client) {
            return redirect()->route('client.loginForm');
        }

        $trx = Transaction::with(['booking.user', 'booking.vehicle', 'service', 'transactionSpareparts.sparepart'])
            ->whereHas('booking', function ($query) use ($client) {
                $query->where('user_id', $client->id);
            })
            ->findOrFail($id);

        $payment = Payment::where('transaction_id', $trx->id)
            ->latest('payment_date')
            ->latest('id')
            ->first();

        return view('client.payment', [
            'trx' => $trx,
            'payment' => $payment,
            'title' => 'Profile',
            'midtransEnabled' => $this->midtransIsConfigured(),
            'midtransClientKey' => config('midtrans.client_key'),
            'midtransSnapJsUrl' => config('midtrans.snap_js_url'),
        ]);
    }

    public function paymentInvoice($id)
    {
        $client = auth('client')->user();

        if (!$client) {
            return redirect()->route('client.loginForm');
        }

        $trx = Transaction::with([
            'booking.user',
            'booking.vehicle',
            'service',
            'mekanik',
            'kasir',
            'transactionSpareparts.sparepart',
        ])
            ->whereHas('booking', function ($query) use ($client) {
                $query->where('user_id', $client->id);
            })
            ->findOrFail($id);

        if ($trx->status !== 'paid') {
            return redirect()->route('client.payment.show', $trx->id)
                ->with('error', 'Invoice hanya tersedia untuk transaksi yang sudah lunas.');
        }

        $payment = Payment::where('transaction_id', $trx->id)
            ->latest('payment_date')
            ->latest('id')
            ->first();

        return view('client.invoice', [
            'trx' => $trx,
            'payment' => $payment,
            'title' => 'Profile',
        ]);
    }

public function paymentMidtransSnap($id)
{
    $client = auth('client')->user();
    if (!$client) {
        return response()->json(['message' => 'Silakan login kembali'], 401);
    }

    if (!$this->midtransIsConfigured()) {
        return response()->json(['message' => 'Midtrans belum dikonfigurasi di file .env.'], 422);
    }

    $trx = Transaction::with(['service', 'transactionSpareparts.sparepart'])
        ->whereHas('booking', function ($query) use ($client) {
            $query->where('user_id', $client->id);
        })
        ->findOrFail($id);

    try {
        $transaction = $this->createMidtransSnapTransaction($trx, $client);

        return response()->json([
            'order_id' => $transaction['order_id'] ?? null,
            'snap_token' => $transaction['token'] ?? null,
            'redirect_url' => $transaction['redirect_url'] ?? null,
            'message' => 'Checkout Midtrans berhasil dibuat.',
        ]);
    } catch (Throwable $e) {
        return response()->json(['message' => $e->getMessage()], 422);
    }
}

    public function paymentMidtransCheck(Request $request, $id)
    {
        $client = auth('client')->user();

        if (!$client) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $trx = Transaction::with('booking')
            ->whereHas('booking', function ($query) use ($client) {
                $query->where('user_id', $client->id);
            })
            ->findOrFail($id);

        $validated = $request->validate([
            'order_id' => 'required|string',
        ]);

        if (!str_starts_with($validated['order_id'], 'TRX-' . $trx->id . '-')) {
            return response()->json(['message' => 'Order Midtrans tidak cocok.'], 422);
        }

        $payload = $this->getMidtransTransactionStatus($validated['order_id']);
        $this->syncMidtransPayment($trx, $payload);

        return response()->json([
            'message' => 'Status pembayaran diperbarui.',
            'status' => $trx->fresh()->status,
            'transaction_status' => $payload['transaction_status'] ?? null,
        ]);
    }

    public function midtransNotification(Request $request)
    {
        $payload = $request->all();

        if (!$this->isMidtransSignatureValid($payload)) {
            Log::warning('Midtrans notification signature invalid', $payload);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        if (!preg_match('/^(?:ADM-)?TRX-(\d+)-/', $payload['order_id'] ?? '', $matches)) {
            Log::warning('Midtrans notification order id not recognized', [
                'order_id' => $payload['order_id'] ?? null,
            ]);

            return response()->json(['message' => 'Order ID tidak dikenali'], 422);
        }

        $trx = Transaction::with('booking')->find($matches[1]);

        if (!$trx) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $this->syncMidtransPayment($trx, $payload);

        return response()->json(['message' => 'Notification processed']);
    }

    public function paymentStore(Request $request, $id)
    {
        $client = auth('client')->user();

        if (!$client) {
            return redirect()->route('client.loginForm');
        }

        $trx = Transaction::with('booking')
            ->whereHas('booking', function ($query) use ($client) {
                $query->where('user_id', $client->id);
            })
            ->findOrFail($id);

        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,transfer,midtrans',
        ]);

        if ($request->payment_method === 'midtrans') {
            return redirect()->route('client.payment.show', $trx->id)
                ->with('error', 'Pembayaran Midtrans diproses melalui popup checkout. Silakan gunakan tombol Bayar dengan Midtrans.');
        }

        $paymentStatus = 'unpaid';
        $transactionStatus = 'pending';
        if ($request->amount_paid >= $trx->grand_total) {
            $paymentStatus = 'paid';
            $transactionStatus = 'paid';
        } elseif ($request->amount_paid > 0) {
            $paymentStatus = 'partial';
            $transactionStatus = 'partial';
        }

        Payment::create([
            'transaction_id' => $trx->id,
            'payment_date' => now(),
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'payment_status' => $paymentStatus,
        ]);

        if ($transactionStatus === 'paid') {
            $trx->booking->update([
                'status' => 'paid',
            ]);
        }

        $trx->update([
            'status' => $transactionStatus,
        ]);

        return redirect()->route('client.transactions.index')
            ->with('success', 'Pembayaran berhasil dikirim.');
    }

    // UPDATE PROFILE
    public function update(Request $request)
    {
        $client = auth('client')->user();

        if (!$client) {
            return redirect()->route('client.loginForm');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $client->id,
            'password' => 'nullable|min:6',
            'retype_password' => 'same:password',
            'phone' => 'required|max:15',
            'address' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // update data utama
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // update password
        if ($request->filled('password')) {
            $client->update([
                'password' => bcrypt($request->password)
            ]);
        }

        // upload foto
        if ($request->hasFile('photo')) {
            if ($client->photo) {
                Storage::disk('public')->delete($client->photo);
            }

            $client->update([
                'photo' => $request->file('photo')->store('photo_clients', 'public')
            ]);
        }

        return redirect()->route('client.profile.show')
            ->with('success', 'Profile berhasil diupdate!');
    }
}
