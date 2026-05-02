@extends('be.master')

@section('Spareparts')
<main class="app-main py-4">
    <div class="app-content-header mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="fw-bold mb-0">Edit Sparepats Form</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active">General Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <div class="d-flex align-items-center p-1">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="card border-0 shadow-lg overflow-hidden " style="border-radius: 20px; border-top: 5px solid #198754;">
                        <div class="card-header bg-white py-3 border-0">
                            <div class="card-title fw-bold text-dark">Edit Sparepat: {{ $spareparts->name }}</div>
                        </div>

                        <form method="POST" action="/admin/spareparts/{{ $spareparts->id }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body p-4 p-md-5">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-bold small text-muted text-uppercase">Nama Spare Part</label>
                                    <input type="text" class="form-control py-2 rounded-3" id="name" name="name" 
                                           placeholder="Masukkan nama spare part" value="{{ old('name', $spareparts->name) }}" required />
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="brand" class="form-label fw-bold small text-muted text-uppercase">Brand</label>
                                        <input type="text" class="form-control py-2 rounded-3" id="brand" name="brand" 
                                               placeholder="Masukkan brand" value="{{ old('brand', $spareparts->brand) }}" required />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="stock" class="form-label fw-bold small text-muted text-uppercase">Stock</label>
                                        <input type="number" class="form-control py-2 rounded-3" id="stock" name="stock" 
                                               placeholder="Jumlah stok" value="{{ old('stock', $spareparts->stock) }}" required />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="harga_beli" class="form-label fw-bold small text-muted text-uppercase">Harga Beli</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                                            <input type="number" class="form-control py-2 fw-bold text-secondary" id="harga_beli" name="harga_beli" 
                                                   placeholder="Masukkan harga beli" value="{{ old('harga_beli', $spareparts->harga_beli) }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="harga_jual" class="form-label fw-bold small text-muted text-uppercase">Harga Jual</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                                            <input type="number" class="form-control py-2 fw-bold text-success" id="harga_jual" name="harga_jual" 
                                                   placeholder="Masukkan harga jual" value="{{ old('harga_jual', $spareparts->harga_jual) }}" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="gambar" class="form-label fw-bold small text-muted text-uppercase">Gambar Sparepart</label>
                                    <div class="p-3 border rounded-3 bg-light">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 text-center">
                                                @if ($spareparts->gambar)
                                                    <img id="gambarPreview" src="{{ $spareparts->gambar_url }}" 
                                                         class="img-fluid rounded-3 shadow-sm border mb-2 mb-sm-0" 
                                                         style="max-height: 140px; object-fit: cover;">
                                                @else
                                                    <img id="gambarPreview" src="{{ asset('be/assets/assets/img/no-img.jpg') }}"
                                                         class="img-fluid rounded-3 shadow-sm border mb-2 mb-sm-0" 
                                                         style="max-height: 140px; object-fit: cover;">
                                                @endif
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" id="gambar" accept=".jpg,.jpeg,.png,image/jpeg,image/png" />
                                                <small class="text-muted d-block mt-2">
                                                    <i class="bi bi-info-circle me-1"></i> Biarkan kosong jika tidak ingin ganti foto.
                                                </small>
                                                @error('gambar')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-light p-4 border-0 d-flex gap-2 text-end">
                                <button type="submit" class="btn btn-success px-4 py-2 fw-bold shadow-sm rounded-pill w-100">
                                    <i class="bi bi-check-all me-1"></i> Update Data
                                </button>
                                <a href="{{ route('spareparts.index') }}" class="btn btn-secondary px-4 py-2 fw-bold rounded-pill w-50">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.getElementById('gambar')?.addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('gambarPreview').src = e.target.result;
    };
    reader.readAsDataURL(file);
});
</script>

<style>
    .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
    }
</style>
@endsection
