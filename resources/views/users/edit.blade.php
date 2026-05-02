@extends('be.master')

@section('Users')
<main class="app-main py-4">
    <div class="app-content-header mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="fw-bold mb-0 text-dark">Edit User Profile</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    
                    <div class="card border-0 shadow-lg overflow-hidden animate__animated animate__fadeInUp" style="border-radius: 20px; border-top: 5px solid #198754;">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="card-title fw-bold text-dark mb-0">Update Data: {{ $users->name }}</h5>
                        </div>

                        <form action="/admin/users/{{ $users->id }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="card-body p-4 p-md-5">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Nama User</label>
                                        <input type="text" class="form-control py-2 rounded-3 @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name', $users->name) }}" placeholder="Masukkan nama">
                                        @error('name') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Email</label>
                                        <input type="email" class="form-control py-2 rounded-3 @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email', $users->email) }}" placeholder="Masukkan email">
                                        @error('email') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Phone</label>
                                        <input type="text" class="form-control py-2 rounded-3 @error('phone') is-invalid @enderror" 
                                               name="phone" value="{{ old('phone', $users->phone) }}" placeholder="0812xxxx">
                                        @error('phone') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Role</label>
                                        <select class="form-select py-2 rounded-3 @error('role') is-invalid @enderror" name="role">
                                            <option value="admin" {{ old('role', $users->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="owner" {{ old('role', $users->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                                            <option value="kasir" {{ old('role', $users->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                            <option value="mekanik" {{ old('role', $users->role) == 'mekanik' ? 'selected' : '' }}>Mekanik</option>
                                            <option value="customer" {{ old('role', $users->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                                        </select>
                                        @error('role') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Address</label>
                                    <input type="text" class="form-control py-2 rounded-3 @error('address') is-invalid @enderror" 
                                           name="address" value="{{ old('address', $users->address) }}" placeholder="Masukkan alamat">
                                    @error('address') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Password Baru</label>
                                    <input type="password" class="form-control py-2 rounded-3 @error('password') is-invalid @enderror" 
                                           name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                    <small class="text-info mt-1 d-block"><i class="bi bi-info-circle me-1"></i> Biarkan kosong jika password tetap sama.</small>
                                    @error('password') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="card-footer bg-light p-4 border-0 d-flex gap-2">
                                <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow-sm rounded-pill w-100">
                                    <i class="bi bi-check-circle-fill me-1"></i> Update Profile
                                </button>
                                <a href="/admin/users" class="btn btn-light border px-4 py-2 fw-bold rounded-pill w-50 text-muted text-center text-decoration-none">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
    }
</style>
@endsection