@extends('be.master')
@section('Service')
<main class="app-main py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; border-top: 5px solid #0d6efd;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Layanan Baru</h4>
                        
                        <form action="{{ route('service.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">NAMA SERVICE</label>
                                <input type="text" name="service_name" class="form-control rounded-3 py-2" placeholder="Contoh: Service Rem" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">DESKRIPSI</label>
                                <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Apa saja yang dikerjakan..."></textarea>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">HARGA (RP)</label>
                                    <input type="number" name="price" class="form-control rounded-3 py-2" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">ESTIMASI (MENIT)</label>
                                    <input type="number" name="estimated_time" class="form-control rounded-3 py-2" required>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 10px;">SIMPAN DATA</button>
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