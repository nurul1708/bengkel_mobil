@extends('be.master')

@section('Transaksi')
<main class="app-main py-3">
  @php $role = auth()->user()->role ?? null; @endphp
  
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-7">
          <div class="d-flex align-items-center flex-wrap gap-3">
            <h3 class="fw-bold text-dark mb-0">
              <i class="bi bi-wallet2 me-2 text-primary"></i>Riwayat Transaksi
            </h3>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-bold">
              <i class="bi bi-layers-fill me-1"></i> {{ $transaksi->count() }} Record
            </span>
          </div>
          <p class="text-muted small mt-2 mb-0">Pantau semua arus kas dan status pembayaran bengkel Anda secara real-time.</p>
        </div>
        
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
          @if(in_array($role, ['admin', 'kasir']))
          <a href="/admin/transaksi/create" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi
          </a>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body">
          <form method="GET" action="/admin/transaksi" class="row g-3 align-items-end">
            <div class="col-md-9">
              <label class="form-label small fw-bold text-muted text-uppercase mb-2">Search Transaksi</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-0 bg-light" value="{{ $search ?? '' }}" placeholder="Cari ID transaksi, booking, customer, service, kendaraan, atau plat">
              </div>
            </div>
            <div class="col-12 col-md-3 d-flex flex-column flex-sm-row gap-2">
              <button type="submit" class="btn btn-primary w-100 rounded-pill">Cari</button>
              @if(!empty($search))
                <a href="/admin/transaksi" class="btn btn-light border rounded-pill w-100">Reset</a>
              @endif
            </div>
          </form>
        </div>
      </div>
      <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="card-title fw-bold text-dark mb-0">Daftar Tagihan & Layanan</h5>
        </div>
        
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light text-secondary">
                <tr>
                  <th class="ps-4 py-3 small fw-bold" style="width: 80px;">ID</th>
                  <th class="py-3 small fw-bold">PELANGGAN & DETAIL</th>
                  <th class="py-3 small fw-bold">TOTAL BAYAR</th>
                  <th class="py-3 small fw-bold">METODE</th>
                  <th class="py-3 small fw-bold">STATUS</th>
                  <th class="py-3 small fw-bold text-center">AKSI</th>
                </tr>
              </thead>
              <tbody class="border-top-0">
                @forelse($transaksi as $t)
                <tr>
                  <td class="ps-4">
                    <span class="text-muted fw-medium">#{{ $t->id }}</span>
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-dark fs-6">
                            {{ $t->booking->user->name ?? 'Guest User' }}
                            <small class="text-muted fw-normal ms-1">(Booking #{{ $t->booking->id }})</small>
                        </span>
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                            <span class="badge bg-secondary-subtle text-secondary fw-normal">
                                <i class="bi bi-car-front me-1"></i>{{ $t->booking->vehicle->brand ?? '-' }}
                            </span>
                            <span class="badge bg-info-subtle text-info fw-normal">
                                <i class="bi bi-wrench-adjustable me-1"></i>{{ $t->service->service_name ?? ($t->booking->service->service_name ?? 'Layanan Umum') }}
                            </span>
                        </div>
                        <small class="text-muted mt-1" style="font-size: 11px;">
                            <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($t->booking->booking_date)->format('d M Y') }}
                        </small>
                    </div>
                  </td>
                  <td>
                    <span class="fw-bold text-dark">Rp{{ number_format($t->grand_total, 0, ',', '.') }}</span>
                  </td>
                  <td>
                    @php $lastPayment = $t->payments->last(); @endphp
                    @if($lastPayment)
                        <span class="badge border text-dark fw-medium px-2 py-1 bg-light">
                            <i class="bi bi-credit-card me-1"></i> {{ $lastPayment->payment_method_label }}
                        </span>
                    @else
                        <span class="text-muted small">N/A</span>
                    @endif
                  </td>
                  <td>
                    @if($t->status == 'paid')
                      <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">Lunas</span>
                    @elseif($t->status == 'partial')
                      <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2">Cicilan</span>
                    @else
                      <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">Belum Bayar</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex justify-content-center gap-2">
                      <a href="/admin/transaksi/{{ $t->id }}" class="btn btn-outline-info btn-sm rounded-pill px-3" title="Detail">
                        <i class="bi bi-eye"></i> Detail
                      </a>
                      
                      @if(in_array($role, ['admin', 'kasir']) && $t->status != 'paid')
                        <a href="/admin/transaksi/{{ $t->id }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                          <i class="bi bi-cash-coin"></i> Bayar
                        </a>
                      @endif
                      
                      @if($t->status == 'paid')
                        <a href="/admin/transaksi/{{ $t->id }}/invoice" class="btn btn-dark btn-sm rounded-pill px-3 shadow-sm">
                          <i class="bi bi-printer"></i> Invoice
                        </a>
                      @endif
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    {{ !empty($search) ? 'Data transaksi tidak ditemukan.' : 'Belum ada data transaksi.' }}
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div> </div> </div> </main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Mantap!',
    text: '{{ session('success') }}',
    timer: 2500,
    showConfirmButton: false,
    timerProgressBar: true
});
</script>
@endif

<style>
    /* UI Clean Up */
    .table thead th {
        border-bottom: none;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-size: 0.72rem;
    }
    .table tbody tr {
        transition: 0.2s;
        border-bottom: 1px solid #f1f5f9;
    }
    .table tbody tr:hover {
        background-color: #f8fafc !important;
    }
    .badge {
        font-weight: 600;
        font-size: 11px;
    }
    .bg-success-subtle { background-color: #e8fadf !important; color: #198754 !important; }
    .bg-warning-subtle { background-color: #fff8e1 !important; color: #ff9800 !important; }
    .bg-danger-subtle { background-color: #ffebee !important; color: #f44336 !important; }
    .bg-primary-subtle { background-color: #e3f2fd !important; color: #0d6efd !important; }
    .bg-info-subtle { background-color: #e0f7fa !important; color: #00bcd4 !important; }
    .bg-secondary-subtle { background-color: #f5f5f5 !important; color: #757575 !important; }
</style>
@endsection
