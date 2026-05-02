@extends('be.master')

@section('Vehicle')
<main class="app-main py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 15px;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-lg" style="border-radius: 20px; border-top: 5px solid #0d6efd;">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary-subtle text-primary rounded-circle p-3 me-3">
                                <i class="bi bi-car-front-fill fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">Tambah Kendaraan</h4>
                                <p class="text-muted small mb-0">Daftarkan unit kendaraan baru ke sistem.</p>
                            </div>
                        </div>

                        <form method="POST" action="/admin/vehicle">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Brand</label>
                                    <input type="text" class="form-control py-2" name="brand" value="{{ old('brand') }}" placeholder="Contoh: Toyota" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Model</label>
                                    <input type="text" class="form-control py-2" name="model" value="{{ old('model') }}" placeholder="Contoh: Avanza" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Tahun</label>
                                    <input type="number" class="form-control py-2" name="year" value="{{ old('year') }}" required min="1900" max="{{ date('Y') }}">
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Nomor Polisi</label>
                                    <input type="text" class="form-control py-2" name="license_plate" value="{{ old('license_plate') }}" placeholder="B 1234 ABC" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Warna</label>
                                <input type="text" class="form-control py-2" name="color" value="{{ old('color') }}" placeholder="Hitam Metalik" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Pemilik (Owner)</label>
                                <select class="form-select py-2" name="user_id" required>
                                    <option value="">-- Pilih Owner --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex gap-2 pt-2">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-pill w-100">
                                    <i class="bi bi-check-lg me-1"></i> Simpan Kendaraan
                                </button>
                                <a href="/admin/vehicle" class="btn btn-light px-4 py-2 fw-bold rounded-pill border w-50">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection