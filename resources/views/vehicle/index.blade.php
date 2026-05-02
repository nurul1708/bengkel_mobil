@extends('be.master')

@section('Vehicle')
<main class="app-main py-3">
    @php $role = auth()->user()->role ?? null; @endphp
    
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-dark">Data Kendaraan</h3>
                <p class="text-muted small mb-0">Kelola informasi kendaraan dan kepemilikan owner.</p>
            </div>
            @if($role === 'admin')
            <a href="/admin/vehicle/create" class="btn btn-primary shadow-sm px-4 rounded-pill">
                <i class="bi bi-plus-lg me-1"></i> Tambah Kendaraan
            </a>
            @endif
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body">
                <form method="GET" action="/admin/vehicle" class="row g-3 align-items-end">
                    <div class="col-md-8 col-lg-9">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Cari Plat Nomor</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input
                                type="text"
                                name="search"
                                class="form-control border-0 bg-light"
                                value="{{ $search ?? '' }}"
                                placeholder="Contoh: B 1234 ABC"
                            >
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3 d-flex flex-column flex-sm-row gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">
                            Cari
                        </button>
                        @if(!empty($search))
                            <a href="/admin/vehicle" class="btn btn-light border rounded-pill w-100">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted small fw-bold">UNIT & MODEL</th>
                                <th class="py-3 text-muted small fw-bold text-center">PLAT NOMOR</th>
                                <th class="py-3 text-muted small fw-bold text-center">TAHUN</th>
                                <th class="py-3 text-muted small fw-bold">PEMILIK (OWNER)</th>
                                <th class="pe-4 py-3 text-muted small fw-bold text-end">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $v)
                            @php $isRejectedBooking = $v->latestBooking?->status === 'cancelled'; @endphp
                            <tr class="{{ $isRejectedBooking ? 'vehicle-booking-rejected' : '' }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box {{ $isRejectedBooking ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info' }} rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi {{ $isRejectedBooking ? 'bi-x-circle-fill' : 'bi-car-front-fill' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold {{ $isRejectedBooking ? 'text-danger' : 'text-dark' }}">{{ $v->brand }}</div>
                                            <small class="text-muted">{{ $v->model }}</small>
                                            @if($isRejectedBooking)
                                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle ms-2">
                                                    Booking Ditolak
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-dark-subtle text-dark px-3 py-2">
                                        {{ $v->license_plate }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3">
                                        {{ $v->year }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle me-2 text-muted"></i>
                                        <span class="text-dark">{{ $v->user->name }}</span>
                                    </div>
                                    @if($v->latestBooking)
                                        <small class="text-muted">
                                            Booking terakhir: {{ $v->latestBooking->booking_date_label }}
                                        </small>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="/admin/vehicle/{{ $v->id }}" class="btn btn-light btn-sm shadow-sm px-3 rounded-pill text-info">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if($role === 'admin')
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm shadow-sm rounded-pill" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                <li><a class="dropdown-item py-2 text-warning" href="/admin/vehicle/{{ $v->id }}/edit"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><button class="dropdown-item py-2 text-danger btn-hapus" data-id="{{ $v->id }}"><i class="bi bi-trash me-2"></i> Hapus</button></li>
                                            </ul>
                                        </div>

                                        <form id="form-hapus-{{ $v->id }}" action="/admin/vehicle/{{ $v->id }}" method="POST" class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                    <p class="text-muted mb-0">
                                        {{ !empty($search) ? 'Data kendaraan dengan plat nomor tersebut tidak ditemukan.' : 'Data kendaraan masih kosong.' }}
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
        showConfirmButton: false,
        timer: 1500
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

<style>
    .vehicle-booking-rejected {
        --bs-table-bg: #fff5f5;
        --bs-table-hover-bg: #ffecec;
        border-left: 4px solid #dc3545;
    }
</style>

<script>
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Data kendaraan ini akan dihapus permanen!",
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
@endsection
