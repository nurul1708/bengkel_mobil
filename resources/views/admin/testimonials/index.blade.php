@extends('be.master')

@section('TestimonialReview')
<style>
    .testimonial-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 12px;
    }
    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    .avatar-circle {
        width: 45px;
        height: 45px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #6c757d;
    }
    .rating-stars {
        color: #ffc107;
        font-size: 0.9rem;
    }
    .status-badge {
        padding: 0.5em 1em;
        border-radius: 50rem;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .bg-pending { background-color: #fff3cd; color: #856404; }
    .bg-approved { background-color: #d4edda; color: #155724; }
    .bg-rejected { background-color: #f8d7da; color: #721c24; }
    @media (max-width: 767.98px) {
        .testimonial-card .card-body {
            padding: 1rem;
        }
        .testimonial-card .status-badge {
            display: inline-flex;
            margin-top: 0.75rem;
        }
    }
</style>

<main class="app-main py-4">
    <div class="app-content-header mb-4">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px; background: linear-gradient(to right, #ffffff, #f8f9fa);">
                <div class="row align-items-center g-3">
                    <div class="col-md-7">
                        <h3 class="fw-bold mb-1 text-dark">Review Testimonial Customer</h3>
                        <p class="text-muted mb-0">Moderasi ulasan pelanggan untuk menjaga kualitas konten website Anda.</p>
                    </div>
                    <div class="col-md-5">
                        <form method="GET" action="/admin/testimonials" class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-filter"></i></span>
                                <select name="status" class="form-select border-start-0 ps-0">
                                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua</option>
                                </select>
                            </div>
                            <button class="btn btn-dark px-4 shadow-sm">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                @forelse($testimonials as $testimonial)
                    <div class="col-12 col-xl-6">
                        <div class="card testimonial-card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle border">
                                            {{ strtoupper(substr($testimonial->user->name ?? 'C', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $testimonial->user->name ?? 'Customer' }}</h6>
                                            <small class="text-muted">Booking #{{ $testimonial->booking_id }}</small>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="status-badge bg-{{ $testimonial->status === 'approved' ? 'approved' : ($testimonial->status === 'rejected' ? 'rejected' : 'pending') }}">
                                            • {{ strtoupper($testimonial->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="rating-stars mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                        <span class="ms-2 text-dark fw-bold">({{ $testimonial->rating }}/5)</span>
                                    </div>
                                    <div class="badge bg-light text-dark border fw-normal">
                                        <i class="bi bi-tag me-1"></i> {{ $testimonial->booking->service->service_name ?? 'Service' }}
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded-3 mb-3 position-relative">
                                    <i class="bi bi-quote fs-4 text-secondary opacity-25 position-absolute top-0 start-0 p-1"></i>
                                    <p class="mb-0 text-dark small ps-3"><em>"{{ $testimonial->comment }}"</em></p>
                                </div>

                                @if($testimonial->admin_note)
                                    <div class="mb-3 small">
                                        <span class="text-muted fw-bold">Catatan Admin:</span>
                                        <p class="text-secondary mb-0 p-2 border-start border-3">{{ $testimonial->admin_note }}</p>
                                    </div>
                                @endif

                                <hr class="my-4 opacity-50">

                                @if($testimonial->status === 'pending')
                                    <div class="row g-2">
                                        <div class="col-12 col-sm-6">
                                            <form method="POST" action="/admin/testimonials/{{ $testimonial->id }}/approve?status={{ $status }}">
                                                @csrf
                                                <button class="btn btn-outline-success w-100 fw-bold rounded-pill">
                                                    <i class="bi bi-check2-circle me-1"></i> Approve
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <form method="POST" action="/admin/testimonials/{{ $testimonial->id }}/reject?status={{ $status }}">
                                                @csrf
                                                <button class="btn btn-outline-danger w-100 fw-bold rounded-pill">
                                                    <i class="bi bi-x-circle me-1"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center text-muted small mt-2">
                                        <i class="bi bi-person-check-fill me-2 text-primary"></i>
                                        <span>
                                            Direview oleh <strong class="text-dark">{{ $testimonial->reviewer->name ?? 'Admin' }}</strong>
                                            @if($testimonial->reviewed_at)
                                                <span class="ms-1">
                                                    pada {{ $testimonial->reviewed_at->translatedFormat('d M Y') }}
                                                    <span class="badge bg-light text-dark border-0 fw-normal">
                                                        <i class="bi bi-clock me-1"></i> {{ $testimonial->reviewed_at->format('H:i') }}
                                                    </span>
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5 class="text-muted">Tidak ada testimonial yang ditemukan.</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection
