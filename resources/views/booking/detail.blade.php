@extends('be.master')

@section('booking')
<main class="app-main py-3">
    @php $role = auth()->user()->role ?? null; @endphp
    
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Detail Reservasi</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/booking" class="text-decoration-none">Booking</a></li>
                        <li class="breadcrumb-item active">#{{ $booking->id }}</li>
                    </ol>
                </nav>
            </div>
            <a href="/admin/booking" class="btn btn-light border shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center py-4">
                          <img src="{{ $booking->user && $booking->user->photo ? asset('storage/' . $booking->user->photo) : asset('be/assets/assets/img/no-img.jpg') }}"
                         class="rounded-circle me-2 mb-2" 
                         width="70" height="70" 
                         style="object-fit: cover;">
                        <h5 class="fw-bold mb-1">{{ $booking->user->name ?? '-' }}</h5>
                        <p class="text-muted small mb-3">Customer ID: #CUST-{{ $booking->user->id ?? '0' }}</p>
                        <p class="text-muted small mb-3">{{ $booking->user->email }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="/admin/chat/{{ $booking->user->id }}" class="badge bg-light text-dark border"><i class="bi bi-telephone me-1"></i> Hubungi</a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4 overflow-hidden">
    <div class="card-header bg-light border-0 pt-3 pb-2">
        <h6 class="fw-bold mb-0 text-primary">
            <i class="bi bi-car-front-fill me-2"></i>Unit Kendaraan
        </h6>
    </div>
    
    <div class="card-body p-0">
        <div class="p-3 text-center border-bottom bg-white">
            <h3 class="fw-bold mb-1 text-dark text-uppercase">
                {{ $booking->vehicle->brand ?? '-' }}
            </h3>
            <p class="text-muted mb-2 fs-7">{{ $booking->vehicle->model ?? '-' }}</p>
            
            <div class="d-inline-block p-2 bg-dark rounded shadow-inner" style="letter-spacing: 2px;">
                <span class="fw-bold text-white fs-5 font-monospace">
                    {{ $booking->vehicle->license_plate ?? 'B 1234 ABC' }}
                </span>
            </div>
        </div>

        <div class="p-3 bg-light-subtle">
            <div class="row text-center">
                <div class="col-6 border-end">
                    <small class="text-muted d-block mb-1">Tahun Rilis</small>
                    <div class="fw-bold text-dark fs-6">
                        <i class="bi bi-calendar3 text-primary me-1"></i>
                        {{ $booking->vehicle->year ?? '2020' }}
                    </div>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block mb-1">Status Unit</small>
                    <div class="fw-bold text-success fs-6">
                        <i class="bi bi-check-circle-fill me-1"></i>Terdaftar
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Rincian Layanan</h6>
                            <div>{!! $booking->status_badge !!}</div>
                        </div>
                    </div>
                    <div class="card-body border-top">
                        <div class="row g-4">
<div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Tipe Layanan</label>
                                <p class="fw-bold mb-0"><i class="bi bi-wrench-adjustable text-primary me-2"></i>{{ $booking->service->service_name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Mekanik</label>
                                <p class="fw-bold mb-0"><i class="bi bi-person-fill text-primary me-2"></i>{{ $booking->mekanik->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Jadwal Kedatangan</label>
                                <p class="fw-bold mb-0"><i class="bi bi-calendar-event text-primary me-2"></i>{{ $booking->booking_date_label }} ({{ $booking->booking_time_label }})</p>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small d-block mb-1">Keluhan Pelanggan</label>
                                <div class="p-3 rounded bg-light italic border-start border-4 border-primary">
                                    "{{ $booking->complaint ?? 'Tidak ada catatan keluhan.' }}"
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white py-3 border-0 d-flex flex-wrap gap-2 justify-content-end">
                        @if(in_array($role, ['admin']) && $booking->status === 'pending')
                            <form method="POST" action="/admin/booking/{{ $booking->id }}/verifikasi" class="m-0">
                                @csrf
                                <button type="submit" name="status" value="confirmed" class="btn btn-success px-4">Terima</button>
                                <button type="submit" name="status" value="cancelled" class="btn btn-outline-danger px-4">Tolak</button>
                            </form>
                        @endif

                        @if(in_array($role, ['mekanik']) && $booking->can_start_service)
                            <form method="POST" action="/admin/booking/{{ $booking->id }}/proses" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="bi bi-play-fill me-1"></i> Mulai Service</button>
                            </form>
                        @endif

                        @if(in_array($role, ['mekanik']) && $booking->status === 'in_progress')
                            <form method="POST" action="/admin/booking/{{ $booking->id }}/selesai" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-warning text-white px-4 fw-bold">Selesai</button>
                            </form>
                        @endif

                        @if(in_array($role, ['admin', 'kasir']) && $booking->status === 'completed')
                            <a href="/admin/transaksi/create?booking_id={{ $booking->id }}" class="btn btn-success px-4 fw-bold shadow-sm">
                                <i class="bi bi-cash-stack me-1"></i> Buat Pembayaran
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .app-main { background-color: #f8f9fa; min-height: 100vh; }
    .card { border-radius: 15px; transition: transform 0.2s; }
    .card:hover { transform: translateY(-3px); }
    .btn { border-radius: 10px; font-weight: 600; }
    .badge { padding: 0.6em 1em; border-radius: 8px; font-size: 0.85rem; }
    .italic { font-style: italic; }
</style>
@endsection