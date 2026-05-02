@extends('be.master')
@section('Profile')
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Profile Saya</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="row g-4">
        <div class="col-12 col-md-4 col-lg-3">
          <!-- Profile Card -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img
                  class="profile-user-img img-fluid rounded-circle"
                  src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('be/assets/assets/img/no-img.jpg') }}"
                  alt="User profile picture"
                  style="width: 100px; height: 100px; object-fit: cover;"
                />
              </div>
              <h3 class="profile-username text-center mt-3">{{ $user->name }}</h3>
              <p class="text-muted text-center">
                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
              </p>
              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Email</b> <a class="float-end text-wrap">{{ $user->email }}</a>
                </li>
                <li class="list-group-item">
                  <b>Phone</b> <a class="float-end">{{ $user->phone ?? '-' }}</a>
                </li>
               
              </ul>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-8 col-lg-9">
          <!-- Edit Profile Card -->
          <div class="card card-warning card-outline">
            <div class="card-header">
              <h3 class="card-title">Edit Profile</h3>
            </div>

            <form method="POST" action="/admin/profile/update" enctype="multipart/form-data" class="card-body">
              @csrf

              <div class="row">
                <div class="col-12 col-md-6 mb-3">
                  <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" 
                         id="name" name="name" value="{{ old('name', $user->name) }}" required>
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-12 col-md-6 mb-3">
                  <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" 
                         id="email" name="email" value="{{ old('email', $user->email) }}" required>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-12 col-md-6 mb-3">
                  <label for="phone" class="form-label">Nomor Telepon</label>
                  <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                         id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                  @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-12 col-md-6 mb-3">
                  <label for="address" class="form-label">Alamat</label>
                  <textarea class="form-control @error('address') is-invalid @enderror" 
                            id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                  @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="mb-3">
                <label for="photo" class="form-label">Foto Profil</label>
                <div class="mb-2">
                  <img id="photoPreview" class="rounded-circle" 
                       src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('be/assets/assets/img/no-img.jpg') }}"
                       alt="Photo preview"
                       style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #ddd; padding: 3px;">
                </div>
                <div class="input-group">
                  <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                         id="photo" name="photo" accept="image/*">
                  <span class="input-group-text">Upload</span>
                </div>
                <small class="text-muted d-block mt-2">
                  Ukuran maksimal: 2MB. Format: JPG, PNG, GIF
                </small>
                @if($user->photo)
                  <small class="text-success d-block mt-2">
                    ✓ Foto saat ini: {{ basename($user->photo) }}
                  </small>
                @endif
                @error('photo')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <script>
                document.getElementById('photo').addEventListener('change', function(e) {
                  const file = e.target.files[0];
                  if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                      document.getElementById('photoPreview').src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                  }
                });
              </script>

              <div class="card-footer">
                <div class="d-flex flex-column flex-sm-row gap-2">
                  <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                  </button>
                  <a href="/admin/dashboard" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                  </a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
