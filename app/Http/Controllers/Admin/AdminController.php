<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Sparepart;
use App\Models\SparepartPurchase;
use App\Models\Transaction;
use App\Models\TransactionSparepart;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private function resolveTrafficRange(Request $request): array
    {
        $groupBy = $request->query('group_by', 'day');

        if (!in_array($groupBy, ['day', 'week', 'month'], true)) {
            $groupBy = 'day';
        }

        try {
            $start = CarbonImmutable::parse($request->query('start_date', now()->subDays(29)->toDateString()))->startOfDay();
        } catch (\Throwable $e) {
            $start = CarbonImmutable::now()->subDays(29)->startOfDay();
        }

        try {
            $end = CarbonImmutable::parse($request->query('end_date', now()->toDateString()))->endOfDay();
        } catch (\Throwable $e) {
            $end = CarbonImmutable::now()->endOfDay();
        }

        if ($start->greaterThan($end)) {
            [$start, $end] = [$end->startOfDay(), $start->endOfDay()];
        }

        $source = $request->query('traffic_source', 'both');
        if (!in_array($source, ['booking', 'vehicle', 'both'], true)) {
            $source = 'both';
        }

        return [
            'groupBy' => $groupBy,
            'source' => $source,
            'start' => $start,
            'end' => $end,
            'startDate' => $start->toDateString(),
            'endDate' => $end->toDateString(),
            'label' => $start->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y'),
        ];
    }

    private function buildTrafficBuckets(CarbonImmutable $start, CarbonImmutable $end, string $groupBy): array
    {
        $cursor = match ($groupBy) {
            'month' => $start->startOfMonth(),
            'week' => $start->startOfWeek(),
            default => $start->startOfDay(),
        };

        $limit = match ($groupBy) {
            'month' => $end->startOfMonth(),
            'week' => $end->startOfWeek(),
            default => $end->startOfDay(),
        };

        $buckets = [];

        while ($cursor->lessThanOrEqualTo($limit)) {
            $key = match ($groupBy) {
                'month' => $cursor->format('Y-m'),
                'week' => $cursor->format('o-\WW'),
                default => $cursor->toDateString(),
            };

            $label = match ($groupBy) {
                'month' => $cursor->translatedFormat('M Y'),
                'week' => $cursor->translatedFormat('d M') . ' - ' . $cursor->endOfWeek()->translatedFormat('d M'),
                default => $cursor->translatedFormat('d M'),
            };

            $buckets[$key] = [
                'key' => $key,
                'label' => $label,
                'period_start' => $cursor,
                'booking_total' => 0,
                'vehicle_total' => 0,
            ];

            $cursor = match ($groupBy) {
                'month' => $cursor->addMonth(),
                'week' => $cursor->addWeek(),
                default => $cursor->addDay(),
            };
        }

        return $buckets;
    }

    private function resolveTrafficBucketKey(CarbonImmutable $date, string $groupBy): string
    {
        return match ($groupBy) {
            'month' => $date->startOfMonth()->format('Y-m'),
            'week' => $date->startOfWeek()->format('o-\WW'),
            default => $date->toDateString(),
        };
    }

    private function buildTrafficData(Request $request): array
    {
        $range = $this->resolveTrafficRange($request);
        $groupBy = $range['groupBy'];

        $bookings = Booking::query()
            ->select('id', 'vehicle_id', 'booking_date', 'created_at')
            ->whereBetween('booking_date', [$range['start']->toDateString(), $range['end']->toDateString()])
            ->orderBy('booking_date')
            ->get();

        $buckets = $this->buildTrafficBuckets($range['start'], $range['end'], $groupBy);

        foreach ($bookings as $booking) {
            $date = CarbonImmutable::parse($booking->booking_date);
            $bucketKey = $this->resolveTrafficBucketKey($date, $groupBy);

            if (!isset($buckets[$bucketKey])) {
                continue;
            }

            $buckets[$bucketKey]['booking_total']++;

            if ($booking->vehicle_id) {
                $buckets[$bucketKey]['vehicle_total']++;
            }
        }

        $rows = collect($buckets)
            ->map(function ($bucket) {
                return [
                    'label' => $bucket['label'],
                    'period_start' => $bucket['period_start'],
                    'booking_total' => $bucket['booking_total'],
                    'vehicle_total' => $bucket['vehicle_total'],
                ];
            })
            ->values();

        $summary = [
            'total_booking_traffic' => $rows->sum('booking_total'),
            'total_vehicle_traffic' => $rows->sum('vehicle_total'),
            'peak_booking_traffic' => (int) $rows->max('booking_total'),
            'peak_vehicle_traffic' => (int) $rows->max('vehicle_total'),
            'average_booking_traffic' => round((float) $rows->avg('booking_total'), 2),
            'average_vehicle_traffic' => round((float) $rows->avg('vehicle_total'), 2),
        ];

        return [
            'trafficGroupBy' => $groupBy,
            'trafficSource' => $range['source'],
            'trafficStartDate' => $range['startDate'],
            'trafficEndDate' => $range['endDate'],
            'trafficPeriodLabel' => $range['label'],
            'trafficRows' => $rows,
            'trafficSummary' => $summary,
            'trafficChartLabels' => $rows->pluck('label')->values(),
            'trafficBookingSeries' => $rows->pluck('booking_total')->map(fn ($value) => (int) $value)->values(),
            'trafficVehicleSeries' => $rows->pluck('vehicle_total')->map(fn ($value) => (int) $value)->values(),
        ];
    }

    private function buildTrafficReportData(Request $request): array
    {
        $groupBy = $request->query('traffic_group_by', 'day');

        if (!in_array($groupBy, ['day', 'week', 'month'], true)) {
            $groupBy = 'day';
        }

        $trafficRequest = new Request([
            'start_date' => $request->query('from_date', now()->subDays(29)->toDateString()),
            'end_date' => $request->query('to_date', now()->toDateString()),
            'traffic_source' => 'both',
        ]);

        $range = $this->resolveTrafficRange($trafficRequest);

        $traffic = $this->buildTrafficData(new Request([
            'group_by' => $groupBy,
            'traffic_source' => 'both',
            'start_date' => $range['startDate'],
            'end_date' => $range['endDate'],
        ]));

        $daily = $this->buildTrafficData(new Request([
            'group_by' => 'day',
            'traffic_source' => 'both',
            'start_date' => $range['startDate'],
            'end_date' => $range['endDate'],
        ]));

        $trafficRows = $traffic['trafficRows']
            ->filter(fn ($row) => $row['booking_total'] > 0 || $row['vehicle_total'] > 0)
            ->values();

        return [
            'trafficFromDate' => $range['startDate'],
            'trafficToDate' => $range['endDate'],
            'trafficPeriodLabel' => $range['label'],
            'trafficReportGroupBy' => $groupBy,
            'trafficReportRows' => $trafficRows,
            'trafficReportChartLabels' => $traffic['trafficChartLabels'],
            'trafficReportBookingSeries' => $traffic['trafficBookingSeries'],
            'trafficReportVehicleSeries' => $traffic['trafficVehicleSeries'],
            'trafficReportSummary' => [
                'total_booking_traffic' => $daily['trafficSummary']['total_booking_traffic'],
                'total_vehicle_traffic' => $daily['trafficSummary']['total_vehicle_traffic'],
                'peak_daily_booking_traffic' => $daily['trafficSummary']['peak_booking_traffic'],
                'peak_daily_vehicle_traffic' => $daily['trafficSummary']['peak_vehicle_traffic'],
                'average_daily_booking_traffic' => $daily['trafficSummary']['average_booking_traffic'],
                'average_daily_vehicle_traffic' => $daily['trafficSummary']['average_vehicle_traffic'],
            ],
        ];
    }

    private function applyReportPeriod(Request $request): Request
    {
        return tap($request, function (Request $request) {
            $periodType = $request->query('traffic_group_by', $request->query('period_type', 'day'));

            if (!in_array($periodType, ['day', 'week', 'month'], true)) {
                $periodType = 'day';
            }

            $request->merge([
                'period_type' => $periodType,
                'traffic_group_by' => $periodType,
                'from_date' => $request->query('from_date', now()->subDays(29)->toDateString()),
                'to_date' => $request->query('to_date', now()->toDateString()),
                'date' => $request->query('date', now()->toDateString()),
                'week' => $request->query('week', now()->format('o-\WW')),
                'month' => $request->query('month', now()->format('Y-m')),
            ]);
        });
    }

    private function buildLaporanData(Request $request): array
    {
        $request = $this->applyReportPeriod($request);
        $period = $this->resolveReportPeriod($request);
        $start = $period['start'];
        $end = $period['end'];
        $trafficData = $this->buildTrafficReportData($request);

        $transactions = Transaction::with([
                'booking.user',
                'booking.vehicle',
                'service',
                'payments',
                'transactionSpareparts.sparepart',
            ])
            ->whereBetween('created_at', [$start, $end])
            ->latest('id')
            ->get();

        $payments = Payment::with(['transaction.booking.user'])
            ->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
            ->latest('id')
            ->get();

        $serviceReports = Transaction::query()
            ->join('services', 'transactions.service_id', '=', 'services.id')
            ->whereBetween('transactions.created_at', [$start, $end])
            ->groupBy('services.id', 'services.service_name')
            ->select(
                'services.service_name',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('SUM(transactions.total_service) as total_pendapatan')
            )
            ->orderByDesc('total_transaksi')
            ->get();

        $sparepartReports = TransactionSparepart::query()
            ->join('transactions', 'transaction_spareparts.transaction_id', '=', 'transactions.id')
            ->join('spareparts', 'transaction_spareparts.sparepart_id', '=', 'spareparts.id')
            ->whereBetween('transactions.created_at', [$start, $end])
            ->groupBy('spareparts.id', 'spareparts.name', 'spareparts.brand')
            ->select(
                'spareparts.name',
                'spareparts.brand',
                DB::raw('SUM(transaction_spareparts.qty) as total_qty'),
                DB::raw('SUM(transaction_spareparts.subtotal) as total_penjualan')
            )
            ->orderByDesc('total_qty')
            ->get();

        $customers = Transaction::query()
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->whereBetween('transactions.created_at', [$start, $end])
            ->groupBy('users.id', 'users.name', 'users.email', 'users.phone')
            ->select(
                'users.name',
                'users.email',
                'users.phone',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('SUM(transactions.grand_total) as total_belanja')
            )
            ->orderByDesc('total_belanja')
            ->get();

        $vehicleReports = Booking::with(['user', 'vehicle', 'service', 'transaction'])
            ->whereBetween('booking_date', [$start->toDateString(), $end->toDateString()])
            ->latest('booking_date')
            ->latest('id')
            ->get();

        $bookingReports = Booking::with(['user', 'vehicle', 'service', 'transaction'])
            ->whereBetween('booking_date', [$start->toDateString(), $end->toDateString()])
            ->latest('booking_date')
            ->latest('id')
            ->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_invoice_value' => (float) $transactions->sum('grand_total'),
            'total_paid_amount' => (float) $payments->sum('amount_paid'),
            'total_service_revenue' => (float) $transactions->sum('total_service'),
            'total_sparepart_revenue' => (float) $transactions->sum('total_sparepart'),
            'total_customers' => $customers->count(),
            'total_vehicles' => $vehicleReports->pluck('vehicle_id')->filter()->unique()->count(),
            'total_bookings' => $bookingReports->count(),
            'total_payments' => $payments->count(),
            'total_paid_transactions' => $transactions->where('status', 'paid')->count(),
            'total_partial_transactions' => $transactions->where('status', 'partial')->count(),
            'total_unpaid_transactions' => $transactions->filter(function ($transaction) {
                return blank($transaction->status) || in_array($transaction->status, ['pending', 'unpaid'], true);
            })->count(),
        ];

        $summary['total_outstanding'] = max(
            0,
            (float) $summary['total_invoice_value'] - (float) $summary['total_paid_amount']
        );

        $bookingStatusSummary = $bookingReports
            ->groupBy('status')
            ->map(fn ($items, $status) => [
                'status' => $status,
                'label' => ucfirst(str_replace('_', ' ', $status ?: 'pending')),
                'total' => $items->count(),
            ])
            ->sortByDesc('total')
            ->values();

        return [
            'transactions' => $transactions,
            'payments' => $payments,
            'serviceReports' => $serviceReports,
            'sparepartReports' => $sparepartReports,
            'customers' => $customers,
            'vehicleReports' => $vehicleReports,
            'bookingReports' => $bookingReports,
            'bookingStatusSummary' => $bookingStatusSummary,
            'summary' => $summary,
            'periodType' => $period['periodType'],
            'periodLabel' => $period['label'],
            'selectedDate' => $period['selectedDate'],
            'selectedWeek' => $period['selectedWeek'],
            'selectedMonth' => $period['selectedMonth'],
        ] + $trafficData;
    }

    private function resolveReportPeriod(Request $request): array
    {
        $periodType = $request->query('traffic_group_by', $request->query('period_type', 'day'));

        if (!in_array($periodType, ['day', 'week', 'month'], true)) {
            $periodType = 'day';
        }

        try {
            $start = CarbonImmutable::parse($request->query('from_date', now()->subDays(29)->toDateString()))->startOfDay();
        } catch (\Throwable $e) {
            $start = CarbonImmutable::now()->subDays(29)->startOfDay();
        }

        try {
            $end = CarbonImmutable::parse($request->query('to_date', now()->toDateString()))->endOfDay();
        } catch (\Throwable $e) {
            $end = CarbonImmutable::now()->endOfDay();
        }

        if ($start->greaterThan($end)) {
            [$start, $end] = [$end->startOfDay(), $start->endOfDay()];
        }

        return [
            'periodType' => $periodType,
            'start' => $start,
            'end' => $end,
            'selectedDate' => $start->toDateString(),
            'selectedWeek' => $request->query('week', now()->format('o-\WW')),
            'selectedMonth' => $request->query('month', now()->format('Y-m')),
            'label' => $start->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $trafficDays = (int) $request->query('traffic_days', 14);
        if (!in_array($trafficDays, [7, 14, 30], true)) {
            $trafficDays = 14;
        }

        $trafficRequest = new Request([
            'group_by' => 'day',
            'traffic_source' => 'both',
            'start_date' => now()->subDays($trafficDays - 1)->toDateString(),
            'end_date' => now()->toDateString(),
        ]);
        $trafficData = $this->buildTrafficData($trafficRequest);

        $selectedMonth = $request->query('month', now()->format('Y-m'));

        try {
            $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Throwable $e) {
            $monthDate = now()->startOfMonth();
            $selectedMonth = $monthDate->format('Y-m');
        }

        $monthStart = $monthDate->copy()->startOfMonth();
        $monthEnd = $monthDate->copy()->endOfMonth();

        $monthlyBookings = Booking::query()
            ->whereBetween('booking_date', [$monthStart->toDateString(), $monthEnd->toDateString()]);

        $monthlyTransactions = Transaction::query()
            ->whereBetween('created_at', [$monthStart, $monthEnd]);

        $monthlyPaidPayments = Payment::query()
            ->where('payment_status', 'paid')
            ->whereBetween('payment_date', [$monthStart->toDateString(), $monthEnd->toDateString()]);

        $monthlySparepartPurchases = SparepartPurchase::query()
            ->whereBetween('created_at', [$monthStart, $monthEnd]);

        $totalBookings = (clone $monthlyBookings)->count();
        $totalTransactions = (clone $monthlyTransactions)->count();
        $totalPaidTransactions = (clone $monthlyPaidPayments)->distinct('transaction_id')->count('transaction_id');
        $totalRevenue = (float) (clone $monthlyPaidPayments)->sum('amount_paid');
        $totalExpense = (float) (clone $monthlySparepartPurchases)
            ->selectRaw('COALESCE(SUM(total_price), 0) as total')
            ->value('total');

        $topSpareparts = TransactionSparepart::query()
            ->join('transactions', 'transaction_spareparts.transaction_id', '=', 'transactions.id')
            ->join('spareparts', 'transaction_spareparts.sparepart_id', '=', 'spareparts.id')
            ->whereBetween('transactions.created_at', [$monthStart, $monthEnd])
            ->groupBy('spareparts.id', 'spareparts.name', 'spareparts.brand')
            ->select(
                'spareparts.name',
                'spareparts.brand',
                DB::raw('SUM(transaction_spareparts.qty) as sold_qty'),
                DB::raw('SUM(transaction_spareparts.subtotal) as sold_amount')
            )
            ->orderByDesc('sold_qty')
            ->limit(5)
            ->get();

        $topBrands = Transaction::query()
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.id')
            ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
            ->whereBetween('transactions.created_at', [$monthStart, $monthEnd])
            ->groupBy('vehicles.brand')
            ->select('vehicles.brand', DB::raw('COUNT(transactions.id) as total_service'))
            ->orderByDesc('total_service')
            ->limit(5)
            ->get();

        $topServices = Transaction::query()
            ->join('bookings', 'transactions.booking_id', '=', 'bookings.id')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->whereBetween('transactions.created_at', [$monthStart, $monthEnd])
            ->groupBy('services.id', 'services.service_name')
            ->select('services.service_name', DB::raw('COUNT(transactions.id) as total_transaction'))
            ->orderByDesc('total_transaction')
            ->limit(5)
            ->get();

        $roleOrder = ['admin', 'owner', 'kasir', 'mekanik', 'customer'];
        $roleSummary = User::query()
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->get()
            ->keyBy('role');

        $roleSummary = collect($roleOrder)->map(function ($role) use ($roleSummary) {
            return [
                'role' => $role,
                'label' => ucfirst($role),
                'total' => (int) ($roleSummary[$role]->total ?? 0),
            ];
        });

        $daysInMonth = $monthDate->daysInMonth;
        $dayLabels = collect(range(1, $daysInMonth))->map(function ($day) use ($monthDate) {
            return $monthDate->copy()->day($day)->format('d M');
        })->values();

        $dailyTransactions = Transaction::query()
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->selectRaw('DAY(created_at) as day_number, COUNT(*) as total')
            ->groupBy('day_number')
            ->pluck('total', 'day_number');

        $dailyRevenue = Payment::query()
            ->where('payment_status', 'paid')
            ->whereBetween('payment_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->selectRaw('DAY(payment_date) as day_number, SUM(amount_paid) as total')
            ->groupBy('day_number')
            ->pluck('total', 'day_number');

        $dailyExpense = SparepartPurchase::query()
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->selectRaw('DAY(created_at) as day_number, SUM(total_price) as total')
            ->groupBy('day_number')
            ->pluck('total', 'day_number');

        $chartTransactions = collect(range(1, $daysInMonth))
            ->map(fn ($day) => (int) ($dailyTransactions[$day] ?? 0))
            ->values();

        $chartRevenue = collect(range(1, $daysInMonth))
            ->map(fn ($day) => (float) ($dailyRevenue[$day] ?? 0))
            ->values();

        $chartExpense = collect(range(1, $daysInMonth))
            ->map(fn ($day) => (float) ($dailyExpense[$day] ?? 0))
            ->values();

        $availableMonths = collect()
            ->merge(Booking::query()->selectRaw("DATE_FORMAT(booking_date, '%Y-%m') as month_key")->pluck('month_key'))
            ->merge(Transaction::query()->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key")->pluck('month_key'))
            ->merge(Payment::query()->selectRaw("DATE_FORMAT(payment_date, '%Y-%m') as month_key")->pluck('month_key'))
            ->merge(SparepartPurchase::query()->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key")->pluck('month_key'))
            ->filter()
            ->push(now()->format('Y-m'))
            ->unique()
            ->sortDesc()
            ->values()
            ->map(fn ($month) => [
                'value' => $month,
                'label' => Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y'),
            ]);

        return view('admin.index', [
            'title' => 'Dashboard',
            'trafficDays' => $trafficDays,
            'selectedMonth' => $selectedMonth,
            'selectedMonthLabel' => $monthDate->translatedFormat('F Y'),
            'availableMonths' => $availableMonths,
            'totalBookings' => $totalBookings,
            'totalTransactions' => $totalTransactions,
            'totalPaidTransactions' => $totalPaidTransactions,
            'totalRevenue' => $totalRevenue,
            'totalExpense' => $totalExpense,
            'netIncome' => $totalRevenue - $totalExpense,
            'totalCustomers' => User::where('role', 'customer')->count(),
            'totalSpareparts' => Sparepart::count(),
            'topSpareparts' => $topSpareparts,
            'topBrands' => $topBrands,
            'topServices' => $topServices,
            'roleSummary' => $roleSummary,
            'chartLabels' => $dayLabels,
            'chartTransactions' => $chartTransactions,
            'chartRevenue' => $chartRevenue,
            'chartExpense' => $chartExpense,
        ] + $trafficData);
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'), [
            'title' => 'Profile'
        ]);
    }

    public function laporan(Request $request)
    {
        $request = $this->applyReportPeriod($request);

        return view('laporan.reports', $this->buildLaporanData($request) + [
            'title' => 'Laporan',
        ]);
    }

    public function exportLaporanExcel(Request $request)
    {
        $request = $this->applyReportPeriod($request);
        $report = $this->buildLaporanData($request);
        $filename = 'laporan-bengkel-' . now()->format('Ymd-His') . '.xls';
        $content = view('laporan.export-excel', $report + [
            'title' => 'Export Excel Laporan',
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    public function exportLaporanPdf(Request $request)
    {
        $request = $this->applyReportPeriod($request);

        return view('laporan.export-pdf', $this->buildLaporanData($request) + [
            'title' => 'Export PDF Laporan',
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '-' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $file->getClientOriginalName());
            
            // Ensure profiles directory exists with proper permissions
            $profilesPath = storage_path('app/public/profiles');
            if (!is_dir($profilesPath)) {
                mkdir($profilesPath, 0755, true);
            }
            
            // Store file directly with full path
            $fullPath = $profilesPath . '/' . $filename;
            $file->move($profilesPath, $filename);
            
            // Verify file was saved
            if (file_exists($fullPath)) {
                $updateData['photo'] = 'profiles/' . $filename;
            }
        }

        $user->update($updateData);

        return redirect('/admin/profile')->with('success', 'Profile berhasil diupdate!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
