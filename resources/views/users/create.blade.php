@extends('be.master')

@section('Users')
<main class="app-main py-4">
    <div class="app-content-header mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="fw-bold mb-0 text-dark">Tambah User Baru</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active">Tambah User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card border-0 shadow-lg overflow-hidden animate__animated animate__fadeInUp" style="border-radius: 20px; border-top: 5px solid #0d6efd;">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="card-title fw-bold text-dark mb-0">Form Input Data User</h5>
                        </div>

                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="card-body p-4 p-md-5">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Nama User</label>
                                        <input type="text" class="form-control py-2 rounded-3 @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name') }}" placeholder="Masukkan nama">
                                        @error('name') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Email</label>
                                        <input type="email" class="form-control py-2 rounded-3 @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" placeholder="email@example.com">
                                        @error('email') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Password</label>
                                        <input type="password" class="form-control py-2 rounded-3 @error('password') is-invalid @enderror" 
                                               name="password" placeholder="Minimal 6 karakter">
                                        @error('password') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Phone</label>
                                        <input type="text" class="form-control py-2 rounded-3 @error('phone') is-invalid @enderror" 
                                               name="phone" value="{{ old('phone') }}" placeholder="0812xxxx">
                                        @error('phone') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Role</label>
                                        <select class="form-select py-2 rounded-3 @error('role') is-invalid @enderror" name="role">
                                            <option value="">-- Pilih Role --</option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                                            <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                            <option value="mekanik" {{ old('role') == 'mekanik' ? 'selected' : '' }}>Mekanik</option>
                                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                                        </select>
                                        @error('role') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Address</label>
                                        <textarea class="form-control rounded-3 @error('address') is-invalid @enderror" 
                                                  name="address" rows="1" placeholder="Masukkan alamat">{{ old('address') }}</textarea>
                                        @error('address') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-light p-4 border-0 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-pill w-100">Simpan</button>
                                <a href="{{ route('users.index') }}" class="btn btn-light border px-4 py-2 fw-bold rounded-pill w-50">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection