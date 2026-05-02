@extends('be.master')

@section('Users')
<main class="app-main py-3">
    @php $role = auth()->user()->role ?? null; @endphp
    
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-dark">Manajemen Users</h3>
                <p class="text-muted small mb-0">Kelola data pengguna dan hak akses sistem.</p>
            </div>
            @if(in_array($role, ['admin', 'owner']))
            <a href="/admin/users/create" class="btn btn-primary shadow-sm px-4 rounded-pill">
                <i class="bi bi-person-plus-fill me-1"></i> Add New User
            </a>
            @endif
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body">
                <form method="GET" action="/admin/users" class="row g-3 align-items-end">
                    <div class="col-md-9">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Search Users</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-light" value="{{ $search ?? '' }}" placeholder="Cari nama, email, phone, atau role">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Cari</button>
                        @if(!empty($search))
                            <a href="/admin/users" class="btn btn-light border rounded-pill w-100">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title fw-bold text-dark mb-0">Users List</h5>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted small fw-bold">USER</th>
                                <th class="py-3 text-muted small fw-bold">KONTAK</th>
                                <th class="py-3 text-muted small fw-bold">ALAMAT</th>
                                <th class="py-3 text-muted small fw-bold text-center">ROLE</th>
                                <th class="pe-4 py-3 text-muted small fw-bold text-end">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                                            <small class="text-muted">ID: #USR-{{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-bold text-dark">{{ $user->email }}</div>
                                    <div class="small text-muted">{{ $user->phone ?? '-' }}</div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($user->address, 40) ?? '-' }}</small>
                                </td>
                                <td class="text-center">
                                    @php
                                        $badgeColor = [
                                            'admin' => 'bg-danger-subtle text-danger',
                                            'owner' => 'bg-primary-subtle text-primary',
                                            'mekanik' => 'bg-success-subtle text-success',
                                            'customer' => 'bg-secondary-subtle text-secondary',
                                            'kasir' => 'bg-warning-subtle text-warning',
                                        ][$user->role] ?? 'bg-secondary-subtle text-secondary';
                                    @endphp
                                    <span class="badge {{ $badgeColor }} px-3 py-2 rounded-pill text-uppercase" style="font-size: 0.7rem;">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        @if(in_array($role, ['admin', 'owner']))
                                            <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-light btn-sm shadow-sm rounded-pill px-3 text-warning">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button class="btn btn-light btn-sm shadow-sm rounded-pill px-3 text-danger btn-hapus" data-id="{{ $user->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>

                                            <form id="form-hapus-{{ $user->id }}" action="/admin/users/{{ $user->id }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @else
                                            <span class="badge bg-light text-muted fw-normal border">Read Only</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    {{ !empty($search) ? 'User tidak ditemukan.' : 'Belum ada data user.' }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Logic untuk Delete (dipindahkan keluar dari if success agar selalu jalan)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Yakin mau hapus?',
                    text: "Data user ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-hapus-' + id).submit();
                    }
                });
            });
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#0d6efd'
    });
</script>
@endif

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonColor: '#dc3545'
    });
</script>
@endif
@endsection
