@extends('be.master')

@section('Service')
<main class="app-main py-3">
    @php $role = auth()->user()->role ?? null; @endphp
    
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-dark">Manajemen Layanan</h3>
                <p class="text-muted small mb-0">Kelola daftar jasa servis dan harga bengkel.</p>
            </div>
            @if($role === 'admin')
            <a href="/admin/service/create" class="btn btn-primary shadow-sm px-4 rounded-pill">
                <i class="bi bi-plus-lg me-1"></i> Tambah Layanan
            </a>
            @endif
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body">
                <form method="GET" action="/admin/service" class="row g-3 align-items-end">
                    <div class="col-md-9">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Search Service</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-light" value="{{ $search ?? '' }}" placeholder="Cari nama layanan atau deskripsi">
                        </div>
                    </div>
                    <div class="col-12 col-md-3 d-flex flex-column flex-sm-row gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Cari</button>
                        @if(!empty($search))
                            <a href="/admin/service" class="btn btn-light border rounded-pill w-100">Reset</a>
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
                                <th class="ps-4 py-3 text-muted small fw-bold">LAYANAN</th>
                                <th class="py-3 text-muted small fw-bold text-center">HARGA</th>
                                <th class="py-3 text-muted small fw-bold text-center">ESTIMASI</th>
                                <th class="pe-4 py-3 text-muted small fw-bold text-end">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $s)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-gear-fill"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $s->service_name }}</div>
                                            <small class="text-muted">{{ Str::limit($s->description, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold text-dark">
                                    <span class="text-success">Rp {{ number_format($s->price, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-info-subtle text-info px-3">
                                        <i class="bi bi-clock-history me-1"></i> {{ $s->estimated_time }} Menit
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    @if($role === 'admin')
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm shadow-sm" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li><a class="dropdown-item py-2" href="/admin/service/{{ $s->id }}/edit"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><button class="dropdown-item py-2 text-danger btn-hapus" data-id="{{ $s->id }}"><i class="bi bi-trash me-2"></i> Hapus</button></li>
                                        </ul>
                                    </div>
                                    <form id="form-hapus-{{ $s->id }}" action="/admin/service/{{ $s->id }}" method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                    @else
                                    <span class="badge bg-light text-muted">View Only</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                    <p class="text-muted">{{ !empty($search) ? 'Layanan tidak ditemukan.' : 'Belum ada data layanan tersedia.' }}</p>
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
{{-- Alert Success --}}
@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false });
</script>
@endif

<script>
document.querySelectorAll('.btn-hapus').forEach(button => {
    button.addEventListener('click', function() {
        let id = this.getAttribute('data-id');
        Swal.fire({
            title: 'Hapus Layanan?',
            text: "Data ini akan hilang permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('form-hapus-' + id).submit(); }
        });
    });
});
</script>
@endsection
