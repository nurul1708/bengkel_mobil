@extends('be.master')

@section('Vehicle')
<main class="app-main py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8">
                
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div>
                        <h3 class="fw-bold mb-0 text-dark">Detail Unit</h3>
                        <p class="text-muted small mb-0">Informasi spesifikasi kendaraan dan data pemilik.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="/admin/vehicle/{{ $vehicle->id }}/edit" class="btn btn-warning shadow-sm px-4 rounded-pill fw-bold">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                        <a href="/admin/vehicle" class="btn btn-light border px-4 rounded-pill">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

                @php $isRejectedBooking = $vehicle->latestBooking?->status === 'cancelled'; @endphp

                <div class="card border-0 shadow-lg overflow-hidden {{ $isRejectedBooking ? 'vehicle-booking-rejected-card' : '' }}" style="border-radius: 20px;">
                    <div class="{{ $isRejectedBooking ? 'bg-danger' : 'bg-dark' }} p-4 text-center text-white">
                        <div class="small opacity-50 text-uppercase tracking-wider">Nomor Polisi</div>
                        <h2 class="display-6 fw-bold mb-0">{{ $vehicle->license_plate }}</h2>
                        @if($isRejectedBooking)
                            <span class="badge bg-light text-danger mt-3 px-3 py-2 rounded-pill">
                                Booking Terakhir Ditolak
                            </span>
                        @endif
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4">
                            <div class="col-md-6 border-md-end">
                                <h6 class="text-primary fw-bold text-uppercase mb-4">Spesifikasi Kendaraan</h6>
                                
                                <div class="mb-3">
                                    <label class="small text-muted d-block">Brand / Merk</label>
                                    <span class="fw-bold fs-5 text-dark">{{ $vehicle->brand }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="small text-muted d-block">Model / Seri</label>
                                    <span class="fw-bold fs-5 text-dark">{{ $vehicle->model }}</span>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="small text-muted d-block">Tahun Rilis</label>
                                        <span class="fw-bold text-dark">{{ $vehicle->year }}</span>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="small text-muted d-block">Warna</label>
                                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $vehicle->color ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-5">
                                <h6 class="text-danger fw-bold text-uppercase mb-4">Data Pemilik</h6>
                                
                                <div class="d-flex align-items-center flex-wrap gap-3 mb-4 bg-light p-3 rounded-4">
                                    <img
                                        src="{{ $vehicle->user && $vehicle->user->photo ? asset('storage/' . $vehicle->user->photo) : asset('be/assets/assets/img/no-img.jpg') }}"
                                        class="rounded-circle"
                                        width="60"
                                        height="60"
                                        style="object-fit: cover;"
                                        alt="{{ $vehicle->user->name ?? 'Owner' }}"
                                    >
                                    
                                    <div>
                                        <div class="fw-bold text-dark">{{ $vehicle->user->name ?? '-' }}</div>
                                        <div class="small text-muted">ID: #USR-{{ $vehicle->user->id ?? '0' }}</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="small text-muted d-block">Email Terdaftar</label>
                                    <span class="text-dark"><i class="bi bi-envelope me-2 text-muted"></i>{{ $vehicle->user->email ?? '-' }}</span>
                                </div>

                                <div class="mb-3">
                                    <label class="small text-muted d-block">Status Akun</label>
                                    <span class="badge bg-success-subtle text-success px-3 rounded-pill">Aktif / Terverifikasi</span>
                                </div>

                                <div class="mb-3">
                                    <label class="small text-muted d-block">Status Booking Terakhir</label>
                                    @if($vehicle->latestBooking)
                                        {!! $vehicle->latestBooking->status_badge !!}
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 rounded-pill">Belum Ada Booking</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 p-3 text-center">
                        <small class="text-muted">Data ini terakhir diperbarui pada: {{ $vehicle->updated_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .card-body label {
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .rounded-4 { border-radius: 1rem !important; }
    .vehicle-booking-rejected-card {
        border: 1px solid rgba(220, 53, 69, .25) !important;
    }
    @media (min-width: 768px) {
        .border-md-end {
            border-right: 1px solid var(--bs-border-color) !important;
        }
    }
    @media (max-width: 767.98px) {
        .card .btn {
            width: 100%;
        }
    }
</style>
@endsection
