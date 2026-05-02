@extends('fe.master')

@section('chat')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-chat-dots-fill text-primary me-2"></i>Konsultasi Service Advisor</h5>
                </div>
                
                <div class="card-body bg-light" id="chatWindow" style="height: 450px; overflow-y: auto;">
                    @if($messages->isEmpty())
                        <div class="text-center mt-5">
                            <i class="bi bi-chat-square-text text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Belum ada percakapan. Silakan kirim pesan untuk memulai konsultasi.</p>
                        </div>
                    @else
                        @foreach($messages as $msg)
                            @php $isCustomerMessage = in_array($msg->pengirim, ['customer', 'user']); @endphp
                            <div class="d-flex {{ $isCustomerMessage ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                                <div class="p-3 rounded-3 shadow-sm {{ $isCustomerMessage ? 'bg-primary text-white' : 'bg-white text-dark border' }}" 
                                     style="max-width: 75%;">
                                    
                                    <div class="small fw-bold mb-1">
                                        {{ $isCustomerMessage ? 'Saya' : 'Service Advisor' }}
                                    </div>
                                    
                                    <div>{{ $msg->pesan }}</div>
                                    
                                    <div class="text-end mt-1 {{ $isCustomerMessage ? 'text-white-50' : 'text-muted' }}" 
                                         style="font-size: 0.75rem;">
                                        {{ $msg->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="card-footer bg-white py-3">
                    <form action="{{ route('chat.store') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            {{-- Input pesan otomatis menangkap info dari katalog jika ada --}}
                            <input type="text" 
                                   name="pesan" 
                                   class="form-control border-primary" 
                                   placeholder="Tulis keluhan atau pertanyaan..." 
                                   value="{{ request('produk') ? 'Halo, saya ingin tanya tentang sparepart: ' . request('produk') : '' }}"
                                   required>
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="bi bi-send-fill"></i> Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script agar otomatis scroll ke bawah saat halaman dibuka
    const chatWindow = document.getElementById('chatWindow');
    chatWindow.scrollTop = chatWindow.scrollHeight;
</script>
@endsection
