<main class="app-main py-3" style="background: #f4f7f6;">
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row align-items-center g-3">
        <div class="col-md-7">
          <h3 class="fw-bold mb-1"><i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>Dashboard Bengkel</h3>
          <p class="text-muted mb-0 small">Traffic harian {{ $trafficDays }} hari terakhir dan ringkasan performa bulan <strong>{{ $selectedMonthLabel }}</strong></p>
        </div>
        <div class="col-md-5 text-md-end">
          <form method="GET" action="/admin/dashboard" class="row g-2 justify-content-md-end">
            <div class="col-md-5">
              <select name="month" class="form-select border-0 shadow-sm rounded-pill">
                @foreach($availableMonths as $monthOption)
                  <option value="{{ $monthOption['value'] }}" {{ $selectedMonth === $monthOption['value'] ? 'selected' : '' }}>{{ $monthOption['label'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <select name="traffic_days" class="form-select border-0 shadow-sm rounded-pill">
                <option value="7" {{ $trafficDays === 7 ? 'selected' : '' }}>7 Hari</option>
                <option value="14" {{ $trafficDays === 14 ? 'selected' : '' }}>14 Hari</option>
                <option value="30" {{ $trafficDays === 30 ? 'selected' : '' }}>30 Hari</option>
              </select>
            </div>
            <div class="col-md-4">
              <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm text-white fw-bold">
                <i class="bi bi-funnel me-1"></i>Filter
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      
      <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 bg-white hover-card" style="border-radius: 15px;">
            <div class="card-body p-3">
              <small class="text-muted d-block mb-1">Total Booking</small>
              <h4 class="fw-bold mb-2 text-info">{{ $totalBookings }}</h4>
              <a href="/admin/booking" class="text-decoration-none small fw-bold text-info">Lihat Info <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 bg-white hover-card" style="border-radius: 15px;">
            <div class="card-body p-3">
              <small class="text-muted d-block mb-1">Customer</small>
              <h4 class="fw-bold mb-2 text-dark">{{ $totalCustomers }}</h4>
              <a href="/admin/users" class="text-decoration-none small fw-bold text-muted">Lihat Info <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 bg-white hover-card" style="border-radius: 15px;">
            <div class="card-body p-3">
              <small class="text-muted d-block mb-1">Transaksi</small>
              <h4 class="fw-bold mb-2 text-primary">{{ $totalTransactions }}</h4>
              <a href="/admin/transaksi" class="text-decoration-none small fw-bold text-primary">Lihat Info <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 bg-white hover-card" style="border-radius: 15px;">
            <div class="card-body p-3">
              <small class="text-muted d-block mb-1">Spareparts</small>
              <h4 class="fw-bold mb-2 text-secondary">{{ $totalSpareparts }}</h4>
              <a href="/admin/spareparts" class="text-decoration-none small fw-bold text-secondary">Lihat Info <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 text-white hover-card" style="border-radius: 15px; background: linear-gradient(45deg, #198754, #2ecc71);">
            <div class="card-body p-3">
              <small class="opacity-75 d-block mb-1">Pemasukan</small>
              <h5 class="fw-bold mb-2">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h5>
              <a href="{{ route('admin.laporan') }}" class="text-decoration-none small fw-bold text-white opacity-75">Lihat Info <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
          <div class="card border-0 shadow-sm h-100 text-white hover-card" style="border-radius: 15px; background: linear-gradient(45deg, #dc3545, #ff5e62);">
            <div class="card-body p-3">
              <small class="opacity-75 d-block mb-1">Pengeluaran</small>
              <h5 class="fw-bold mb-2">Rp{{ number_format($totalExpense, 0, ',', '.') }}</h5>
              <a href="/admin/spareparts" class="text-decoration-none small fw-bold text-white opacity-75">Lihat Info <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-xl-8">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header bg-white border-0 py-3">
              <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                  <h5 class="fw-bold text-dark mb-0">Daily Traffic</h5>
                  <small class="text-muted small">Visualisasi booking masuk dan kendaraan masuk per hari</small>
                </div>
                <a href="{{ route('admin.laporan') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Lihat di Laporan</a>
              </div>
            </div>
            <div class="card-body">
              <div id="monthly-performance-chart" style="min-height: 340px;"></div>
            </div>
          </div>
        </div>

        <div class="col-xl-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header bg-white border-0 py-3 text-center">
              <h5 class="fw-bold text-dark mb-0">Statistik User Role</h5>
            </div>
            <div class="card-body text-center">
              <div id="role-summary-chart" style="min-height: 240px;"></div>
              <div class="mt-4">
                @foreach($roleSummary as $role)
                <div class="d-flex justify-content-between py-2 border-bottom border-light">
                  <span class="text-muted small fw-semibold">{{ $role['label'] }}</span>
                  <span class="badge bg-light text-dark border rounded-pill">{{ $role['total'] }}</span>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 connectedSortable">
        <div class="col-xl-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header border-0 bg-white py-3">
              <h6 class="fw-bold mb-0">Sparepart Terlaris</h6>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="bg-light">
                    <tr><th class="ps-3 border-0">Nama Barang</th><th class="text-center border-0">Qty</th></tr>
                  </thead>
                  <tbody>
                    @forelse($topSpareparts as $sparepart)
                    <tr>
                      <td class="ps-3 fw-bold small text-dark">{{ $sparepart->name }} <br><span class="text-muted fw-normal" style="font-size: 10px;">{{ $sparepart->brand }}</span></td>
                      <td class="text-center"><span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ $sparepart->sold_qty }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-4 text-muted">Belum ada transaksi</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header border-0 bg-dark py-3 text-white" style="border-radius: 20px 20px 0 0;">
              <h6 class="fw-bold mb-0">Brand Motor Terbanyak</h6>
            </div>
            <div class="card-body">
              @forelse($topBrands as $index => $brand)
              <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-light rounded-3">
                <span class="fw-bold small">{{ $brand->brand }}</span>
                <span class="badge bg-white text-dark border fw-bold">{{ $brand->total_service }} Unit</span>
              </div>
              @empty
              <p class="text-center text-muted py-4">Data tidak tersedia</p>
              @endforelse
            </div>
          </div>
        </div>

        <div class="col-xl-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header border-0 bg-white py-3">
              <h6 class="fw-bold mb-0">Layanan Paling Laku</h6>
            </div>
            <div class="card-body">
              @php
                $maxServiceTransactions = max(1, (int) $topServices->max('total_transaction'));
              @endphp
              @forelse($topServices as $service)
              @php
                $serviceProgress = ((int) $service->total_transaction / $maxServiceTransactions) * 100;
              @endphp
              <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                  <span class="small fw-bold text-dark">{{ $service->service_name }}</span>
                  <span class="small text-muted fw-bold">{{ $service->total_transaction }} Servis</span>
                </div>
                <div class="progress" style="height: 8px; border-radius: 10px;">
                  <div class="progress-bar bg-success rounded-pill" style="width: {{ $serviceProgress }}%;"></div>
                </div>
              </div>
              @empty
              <p class="text-center text-muted py-4">Belum ada data</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<style>
  .hover-card { transition: all 0.2s ease-in-out; cursor: pointer; }
  .hover-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Grafik Performa
  const monthlyChartEl = document.querySelector('#monthly-performance-chart');
  if (monthlyChartEl) {
    new ApexCharts(monthlyChartEl, {
      chart: { type: 'area', height: 340, toolbar: { show: false } },
      stroke: { curve: 'smooth', width: 2 },
      colors: ['#0d6efd', '#198754'],
      series: [
        { name: 'Booking Masuk', data: @json($trafficBookingSeries) },
        { name: 'Kendaraan Masuk', data: @json($trafficVehicleSeries) },
      ],
      fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 } },
      xaxis: { categories: @json($trafficChartLabels) },
      yaxis: [{ title: { text: 'Jumlah Traffic' } }],
      legend: { position: 'top' }
    }).render();
  }

  // Grafik Role
  const roleChartEl = document.querySelector('#role-summary-chart');
  if (roleChartEl) {
    new ApexCharts(roleChartEl, {
      chart: { type: 'donut', height: 260 },
      labels: @json($roleSummary->pluck('label')->values()),
      series: @json($roleSummary->pluck('total')->values()),
      colors: ['#0d6efd', '#6610f2', '#20c997', '#fd7e14', '#adb5bd'],
      legend: { position: 'bottom' }
    }).render();
  }
});
</script>
