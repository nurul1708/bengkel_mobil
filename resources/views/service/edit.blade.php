@extends('be.master')
@section('Service')
<main class="app-main py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; border-top: 5px solid #ffc107;">
                    <div class="card-body p-4 text-dark">
                        <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Detail Layanan</h4>
                        
                        <form method="POST" action="{{ route('service.update', $service->id) }}">
                            @csrf
                            @method('PUT') {{-- INI WAJIB ADA BIAR TIDAK ERROR 405 --}}

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Service</label>
                                <input type="text" name="service_name" class="form-control rounded-3 py-2 border-warning-subtle" value="{{ $service->service_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi</label>
                                <textarea name="description" class="form-control rounded-3 border-warning-subtle" rows="3">{{ $service->description }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Harga (Rp)</label>
                                    <input type="number" name="price" class="form-control rounded-3 py-2 text-success fw-bold border-warning-subtle" value="{{ $service->price }}" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Estimasi (Menit)</label>
                                    <input type="number" name="estimated_time" class="form-control rounded-3 py-2 border-warning-subtle" value="{{ $service->estimated_time }}" required>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-warning w-100 py-2 fw-bold text-dark" style="border-radius: 10px;">UPDATE PERUBAHAN</button>
                                <a href="{{ route('service.index') }}" class="btn btn-light border w-50 py-2 fw-bold" style="border-radius: 10px;">BATAL</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection