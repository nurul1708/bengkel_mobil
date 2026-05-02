@extends('fe.master')

@section('Profile')
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="text-primary text-uppercase">// Transaksi //</h6>
            <h1 class="mb-5">Billing & Transactions</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="card border-0 shadow-sm mb-4 wow fadeIn" data-wow-delay="0.2s">
            <div class="card-header bg-warning py-3">
                <h5 class="mb-0"><i class="fa fa-receipt me-2"></i>Tagihan Pembayaran</h5>
            </div>
            <div class="card-body">
                @if($paymentBookings->isEmpty())
                    <div class="text-center py-4">
                        <i class="fa fa-check-circle fa-3x text-light mb-3"></i>
                        <p class="text-muted mb-0">Tidak ada tagihan tertunda.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Booking</th>
                                    <th class="border-0">Layanan</th>
                                    <th class="border-0">Total</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentBookings as $bookingPayment)
                                <tr>
                                    <td class="fw-bold text-primary">#{{ $bookingPayment->id }}</td>
                                    <td>{{ $bookingPayment->service->service_name ?? '-' }}</td>
                                    <td class="fw-bold">Rp {{ number_format($bookingPayment->transaction->grand_total ?? 0, 0, ',', '.') }}</td>
                                    <td>{!! $bookingPayment->transaction->status_badge ?? '<span class="badge bg-secondary">Pending</span>' !!}</td>
                                    <td class="text-center">
                                        @if($bookingPayment->transaction)
                                            <a href="{{ route('client.payment.show', $bookingPayment->transaction->id) }}" class="btn btn-success btn-sm px-3 rounded-pill">
                                                Bayar <i class="fa fa-chevron-right ms-1 small"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">Menunggu Admin</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm wow fadeIn" data-wow-delay="0.3s">
            <div class="card-header bg-dark py-3">
                <h5 class="text-white mb-0"><i class="fa fa-wallet me-2"></i>Riwayat Transaksi</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0">Transaksi</th>
                                <th class="border-0">Layanan</th>
                                <th class="border-0">Dibayar</th>
                                <th class="border-0">Metode</th>
                                <th class="border-0">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">#{{ $payment->transaction_id }}</td>
                                <td>{{ $payment->transaction->service->service_name ?? '-' }}</td>
                                <td>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                                <td>{{ $payment->payment_method_label }}</td>
                                <td>{!! $payment->payment_status_badge !!}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada transaksi pembayaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@if(!$clientTestimonial && $eligibleTestimonialTransaction)
    <div class="modal fade" id="testimonialModal" tabindex="-1" aria-labelledby="testimonialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <div>
                        <h5 class="modal-title mb-1" id="testimonialModalLabel">
                            <i class="fa fa-star me-2"></i>Rating Service Pertama
                        </h5>
                        <small class="text-white-50">Booking #{{ $eligibleTestimonialTransaction->booking->id ?? '-' }}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('client.testimonial.store', $eligibleTestimonialTransaction->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <p class="text-muted small mb-4">
                            Terima kasih, pembayaran service pertama Anda sudah lunas. Silakan beri penilaian untuk membantu kami meningkatkan layanan.
                        </p>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Berikan Bintang</label>
                            <select name="rating" class="form-select bg-light border-0 shadow-none" required>
                                <option value="" selected disabled>Pilih Rating...</option>
                                <option value="5">5 - Sangat Puas</option>
                                <option value="4">4 - Puas</option>
                                <option value="3">3 - Cukup</option>
                                <option value="2">2 - Buruk</option>
                                <option value="1">1 - Sangat Buruk</option>
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-bold text-muted text-uppercase">Komentar / Saran</label>
                            <textarea name="comment" rows="4" class="form-control bg-light border-0 shadow-none" placeholder="Tuliskan pengalaman service Anda..." required>{{ old('comment') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0">
                        <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Nanti</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                            Kirim Penilaian <i class="fa fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const testimonialModal = document.getElementById('testimonialModal');

            if (!testimonialModal || !window.bootstrap) {
                return;
            }

            new bootstrap.Modal(testimonialModal).show();
        });
    </script>
@endif
@endsection
