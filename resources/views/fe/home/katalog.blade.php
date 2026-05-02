<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="text-center">
            <h6 class="text-primary text-uppercase">// Spareparts //</h6>
            <h1 class="mb-5">Our Sparepats</h1>
        </div>
       <div class="owl-carousel testimonial-carousel position-relative">
    {{-- Use foreach to loop through the collection --}}
    @foreach($spareparts as $s)
    <div class="testimonial-item text-center">
        <img class="bg-light p-2 mx-auto mb-3"
             src="{{ $s->gambar_url }}"
             style="width: 150px; height: 150px; object-fit: contain;"
             alt="{{ $s->name }}">
        
        <h5 class="mb-0">{{ $s->name }}</h5>
        <p class="text-primary fw-bold">Rp {{ number_format($s->harga_jual, 0, ',', '.') }}</p>
         <p class="mb-3">{{ $s->brand }}</p>
    
    </div>
    @endforeach
</div>
    </div>
</div>
