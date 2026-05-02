<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SerVix | Dashboard</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="SerVix | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{asset('be/assets/css/adminlte.css')}}" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!-- Animate.css -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{asset('be/assets/css/adminlte.css')}}" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary d-flex flex-column min-vh-100">
    @php
      $role = Auth::user()->role ?? null;
      $roleHomeUrl = match ($role) {
          'admin', 'owner' => '/admin/dashboard',
          'kasir' => '/admin/transaksi',
          'mekanik' => '/admin/booking',
          default => '/admin/login',
      };
      $hasChatReadAtColumn = \Illuminate\Support\Facades\Schema::hasColumn('chats', 'read_at');

      $adminChatContacts = \App\Models\Chat::query()
          ->select('user_id')
          ->selectRaw('MAX(created_at) as last_chat')
          ->with(['user'])
          ->groupBy('user_id')
          ->orderByDesc('last_chat')
          ->get()
          ->map(function ($item) use ($hasChatReadAtColumn) {
              $lastMessage = \App\Models\Chat::where('user_id', $item->user_id)
                  ->latest('created_at')
                  ->latest('id')
                  ->first();

              $unreadCount = $hasChatReadAtColumn
                  ? \App\Models\Chat::where('user_id', $item->user_id)
                      ->whereIn('pengirim', ['customer', 'user'])
                      ->whereNull('read_at')
                      ->count()
                  : (in_array($lastMessage?->pengirim, ['customer', 'user']) ? 1 : 0);

              return (object) [
                  'user_id' => $item->user_id,
                  'user' => $item->user,
                  'last_chat' => $item->last_chat,
                  'last_message' => $lastMessage?->pesan,
                  'last_sender' => $lastMessage?->pengirim,
                  'unread_count' => $unreadCount,
              ];
          })
          ->filter(fn ($item) => $item->user)
          ->values();

      $adminUnreadChats = $adminChatContacts
          ->filter(fn ($item) => $item->unread_count > 0)
          ->values();

      $adminNotifications = collect();

      if (in_array($role, ['admin', 'owner', 'mekanik'])) {
          $pendingBookingsCount = \App\Models\Booking::where('status', 'pending')->count();
          $pendingBookingsLatest = \App\Models\Booking::where('status', 'pending')->latest('created_at')->value('created_at');

          if ($pendingBookingsCount > 0) {
              $adminNotifications->push((object) [
                  'icon' => 'bi bi-calendar-check',
                  'icon_color' => 'text-primary',
                  'text' => $pendingBookingsCount . ' booking baru menunggu verifikasi',
                  'time' => $pendingBookingsLatest,
                  'url' => '/admin/booking',
              ]);
          }
      }

      if (in_array($role, ['admin', 'owner', 'kasir'])) {
          $completedBookingsCount = \App\Models\Booking::where('status', 'completed')->count();
          $completedBookingsLatest = \App\Models\Booking::where('status', 'completed')->latest('updated_at')->value('updated_at');

          if ($completedBookingsCount > 0) {
              $adminNotifications->push((object) [
                  'icon' => 'bi bi-cash-coin',
                  'icon_color' => 'text-warning',
                  'text' => $completedBookingsCount . ' service selesai menunggu pembayaran',
                  'time' => $completedBookingsLatest,
                  'url' => '/admin/transaksi',
              ]);
          }
      }

      if (in_array($role, ['admin', 'owner'])) {
          $pendingTestimonialsCount = \App\Models\Testimonial::where('status', 'pending')->count();
          $pendingTestimonialsLatest = \App\Models\Testimonial::where('status', 'pending')->latest('created_at')->value('created_at');

          if ($pendingTestimonialsCount > 0) {
              $adminNotifications->push((object) [
                  'icon' => 'bi bi-star-half',
                  'icon_color' => 'text-danger',
                  'text' => $pendingTestimonialsCount . ' testimonial menunggu review',
                  'time' => $pendingTestimonialsLatest,
                  'url' => '/admin/testimonials',
              ]);
          }
      }

      $adminNotifications = $adminNotifications
          ->sortByDesc(fn ($item) => $item->time)
          ->values();
    @endphp
    <!--begin::App Wrapper-->
    <div class="app-wrapper flex-fill">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <!-- <li class="nav-item d-none d-md-block">
              <a href="#" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="#" class="nav-link">Contact</a>
            </li> -->
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Navbar Search-->
            <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
              </a>
            </li>
            <!--end::Navbar Search-->

            @if($role === 'admin')
              <!--begin::Messages Dropdown Menu-->
              <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                  <i class="bi bi-chat-text"></i>
                  @if($adminUnreadChats->isNotEmpty())
                    <span class="navbar-badge badge text-bg-danger">{{ $adminUnreadChats->count() }}</span>
                  @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                  <span class="dropdown-item dropdown-header">{{ $adminUnreadChats->count() }} Chat Baru</span>
                  @forelse($adminUnreadChats->take(5) as $chatItem)
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.chat.show', $chatItem->user_id) }}" class="dropdown-item">
                      <div class="d-flex">
                        <div class="flex-shrink-0">
                          <img
                            src="{{ $chatItem->user->photo ? asset('storage/' . $chatItem->user->photo) : asset('be/assets/assets/img/no-img.jpg') }}"
                            alt="User Avatar"
                            class="img-size-50 rounded-circle me-3"
                            style="object-fit: cover;"
                          />
                        </div>
                        <div class="flex-grow-1">
                          <h3 class="dropdown-item-title">
                            {{ $chatItem->user->name }}
                            <span class="float-end fs-7 text-danger">
                              <i class="bi bi-circle-fill"></i> {{ $chatItem->unread_count }}
                            </span>
                          </h3>
                          <p class="fs-7 mb-1">{{ \Illuminate\Support\Str::limit($chatItem->last_message ?? 'Pesan baru masuk', 35) }}</p>
                          <p class="fs-7 text-secondary mb-0">
                            <i class="bi bi-clock-fill me-1"></i> {{ \Carbon\Carbon::parse($chatItem->last_chat)->diffForHumans() }}
                          </p>
                        </div>
                      </div>
                    </a>
                  @empty
                    <div class="dropdown-divider"></div>
                    <span class="dropdown-item text-muted">Belum ada chat baru.</span>
                  @endforelse
                  <div class="dropdown-divider"></div>
                  <a href="{{ route('admin.chat.index') }}" class="dropdown-item dropdown-footer">See All Messages</a>
                </div>
              </li>
              <!--end::Messages Dropdown Menu-->
            @endif

            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                @if($adminNotifications->isNotEmpty())
                  <span class="navbar-badge badge text-bg-warning">{{ $adminNotifications->count() }}</span>
                @endif
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">{{ $adminNotifications->count() }} Notifikasi</span>
                @forelse($adminNotifications->take(6) as $notification)
                  <div class="dropdown-divider"></div>
                  <a href="{{ $notification->url }}" class="dropdown-item">
                    <i class="{{ $notification->icon }} me-2 {{ $notification->icon_color }}"></i> {{ $notification->text }}
                    <span class="float-end text-secondary fs-7">
                      {{ $notification->time ? \Carbon\Carbon::parse($notification->time)->diffForHumans() : 'baru saja' }}
                    </span>
                  </a>
                @empty
                  <div class="dropdown-divider"></div>
                  <span class="dropdown-item text-muted">Belum ada notifikasi baru.</span>
                @endforelse
                <div class="dropdown-divider"></div>
                <a href="{{ $roleHomeUrl }}" class="dropdown-item dropdown-footer">Lihat Halaman Utama</a>
              </div>
            </li>
            <!--end::Notifications Dropdown Menu-->

            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <img
                  src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('be/assets/assets/img/no-img.jpg') }}"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                  style="width: 32px; height: 32px; object-fit: cover;"
                  @if(Auth::user()) data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ Auth::user()->name }}" @endif
                />
                <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'User' }}</span>
              </a>
              <script>
                document.addEventListener('DOMContentLoaded', function() {
                  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                  });
                });
              </script>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('be/assets/assets/img/no-img.jpg') }}"
                    class="rounded-circle shadow"
                    alt="User Image"
                    style="width: 90px; height: 90px; object-fit: cover;"
                  />
                  <p>
                    {{ Auth::user()->name ?? 'User' }}<br>
                    <small class="badge bg-light text-dark">{{ ucfirst(Auth::user()->role ?? 'user') }}</small>
                  </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Body-->
                <li class="user-body">
                  <!--begin::Row-->
                  <div class="row">
                    <div class="col-12">
                      <p class="text-muted small mb-2">
                        <i class="bi bi-envelope"></i> {{ Auth::user()->email ?? '-' }}
                      </p>
                      @if(Auth::user()->phone)
                        <p class="text-muted small mb-2">
                          <i class="bi bi-telephone"></i> {{ Auth::user()->phone }}
                        </p>
                      @endif
                      @if(Auth::user()->address)
                        <p class="text-muted small mb-2">
                          <i class="bi bi-geo-alt"></i> {{ Auth::user()->address }}
                        </p>
                      @endif
                     
                    </div>
                  </div>
                  <!--end::Row-->
                </li>
                <!--end::Menu Body-->
                <!--begin::Menu Footer-->
                <li class="user-footer">
                  <a href="/admin/profile" class="btn btn-outline-primary w-100 mb-2">Profile</a>
                  <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">Sign out</button>
                  </form>
                </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="{{asset('be/assets/assets/img/logo.png')}}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow rounded-circle"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">SerVix Admin</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              @if(in_array($role, ['admin', 'owner']))
              <li class="nav-item">
                <a href="/admin/dashboard" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                    
                  </p>
                </a>
               </li>
               @endif
                  @if(in_array($role, ['admin', 'owner', 'kasir', 'mekanik']))
                  <li class="nav-item">
                    <a href="/admin/booking" class="nav-link {{ request()->is('admin/booking*') ? 'active' : '' }}">
                       <i class="nav-icon bi bi-clipboard-fill"></i>
                      <p>Booking</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array($role, ['admin', 'mekanik']))
                  <li class="nav-item">
                    <a href="/admin/service" class="nav-link {{ request()->is('admin/service*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-box-seam-fill"></i>
                      <p>Service</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array($role, ['admin', 'mekanik']))
                  <li class="nav-item">
                    <a href="/admin/vehicle" class="nav-link {{ request()->is('admin/vehicle*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-truck"></i>
                      <p>Vehicle</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array($role, ['admin', 'mekanik', 'kasir']))
                  <li class="nav-item">
                    <a href="/admin/spareparts" class="nav-link {{ request()->is('admin/spareparts*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-gear-wide"></i>
                      <p>Spareparts</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array($role, ['admin', 'owner', 'kasir']))
                  <li class="nav-item">
                    <a href="/admin/transaksi" class="nav-link {{ request()->is('admin/transaksi*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-cash-coin"></i>
                      <p>Transaksi</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array($role, ['admin', 'owner']))
                  <li class="nav-item">
                    <a href="/admin/testimonials" class="nav-link {{ request()->is('admin/testimonials*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-star-half"></i>
                      <p>Review Testimonial</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array($role, ['admin', 'owner']))
                  <li class="nav-item">
                    <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-bar-chart-line"></i>
                      <p>Laporan</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array($role, ['admin', 'owner']))
                  <li class="nav-item">
                    <a href="/admin/users" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-people"></i>
                      <p>Users</p>
                    </a>
                  </li>
                  @endif
                  @if($role === 'admin')
                  <li class="nav-item">
                    <a href="/admin/chat" class="nav-link {{ request()->is('admin/chat*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-chat-dots"></i>
                      <p>Chat</p>
                    </a>
                    </li>
                    @endif
                
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->

      <main class="app-main">
        @if ($title == 'Dashboard')
          @yield('dashboard')
          @elseif ($title == 'Profile')
          @yield('Profile')
        @elseif ($title == 'Booking')
          @yield('booking')
        @elseif ($title == 'Service')
          @yield('Service')
        @elseif ($title == 'Vehicle')
          @yield('Vehicle')
        @elseif ($title == 'Transaksi')
          @yield('Transaksi')
        @elseif ($title == 'Testimonial Review')
          @yield('TestimonialReview')
        @elseif ($title == 'Laporan')
          @yield('Laporan')
        @elseif ($title == 'Traffic')
          @yield('Traffic')
        @elseif ($title == 'Spareparts')
          @yield('Spareparts')
        @elseif ($title == 'Users')
          @yield('Users')
        @elseif ($title == 'chat')
          @yield('chat')
        @endif
      </main>

      <footer class="app-footer mt-auto">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">SerVix Admin</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; {{ date('Y') }}&nbsp;
          <a href="/" class="text-decoration-none">Servix Admin</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{asset('be/assets/js/adminlte.js')}}"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!-- OPTIONAL SCRIPTS -->

    <!-- sortablejs -->
    <script
      src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
      crossorigin="anonymous"
    ></script>

    <!-- sortablejs -->
    <script>
      const sortableContainer = document.querySelector('.connectedSortable');
      if (sortableContainer) {
        new Sortable(sortableContainer, {
          group: 'shared',
          handle: '.card-header',
        });

        const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
        cardHeaders.forEach((cardHeader) => {
          cardHeader.style.cursor = 'move';
        });
      }
    </script>

    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>

    <!-- ChartJS -->
    <script>
      // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
      // IT'S ALL JUST JUNK FOR DEMO
      // ++++++++++++++++++++++++++++++++++++++++++

      const sales_chart_options = {
        series: [
          {
            name: 'Digital Goods',
            data: [28, 48, 40, 19, 86, 27, 90],
          },
          {
            name: 'Electronics',
            data: [65, 59, 80, 81, 56, 55, 40],
          },
        ],
        chart: {
          height: 300,
          type: 'area',
          toolbar: {
            show: false,
          },
        },
        legend: {
          show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth',
        },
        xaxis: {
          type: 'datetime',
          categories: [
            '2023-01-01',
            '2023-02-01',
            '2023-03-01',
            '2023-04-01',
            '2023-05-01',
            '2023-06-01',
            '2023-07-01',
          ],
        },
        tooltip: {
          x: {
            format: 'MMMM yyyy',
          },
        },
      };

      const revenueChartEl = document.querySelector('#revenue-chart');
      if (revenueChartEl) {
        const sales_chart = new ApexCharts(revenueChartEl, sales_chart_options);
        sales_chart.render();
      }
    </script>

    <!-- jsvectormap -->
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
      integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
      integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
      crossorigin="anonymous"
    ></script>

    <!-- jsvectormap -->
    <script>
      // World map by jsVectorMap
      if (document.querySelector('#world-map')) {
        new jsVectorMap({
          selector: '#world-map',
          map: 'world',
        });
      }

      // Sparkline charts
      const option_sparkline1 = {
        series: [
          {
            data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
          },
        ],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline1El = document.querySelector('#sparkline-1');
      if (sparkline1El) {
        const sparkline1 = new ApexCharts(sparkline1El, option_sparkline1);
        sparkline1.render();
      }

      const option_sparkline2 = {
        series: [
          {
            data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
          },
        ],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline2El = document.querySelector('#sparkline-2');
      if (sparkline2El) {
        const sparkline2 = new ApexCharts(sparkline2El, option_sparkline2);
        sparkline2.render();
      }

      const option_sparkline3 = {
        series: [
          {
            data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
          },
        ],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline3El = document.querySelector('#sparkline-3');
      if (sparkline3El) {
        const sparkline3 = new ApexCharts(sparkline3El, option_sparkline3);
        sparkline3.render();
      }
    </script>
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
