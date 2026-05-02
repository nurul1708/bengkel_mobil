@extends('be.master')

@section('Transaksi')
<main class="app-main py-4 admin-payment-page" style="background: linear-gradient(180deg, #eef4fb 0%, #f8fbff 100%); min-height: calc(100vh - 120px);">
  @php $role = auth()->user()->role ?? null; @endphp

  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h3 class="fw-bold text-dark mb-0">
            <i class="bi bi-receipt-cutoff me-2 text-primary"></i>Detail Transaksi
          </h3>
          <p class="text-muted small mb-0">Invoice ID: <span class="fw-bold text-primary">#{{ $trx->id }}</span></p>
        </div>
        <div class="col-md-6 text-md-end">
          <nav aria-label="breadcrumb" class="d-inline-block">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="/admin/transaksi" class="text-decoration-none">Transaksi</a></li>
              <li class="breadcrumb-item active">Detail</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
          <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
          <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
      @endif

      <div class="admin-checkout-hero mb-4">
        <div class="row align-items-center g-4">
          <div class="col-lg-8">
            <h6 class="text-primary text-uppercase fw-bold mb-2">// Admin Checkout //</h6>
            <h2 class="fw-bold text-dark mb-2">Konfirmasi Pembayaran Transaksi</h2>
            <p class="text-muted mb-0">
              Saat metode Midtrans dipilih, popup checkout akan muncul di atas halaman ini dan background transaksi admin tetap terlihat seperti flow customer.
            </p>
          </div>
          <div class="col-lg-4 text-lg-end">
            <span class="text-muted small d-block">Total pembayaran</span>
            <strong class="h2 text-primary mb-0">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</strong>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 admin-info-card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
              <h5 class="fw-bold mb-0">Informasi Unit & Layanan</h5>
              <div>
                @if($trx->status == 'paid')
                  <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Lunas</span>
                @elseif($trx->status == 'partial')
                  <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">Sebagian</span>
                @else
                  <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Belum Bayar</span>
                @endif
              </div>
            </div>

            <div class="card-body bg-light-subtle">
              <div class="row g-4 mb-4">
                <div class="col-md-4">
                  <label class="text-muted small fw-bold mb-1 d-block text-uppercase">Booking ID</label>
                  <p class="fw-bold mb-0 text-dark">#{{ $trx->booking->id ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                  <label class="text-muted small fw-bold mb-1 d-block text-uppercase">Pelanggan</label>
                  <p class="fw-bold mb-0 text-dark">{{ $trx->booking->user->name ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                  <label class="text-muted small fw-bold mb-1 d-block text-uppercase">Kendaraan</label>
                  <p class="fw-bold mb-0 text-dark">{{ $trx->booking->vehicle->brand ?? '-' }} {{ $trx->booking->vehicle->model ?? '' }}</p>
                </div>
              </div>

              <div class="row g-4 mb-4">
                <div class="col-md-4">
                  <label class="text-muted small fw-bold mb-1 d-block text-uppercase">Jenis Service</label>
                  <p class="fw-bold mb-0 text-primary">{{ $trx->service->service_name ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                  <label class="text-muted small fw-bold mb-1 d-block text-uppercase">Tanggal Booking</label>
                  <p class="fw-bold mb-0">{{ $trx->booking->booking_date ?? '-' }}</p>
                </div>
                <div class="col-md-4">
                  <label class="text-muted small fw-bold mb-1 d-block text-uppercase">Jam Kedatangan</label>
                  <p class="fw-bold mb-0">{{ $trx->booking->booking_time ?? '-' }}</p>
                </div>
              </div>

              <hr class="my-4 opacity-25">

              <h6 class="fw-bold mb-3 mt-2"><i class="bi bi-box-seam me-2"></i>Daftar Penggantian Sparepart</h6>
              @if(!empty($trx->items) && count($trx->items))
                <div class="table-responsive rounded-3 border">
                  <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                      <tr class="small text-uppercase">
                        <th class="ps-3">Nama Sparepart</th>
                        <th>Harga</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end pe-3">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($trx->items as $item)
                        <tr>
                          <td class="ps-3 fw-medium">{{ $item['sparepart_name'] ?? '-' }}</td>
                          <td>Rp {{ number_format($item['harga_beli'], 0, ',', '.') }}</td>
                          <td class="text-center">{{ $item['jumlah_beli'] }}</td>
                          <td class="text-end pe-3 fw-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                    <tfoot class="table-light border-top-0">
                      <tr>
                        <td colspan="3" class="text-end fw-bold py-3">Total Sparepart:</td>
                        <td class="text-end pe-3 fw-bold text-primary py-3 fs-5">Rp {{ number_format($trx->total_sparepart, 0, ',', '.') }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              @else
                <div class="alert alert-light border text-center py-4">
                  <i class="bi bi-info-circle me-2"></i> Tidak ada penggantian sparepart pada transaksi ini.
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card border-0 shadow-sm rounded-4 mb-4 admin-summary-card text-white">
            <div class="card-body p-4">
              <h5 class="fw-bold mb-4">Ringkasan Biaya</h5>
              <div class="d-flex justify-content-between mb-2 opacity-75">
                <span>Biaya Jasa:</span>
                <span>Rp {{ number_format($trx->total_service, 0, ',', '.') }}</span>
              </div>
              <div class="d-flex justify-content-between mb-3 opacity-75">
                <span>Total Sparepart:</span>
                <span>Rp {{ number_format($trx->total_sparepart, 0, ',', '.') }}</span>
              </div>
              <hr class="border-white opacity-25">
              <div class="d-flex justify-content-between align-items-center">
                <span class="h5 mb-0 fw-bold">Grand Total:</span>
                <span class="h3 mb-0 fw-bold">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</span>
              </div>
            </div>
          </div>

          @if(in_array($role, ['admin', 'kasir']) && $trx->status != 'paid')
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 admin-payment-card">
              <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0">Form Pembayaran</h6>
              </div>
              <form method="POST" action="/admin/transaksi/{{ $trx->id }}/bayar" id="adminPaymentForm">
                @csrf
                <div class="card-body">
                  <input type="hidden" id="admin_midtrans_order_id" value="">
                  <input type="hidden" id="admin_midtrans_redirect_url" value="">
                  <div class="mb-3">
                    <label class="form-label small fw-bold">Nominal Bayar</label>
                    <div class="input-group">
                      <span class="form-control bg-light border-start-0 fw-bold">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</span>
                     
                  </div>

                  <div class="mb-3">
                    <label class="form-label small fw-bold">Metode Pembayaran</label>
                    <select name="payment_method" class="form-select" id="payment_method" required>
                      <option value="cash" selected>Tunai (Cash)</option>
                      <option value="transfer">Transfer Bank</option>
                      <option value="midtrans" {{ $midtransEnabled ? '' : 'disabled' }}>QRIS / Midtrans Popup</option>
                    </select>
                    @unless($midtransEnabled)
                      <small class="text-danger d-block mt-2">Midtrans belum aktif. Isi `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` di file `.env`.</small>
                    @endunless
                  </div>

                    <div id="qris_section" class="mt-4 p-3 border rounded-4 admin-qris-panel" style="display: none;">
                      <div class="text-center mb-3">
                        <div class="small text-muted text-uppercase fw-bold">Popup Midtrans</div>
                        <div class="fw-semibold">Checkout admin</div>
                      </div>
                    <div id="admin_qris_loading" class="text-center d-none">
                      <div class="spinner-border text-primary mb-3" role="status"></div>
                      <div class="fw-semibold">Menyiapkan checkout Midtrans...</div>
                    </div>
                    <div id="admin_qris_media" class="d-none">
                      <div class="small text-muted text-center mb-2" id="admin_midtrans_order_label">Order belum dibuat.</div>
                      <div class="small text-muted text-center mb-3">Saat pilih QRIS / Midtrans, popup checkout akan terbuka seperti di halaman customer.</div>
                    </div>
                    <div id="admin_qris_error" class="alert alert-danger d-none mt-3 mb-0"></div>
                    <div class="text-center fw-bold text-primary mb-3">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</div>
                    <div class="d-grid gap-2">
                      <button type="button" class="btn btn-primary rounded-pill px-4 py-2" id="adminOpenSnapButton" disabled>
                        Buka Popup Midtrans
                      </button>
                      <button type="button" class="btn btn-outline-primary rounded-pill px-4 py-2" id="adminQrisCheckButton">
                        Cek Status Pembayaran
                      </button>
                    </div>
                  </div>
                </div>
                <div class="card-footer bg-white border-0 p-3 pt-0">
                  <button class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" type="submit" id="adminPaymentSubmitButton">
                    Proses Pembayaran
                  </button>
                </div>
              </form>
            </div>
          @endif

          <div class="d-grid gap-2">
            @if($trx->status == 'paid')
              <a href="/admin/transaksi/{{ $trx->id }}/invoice" class="btn btn-dark rounded-pill shadow-sm">
                <i class="bi bi-printer me-2"></i>Cetak Invoice
              </a>
            @endif
            <a href="/admin/transaksi" class="btn btn-light rounded-pill border shadow-sm">
              <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

@if($midtransEnabled && filled($midtransClientKey))
<script src="{{ $midtransSnapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
@endif
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const paymentMethod = document.getElementById('payment_method');
    const qrisSection = document.getElementById('qris_section');
    const qrisLoading = document.getElementById('admin_qris_loading');
    const qrisMedia = document.getElementById('admin_qris_media');
    const qrisError = document.getElementById('admin_qris_error');
    const qrisCheckButton = document.getElementById('adminQrisCheckButton');
    const openSnapButton = document.getElementById('adminOpenSnapButton');
    const orderLabel = document.getElementById('admin_midtrans_order_label');
    const submitButton = document.getElementById('adminPaymentSubmitButton');
    const paymentForm = document.getElementById('adminPaymentForm');
    const midtransOrderId = document.getElementById('admin_midtrans_order_id');
    const trxId = @json($trx->id);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || @json(csrf_token());
    const midtransEnabled = @json($midtransEnabled);
    let qrisLoaded = false;
    let snapToken = '';

    if (!paymentMethod || !qrisSection) return;

    const resetQrisState = () => {
      qrisLoaded = false;

      if (midtransOrderId) {
        midtransOrderId.value = '';
      }

      if (qrisMedia) {
        qrisMedia.classList.add('d-none');
      }

      if (orderLabel) {
        orderLabel.textContent = 'Order belum dibuat.';
      }

      if (qrisError) {
        qrisError.textContent = '';
        qrisError.classList.add('d-none');
      }

      if (qrisLoading) {
        qrisLoading.classList.add('d-none');
      }

      if (openSnapButton) {
        openSnapButton.disabled = true;
      }

      snapToken = '';
    };

    const openMidtransPopup = () => {
      if (snapToken && window.snap) {
        window.snap.pay(snapToken, {
          onSuccess: function (result) {
            if (midtransOrderId) {
              midtransOrderId.value = result.order_id || midtransOrderId.value;
            }
            if (qrisCheckButton) {
              qrisCheckButton.click();
            }
          },
          onPending: function (result) {
            if (midtransOrderId) {
              midtransOrderId.value = result.order_id || midtransOrderId.value;
            }
            alert('Transaksi Midtrans dibuat. Selesaikan pembayaran lalu sistem akan cek statusnya.');
          },
          onError: function () {
            alert('Pembayaran Midtrans gagal diproses.');
          },
          onClose: function () {
            if (midtransOrderId?.value) {
              alert('Popup Midtrans ditutup. Anda bisa membukanya lagi dari tombol pembayaran.');
            }
          }
        });
        return;
      }

      alert('Popup Midtrans belum siap. Silakan refresh halaman lalu coba lagi.');
    };

    const generateSnap = async () => {
      if (!midtransEnabled) return;

      resetQrisState();

      if (qrisLoading) {
        qrisLoading.classList.remove('d-none');
      }

      try {
        const response = await fetch("{{ route('admin.transaksi.midtrans.snap', ':id') }}".replace(':id', trxId), {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
        });

        const payload = await response.json();

        if (!response.ok) {
          throw new Error(payload.message || 'Gagal membuat checkout Midtrans.');
        }

        if (midtransOrderId) {
          midtransOrderId.value = payload.order_id || '';
        }

        if (orderLabel) {
          orderLabel.textContent = payload.order_id
            ? `Order ID: ${payload.order_id}`
            : 'Order berhasil dibuat.';
        }

        snapToken = payload.snap_token || '';

        if (payload.snap_token) {
          qrisMedia.classList.remove('d-none');
          qrisLoaded = true;
          if (openSnapButton) {
            openSnapButton.disabled = false;
          }
          if (submitButton) {
            submitButton.innerHTML = 'Buka Checkout Midtrans <i class="bi bi-arrow-right ms-2"></i>';
          }
        } else {
          throw new Error('Midtrans tidak mengembalikan token checkout.');
        }
      } catch (error) {
        if (qrisError) {
          qrisError.textContent = error.message;
          qrisError.classList.remove('d-none');
        }
      } finally {
        if (qrisLoading) {
          qrisLoading.classList.add('d-none');
        }
      }
    };

    const renderQrisSection = () => {
      if (paymentMethod.value === 'midtrans') {
        qrisSection.style.display = 'block';

        if (submitButton) {
          submitButton.innerHTML = qrisLoaded
            ? 'Buka Checkout Midtrans <i class="bi bi-arrow-right ms-2"></i>'
            : 'Buat Checkout Midtrans <i class="bi bi-arrow-right ms-2"></i>';
        }
      } else {
        qrisSection.style.display = 'none';

        if (submitButton) {
          submitButton.textContent = 'Proses Pembayaran';
        }
      }
    };

    const launchMidtransCheckout = async () => {
      if (qrisLoaded) {
        openMidtransPopup();
        return;
      }

      if (!qrisLoaded) {
        await generateSnap();
      }

      if (qrisLoaded) {
        openMidtransPopup();
      }
    };

    if (paymentForm) {
      paymentForm.addEventListener('submit', function (event) {
        if (paymentMethod.value === 'midtrans') {
          event.preventDefault();
          launchMidtransCheckout();
        }
      });
    }

    if (openSnapButton) {
      openSnapButton.addEventListener('click', function () {
        launchMidtransCheckout();
      });
    }

    if (qrisCheckButton) {
      qrisCheckButton.addEventListener('click', async function () {
        if (!midtransOrderId || !midtransOrderId.value) {
          alert('Tunggu checkout Midtrans selesai dibuat terlebih dahulu.');
          return;
        }

        qrisCheckButton.disabled = true;
        qrisCheckButton.textContent = 'Mengecek Status...';

        try {
          const response = await fetch("{{ route('admin.transaksi.midtrans.check', ':id') }}".replace(':id', trxId), {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            body: JSON.stringify({ order_id: midtransOrderId.value }),
          });

          const payload = await response.json();

          if (!response.ok) {
            throw new Error(payload.message || 'Gagal cek status pembayaran.');
          }

          if (payload.status === 'paid') {
            window.location.reload();
            return;
          }

          alert('Status pembayaran masih belum lunas.');
        } catch (error) {
          alert(error.message);
        } finally {
          qrisCheckButton.disabled = false;
          qrisCheckButton.textContent = 'Cek Status Pembayaran';
        }
      });
    }

    paymentMethod.addEventListener('change', function () {
      renderQrisSection();

      if (paymentMethod.value === 'midtrans') {
        launchMidtransCheckout();
      }
    });
    renderQrisSection();
  });
</script>

<style>
  .admin-payment-page {
    --ap-primary: #0d6efd;
    --ap-dark: #0f172a;
    --ap-soft: #64748b;
    --ap-line: #e2e8f0;
    --ap-bg: #f8fafc;
  }
  .app-main {
    background: linear-gradient(180deg, #eef4fb 0%, #f8fbff 100%) !important;
  }
  .admin-checkout-hero {
    padding: 28px 30px;
    border-radius: 24px;
    background:
      radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 34%),
      linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    border: 1px solid rgba(13, 110, 253, 0.10);
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
  }
  .admin-info-card {
    background: #ffffff;
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08) !important;
  }
  .admin-info-card .card-body {
    background:
      linear-gradient(180deg, rgba(248, 251, 255, 0.96) 0%, rgba(244, 248, 255, 0.96) 100%) !important;
  }
  .bg-success-subtle { background-color: #d1e7dd !important; color: #0f5132 !important; }
  .bg-warning-subtle { background-color: #fff3cd !important; color: #664d03 !important; }
  .bg-danger-subtle { background-color: #f8d7da !important; color: #842029 !important; }
  .card-header { border-bottom: 1px solid rgba(0,0,0,0.05); }
  .table td { font-size: 0.95rem; }
  .breadcrumb-item a { color: #6c757d; }
  .breadcrumb-item.active { color: #0d6efd; font-weight: bold; }
  .admin-summary-card {
    background:
      radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 30%),
      linear-gradient(135deg, #0d6efd 0%, #3b82f6 100%) !important;
    box-shadow: 0 20px 60px rgba(13, 110, 253, 0.18) !important;
  }
  .admin-payment-card {
    border-radius: 24px !important;
    background: #fff;
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08) !important;
  }
  .admin-payment-card .card-body {
    padding: 1.5rem;
  }
  .admin-payment-card .form-select,
  .admin-payment-card .form-control,
  .admin-payment-card .input-group-text {
    border-radius: 16px !important;
    border-color: var(--ap-line);
    background: #f8fbff;
  }
  .admin-payment-card .input-group-text {
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
  }
  .admin-payment-card .form-control.bg-light {
    background: #f8fbff !important;
  }
  .admin-qris-panel {
    border-color: rgba(13, 110, 253, 0.12) !important;
    background:
      radial-gradient(circle at top right, rgba(13, 110, 253, 0.08), transparent 35%),
      linear-gradient(180deg, #ffffff 0%, #f5f9ff 100%) !important;
    box-shadow: inset 0 0 0 1px rgba(13, 110, 253, 0.04);
  }
  .admin-payment-card .btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #2563eb 100%);
    border: 0;
  }
  .admin-payment-card .btn-outline-primary {
    border-width: 1.5px;
  }
  #snap-midtrans,
  #snap-midtrans * {
    box-sizing: border-box;
  }
</style>
@endsection
