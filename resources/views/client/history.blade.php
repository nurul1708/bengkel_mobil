@extends('fe.master')

@section('Profile')
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="text-primary text-uppercase">// Riwayat //</h6>
            <h1 class="mb-5">Vehicle Booking History</h1>
        </div>

        <div class="card border-0 shadow-sm wow fadeIn" data-wow-delay="0.2s">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0">Kendaraan</th>
                                <th class="border-0">Layanan</th>
                                <th class="border-0">Jadwal</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-center pe-4">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $booking->vehicle->brand ?? '-' }} {{ $booking->vehicle->model ?? '' }}</div>
                                    <div class="small text-muted">{{ $booking->vehicle->license_plate ?? '-' }} ({{ $booking->vehicle->color ?? '' }})</div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $booking->service->service_name ?? '-' }}</span></td>
                                <td>
                                    <div class="small"><i class="fa fa-calendar-alt text-primary me-1"></i> {{ $booking->booking_date_label }}</div>
                                    <div class="small"><i class="fa fa-clock text-primary me-1"></i> {{ $booking->booking_time_label }}</div>
                                </td>
                                <td>{!! $booking->status_badge !!}</td>
                                <td class="text-center pe-4">
                                    @if(optional($booking->transaction)->status === 'paid')
                                        <a href="{{ route('client.payment.invoice', $booking->transaction->id) }}" class="btn btn-dark btn-sm rounded-pill px-3">
                                            Invoice
                                        </a>
                                    @else
                                        <span class="text-muted small">Belum tersedia</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat booking.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
