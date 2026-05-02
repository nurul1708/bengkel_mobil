<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bengkel - PDF</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
            margin: 0;
            background: #f8fafc;
            font-size: 12px;
        }
        .screen-state {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: 15px;
        }
        .report-sheet {
            display: none;
            padding: 24px;
            background: #ffffff;
        }
        .report-header {
            margin-bottom: 18px;
        }
        .report-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 4px;
        }
        .report-subtitle {
            color: #475569;
            margin: 0;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 22px;
        }
        .summary-card {
            border: 1px solid #dbe2ea;
            border-radius: 12px;
            padding: 14px;
        }
        .summary-label {
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: 700;
        }
        .section {
            margin-top: 22px;
        }
        .section h3 {
            margin: 0 0 10px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dbe2ea;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f1f5f9;
        }
        @media print {
            body {
                background: #ffffff;
            }
            .screen-state {
                display: none !important;
            }
            .report-sheet {
                display: block !important;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="screen-state">
        Menyiapkan file PDF...
    </div>

    <div class="report-sheet">
        <div class="report-header">
            <h1 class="report-title">Laporan Bengkel Servix</h1>
            <p class="report-subtitle">Periode: {{ $periodLabel }}</p>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Total Transaksi</div>
                <div class="summary-value">{{ $summary['total_transactions'] }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Nilai Invoice</div>
                <div class="summary-value">Rp {{ number_format($summary['total_invoice_value'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Pembayaran Masuk</div>
                <div class="summary-value">Rp {{ number_format($summary['total_paid_amount'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Sisa Piutang</div>
                <div class="summary-value">Rp {{ number_format($summary['total_outstanding'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Booking</div>
                <div class="summary-value">{{ $summary['total_bookings'] }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Customer</div>
                <div class="summary-value">{{ $summary['total_customers'] }}</div>
            </div>
        </div>

        <div class="section">
            <h3>Ringkasan Booking</h3>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookingStatusSummary as $status)
                        <tr>
                            <td>{{ $status['label'] }}</td>
                            <td>{{ $status['total'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">Belum ada data booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Laporan Transaksi</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>#{{ $transaction->id }}</td>
                            <td>{{ $transaction->booking->user->name ?? '-' }}</td>
                            <td>{{ $transaction->service->service_name ?? '-' }}</td>
                            <td>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                            <td>{{ strtoupper($transaction->status) }}</td>
                            <td>{{ $transaction->created_at?->format('d-m-Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Laporan Pembayaran</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Transaksi</th>
                        <th>Customer</th>
                        <th>Metode</th>
                        <th>Jumlah Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date }}</td>
                            <td>#{{ $payment->transaction_id }}</td>
                            <td>{{ $payment->transaction->booking->user->name ?? '-' }}</td>
                            <td>{{ $payment->payment_method_label }}</td>
                            <td>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                            <td>{{ strtoupper($payment->payment_status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Laporan Service</h3>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Total Transaksi</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($serviceReports as $service)
                        <tr>
                            <td>{{ $service->service_name }}</td>
                            <td>{{ $service->total_transaksi }}</td>
                            <td>Rp {{ number_format($service->total_pendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Belum ada data service.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Laporan Sparepart</h3>
            <table>
                <thead>
                    <tr>
                        <th>Sparepart</th>
                        <th>Brand</th>
                        <th>Qty Terjual</th>
                        <th>Total Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sparepartReports as $sparepart)
                        <tr>
                            <td>{{ $sparepart->name }}</td>
                            <td>{{ $sparepart->brand }}</td>
                            <td>{{ $sparepart->total_qty }}</td>
                            <td>Rp {{ number_format($sparepart->total_penjualan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada data sparepart.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Laporan Customer</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Total Transaksi</th>
                        <th>Total Belanja</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?: '-' }}</td>
                            <td>{{ $customer->total_transaksi }}</td>
                            <td>Rp {{ number_format($customer->total_belanja, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada data customer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Laporan Kendaraan</h3>
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Kendaraan</th>
                        <th>Plat</th>
                        <th>Service</th>
                        <th>Status Booking</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicleReports as $booking)
                        <tr>
                            <td>{{ $booking->user->name ?? '-' }}</td>
                            <td>{{ trim(($booking->vehicle->brand ?? '-') . ' ' . ($booking->vehicle->model ?? '')) }}</td>
                            <td>{{ $booking->vehicle->license_plate ?? '-' }}</td>
                            <td>{{ $booking->service->service_name ?? '-' }}</td>
                            <td>{{ strtoupper($booking->status) }}</td>
                            <td>{{ trim($booking->booking_date_label . ' ' . ($booking->booking_time_label !== '-' ? $booking->booking_time_label : '')) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data kendaraan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 250);
        });

        window.addEventListener('afterprint', function () {
            window.close();
        });
    </script>
</body>
</html>
