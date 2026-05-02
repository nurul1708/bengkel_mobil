@extends('fe.master')

@section('Profile')
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="text-primary text-uppercase">// Profile //</h6>
            <h1 class="mb-5">Account Information</h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-primary py-3">
                        <h5 class="text-white mb-0"><i class="fa fa-user-circle me-2"></i>Edit Profile</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    @if($data->photo)
                                        <img id="profile-photo-preview" src="{{ asset('storage/'.$data->photo) }}" class="img-fluid rounded-circle border border-4 border-light shadow-sm" style="width: 130px; height: 130px; max-width: 40vw; max-height: 40vw; object-fit: cover;">
                                    @else
                                        <img id="profile-photo-preview" src="https://via.placeholder.com/130" class="img-fluid rounded-circle border border-4 border-light shadow-sm" style="width: 130px; height: 130px; max-width: 40vw; max-height: 40vw; object-fit: cover;">
                                    @endif
                                    <label for="photo" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2" style="cursor: pointer; width: 35px; height: 35px; line-height: 18px;">
                                        <i class="fa fa-camera small"></i>
                                        <input type="file" id="photo" name="photo" class="d-none">
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small text-muted fw-bold">NAMA LENGKAP</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text bg-light border-0"><i class="fa fa-id-card text-primary"></i></span>
                                    <input type="text" name="name" class="form-control border-0 bg-light" value="{{ old('name', $data->name) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small text-muted fw-bold">EMAIL</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text bg-light border-0"><i class="fa fa-envelope text-primary"></i></span>
                                    <input type="email" name="email" class="form-control border-0 bg-light" value="{{ old('email', $data->email) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small text-muted fw-bold">NO. HANDPHONE</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text bg-light border-0"><i class="fa fa-phone text-primary"></i></span>
                                    <input type="text" name="phone" class="form-control border-0 bg-light" value="{{ old('phone', $data->phone) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small text-muted fw-bold">ALAMAT</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text bg-light border-0"><i class="fa fa-map-marker-alt text-primary"></i></span>
                                    <textarea name="address" class="form-control border-0 bg-light" rows="3">{{ old('address', $data->address) }}</textarea>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="text-primary mb-3"><i class="fa fa-lock me-2"></i>Ganti Password</h6>

                            <div class="mb-3">
                                <input type="password" name="password" class="form-control border-0 bg-light" placeholder="Password Baru">
                            </div>
                            <div class="mb-4">
                                <input type="password" name="retype_password" class="form-control border-0 bg-light" placeholder="Ulangi Password">
                            </div>

                            <button class="btn btn-primary w-100 py-2">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('profile-photo-preview');

        if (!photoInput || !photoPreview) {
            return;
        }

        photoInput.addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                photoPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection
