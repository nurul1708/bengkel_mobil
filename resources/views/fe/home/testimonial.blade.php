
<!-- Testimonial Start -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="text-center">
            <h6 class="text-primary text-uppercase">// Testimonial //</h6>
            <h1 class="mb-5">What Our Customers Say</h1>
        </div>

        @if(($testimonials ?? collect())->isNotEmpty())
            <div class="owl-carousel testimonial-carousel position-relative">
                @foreach($testimonials as $testimonial)
                    <div class="testimonial-item text-center">
                        <img
                            class="bg-light rounded-circle p-2 mx-auto mb-3"
                            src="{{ $testimonial->user && $testimonial->user->photo ? asset('storage/' . $testimonial->user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($testimonial->user->name ?? 'Customer') }}"
                            style="width: 80px; height: 80px; object-fit: cover;"
                        >
                        <h5 class="mb-1">{{ $testimonial->user->name ?? 'Customer' }}</h5>
                        <p class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-secondary' }}"></i>
                            @endfor
                        </p>
                        <div class="testimonial-text bg-light text-center p-4">
                            <p class="mb-0">"{{ $testimonial->comment }}"</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bg-light rounded p-4 text-center">
                        <h5 class="mb-2">Belum ada testimonial tampil</h5>
                        <p class="mb-0 text-muted">Testimonial dari customer yang sudah di-approve admin akan muncul di sini.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Testimonial End -->
