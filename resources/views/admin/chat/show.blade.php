@extends('be.master')

@section('chat')
<div class="container-fluid">
    <div class="card direct-chat direct-chat-primary">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3 class="card-title mb-0">Chat dengan: <strong>{{ $user->name }}</strong></h3>
            <div class="card-tools">
                <a href="/admin/chat" type="button" class="btn btn-tool" >
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="direct-chat-messages" style="min-height: 300px; max-height: 400px; overflow-y: auto;">
                @foreach($messages as $msg)
                    {{-- Jika pengirimnya admin, taruh di kanan (right) --}}
                    <div class="direct-chat-msg {{ $msg->pengirim == 'admin' ? 'end' : '' }} mb-3">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name {{ $msg->pengirim == 'admin' ? 'float-end' : 'float-start' }}">
                                {{ $msg->pengirim == 'admin' ? 'Me (Admin)' : $user->name }}
                            </span>
                        </div>
                        <div class="direct-chat-text {{ $msg->pengirim == 'admin' ? 'bg-primary text-white border-0' : '' }} d-inline-block" 
                             style="{{ $msg->pengirim == 'admin' ? 'float: right;' : '' }} max-width: 80%;">
                            {{ $msg->pesan }}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer">
            <form action="{{ route('chat.send') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="input-group">
                    <input type="text" name="pesan" placeholder="Ketik balasan..." class="form-control" required>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection