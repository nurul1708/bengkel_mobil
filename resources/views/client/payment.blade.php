@extends('fe.master')

@section('Profile')
<style>
    .client-payment-page {
        --cp-primary: #0d6efd;
        --cp-dark: #0f172a;
        --cp-soft: #64748b;
        --cp-line: #e2e8f0;
        --cp-bg: #f8fafc;
        --cp-success: #16a34a;
    }

    .client-payment-page .cp-card {
        border: 0;
        border-radius: 24px;
        background: #fff;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
    }

    .client-payment-page .cp-hero {
        padding: 24px;
        border-radius: 24px;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.12), transparent 34%),
            linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(13, 110, 253, 0.1);
    }

    .client-payment-page .cp-method-option {
        position: relative;
        display: block;
        cursor: pointer;
    }

    .client-payment-page .cp-method-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .client-payment-page .cp-method-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 18px;
        border-radius: 18px;
        border: 1px solid var(--cp-line);
        background: #fff;
        transition: all 0.2s ease;
    }

    .client-payment-page .cp-method-option input:checked + .cp-method-box {
        border-color: var(--cp-primary);
        background: #f4f8ff;
        box-shadow: 0 14px 30px rgba(13, 110, 253, 0.14);
    }

    .client-payment-page .cp-method-option input:disabled + .cp-method-box {
        opacity: 0.55;
        cursor: not-allowed;
        background: #f8fafc;
    }

    .client-payment-page .cp-method-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--cp-bg);
        color: var(--cp-primary);
        font-size: 20px;
    }

    .client-payment-page .cp-mini-box {
        border-radius: 18px;
        background: var(--cp-bg);
        border: 1px solid #edf2f7;
    }

    .client-payment-page .cp-pay-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 16px;
        padding: 14px 16px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.1);
    }

    .client-payment-page .cp-step {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .client-payment-page .cp-step-index {
        width: 28px;
        height: 28px;
        flex: 0 0 28px;
        border-radius: 50%;
        background: rgba(13, 110, 253, 0.12);
        color: var(--cp-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
    }

    .client-payment-page .cp-status-text {
        min-height: 22px;
        font-size: 14px;
    }

</style>
<div class="container-xxl py-5 client-payment-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="cp-hero mb-5 wow fadeInUp">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-8">
                            <h6 class="text-primary text-uppercase">// Secure Checkout //</h6>
                            <h1 class="mb-2">Konfirmasi Pembayaran</h1>
                            <p class="text-muted mb-0">Saat memilih QRIS, halaman ini akan menampilkan panel scan yang terasa lebih dekat dengan pengalaman Midtrans.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <span class="text-muted small d-block">Total pembayaran</span>
                            <strong class="h3 text-primary mb-0">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm mb-4 wow fadeInLeft">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
    <img src="{{asset('be/assets/assets/img/logo.png')}}" alt="SerVix Logo" class="img-fluid" style="max-height: 100%; max-width: 100%;">
</div>
                                    <div>
                                        <h5 class="mb-1">Detail Servis #{{ $trx->booking->id }}</h5>
                                        <p class="text-muted mb-0">{{ $trx->service->service_name ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="small text-muted d-block">KENDARAAN</label>
                                        <span class="fw-bold">{{ ($trx->booking->vehicle->brand ?? '-') . ' ' . ($trx->booking->vehicle->model ?? '') }}</span>
                                    </div>
                                    <div class="col-6">
                                        <label class="small text-muted d-block">PLAT NOMOR</label>
                                        <span class="fw-bold">{{ $trx->booking->vehicle->license_plate ?? '-' }}</span>
                                    </div>
                                    <div class="col-6">
                                        <label class="small text-muted d-block">TANGGAL</label>
                                        <span><i class="fa fa-calendar-alt small text-primary me-1"></i> {{ $trx->booking->booking_date ?? '-' }}</span>
                                    </div>
                                    <div class="col-6">
                                        <label class="small text-muted d-block">WAKTU</label>
                                        <span><i class="fa fa-clock small text-primary me-1"></i> {{ $trx->booking->booking_time ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm wow fadeInUp">
                            <div class="card-header bg-white py-3 border-0">
                                <h6 class="mb-0 fw-bold"><i class="fa fa-cog me-2 text-primary"></i>Sparepart Yang Digunakan</h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Item</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end pe-4">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($trx->items ?? $trx->transactionSpareparts as $item)
                                            <tr class="border-bottom">
                                                <td class="ps-4">
                                                    <div class="fw-bold">{{ $item['sparepart_name'] ?? ($item->sparepart->name ?? '-') }}</div>
                                                    <div class="small text-muted">Rp {{ number_format($item['harga_beli'] ?? ($item->price ?? 0), 0, ',', '.') }}</div>
                                                </td>
                                                <td class="text-center">x{{ $item['jumlah_beli'] ?? ($item->qty ?? 0) }}</td>
                                                <td class="text-end pe-4 fw-bold">Rp {{ number_format($item['subtotal'] ?? ($item->subtotal ?? 0), 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-3 text-muted">Tidak ada sparepart tambahan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        @if(session('error'))
                            <div class="alert alert-danger border-0 shadow-sm wow fadeInDown">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="cp-card mb-4 wow fadeInRight">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div>
                                        <h5 class="mb-1">Ringkasan Pembayaran</h5>
                                        <p class="text-muted small mb-0">Pilih metode pembayaran dan lanjutkan proses sesuai instruksi.</p>
                                    </div>
                                    <span class="badge rounded-pill text-bg-primary px-3 py-2">Secure</span>
                                </div>

                                <div class="cp-mini-box p-3 mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Biaya Jasa</span>
                                        <strong>Rp {{ number_format($trx->total_service, 0, ',', '.') }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Total Sparepart</span>
                                        <strong>Rp {{ number_format($trx->total_sparepart, 0, ',', '.') }}</strong>
                                    </div>
                                    <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Total Bayar</h6>
                                        <h4 class="text-primary mb-0">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</h4>
                                    </div>
                                </div>

                                @if($trx->status !== 'paid')
                                    <form method="POST" action="{{ route('client.payment.store', $trx->id) }}" id="paymentForm">
                                        @csrf
                                        <input type="hidden" name="amount_paid" value="{{ $trx->grand_total }}">
                                        <input type="hidden" name="payment_method" id="customer_payment_method" value="cash">
                                        <input type="hidden" id="midtrans_order_id" value="">

                                        <div class="mb-4">
                                            <label class="form-label small fw-bold mb-3">METODE PEMBAYARAN</label>
                                            <div class="d-grid gap-3">
                                                <label class="cp-method-option">
                                                    <input type="radio" name="payment_method_display" value="cash" checked>
                                                    <span class="cp-method-box">
                                                        <span class="d-flex align-items-center gap-3">
                                                            <span class="cp-method-icon"><i class="fa fa-money-bill-wave"></i></span>
                                                            <span>
                                                                <strong class="d-block">Tunai di Bengkel</strong>
                                                                <small class="text-muted">Bayar langsung saat pengambilan</small>
                                                            </span>
                                                        </span>
                                                        <i class="fa fa-chevron-right text-muted"></i>
                                                    </span>
                                                </label>
                                                <label class="cp-method-option">
                                                    <input type="radio" name="payment_method_display" value="transfer">
                                                    <span class="cp-method-box">
                                                        <span class="d-flex align-items-center gap-3">
                                                            <span class="cp-method-icon"><i class="fa fa-university"></i></span>
                                                            <span>
                                                                <strong class="d-block">Transfer Bank</strong>
                                                                <small class="text-muted">Konfirmasi manual ke admin</small>
                                                            </span>
                                                        </span>
                                                        <i class="fa fa-chevron-right text-muted"></i>
                                                    </span>
                                                </label>
                                                <label class="cp-method-option">
                                                    <input type="radio" name="payment_method_display" value="midtrans" {{ $midtransEnabled ? '' : 'disabled' }}>
                                                    <span class="cp-method-box">
                                                        <span class="d-flex align-items-center gap-3">
                                                            <span class="cp-method-icon"><i class="fa fa-credit-card"></i></span>
                                                            <span>
                                                                <strong class="d-block">QRIS / Midtrans</strong>
                                                                <small class="text-muted">QRIS, VA bank, GoPay, ShopeePay, kartu, gerai retail</small>
                                                            </span>
                                                        </span>
                                                        <i class="fa fa-chevron-right text-muted"></i>
                                                    </span>
                                                </label>


                                            </div>
                                            @unless($midtransEnabled)
                                                <small class="text-danger d-block mt-3">Midtrans belum aktif. Isi `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` di file `.env`.</small>
                                            @endunless
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100 py-3 mt-2" id="paymentSubmitButton">
                                            PROSES PEMBAYARAN <i class="fa fa-arrow-right ms-2"></i>
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-success text-center py-4 border-0">
                                        <i class="fa fa-check-circle fa-3x mb-3 d-block"></i>
                                        <strong class="d-block mb-2 fs-5">LUNAS</strong>
                                        <span class="small d-block mb-3">Pembayaran berhasil diterima{{ $payment ? ' pada ' . \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') : '' }}.</span>
                                        <a href="{{ route('client.payment.invoice', $trx->id) }}" class="btn btn-dark px-4">
                                            <i class="fa fa-file-invoice me-2"></i>Lihat Invoice
                                        </a>
                                    </div>
                                @endif

                                <a href="{{ route('client.transactions.index') }}" class="btn btn-link w-100 text-muted mt-3 text-decoration-none small">
                                    <i class="fa fa-arrow-left me-1"></i> Kembali ke Transaksi
                                </a>
                            </div>
                        </div>

                        <div class="cp-card">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="cp-method-icon flex-shrink-0"><i class="fa fa-info-circle"></i></span>
                                    <div>
                                        <h6 class="mb-2">Informasi Penting</h6>
                                        <p class="small text-muted mb-0">Simpan bukti transaksi ini. Jika memilih Midtrans, Anda bisa memakai QRIS, Virtual Account, GoPay, ShopeePay, kartu, atau pembayaran di gerai retail.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($midtransEnabled && filled($midtransClientKey))
<script src="{{ $midtransSnapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentForm = document.getElementById('paymentForm');
        if (!paymentForm) return;

        const paymentMethod = document.getElementById('customer_payment_method');
        const paymentMethodOptions = document.querySelectorAll('input[name="payment_method_display"]');
        const paymentButton = document.getElementById('paymentSubmitButton');
        const midtransOrderId = document.getElementById('midtrans_order_id');
        let qrisLoaded = false;
        let snapToken = '';
        let snapRedirectUrl = '';

        const trxId = "{{ $trx->id }}";

        const resetQrMedia = () => {
            snapToken = '';
            snapRedirectUrl = '';
        };

        const openMidtransPopup = () => {
            if (!snapToken || !window.snap) {
                alert('Popup Midtrans belum siap. Silakan refresh halaman lalu coba lagi.');
                return;
            }

            window.snap.pay(snapToken, {
                onSuccess: function (result) {
                    midtransOrderId.value = result.order_id || midtransOrderId.value;
                    paymentForm.requestSubmit();
                },
                onPending: function (result) {
                    midtransOrderId.value = result.order_id || midtransOrderId.value;
                    alert('Transaksi Midtrans dibuat. Selesaikan pembayaran lalu sistem akan cek statusnya.');
                },
                onError: function (result) {
                    midtransOrderId.value = result.order_id || midtransOrderId.value;
                    alert('Pembayaran Midtrans gagal. Silakan coba lagi.');
                },
                onClose: function () {
                    if (midtransOrderId.value) {
                        alert('Popup Midtrans ditutup. Anda bisa buka lagi dari tombol pembayaran.');
                    }
                }
            });
        };

        const generateSnap = async () => {
            qrisLoaded = false;
            resetQrMedia();
            paymentButton.disabled = true;
            paymentButton.innerHTML = 'MENYIAPKAN MIDTRANS <i class="fa fa-spinner fa-spin ms-2"></i>';

            try {
                const url = "{{ route('client.payment.midtrans.snap', ':id') }}".replace(':id', trxId);
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const payload = await response.json();
                if (!response.ok) throw new Error(payload.message || 'Gagal membuat checkout Midtrans.');

                midtransOrderId.value = payload.order_id || '';
                snapToken = payload.snap_token || '';
                snapRedirectUrl = payload.redirect_url || '';
                qrisLoaded = true;
                paymentButton.innerHTML = 'BUKA CHECKOUT MIDTRANS <i class="fa fa-arrow-right ms-2"></i>';
            } catch (error) {
                alert(error.message || 'Gagal membuat checkout Midtrans.');
                paymentButton.innerHTML = 'BUAT CHECKOUT MIDTRANS <i class="fa fa-arrow-right ms-2"></i>';
            } finally {
                paymentButton.disabled = false;
            }
        };

        const renderPaymentState = async () => {
            if (paymentMethod.value === 'midtrans') {
                paymentButton.innerHTML = qrisLoaded
                    ? 'BUKA CHECKOUT MIDTRANS <i class="fa fa-arrow-right ms-2"></i>'
                    : 'BUAT CHECKOUT MIDTRANS <i class="fa fa-arrow-right ms-2"></i>';

                return;
            }

            paymentButton.innerHTML = 'PROSES PEMBAYARAN <i class="fa fa-arrow-right ms-2"></i>';
        };

        const launchMidtransCheckout = async () => {
            if (!qrisLoaded) {
                await generateSnap();
            }

            if (qrisLoaded) {
                openMidtransPopup();
            }
        };

        paymentMethodOptions.forEach((option) => {
            option.addEventListener('change', function () {
                paymentMethod.value = this.value;
                qrisLoaded = false;
                resetQrMedia();
                renderPaymentState();

                if (this.value === 'midtrans') {
                    launchMidtransCheckout();
                }
            });
        });

        if (paymentButton) {
            paymentButton.addEventListener('click', function (event) {
                if (paymentMethod.value !== 'midtrans') return;

                event.preventDefault();
                launchMidtransCheckout();
            });
        }

        renderPaymentState();

        paymentForm.addEventListener('submit', async function (e) {
            if (paymentMethod.value !== 'midtrans') return;
            e.preventDefault();

            if (!midtransOrderId.value) {
                alert('Tunggu checkout Midtrans selesai dibuat terlebih dahulu.');
                return;
            }

            const checkUrl = "{{ route('client.payment.midtrans.check', ':id') }}".replace(':id', trxId);
            paymentButton.disabled = true;
            paymentButton.innerHTML = 'MENGECEK STATUS <i class="fa fa-spinner fa-spin ms-2"></i>';

            try {
                const res = await fetch(checkUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order_id: midtransOrderId.value })
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Gagal cek status pembayaran.');

                if (data.status === 'paid') {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Pembayaran belum terdeteksi. Silakan selesaikan pembayaran dan coba lagi.');
                }
            } catch (err) {
                alert(err.message || 'Gagal cek status.');
            } finally {
                paymentButton.disabled = false;
                renderPaymentState();
            }
        });
    });
</script>
@endsection
