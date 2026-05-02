@extends('be.master')

@section('Spareparts')
<main class="app-main py-4">
    <div class="app-content-header mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="fw-bold mb-0">Tambah Spare Part</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active">Create Spare Part</li>
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
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="card border-0 shadow-lg overflow-hidden " style="border-radius: 20px; border-top: 5px solid #0d6efd;">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="card-title fw-bold text-dark mb-0">Form Input Data Baru</h5>
                        </div>

                        <form action="{{ route('spareparts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body p-4 p-md-5">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="name" class="form-label fw-bold small text-muted text-uppercase">Nama Spare Part</label>
                                        <input type="text" class="form-control py-2 rounded-3" id="name" name="name" 
                                               placeholder="Masukkan nama spare part" value="{{ old('name') }}" required />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="brand" class="form-label fw-bold small text-muted text-uppercase">Brand</label>
                                        <input type="text" class="form-control py-2 rounded-3" id="brand" name="brand" 
                                               placeholder="Masukkan brand" value="{{ old('brand') }}" required />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="stock" class="form-label fw-bold small text-muted text-uppercase">Stock</label>
                                        <input type="number" class="form-control py-2 rounded-3" id="stock" name="stock" 
                                               placeholder="Jumlah stok" value="{{ old('stock') }}" required />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="harga_beli" class="form-label fw-bold small text-muted text-uppercase">Harga Beli</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">Rp</span>
                                            <input type="number" class="form-control py-2" id="harga_beli" name="harga_beli" 
                                                   placeholder="Masukkan harga beli" value="{{ old('harga_beli') }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="harga_jual" class="form-label fw-bold small text-muted text-uppercase">Harga Jual</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">Rp</span>
                                            <input type="number" class="form-control py-2" id="harga_jual" name="harga_jual" 
                                                   placeholder="Masukkan harga jual" value="{{ old('harga_jual') }}" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="gambar" class="form-label fw-bold small text-muted text-uppercase">Gambar Sparepart</label>
                                    <div class="p-3 border rounded-3 bg-light">
                                        <input type="file" class="form-control" id="gambar" name="gambar" 
                                               accept=".jpg,.jpeg,.png,image/jpeg,image/png" />
                                        <div class="mt-2">
                                            <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Format: <strong>jpg, jpeg, png</strong> (Max 2MB)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-light p-4 border-0 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-pill w-100">
                                    <i class="bi bi-check-lg me-1"></i> Submit Data
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

<style>
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
</style>
@endsection
