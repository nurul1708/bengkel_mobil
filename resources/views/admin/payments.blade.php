@extends('be.master')

@section('Pembayaran')
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-sm-6">
          <h3 class="mb-2 mb-sm-0">Riwayat Pembayaran</h3>
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

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Pembayaran</h3>
        </div>
        <div class="card-body p-0">
          @if($payments->isEmpty())
            <div class="p-3 text-muted">Belum ada data pembayaran.</div>
          @else
            <div class="table-responsive">
              <table class="table table-bordered table-hover w-100 mb-0">
                <thead>
                  <tr>
                    <th class="text-nowrap">ID</th>
                    <th class="text-nowrap">Booking</th>
                    <th class="text-nowrap">Customer</th>
                    <th class="text-nowrap">Service</th>
                    <th class="text-nowrap">Tanggal Bayar</th>
                    <th class="text-nowrap">Metode</th>
                    <th class="text-nowrap">Nominal</th>
                    <th class="text-nowrap">Status</th>
                    <th class="text-nowrap">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($payments as $payment)
                    <tr>
                      <td>{{ $payment->id }}</td>
                      <td>#{{ $payment->transaction->booking->id ?? '-' }}</td>
                      <td>{{ $payment->transaction->booking->user->name ?? '-' }}</td>
                      <td>{{ $payment->transaction->service->service_name ?? '-' }}</td>
                      <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y H:i') }}</td>
                      <td>{{ $payment->payment_method_label }}</td>
                      <td>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                      <td>{!! $payment->payment_status_badge !!}</td>
                      <td>
                        @if($payment->payment_status === 'paid' && $payment->transaction)
                          <a href="/admin/transaksi/{{ $payment->transaction->id }}/invoice" class="btn btn-dark btn-sm">Invoice</a>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
