@extends('be.master')

@section('Laporan')
<main class="app-main py-3" style="background: #f5f7fb;">
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div>
          <h3 class="fw-bold mb-1">Laporan Bengkel</h3>
          <p class="text-muted mb-0 small">Periode aktif: <strong>{{ $periodLabel }}</strong></p>
        </div>
        <div class="d-flex flex-column align-items-lg-end gap-2">
          <form method="GET" action="{{ route('admin.laporan') }}" class="row g-2 align-items-end" id="reportFilterForm">
            <div class="col-sm-auto">
              <label class="form-label small fw-bold text-muted mb-1">Tampilkan</label>
              <select name="traffic_group_by" class="form-select">
                <option value="day" {{ $trafficReportGroupBy === 'day' ? 'selected' : '' }}>Harian</option>
                <option value="week" {{ $trafficReportGroupBy === 'week' ? 'selected' : '' }}>Mingguan</option>
                <option value="month" {{ $trafficReportGroupBy === 'month' ? 'selected' : '' }}>Bulanan</option>
              </select>
            </div>
            <div class="col-sm-auto">
              <label class="form-label small fw-bold text-muted mb-1">Dari</label>
              <input type="date" name="from_date" class="form-control" value="{{ $trafficFromDate }}">
            </div>
            <div class="col-sm-auto">
              <label class="form-label small fw-bold text-muted mb-1">Sampai</label>
              <input type="date" name="to_date" class="form-control" value="{{ $trafficToDate }}">
            </div>
            <div class="col-sm-auto">
              <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm text-white fw-bold"><i class="bi bi-funnel me-1"></i>Tampilkan</button>
            </div>
          </form>
          <div class="d-flex flex-wrap gap-2">
           
            <a href="{{ route('admin.laporan.export.pdf', request()->query()) }}" class="btn btn-danger w-100 rounded-pill shadow-sm text-white fw-bold" target="_blank">
              <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body">
              <small class="text-muted d-block mb-2">Transaksi</small>
              <h4 class="fw-bold mb-0">{{ $summary['total_transactions'] }}</h4>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body">
              <small class="text-muted d-block mb-2">Booking</small>
              <h4 class="fw-bold mb-0">{{ $summary['total_bookings'] }}</h4>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body">
              <small class="text-muted d-block mb-2">Customer</small>
              <h4 class="fw-bold mb-0">{{ $summary['total_customers'] }}</h4>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body">
              <small class="text-muted d-block mb-2">Kendaraan</small>
              <h4 class="fw-bold mb-0">{{ $summary['total_vehicles'] }}</h4>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body">
              <small class="text-muted d-block mb-2">Nilai Invoice</small>
              <h6 class="fw-bold mb-0">Rp {{ number_format($summary['total_invoice_value'], 0, ',', '.') }}</h6>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 rounded-4 text-bg-dark">
            <div class="card-body">
              <small class="text-white-50 d-block mb-2">Pembayaran Masuk</small>
              <h6 class="fw-bold mb-0">Rp {{ number_format($summary['total_paid_amount'], 0, ',', '.') }}</h6>
              <small class="text-white-50 d-block mt-2">Sisa Piutang: Rp {{ number_format($summary['total_outstanding'], 0, ',', '.') }}</small>
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 py-3">
          <div>
            <h5 class="fw-bold mb-1">Laporan Traffic Booking / Kendaraan Masuk</h5>
            <p class="text-muted small mb-0">Rentang: <strong>{{ $trafficPeriodLabel }}</strong></p>
          </div>
        </div>
        <div class="card-body">
          <div class="row g-4">
            <div class="col-xl-7">
              <div class="border rounded-4 h-100 p-3">
                <div id="traffic-report-chart" style="min-height: 340px;"></div>
              </div>
            </div>
            <div class="col-xl-5">
              <div class="border rounded-4 h-100">
                <div class="p-3 border-bottom">
                  <h6 class="fw-bold mb-0">
                    Tabel Traffic
                    <span class="badge bg-light text-dark border ms-1">
                      {{ ['day' => 'Harian', 'week' => 'Mingguan', 'month' => 'Bulanan'][$trafficReportGroupBy] ?? 'Harian' }}
                    </span>
                  </h6>
                </div>
                <div class="table-responsive">
                  <table class="table table-sm align-middle mb-0">
                    <thead class="bg-light">
                      <tr>
                        <th class="ps-3">Periode</th>
                        <th>Booking</th>
                        <th>Kendaraan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($trafficReportRows as $row)
                        <tr>
                          <td class="ps-3">{{ $row['label'] }}</td>
                          <td>{{ $row['booking_total'] }}</td>
                          <td>{{ $row['vehicle_total'] }}</td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="3" class="text-center text-muted py-4">Belum ada data traffic.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
              <small class="text-muted d-block mb-2">Pendapatan Jasa</small>
              <h5 class="fw-bold mb-0">Rp {{ number_format($summary['total_service_revenue'], 0, ',', '.') }}</h5>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
              <small class="text-muted d-block mb-2">Pendapatan Sparepart</small>
              <h5 class="fw-bold mb-0">Rp {{ number_format($summary['total_sparepart_revenue'], 0, ',', '.') }}</h5>
            </div>
          </div>
        </div>
        <!-- <div class="col-lg-4">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
              <small class="text-muted d-block mb-2">Status Pembayaran</small>
              <div class="small text-muted">
                Lunas: {{ $summary['total_paid_transactions'] }} |
                Sebagian: {{ $summary['total_partial_transactions'] }} |
                Belum Bayar: {{ $summary['total_unpaid_transactions'] }}
              </div>
            </div>
          </div>
        </div> -->
      </div>

      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="fw-bold mb-0">Laporan Transaksi</h5>
        </div>
        <div class="card-body p-0">
          @if($transactions->isEmpty())
            <div class="p-4 text-muted">Belum ada transaksi pada periode ini.</div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="ps-3">ID</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Kendaraan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($transactions as $transaction)
                    <tr>
                      <td class="ps-3 fw-bold">#{{ $transaction->id }}</td>
                      <td>{{ $transaction->booking->user->name ?? '-' }}</td>
                      <td>{{ $transaction->service->service_name ?? '-' }}</td>
                      <td>{{ ($transaction->booking->vehicle->brand ?? '-') }} {{ ($transaction->booking->vehicle->model ?? '') }}</td>
                      <td class="fw-semibold">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                      <td>{!! $transaction->status_badge !!}</td>
                      <td>{{ $transaction->created_at?->format('d-m-Y H:i') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3">
              <h5 class="fw-bold mb-0">Laporan Service</h5>
            </div>
            <div class="card-body p-0">
              @if($serviceReports->isEmpty())
                <div class="p-4 text-muted">Belum ada data service.</div>
              @else
                <div class="table-responsive">
                  <table class="table align-middle mb-0">
                    <thead class="bg-light">
                      <tr>
                        <th class="ps-3">Service</th>
                        <th>Total Transaksi</th>
                        <th>Total Pendapatan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($serviceReports as $service)
                        <tr>
                          <td class="ps-3">{{ $service->service_name }}</td>
                          <td>{{ $service->total_transaksi }}</td>
                          <td class="fw-semibold">Rp {{ number_format($service->total_pendapatan, 0, ',', '.') }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3">
              <h5 class="fw-bold mb-0">Laporan Spareparts</h5>
            </div>
            <div class="card-body p-0">
              @if($sparepartReports->isEmpty())
                <div class="p-4 text-muted">Belum ada data sparepart.</div>
              @else
                <div class="table-responsive">
                  <table class="table align-middle mb-0">
                    <thead class="bg-light">
                      <tr>
                        <th class="ps-3">Sparepart</th>
                        <th>Brand</th>
                        <th>Qty Terjual</th>
                        <th>Total Penjualan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($sparepartReports as $sparepart)
                        <tr>
                          <td class="ps-3">{{ $sparepart->name }}</td>
                          <td>{{ $sparepart->brand }}</td>
                          <td>{{ $sparepart->total_qty }}</td>
                          <td class="fw-semibold">Rp {{ number_format($sparepart->total_penjualan, 0, ',', '.') }}</td>
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

      <div class="row g-4 mb-4">
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3">
              <h5 class="fw-bold mb-0">Laporan Customer</h5>
            </div>
            <div class="card-body p-0">
              @if($customers->isEmpty())
                <div class="p-4 text-muted">Belum ada data customer.</div>
              @else
                <div class="table-responsive">
                  <table class="table align-middle mb-0">
                    <thead class="bg-light">
                      <tr>
                        <th class="ps-3">Nama</th>
                        <th>Kontak</th>
                        <th>Total Transaksi</th>
                        <th>Total Belanja</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($customers as $customer)
                        <tr>
                          <td class="ps-3">
                            <div class="fw-semibold">{{ $customer->name }}</div>
                            <div class="small text-muted">{{ $customer->email }}</div>
                          </td>
                          <td>{{ $customer->phone ?: '-' }}</td>
                          <td>{{ $customer->total_transaksi }}</td>
                          <td class="fw-semibold">Rp {{ number_format($customer->total_belanja, 0, ',', '.') }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
              <h5 class="fw-bold mb-0">Ringkasan Booking</h5>
              <span class="badge bg-primary-subtle text-primary">{{ $summary['total_bookings'] }} booking</span>
            </div>
            <div class="card-body">
              @if($bookingStatusSummary->isEmpty())
                <div class="text-muted">Belum ada data booking.</div>
              @else
                @foreach($bookingStatusSummary as $status)
                  <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="fw-medium">{{ $status['label'] }}</span>
                    <span class="badge bg-light text-dark">{{ $status['total'] }}</span>
                  </div>
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="fw-bold mb-0">Laporan Kendaraan</h5>
        </div>
        <div class="card-body p-0">
          @if($vehicleReports->isEmpty())
            <div class="p-4 text-muted">Belum ada data kendaraan pada periode ini.</div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="ps-3">Customer</th>
                    <th>Kendaraan</th>
                    <th>Plat</th>
                    <th>Service</th>
                    <th>Status Booking</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($vehicleReports as $booking)
                    <tr>
                      <td class="ps-3">{{ $booking->user->name ?? '-' }}</td>
                      <td>{{ ($booking->vehicle->brand ?? '-') }} {{ ($booking->vehicle->model ?? '') }}</td>
                      <td>{{ $booking->vehicle->license_plate ?? '-' }}</td>
                      <td>{{ $booking->service->service_name ?? '-' }}</td>
                      <td>{!! $booking->status_badge !!}</td>
                      <td>{{ $booking->booking_date_label }} {{ $booking->booking_time_label !== '-' ? $booking->booking_time_label : '' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="fw-bold mb-0">Laporan Booking</h5>
        </div>
        <div class="card-body p-0">
          @if($bookingReports->isEmpty())
            <div class="p-4 text-muted">Belum ada data booking pada periode ini.</div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="ps-3">ID</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Keluhan</th>
                    <th>Status</th>
                    <th>Tanggal Booking</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($bookingReports as $booking)
                    <tr>
                      <td class="ps-3 fw-bold">#{{ $booking->id }}</td>
                      <td>{{ $booking->user->name ?? '-' }}</td>
                      <td>{{ $booking->service->service_name ?? '-' }}</td>
                      <td>{{ \Illuminate\Support\Str::limit($booking->complaint, 60) }}</td>
                      <td>{!! $booking->status_badge !!}</td>
                      <td>{{ $booking->booking_date_label }} {{ $booking->booking_time_label !== '-' ? $booking->booking_time_label : '' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="fw-bold mb-0">Laporan Pembayaran</h5>
        </div>
        <div class="card-body p-0">
          @if($payments->isEmpty())
            <div class="p-4 text-muted">Belum ada pembayaran pada periode ini.</div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="ps-3">Tanggal</th>
                    <th>Transaksi</th>
                    <th>Customer</th>
                    <th>Metode</th>
                    <th>Jumlah Bayar</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($payments as $payment)
                    <tr>
                      <td class="ps-3">{{ $payment->payment_date }}</td>
                      <td>#{{ $payment->transaction_id }}</td>
                      <td>{{ $payment->transaction->booking->user->name ?? '-' }}</td>
                      <td>{{ $payment->payment_method_label }}</td>
                      <td class="fw-semibold">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                      <td>{!! $payment->payment_status_badge !!}</td>
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
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const renderTrafficChart = (selector, labels, bookingSeries, vehicleSeries) => {
      const element = document.querySelector(selector);

      if (!element || typeof ApexCharts === 'undefined') {
        return;
      }

      new ApexCharts(element, {
        chart: { type: 'line', height: 260, toolbar: { show: false } },
        stroke: { curve: 'smooth', width: 2 },
        markers: { size: 3 },
        colors: ['#0d6efd', '#198754'],
        series: [
          { name: 'Booking Masuk', data: bookingSeries },
          { name: 'Kendaraan Masuk', data: vehicleSeries },
        ],
        xaxis: { categories: labels },
        yaxis: { min: 0, forceNiceScale: true },
        legend: { position: 'top' },
        tooltip: { shared: true }
      }).render();
    };

    renderTrafficChart(
      '#traffic-report-chart',
      @json($trafficReportChartLabels),
      @json($trafficReportBookingSeries),
      @json($trafficReportVehicleSeries)
    );
  });
</script>
@endsection
