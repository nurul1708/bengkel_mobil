@extends('be.master')

@section('Transaksi')
<main class="app-main py-3">
    <div class="app-content-header mb-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="fw-bold text-dark mb-0">
                        <i class="bi bi-plus-circle-fill me-2 text-primary"></i>Buat Transaksi Baru
                    </h3>
                    <p class="text-muted small">Input data layanan dan penggantian sparepart pelanggan.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <form action="/admin/transaksi/bayar" method="POST" id="formPurchasing">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white py-3 border-0">
                                <h5 class="fw-bold mb-0">Informasi Dasar</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="tgl_beli" class="form-label fw-bold small">Tanggal Transaksi</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event"></i></span>
                                        <input type="date" class="form-control bg-light border-start-0 @error('tgl_beli') is-invalid @enderror" 
                                               id="tgl_beli" name="tgl_beli" value="{{ old('tgl_beli', date('Y-m-d')) }}" required>
                                    </div>
                                    @error('tgl_beli')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                                </div>

<div class="mb-3">
                                    <label for="id_kasir" class="form-label fw-bold small">Kasir</label>
                                    @php
                                        $currentUser = auth()->user();
                                        $autoSelectKasir = old('id_kasir') ?: ($currentUser->role === 'kasir' ? $currentUser->id : '');
                                    @endphp
                                    <select class="form-select bg-light @error('id_kasir') is-invalid @enderror" id="id_kasir" name="id_kasir" required>
                                        <option value="">-- Pilih Kasir --</option>
                                        @foreach($userkasir as $k)
                                            <option value="{{ $k->id }}" {{ $autoSelectKasir == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

<div class="mb-3">
                                    <label for="id_mekanik" class="form-label fw-bold small">Montir / Mekanik</label>
                                    @php
                                        $autoSelectMekanik = '';
                                        if (old('id_mekanik')) {
                                            $autoSelectMekanik = old('id_mekanik');
                                        } elseif (isset($selectedBooking) && $selectedBooking->mekanik) {
                                            $autoSelectMekanik = $selectedBooking->mekanik->id;
                                        }
                                    @endphp
                                    <select class="form-select bg-light @error('id_mekanik') is-invalid @enderror" id="id_mekanik" name="id_mekanik" required>
                                        <option value="">-- Pilih Montir --</option>
                                        @foreach($usermekanik as $montir)
                                            <option value="{{ $montir->id }}" {{ $autoSelectMekanik == $montir->id ? 'selected' : '' }}>{{ $montir->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white py-3 border-0">
                                <h5 class="fw-bold mb-0">Detail Layanan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="booking_id" class="form-label fw-bold small">Data Booking</label>
<select class="form-select @error('booking_id') is-invalid @enderror" id="booking_id" name="booking_id" required>
                                            <option value="">-- Pilih Booking --</option>
                                            @foreach($bookings as $booking)
                                                <option value="{{ $booking->id }}" data-service-id="{{ $booking->service_id }}" data-mekanik-id="{{ $booking->mekanik_id }}" {{ (isset($selectedBooking) && $selectedBooking->id == $booking->id) ? 'selected' : '' }}>
                                                    #{{ $booking->id }} - {{ $booking->user->name ?? '-' }} ({{ $booking->vehicle->brand ?? '-' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="id_service" class="form-label fw-bold small">Jenis Service</label>
                                        <select class="form-select @error('id_service') is-invalid @enderror" id="id_service" name="id_service" required>
                                            <option value="">-- Pilih Service --</option>
                                            @foreach($services as $srv)
                                                <option value="{{ $srv->id }}" data-price="{{ $srv->price }}" {{ (string) old('id_service', $selectedBooking->service_id ?? '') === (string) $srv->id ? 'selected' : '' }}>
                                                    {{ $srv->service_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="p-3 rounded-3 bg-primary-subtle d-flex justify-content-between align-items-center">
                                            <span class="text-primary fw-bold">Biaya Jasa Service:</span>
                                            <input type="text" readonly class="form-control-plaintext text-end fw-bold text-primary fs-5" id="service_price_display" value="Rp 0" style="width: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0">Item Sparepart</h5>
                                <button type="button" class="btn btn-success btn-sm rounded-pill px-3" id="btnAddItem">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Sparepart
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0" id="tableItems">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 small fw-bold text-muted">SPAREPART</th>
                                                <th class="small fw-bold text-muted" width="180">HARGA</th>
                                                <th class="small fw-bold text-muted" width="100">QTY</th>
                                                <th class="small fw-bold text-muted" width="180">SUBTOTAL</th>
                                                <th class="text-center" width="60"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsBody">
                                            <tr class="item-row">
                                                <td class="ps-4">
                                                    <select class="form-select border-0 bg-transparent sparepart-select" name="items[0][sparepart_id]">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach($spareparts as $s)
                                                            <option value="{{ $s->id }}" data-price="{{ $s->harga_jual }}">{{ $s->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-transparent border-0 small">Rp</span>
                                                        <input type="number" class="form-control form-control-sm harga-beli" name="items[0][harga_beli]" min="0">
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm jumlah-beli" name="items[0][jumlah_beli]" min="1" value="1">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm border-0 bg-transparent subtotal fw-bold" readonly value="Rp 0">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-link text-danger p-0 btn-remove-item" disabled>
                                                        <i class="bi bi-trash-fill fs-5"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-dark p-4 rounded-bottom-4">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h4 class="text-white-50 fw-normal mb-0">Total Keseluruhan:</h4>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <h2 class="text-white fw-bold mb-0" id="totalBayar">Rp 0</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="total_service" id="total_service" value="{{ old('total_service', 0) }}">
                        <input type="hidden" name="total_sparepart" id="total_sparepart" value="0">
                        <input type="hidden" name="grand_total" id="grand_total" value="0">

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm" id="btnSubmit">
                                <i class="bi bi-check-circle-fill me-2"></i>Simpan Transaksi
                            </button>
                            <a href="/admin/transaksi" class="btn btn-light btn-lg rounded-pill px-4">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let itemIndex = 0;

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function getItemRowTemplate(index) {
        return `
            <tr class="item-row border-top">
                <td class="ps-4">
                    <select class="form-select border-0 bg-transparent sparepart-select" name="items[${index}][sparepart_id]">
                        <option value="">-- Pilih --</option>
                        @foreach($spareparts as $s)
                            <option value="{{ $s->id }}" data-price="{{ $s->harga_jual }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-0 small">Rp</span>
                        <input type="number" class="form-control form-control-sm harga-beli" name="items[${index}][harga_beli]" min="0">
                    </div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm jumlah-beli" name="items[${index}][jumlah_beli]" min="1" value="1">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm border-0 bg-transparent subtotal fw-bold" readonly value="Rp 0">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-link text-danger p-0 btn-remove-item">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        `;
    }

const serviceSelect = document.getElementById('id_service');
    const bookingSelect = document.getElementById('booking_id');
    const mekanikSelect = document.getElementById('id_mekanik');
    const servicePriceDisplay = document.getElementById('service_price_display');
    const totalServiceInput = document.getElementById('total_service');
    const totalSparepartInput = document.getElementById('total_sparepart');
    const grandTotalInput = document.getElementById('grand_total');

    function onServiceChange() {
        const selected = serviceSelect.selectedOptions[0];
        const servicePrice = parseFloat(selected?.dataset.price) || 0;
        servicePriceDisplay.value = formatRupiah(servicePrice);
        totalServiceInput.value = servicePrice;
        calculateTotal();
    }

function onBookingChange() {
        const selectedBooking = bookingSelect.selectedOptions[0];
        const selectedServiceId = selectedBooking?.dataset.serviceId;
        const selectedMekanikId = selectedBooking?.dataset.mekanikId;
        
        if (selectedServiceId) {
            serviceSelect.value = selectedServiceId;
            onServiceChange();
        }
        
        // Auto-select mekanik jika ada
        if (selectedMekanikId) {
            mekanikSelect.value = selectedMekanikId;
        }
    }

    function onSparepartChange(event) {
        const row = event.target.closest('.item-row');
        const selected = event.target.selectedOptions[0];
        const price = parseFloat(selected?.dataset.price) || 0;
        row.querySelector('.harga-beli').value = price;
        calculateTotal();
    }

    document.getElementById('btnAddItem').addEventListener('click', function() {
        itemIndex++;
        document.getElementById('itemsBody').insertAdjacentHTML('beforeend', getItemRowTemplate(itemIndex));
        updateRemoveButtons();
        bindCalculateEvents();
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-item')) {
            const row = e.target.closest('.item-row');
            row.remove();
            updateRemoveButtons();
            calculateTotal();
        }
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        document.querySelectorAll('.btn-remove-item').forEach(btn => {
            btn.disabled = rows.length <= 1;
        });
    }

    function calculateSubtotal(row) {
        const harga = parseFloat(row.querySelector('.harga-beli').value) || 0;
        const jumlah = parseFloat(row.querySelector('.jumlah-beli').value) || 0;
        const subtotal = harga * jumlah;
        row.querySelector('.subtotal').value = 'Rp ' + subtotal.toLocaleString('id-ID');
        return subtotal;
    }

    function calculateTotal() {
        let sparepartTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            sparepartTotal += calculateSubtotal(row);
        });
        totalSparepartInput.value = sparepartTotal;
        const serviceTotal = parseFloat(totalServiceInput.value) || 0;
        const grandTotal = serviceTotal + sparepartTotal;
        grandTotalInput.value = grandTotal;
        document.getElementById('totalBayar').textContent = formatRupiah(grandTotal);
    }

    function bindCalculateEvents() {
        document.querySelectorAll('.harga-beli, .jumlah-beli').forEach(input => {
            input.removeEventListener('input', calculateTotal);
            input.addEventListener('input', calculateTotal);
        });
        document.querySelectorAll('.sparepart-select').forEach(select => {
            select.removeEventListener('change', onSparepartChange);
            select.addEventListener('change', onSparepartChange);
        });
        serviceSelect.removeEventListener('change', onServiceChange);
        serviceSelect.addEventListener('change', onServiceChange);
        bookingSelect.removeEventListener('change', onBookingChange);
        bookingSelect.addEventListener('change', onBookingChange);
    }

    bindCalculateEvents();
    onBookingChange();
    onServiceChange();

    document.getElementById('formPurchasing').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('.item-row');
        let isValid = true;
        rows.forEach(row => {
            const sparepartId = row.querySelector('.sparepart-select').value;
            const price = row.querySelector('.harga-beli').value;
            const qty = row.querySelector('.jumlah-beli').value;

            if ((sparepartId || price) && (!sparepartId || !price || !qty)) {
                isValid = false;
            }
        });
        if (!isValid) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Form Tidak Lengkap', text: 'Lengkapi sparepart yang dipilih, atau kosongkan baris sparepart jika hanya transaksi jasa.', confirmButtonColor: '#d33' });
        }
    });

    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session('error') }}', confirmButtonColor: '#d33' });
    @endif
</script>

<style>
    .bg-primary-subtle { background-color: #e7f1ff; border: 1px dashed #0d6efd; }
    .table thead th { border-top: none; }
    .item-row:hover { background-color: #fafafa; }
    .form-select, .form-control { border-radius: 8px; }
    .card { border: none !important; }
</style>
@endsection
