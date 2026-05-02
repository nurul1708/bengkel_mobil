@extends('be.master')

@section('Vehicle')
<main class="app-main py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
                        <div class="d-flex">
                            <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="card border-0 shadow-lg" style="border-radius: 20px; border-top: 5px solid #ffc107;">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-warning-subtle text-warning rounded-circle p-3 me-3">
                                <i class="bi bi-pencil-square fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">Edit Data Kendaraan</h4>
                                <p class="text-muted small mb-0">Perbarui informasi kendaraan <strong>{{ $vehicle->license_plate }}</strong></p>
                            </div>
                        </div>

                        <form method="POST" action="/admin/vehicle/{{ $vehicle->id }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Brand</label>
                                    <input type="text" class="form-control py-2 border-warning-subtle" name="brand" value="{{ old('brand', $vehicle->brand) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Model</label>
                                    <input type="text" class="form-control py-2 border-warning-subtle" name="model" value="{{ old('model', $vehicle->model) }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Tahun</label>
                                    <input type="number" class="form-control py-2 border-warning-subtle" name="year" value="{{ old('year', $vehicle->year) }}" required min="1900" max="{{ date('Y') }}">
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">License Plate</label>
                                    <input type="text" class="form-control py-2 border-warning-subtle fw-bold" name="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Color (Warna)</label>
                                <input type="text" class="form-control py-2 border-warning-subtle" name="color" value="{{ old('color', $vehicle->color) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Owner (Pemilik)</label>
                                <select class="form-select py-2 border-warning-subtle" name="user_id" required>
                                    <option value="">-- Pilih Owner --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $vehicle->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex flex-column flex-sm-row gap-2 pt-2">
                                <button type="submit" class="btn btn-warning px-4 py-2 fw-bold shadow-sm rounded-pill w-100">
                                    <i class="bi bi-save2 me-1"></i> Update Perubahan
                                </button>
                                <a href="/admin/vehicle" class="btn btn-light px-4 py-2 fw-bold rounded-pill border w-100 btn-cancel-vehicle">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.1);
    }
    @media (min-width: 576px) {
        .btn-cancel-vehicle {
            width: auto !important;
        }
    }
</style>
@endsection
