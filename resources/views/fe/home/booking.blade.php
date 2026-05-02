  <!-- Booking Start -->
    <div class="container-fluid bg-secondary booking my-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-6 py-5">
                    <div class="py-5">
                        <h1 class="text-white mb-4">Certified and Award Winning Car Repair Service Provider</h1>
                        <p class="text-white mb-0">Eirmod sed tempor lorem ut dolores. Aliquyam sit sadipscing kasd ipsum. Dolor ea et dolore et at sea ea at dolor, justo ipsum duo rebum sea invidunt voluptua. Eos vero eos vero ea et dolore eirmod et. Dolores diam duo invidunt lorem. Elitr ut dolores magna sit. Sea dolore sanctus sed et. Takimata takimata sanctus sed.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-primary h-100 d-flex flex-column justify-content-center text-center p-5 wow zoomIn" data-wow-delay="0.6s">
                        <h1 class="text-white mb-4">Book For A Service</h1>
                        @if (session('success'))
                            <div class="alert alert-success text-start">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger text-start">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @php
                            $isClientLoggedIn = auth()->guard('client')->check();
                        @endphp

                        @if (!$isClientLoggedIn)
                            <div class="alert alert-light text-start">
                                Silakan login dulu untuk membuat booking.
                            </div>
                        @else
                            <form action="{{ route('customer.bookings.store') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="form-control border-0" value="{{ auth('client')->user()->name }}" readonly style="height: 55px;">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="email" class="form-control border-0" value="{{ auth('client')->user()->email }}" readonly style="height: 55px;">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <select name="service_id" class="form-select border-0" style="height: 55px;" required>
                                            <option value="">Pilih Service</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}" {{ (string) old('service_id') === (string) $service->id ? 'selected' : '' }}>
                                                    {{ $service->service_name }} 
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <select name="vehicle_brand_id" id="vehicle_brand_id" class="form-select border-0" style="height: 55px;" required>
                                            <option value="">Pilih Merek Kendaraan</option>
                                            @foreach ($vehicleBrands ?? collect() as $brand)
                                                <option value="{{ $brand->id }}" {{ (string) old('vehicle_brand_id') === (string) $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <select name="vehicle_model_id" id="vehicle_model_id" class="form-select border-0" data-selected="{{ old('vehicle_model_id') }}" style="height: 55px;" required disabled>
                                            <option value="">Pilih Model Kendaraan</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="text" name="license_plate" class="form-control border-0" placeholder="Plat Nomor" value="{{ old('license_plate') }}" style="height: 55px;" required>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="text" name="color" class="form-control border-0" placeholder="Warna Kendaraan" value="{{ old('color') }}" style="height: 55px;" required>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="text" name="year" class="form-control border-0" placeholder="Tahun Kendaraan" value="{{ old('year') }}" style="height: 55px;" required>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="date" name="booking_date" class="form-control border-0" value="{{ old('booking_date') }}" min="{{ now()->toDateString() }}" style="height: 55px;" required>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="time" name="booking_time" class="form-control border-0" value="{{ old('booking_time') }}" style="height: 55px;" required>
                                    </div>
                                    <div class="col-12">
                                        <textarea name="complaint" class="form-control border-0" placeholder="Keluhan kendaraan" required>{{ old('complaint') }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-secondary w-100 py-3" type="submit">Book Now</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Booking End -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const brandSelect = document.getElementById('vehicle_brand_id');
            const modelSelect = document.getElementById('vehicle_model_id');

            if (!brandSelect || !modelSelect) {
                return;
            }

            const selectedModel = modelSelect.dataset.selected;

            function resetModels() {
                modelSelect.innerHTML = '<option value="">Pilih Model Kendaraan</option>';
                modelSelect.disabled = true;
            }

            async function loadModels(brandId, modelId = '') {
                resetModels();

                if (!brandId) {
                    return;
                }

                try {
                    const response = await fetch(`/customer/vehicle-brands/${brandId}/models`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const models = await response.json();

                    models.forEach(function (model) {
                        const option = document.createElement('option');
                        option.value = model.id;
                        option.textContent = model.name;
                        option.selected = String(model.id) === String(modelId);
                        modelSelect.appendChild(option);
                    });

                    modelSelect.disabled = false;
                } catch (error) {
                    resetModels();
                }
            }

            brandSelect.addEventListener('change', function () {
                loadModels(this.value);
            });

            if (brandSelect.value) {
                loadModels(brandSelect.value, selectedModel);
            }
        });
    </script>
