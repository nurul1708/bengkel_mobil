@extends('be.master')

@section('booking')
<style>
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #495057;
        border-top: none;
    }
    .table tbody tr {
        transition: all 0.2s;
    }
    .table tbody tr:hover {
        background-color: rgba(0,123,255, 0.05) !important;
        transform: scale(1.002);
    }
    .badge {
        font-weight: 600;
        padding: 0.5em 0.8em;
    }
</style>
<main class="app-main">
  @php $role = auth()->user()->role ?? null; @endphp
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Daftar Booking</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="card mb-3">
        <div class="card-body">
          <form method="GET" action="/admin/booking" class="row g-3 align-items-end">
            <div class="col-md-9">
              <label class="form-label small fw-bold text-muted text-uppercase mb-2">Search Booking</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-0 bg-light" value="{{ $search ?? '' }}" placeholder="Cari customer, service, brand, model, atau plat nomor">
              </div>
            </div>
            <div class="col-12 col-md-3 d-flex flex-column flex-sm-row gap-2">
              <button type="submit" class="btn btn-primary w-100 rounded-pill">Cari</button>
              @if(!empty($search))
                <a href="/admin/booking" class="btn btn-light border w-100">Reset</a>
              @endif
            </div>
          </form>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Booking Masuk</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
<tr>
                  <th>Customer</th>
                  <th>Service</th>
                  <th>Kendaraan</th>
                  <th>Mekanik</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
            <tbody>
    @forelse($bookings as $b)
    <tr>
        <td>
            <div class="d-flex flex-column">
                <span class="fw-bold text-dark">
                    <i class="bi bi-person-circle me-1 text-primary"></i> {{ $b->user->name ?? '-' }}
                </span>
              </div>
            </td>
            
            <td>
              <div class="d-flex flex-column">
                <span class="badge border border-info text-info bg-light align-self-start">
                  <i class="bi bi-tools me-1"></i> {{ $b->service->service_name ?? '-' }}
                </span>
                @if($b->status === 'processing')
                <small class="text-warning mt-1" style="font-size: 0.75rem;">
                  <i class="bi bi-clock-history"></i> Sedang dikerjakan...
                </small>
                @endif
              </div>
            </td>
            
<td>
          <small class="text-secondary">
              <i class="bi bi-car-front me-1"></i> 
              {{ ($b->vehicle->brand ?? '-') }} - {{ ($b->vehicle->model ?? '-') }}
          </small>
        </td>
        <td>
            <span class="text-dark">
                <i class="bi bi-person me-1"></i> {{ $b->mekanik->name ?? '-' }}
            </span>
        </td>
        <td>
        <div class="d-flex flex-column">
            <span class="text-dark">
                <i class="bi bi-calendar-event me-1"></i> {{ $b->booking_date_label }}
            </span>
            <small class="text-muted" style="font-size: 0.8rem;">
                ID Booking: <span class="text-monospace">#{{ $b->id }}</span>
            </small>
        </div>
</td>
        <td class="align-middle">
            {!! $b->status_badge !!}
        </td>

        <td class="text-end align-middle">
            <div class="d-inline-flex gap-2">
                <a href="/admin/booking/{{ $b->id }}" class="btn btn-sm btn-outline-primary" title="Detail">
                    <i class="bi bi-eye"></i>
                </a>

                @if(in_array($role, ['mekanik']) && $b->can_start_service)
                <form method="POST" action="/admin/booking/{{ $b->id }}/proses" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning text-white" title="Mulai Service">
                        <i class="bi bi-play-circle-fill"></i> Proses
                    </button>
                </form>
                @endif

                 @if(in_array($role, ['mekanik']) && $b->status === 'in_progress')
                            <form method="POST" action="/admin/booking/{{ $b->id }}/selesai" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Selesai">
                                    <i class="bi bi-check-circle-fill"></i> 
                                </button>
                            </form>
                        @endif

                @if(in_array($role, ['kasir']) && $b->status === 'completed')
                <a href="/admin/transaksi/create?booking_id={{ $b->id }}" class="btn btn-sm btn-success" title="Bayar">
                    <i class="bi bi-cash-stack"></i> Bayar
                </a>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
<td colspan="7" class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            {{ !empty($search) ? 'Data booking tidak ditemukan.' : 'Belum ada data booking yang masuk.' }}
        </td>
    </tr>
    @endforelse
</tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    confirmButtonColor: '#dc3545'
});
document.querySelectorAll('.btn-hapus').forEach(button => {
    button.addEventListener('click', function() {
        let id = this.getAttribute('data-id');

        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-' + id).submit();
            }
        });
    });
});
</script>
@endif
@if ($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    html: `{!! implode('<br>', $errors->all()) !!}`,
    confirmButtonColor: '#dc3545'
});
</script>
@endif
@endsection
