<main class="app-main py-3" style="background: #f8f9fa;">
  <div class="app-content-header mb-4">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-12 text-center text-sm-start">
          <h3 class="fw-bold text-dark mb-0">Admin Overview</h3>
          <p class="text-muted small">Ringkasan performa bengkel secara keseluruhan.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <!-- Grid untuk Small Boxes -->
      <!-- Di HP (xs) pakai col-12 atau col-6, di tablet (md) pakai col-6, di Desktop (lg) pakai col-3 -->
      <div class="row g-3 g-md-4 mb-4">
        <!-- Total Booking -->
        <div class="col-6 col-md-6 col-lg-3">
          <div class="small-box shadow-sm border-0 h-100" style="background: linear-gradient(45deg, #4e73df, #224abe); border-radius: 15px; color: white; position: relative; overflow: hidden;">
            <div class="inner p-3">
              <h3 class="fw-bold fs-4 fs-md-2">{{ $totalBookings }}</h3>
              <p class="mb-0 opacity-75 small">Total Booking</p>
            </div>
            <div class="small-box-icon" style="position: absolute; right: 10px; top: 10px; font-size: 2rem; opacity: 0.2;"><i class="bi bi-calendar-check"></i></div>
            <a href="/admin/booking" class="small-box-footer bg-dark bg-opacity-10 py-2 text-decoration-none border-0 d-block text-center text-white small">Detail <i class="bi bi-arrow-right-short"></i></a>
          </div>
        </div>

        <!-- Total Transaksi -->
        <div class="col-6 col-md-6 col-lg-3">
          <div class="small-box shadow-sm border-0 h-100" style="background: linear-gradient(45deg, #1cc88a, #13855c); border-radius: 15px; color: white; position: relative; overflow: hidden;">
            <div class="inner p-3">
              <h3 class="fw-bold fs-4 fs-md-2">{{ $totalTransactions }}</h3>
              <p class="mb-0 opacity-75 small">Total Transaksi</p>
            </div>
            <div class="small-box-icon" style="position: absolute; right: 10px; top: 10px; font-size: 2rem; opacity: 0.2;"><i class="bi bi-cash-stack"></i></div>
            <a href="/admin/transaksi" class="small-box-footer bg-dark bg-opacity-10 py-2 text-decoration-none border-0 d-block text-center text-white small">Detail <i class="bi bi-arrow-right-short"></i></a>
          </div>
        </div>

        <!-- User Terdaftar -->
        <div class="col-6 col-md-6 col-lg-3">
          <div class="small-box shadow-sm border-0 h-100" style="background: linear-gradient(45deg, #f6c23e, #dda20a); border-radius: 15px; color: #333; position: relative; overflow: hidden;">
            <div class="inner p-3">
              <h3 class="fw-bold fs-4 fs-md-2">{{ $totalCustomers }}</h3>
              <p class="mb-0 opacity-75 small">User Terdaftar</p>
            </div>
            <div class="small-box-icon" style="position: absolute; right: 10px; top: 10px; font-size: 2rem; opacity: 0.2;"><i class="bi bi-people-fill"></i></div>
            <a href="/admin/users" class="small-box-footer bg-dark bg-opacity-10 py-2 text-decoration-none border-0 d-block text-center text-dark small">Detail <i class="bi bi-arrow-right-short"></i></a>
          </div>
        </div>

        <!-- Stok Spareparts -->
        <div class="col-6 col-md-6 col-lg-3">
          <div class="small-box shadow-sm border-0 h-100" style="background: linear-gradient(45deg, #e74a3b, #be2617); border-radius: 15px; color: white; position: relative; overflow: hidden;">
            <div class="inner p-3">
              <h3 class="fw-bold fs-4 fs-md-2">{{ $totalSpareparts }}</h3>
              <p class="mb-0 opacity-75 small">Stok Spareparts</p>
            </div>
            <div class="small-box-icon" style="position: absolute; right: 10px; top: 10px; font-size: 2rem; opacity: 0.2;"><i class="bi bi-box-seam"></i></div>
            <a href="/admin/spareparts" class="small-box-footer bg-dark bg-opacity-10 py-2 text-decoration-none border-0 d-block text-center text-white small">Detail <i class="bi bi-arrow-right-short"></i></a>
          </div>
        </div>
      </div>

      <!-- Section Grafik & Promo -->
      <div class="row g-4">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3 text-center text-md-start">
              <h5 class="fw-bold mb-0 text-dark">Grafik Pendapatan</h5>
            </div>
            <div class="card-body p-2 p-md-3">
              <!-- Pastikan library chart Anda mendukung responsivitas otomatis -->
              <div id="revenue-chart" style="min-height: 300px; width: 100%;"></div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 text-white h-100 text-center" style="border-radius: 15px; background: linear-gradient(135deg, #6366f1, #a855f7);">
                <div>
                    <i class="bi bi-lightning-charge-fill display-4 mb-3 d-block"></i>
                    <h4 class="fw-bold">Go Monthly!</h4>
                    <p class="opacity-75 mb-4 small">Lihat analisis mendalam per bulan untuk strategi bengkel yang lebih baik.</p>
                </div>
                <div class="mt-auto">
                    <a href="/admin/dashboard-monthly" class="btn btn-light rounded-pill px-4 fw-bold w-100 w-md-auto">Buka Laporan</a>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</main>

<style>
/* Tambahan agar kartu tidak terlihat gepeng di layar kecil */
.small-box {
    transition: transform 0.2s;
    min-height: 110px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

@media (max-width: 576px) {
    .inner h3 {
        font-size: 1.5rem !important;
    }
    .inner p {
        font-size: 0.75rem !important;
    }
    .small-box-icon {
        display: none; /* Sembunyikan icon di layar sangat kecil agar tidak bertabrakan dengan angka */
    }
}

.hover-card:hover {
    transform: translateY(-5px);
}
</style>