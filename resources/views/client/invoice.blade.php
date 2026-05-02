@extends('fe.master')

@section('Profile')
<style>
    .client-invoice-page {
        --invoice-primary: #0f172a;
        --invoice-accent: #0d6efd;
        --invoice-success: #198754;
        --invoice-soft: #64748b;
        --invoice-line: rgba(15, 23, 42, 0.08);
    }

    .client-invoice-page .ci-shell {
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.12), transparent 26%),
            linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%);
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.1);
        border: 1px solid var(--invoice-line);
    }

    .client-invoice-page .ci-topbar {
        height: 10px;
        background: linear-gradient(90deg, #0d6efd 0%, #20c997 100%);
    }

    .client-invoice-page .ci-brand {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .client-invoice-page .ci-brand-mark {
        width: 68px;
        height: 68px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 1.35rem;
        background: linear-gradient(135deg, #0d6efd 0%, #198754 100%);
        box-shadow: 0 14px 30px rgba(13, 110, 253, 0.22);
    }

    .client-invoice-page .ci-chip {
        display: inline-block;
        padding: 14px 18px;
        border-radius: 18px;
        background: #0f172a;
        color: #fff;
        font-weight: 700;
    }

    .client-invoice-page .ci-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(25, 135, 84, 0.12);
        color: #146c43;
        border: 1px solid rgba(25, 135, 84, 0.2);
        font-weight: 700;
    }

    .client-invoice-page .ci-panel {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid var(--invoice-line);
        border-radius: 22px;
        padding: 22px;
        height: 100%;
    }

    .client-invoice-page .ci-panel-title {
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--invoice-soft);
        margin-bottom: 14px;
    }

    .client-invoice-page .ci-mini {
        background: #fff;
        border: 1px solid var(--invoice-line);
        border-radius: 18px;
        padding: 18px;
        height: 100%;
    }

    .client-invoice-page .ci-mini-label {
        font-size: 0.78rem;
        color: var(--invoice-soft);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .client-invoice-page .ci-mini-value {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--invoice-primary);
    }

    .client-invoice-page .ci-table-wrap {
        background: #fff;
        border: 1px solid var(--invoice-line);
        border-radius: 24px;
        overflow: hidden;
    }

    .client-invoice-page .ci-table thead th {
        background: #eef5ff;
        color: var(--invoice-primary);
        border-bottom: 1px solid var(--invoice-line);
    }

    .client-invoice-page .ci-table th,
    .client-invoice-page .ci-table td {
        padding: 14px 16px;
        border-color: var(--invoice-line);
    }

    .client-invoice-page .ci-grand-total td {
        background: #0f172a;
        color: #fff;
    }

    .client-invoice-page .ci-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 24px;
    }

    @media (max-width: 767.98px) {
        .client-invoice-page .ci-footer {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media print {
        @page {
            size: A4;
            margin: 12mm;
        }

        .navbar,
        .footer,
        .btn,
        .wow,
        .topbar,
        .back-to-top,
        .container-xxl > .container > .d-flex:first-child {
            display: none !important;
        }

        html,
        body {
            background: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .client-invoice-page .ci-shell {
            box-shadow: none !important;
            border-radius: 0 !important;
            border: 1.5px solid #d8e0ea !important;
            background: #fff !important;
        }

        .client-invoice-page .ci-topbar {
            height: 7px !important;
        }

        .client-invoice-page .ci-brand-mark {
            box-shadow: none !important;
        }

        .client-invoice-page .ci-panel,
        .client-invoice-page .ci-mini,
        .client-invoice-page .ci-table-wrap {
            box-shadow: none !important;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .client-invoice-page .ci-table thead th {
            background: #eef5ff !important;
        }
    }
</style>
<div class="container-xxl py-5 client-invoice-page">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="mb-1">Invoice Pembayaran</h2>
                <p class="text-muted mb-0">Invoice transaksi service & sparepart yang sudah lunas.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('client.transactions.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="button" class="btn btn-dark" onclick="window.print()">
                    <i class="fa fa-print me-2"></i>Print
                </button>
            </div>
        </div>

        <div class="ci-shell" id="clientInvoiceArea">
            <div class="ci-topbar"></div>
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-start g-4 mb-4">
                    <div class="col-md-7">
                        <div class="ci-brand">
                            <img src="{{ asset('be/assets/assets/img/logo.png') }}" 
     alt="SerVix Logo" 
     class="img-fluid rounded-circle" 
     style="width: 90px; height: 90px; object-fit: cover;">
                            <div>
                                <h3 class="fw-bold mb-1">SerVix Bengkel</h3>
                                <p class="text-muted mb-1">Invoice Pembayaran Service & Sparepart</p>
                                <p class="text-muted mb-0">Tanggal cetak: {{ now()->format('d-m-Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <div class="ci-chip mb-2">
                            INV-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}
                        </div>
                        <div>
                            <span class="ci-status"><i class="fa fa-check-circle"></i>Lunas</span>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="ci-panel">
                            <div class="ci-panel-title">Pelanggan</div>
                            <p class="mb-2"><strong>Nama:</strong> {{ $trx->booking->user->name ?? '-' }}</p>
                            <p class="mb-2"><strong>Kendaraan:</strong> {{ $trx->booking->vehicle->brand ?? '-' }} {{ $trx->booking->vehicle->model ?? '' }}</p>
                            <p class="mb-0"><strong>Plat:</strong> {{ $trx->booking->vehicle->license_plate ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="ci-panel">
                            <div class="ci-panel-title">Pembayaran</div>
                            <p class="mb-2"><strong>Tanggal Bayar:</strong> {{ $payment ? \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') : '-' }}</p>
                            <p class="mb-2"><strong>Metode:</strong> {{ $payment?->payment_method_label ?? '-' }}</p>
                            <p class="mb-0"><strong>Nominal:</strong> Rp {{ number_format($payment->amount_paid ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="ci-mini">
                            <div class="ci-mini-label">Layanan</div>
                            <div class="ci-mini-value">{{ $trx->service->service_name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ci-mini">
                            <div class="ci-mini-label">Booking ID</div>
                            <div class="ci-mini-value">#{{ $trx->booking->id ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ci-mini">
                            <div class="ci-mini-label">Grand Total</div>
                            <div class="ci-mini-value">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="ci-table-wrap mb-4">
                    <div class="table-responsive">
                    <table class="table ci-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jasa {{ $trx->service->service_name ?? '-' }}</td>
                                <td>1</td>
                                <td>Rp {{ number_format($trx->total_service, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($trx->total_service, 0, ',', '.') }}</td>
                            </tr>
                            @forelse($trx->items ?? [] as $item)
                                <tr>
                                    <td>Sparepart - {{ $item['sparepart_name'] ?? '-' }}</td>
                                    <td>{{ $item['jumlah_beli'] ?? 0 }}</td>
                                    <td>Rp {{ number_format($item['harga_beli'] ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Service</td>
                                <td class="fw-bold">Rp {{ number_format($trx->total_service, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Sparepart</td>
                                <td class="fw-bold">Rp {{ number_format($trx->total_sparepart, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="ci-grand-total">
                                <td colspan="3" class="text-end fw-bold">Grand Total</td>
                                <td class="fw-bold">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>

                <div class="ci-footer">
                    <div>
                        <div class="ci-panel-title">Catatan</div>
                        <p class="text-muted mb-0">Terima kasih telah melakukan pembayaran. Simpan invoice ini sebagai bukti transaksi resmi dari bengkel.</p>
                    </div>
                    <div class="text-md-end">
                        <p class="text-muted mb-1">Hormat kami,</p>
                        <h6 class="mb-0">{{ $trx->kasir->name ?? 'Admin SerVix' }}</h6>
                        <small class="text-muted">Kasir</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
