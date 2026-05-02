@extends('be.master')

@section('Spareparts')
<main class="app-main py-3">
    @php $role = auth()->user()->role ?? null; @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-dark">Inventaris Spareparts</h3>
                <p class="text-muted small mb-0">Kelola stok suku cadang dan komponen bengkel.</p>
            </div>
            @if(in_array($role, ['admin', 'mekanik']))
            <a href="/admin/spareparts/create" class="btn btn-primary shadow-sm px-4 rounded-pill">
                <i class="bi bi-plus-lg me-1"></i> Tambah Spareparts
            </a>
            @endif
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body">
                <form method="GET" action="/admin/spareparts" class="row g-3 align-items-end">
                    <div class="col-md-9">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Search Spareparts</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-light" value="{{ $search ?? '' }}" placeholder="Cari nama sparepart atau brand">
                        </div>
                    </div>
                    <div class="col-12 col-md-3 d-flex flex-column flex-sm-row gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Cari</button>
                        @if(!empty($search))
                            <a href="/admin/spareparts" class="btn btn-light border rounded-pill w-100">Reset</a>
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
                                <th class="ps-4 py-3 text-muted small fw-bold">PRODUK</th>
                                <th class="py-3 text-muted small fw-bold text-center">BRAND</th>
                                <th class="py-3 text-muted small fw-bold text-center">STOK</th>
                                <th class="py-3 text-muted small fw-bold text-center">HARGA BELI</th>
                                <th class="py-3 text-muted small fw-bold text-center">HARGA JUAL</th>
                                <th class="pe-4 py-3 text-muted small fw-bold text-end">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spareparts as $s)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 position-relative">
                                            @if($s->gambar)
                                                <img src="{{ $s->gambar_url }}" width="55" height="55" 
                                                     style="object-fit: cover; border-radius: 12px;" 
                                                     class="shadow-sm border img-hover" alt="{{ $s->name }}">
                                            @else
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 55px; height: 55px;">
                                                    <i class="bi bi-image text-muted fs-4"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $s->name }}</div>
                                            <small class="text-muted">ID: #SPP-{{ $s->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary px-3 rounded-pill">{{ $s->brand }}</span>
                                </td>
                                <td class="text-center">
                                    @if($s->stock <= 5)
                                        <span class="text-danger fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $s->stock }}</span>
                                        <div style="font-size: 10px;" class="text-danger fw-bold text-uppercase">Limit</div>
                                    @else
                                        <span class="text-dark fw-bold">{{ $s->stock }}</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold text-secondary">
                                    Rp {{ number_format($s->harga_beli, 0, ',', '.') }}
                                </td>
                                <td class="text-center fw-bold text-success">
                                    Rp {{ number_format($s->harga_jual, 0, ',', '.') }}
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        @if(in_array($role, ['admin', 'mekanik']))
                                        <a href="/admin/spareparts/{{ $s->id }}/edit" class="btn btn-light btn-sm shadow-sm rounded-pill px-3 text-warning">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <button class="btn btn-light btn-sm shadow-sm rounded-pill px-3 text-danger btn-hapus" data-id="{{ $s->id }}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        
                                        <form id="form-hapus-{{ $s->id }}" action="/admin/spareparts/{{ $s->id }}" method="POST" class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Lihat Stok</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                    <p class="text-muted">{{ !empty($search) ? 'Sparepart tidak ditemukan.' : 'Data spare parts belum tersedia.' }}</p>
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

<style>
    .img-hover { transition: transform 0.2s; cursor: pointer; }
    .img-hover:hover { transform: scale(1.5); z-index: 100; position: relative; }
</style>

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

<script>
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Hapus Sparepart?',
                text: "Stok produk ini akan dihapus dari sistem!",
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
