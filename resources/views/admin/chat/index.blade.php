@extends('be.master')

@section('chat')
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.chat.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-9">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-2">Search Chat</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-0 bg-light" value="{{ $search ?? '' }}" placeholder="Cari nama atau email customer">
                    </div>
                </div>
                <div class="col-12 col-md-3 d-flex flex-column flex-sm-row gap-2 ">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Cari</button>
                    @if(!empty($search))
                        <a href="{{ route('admin.chat.index') }}" class="btn btn-light border w-100">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pesan Pelanggan</h3>
        </div>
        <div class="card-body p-0">
            <ul class="nav nav-pills flex-column">
                @foreach($kontak as $item)
                <li class="nav-item border-bottom">
                    <a href="{{ route('admin.chat.show', $item->user_id) }}" class="nav-link text-dark py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle fs-4 me-2 d-none d-sm-block"></i>
                                <div>
                                    <strong>{{ $item->user->name }}</strong>
                                    <p class="text-muted small mb-0 d-block d-md-none">{{ Str::limit($item->user->email, 20) }}</p>
                                    <p class="text-muted small mb-0 d-none d-md-block">{{ $item->user->email }}</p>
                                </div>
                            </div>
                            <div class="text-end">
                                @if(($item->unread_count ?? 0) > 0)
                                    <span class="badge bg-danger">{{ $item->unread_count }} pesan baru</span>
                                @else
                                    <span class="badge bg-primary">Lihat Chat</span>
                                @endif
                                <br>
                                <small class="text-muted">{{ $item->last_chat }}</small>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach

                @if($kontak->isEmpty())
                <li class="nav-item p-4 text-center text-muted">{{ !empty($search) ? 'Chat tidak ditemukan.' : 'Belum ada pesan masuk.' }}</li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection
