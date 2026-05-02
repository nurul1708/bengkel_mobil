@extends('be.master')

@section('Transaksi')
<main class="app-main">
  <div class="app-content-header d-print-none">
    <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
      <div>
        <h3 class="mb-0 fw-bold text-dark">Invoice Transaksi #{{ $trx->id }}</h3>
        <p class="text-muted small mb-0">E-Invoice resmi untuk pelanggan SerVix.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="/admin/transaksi" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
          <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <button type="button" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm" onclick="window.print()">
          <i class="bi bi-printer me-1"></i>Cetak Invoice
        </button>
      </div>
    </div>
  </div>

  <div class="app-content pb-5">
    <div class="container-fluid">
      
      @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm d-print-none">{{ session('success') }}</div>
      @endif

      <div class="invoice-shell position-relative" id="invoiceArea">
        @if($trx->status == 'paid')
          <div class="paid-watermark">LUNAS</div>
        @endif

        <div class="invoice-topbar"></div>
        <div class="invoice-body p-4 p-md-5">
          
          <div class="invoice-print-head">
            <div>
              <div class="invoice-print-title text-primary">SerVix Bengkel</div>
              <div class="invoice-print-subtitle">Digital Transaction Record</div>
            </div>
            <div class="invoice-print-meta text-end">
              <div class="fw-bold">INV-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</div>
              <div class="small text-muted">{{ $payment ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : now()->format('d/m/Y') }}</div>
            </div>
          </div>

          <div class="row align-items-start mb-5 g-4">
            <div class="col-md-7 col-7">
              <div class="invoice-brand">
                
               <div class="row align-items-start mb-5 g-4">
  <div class="col-md-7 col-7">
    <div class="invoice-brand">
      <div class="invoice-brand-mark overflow-hidden bg-white border">
        <img src="{{asset('be/assets/assets/img/logo.png')}}" alt="SerVix Logo" class="img-fluid">
      </div>
      <div>
        <h2 class="fw-bold mb-1 text-dark">SerVix Bengkel</h2>
        <p class="mb-0 text-muted small"><i class="bi bi-geo-alt-fill me-1"></i>Jl. Raya Bengkel No. 123, Cibinong</p>
        <p class="mb-0 text-muted small"><i class="bi bi-telephone-fill me-1"></i>+62 812-3456-7890</p>
      </div>
    </div>
  </div>
  </div>
              </div>
            </div>
            <div class="col-md-5 col-5 text-end">
              <div class="invoice-chip mb-3">
                <div class="small text-uppercase opacity-75" style="font-size: 0.65rem;">Nomor Invoice</div>
                <div class="fw-bold h5 mb-0">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</div>
              </div>
              <div>
                <span class="invoice-status shadow-sm">
                  <i class="bi bi-patch-check-fill me-1"></i>Terbayar
                </span>
              </div>
            </div>
          </div>

          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <div class="invoice-panel h-100 shadow-sm">
                <div class="invoice-panel-title border-bottom pb-2 mb-3">Informasi Pelanggan</div>
                <div class="d-flex flex-column gap-2">
                  <div class="d-flex justify-content-between">
                    <span class="text-muted small">Nama:</span>
                    <span class="fw-bold text-dark">{{ $trx->booking->user->name ?? '-' }}</span>
                  </div>
                  <div class="d-flex justify-content-between">
                    <span class="text-muted small">Unit:</span>
                    <span class="fw-bold text-dark">{{ $trx->booking->vehicle->brand ?? '-' }} {{ $trx->booking->vehicle->model ?? '' }}</span>
                  </div>
                  <div class="d-flex justify-content-between">
                    <span class="text-muted small">Jadwal:</span>
                    <span class="fw-bold text-dark text-end">{{ $trx->booking->booking_date ?? '-' }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="invoice-panel h-100 shadow-sm">
                <div class="invoice-panel-title border-bottom pb-2 mb-3">Detail Pembayaran</div>
                <div class="d-flex flex-column gap-2">
                  <div class="d-flex justify-content-between">
                    <span class="text-muted small">Metode:</span>
                    <span class="badge bg-dark rounded-pill px-3">{{ $payment?->payment_method_label ?? '-' }}</span>
                  </div>
                  <div class="d-flex justify-content-between">
                    <span class="text-muted small">Tanggal Bayar:</span>
                    <span class="fw-bold text-dark text-end">{{ $payment ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y, H:i') : '-' }}</span>
                  </div>
                  <div class="d-flex justify-content-between">
                    <span class="text-muted small">Kasir:</span>
                    <span class="fw-bold text-primary">{{ $trx->kasir->name ?? 'System' }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="invoice-table-wrap mb-4 shadow-sm border-0">
            <table class="table align-middle mb-0 invoice-table">
              <thead class="table-light">
                <tr class="text-uppercase small fw-bold">
                  <th class="ps-4">Deskripsi Layanan & Sparepart</th>
                  <th class="text-center">Qty</th>
                  <th class="text-end">Harga Satuan</th>
                  <th class="text-end pe-4">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="ps-4 py-3">
                    <div class="fw-bold text-dark">Jasa {{ $trx->service->service_name ?? '-' }}</div>
                    <small class="text-muted">Biaya penanganan oleh mekanik: {{ $trx->mekanik->name ?? '-' }}</small>
                  </td>
                  <td class="text-center">1</td>
                  <td class="text-end">Rp {{ number_format($trx->total_service, 0, ',', '.') }}</td>
                  <td class="text-end pe-4 fw-bold text-dark">Rp {{ number_format($trx->total_service, 0, ',', '.') }}</td>
                </tr>
                @forelse($trx->items ?? [] as $item)
                  <tr>
                    <td class="ps-4 py-3">
                      <div class="fw-bold text-dark">{{ $item['sparepart_name'] ?? '-' }}</div>
                      <small class="text-muted">Suku cadang original</small>
                    </td>
                    <td class="text-center">{{ $item['jumlah_beli'] ?? 0 }}</td>
                    <td class="text-end">Rp {{ number_format($item['harga_beli'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end pe-4 fw-bold text-dark">Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</td>
                  </tr>
                @empty
                @endforelse
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3" class="text-end py-2 text-muted">Total Jasa</td>
                  <td class="text-end pe-4 py-2 fw-medium">Rp {{ number_format($trx->total_service, 0, ',', '.') }}</td>
                </tr>
                <tr>
                  <td colspan="3" class="text-end py-2 text-muted">Total Sparepart</td>
                  <td class="text-end pe-4 py-2 fw-medium">Rp {{ number_format($trx->total_sparepart, 0, ',', '.') }}</td>
                </tr>
            <tr class="invoice-grand-total">
  <td colspan="3" class="text-end py-3 border-0">
    <span class="h5 mb-0 fw-bold text-dark">TOTAL PEMBAYARAN</span>
  </td>
  <td class="text-end pe-4 py-3 border-0">
    <span class="h4 mb-0 fw-bold text-dark">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</span>
  </td>
</tr>
              </tfoot>
            </table>
          </div>

          <div class="invoice-footer mt-5">
            <div class="flex-grow-1">
              <div class="invoice-footer-title"><i class="bi bi-info-circle-fill me-1"></i>Syarat & Ketentuan</div>
              <ul class="small text-muted ps-3 mb-0">
                <li>Garansi service berlaku selama 7 hari sejak tanggal servis.</li>
                <li>Invoice ini adalah bukti pembayaran yang sah.</li>
                <li>Sparepart yang sudah dibeli tidak dapat ditukar/dikembalikan.</li>
              </ul>
            </div>
            <div class="text-center mt-4 mt-md-0" style="min-width: 200px;">
              <p class="mb-5 small text-muted">Hormat kami,</p>
              <div class="border-bottom mx-auto" style="width: 150px;"></div>
              <h6 class="mt-2 fw-bold mb-0 text-dark">{{ $trx->kasir->name ?? 'Admin SerVix' }}</h6>
              <small class="text-uppercase text-muted" style="font-size: 0.65rem; letter-spacing: 1px;">Petugas Kasir</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<style>
/* --- Custom Styling --- */
.invoice-shell {
  background: #ffffff;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(226, 232, 240, 0.8);
}

/* Watermark Lunas */
.paid-watermark {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) rotate(-30deg);
  font-size: 150px;
  font-weight: 900;
  color: rgba(25, 135, 84, 0.08);
  z-index: 0;
  pointer-events: none;
  border: 15px solid rgba(25, 135, 84, 0.08);
  padding: 20px 50px;
  border-radius: 50px;
  text-transform: uppercase;
  white-space: nowrap;
}

.invoice-topbar {
  height: 8px;
  background: linear-gradient(90deg, #0d6efd 0%, #20c997 100%);
}

.invoice-brand-mark {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  background: linear-gradient(135deg, #0d6efd 0%, #198754 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 800;
  font-size: 1.4rem;
  box-shadow: 0 10px 20px rgba(13, 110, 253, 0.15);
}

.invoice-chip {
  padding: 12px 20px;
  background: #1e293b;
  color: white;
  border-radius: 16px;
  display: inline-block;
}

.invoice-status {
  background: rgba(25, 135, 84, 0.1);
  color: #198754;
  padding: 6px 16px;
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.9rem;
  border: 1px solid rgba(25, 135, 84, 0.2);
}

.invoice-panel {
  background: #f8fafc;
  border-radius: 20px;
  padding: 24px;
  border: 1px solid #edf2f7;
}

.invoice-table thead th {
  background: #f1f5f9 !important;
  color: #475569 !important;
  border: none;
}

/* Update kotak logo */
.invoice-brand-mark {
  width: 65px;
  height: 65px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.invoice-brand-mark img {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Agar logo rapi memenuhi kotak */
}

/* Mengubah Total dari Biru ke Hitam */
.invoice-grand-total td {
  background: #f8fafc !important; /* Background abu sangat muda */
  color: #0f172a !important; /* Warna text hitam navy */
  border-top: 2px solid #0f172a !important; /* Garis penegas di atas total */
  border-bottom: 2px solid #0f172a !important;
}

/* Tambahan: Pastikan Sidebar & Navbar beneran hilang pas di print */
@media print {
  .app-header, 
  .app-sidebar, 
  .sidebar-wrapper,
  .d-print-none {
    display: none !important;
    width: 0 !important;
  }
  
  .app-main {
    margin-left: 0 !important;
    padding: 0 !important;
  }
}
</style>
@endsection
