<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Servix - Bengkel Mobil</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="{{ asset('/fe/img/favicon.ico') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('/fe/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/fe/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/fe/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/fe/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/fe/css/style.css') }}" rel="stylesheet">

    <style>
        html, body {
            overflow-x: hidden;
        }
        .floating-chat {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.5s;
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }
        .floating-chat-wrapper {
            position: fixed;
            right: 24px;
            bottom: 24px;
            z-index: 99;
            width: 50px;
            height: 50px;
        }
        .floating-chat-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 999px;
            border: 2px solid #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            box-shadow: 0 6px 18px rgba(220, 53, 69, 0.35);
        }
        .floating-chat:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }
        .site-navbar .navbar-brand {
            min-width: 0;
            gap: 0.75rem;
        }
        .site-navbar .brand-logo {
            width: 72px;
            height: 72px;
            object-fit: cover;
        }
        .site-navbar .brand-title {
            font-size: 2rem;
            line-height: 1;
        }
        .site-navbar .navbar-nav .nav-link,
        .site-navbar .dropdown-item {
            white-space: normal;
        }
        .site-navbar .account-avatar {
            width: 38px;
            height: 38px;
            object-fit: cover;
        }
        .site-navbar .account-toggle {
            border-radius: 999px;
        }
        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }
        @media (max-width: 991.98px) {
            .site-navbar {
                padding-block: 0.5rem;
            }
            .site-navbar .navbar-brand {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            .site-navbar .brand-logo {
                width: 54px;
                height: 54px;
            }
            .site-navbar .brand-title {
                font-size: 1.5rem;
            }
            .site-navbar .navbar-toggler {
                margin-right: 1rem !important;
            }
            .site-navbar .navbar-collapse {
                padding: 1rem;
                border-top: 1px solid rgba(0, 0, 0, 0.08);
            }
            .site-navbar .account-menu .dropdown-menu {
                position: static !important;
                transform: none !important;
                width: 100%;
                margin-top: 0.5rem;
            }
        }
        @media (max-width: 767.98px) {
            .floating-chat-wrapper {
                right: 16px;
                bottom: 16px;
            }
            .floating-chat,
            .floating-chat-wrapper {
                width: 46px;
                height: 46px;
            }
            .page-header {
                background-position: center;
                background-size: cover;
            }
        }
    </style>
</head>

<body>
    <div id="spinner" class="bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="container-fluid bg-light p-0">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-map-marker-alt text-primary me-2"></small>
                    <small>1Jln Karadenan 07, Sukahati, Bogor</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center py-3">
                    <small class="far fa-clock text-primary me-2"></small>
                    <small>Mon - Fri : 09.00 AM - 09.00 PM</small>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <small>+62 89989 95332</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center">
                    <a class="btn btn-sm-square bg-white text-primary me-1" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-sm-square bg-white text-primary me-1" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-sm-square bg-white text-primary me-1" href=""><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-sm-square bg-white text-primary me-0" href=""><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0 site-navbar">
        <a href="/customer/home" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <img src="{{ asset('be/assets/assets/img/logo.png') }}" alt="SerVix Logo" class="img-fluid rounded-circle brand-logo">
            <h2 class="m-0 text-primary brand-title">Servix</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0 align-items-lg-center">
                <a href="/customer/home" class="nav-item nav-link {{ request()->is('customer/home') ? 'active' : '' }}">Home</a>
                <a href="/customer/about" class="nav-item nav-link {{ request()->is('customer/about') ? 'active' : '' }}">About</a>
                <a href="/customer/services" class="nav-item nav-link {{ request()->is('customer/services') ? 'active' : '' }}">Services</a>
                <a href="{{ route('customer.bookings.index') }}" class="nav-item nav-link {{ request()->is('customer/bookings') ? 'active' : '' }}">Booking</a>
                <!-- @if (Auth::guard('client')->check())
                    <a href="{{ route('client.history.index') }}" class="nav-item nav-link {{ request()->is('customer/history') ? 'active' : '' }}">Riwayat</a>
                    <a href="{{ route('client.transactions.index') }}" class="nav-item nav-link {{ request()->is('customer/transactions') || request()->is('customer/payments*') ? 'active' : '' }}">Transaksi</a>
                @endif -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu fade-up m-0">
                        <a href="/customer/team" class="dropdown-item {{ request()->is('customer/team') ? 'active' : '' }}"><i class="bi bi-people me-2"></i>Technicians</a>
                        <a href="/customer/testimonial" class="dropdown-item {{ request()->is('customer/testimonial') ? 'active' : '' }}"><i class="bi bi-chat-square-quote me-2"></i>Testimonial</a>
                    </div>
                </div>

                @if (Auth::guard('client')->check())
                    <div class="nav-item dropdown account-menu ms-lg-3">
                        <button class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 mt-2 mt-lg-0 account-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(auth('client')->user()->photo)
                                <img src="{{ asset('storage/' . auth('client')->user()->photo) }}" class="rounded-circle account-avatar" alt="{{ auth('client')->user()->name }}">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth('client')->user()->name) }}" class="rounded-circle account-avatar" alt="{{ auth('client')->user()->name }}">
                            @endif
                            <span class="text-truncate">{{ auth('client')->user()->name }}</span>
                            <i class="bi bi-chevron-down small"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li>
                                <a class="dropdown-item {{ request()->is('customer/profile*') ? 'active' : '' }}" href="{{ route('client.profile.show') }}">
                                    <i class="bi bi-person-circle me-2"></i>Profile
                                </a>
                            </li>
                            <!-- <li>
                                <a class="dropdown-item {{ request()->is('customer/bookings') ? 'active' : '' }}" href="{{ route('customer.bookings.index') }}">
                                    <i class="bi bi-calendar-check me-2"></i>Booking
                                </a>
                            </li> -->
                            <li>
                                <a class="dropdown-item {{ request()->is('customer/history') ? 'active' : '' }}" href="{{ route('client.history.index') }}">
                                    <i class="bi bi-clock-history me-2"></i>Riwayat
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->is('customer/transactions') || request()->is('customer/payments*') ? 'active' : '' }}" href="{{ route('client.transactions.index') }}">
                                    <i class="bi bi-receipt me-2"></i>Transaksi
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('client.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="d-flex flex-column flex-lg-row gap-2 ms-lg-3 mt-3 mt-lg-0">
    <!-- Tambahkan class 'd-inline-flex justify-content-center align-items-center' -->
    <a href="{{ route('client.loginForm') }}" class="btn btn-outline-primary rounded-pill px-4 d-inline-flex justify-content-center align-items-center">
        <i class="bi bi-box-arrow-in-right me-1"></i>Login
    </a>
    
    <a href="{{ route('client.register') }}" class="btn btn-primary rounded-pill px-4 d-inline-flex justify-content-center align-items-center">
        <i class="bi bi-person-plus me-1"></i>Register
    </a>
</div>
                @endif
            </div>
        </div>
    </nav>

    @if($title == 'Home')
        @yield('carosel')
        @yield('service1')
        @yield('about')
        @yield('fact')
        @yield('kt')
        @yield('service')
        @yield('booking')
        @yield('team')
        @yield('testimonial')
    @endif

    @if($title == 'About')
        @yield('header')
        @yield('about')
        @yield('team')
    @endif

    @if($title == 'Services')
        @yield('header')
        @yield('service')
        @yield('booking')
        @yield('testimonial')
    @endif

    @if($title == 'Booking')
        @yield('header')
        @yield('service')
        @yield('booking')
        @yield('action')
    @endif

    @if($title == 'Team')
        @yield('header')
        @yield('team')
    @endif

    @if($title == 'Testimonial')
        @yield('header')
        @yield('testimonial')
    @endif

    @if($title == 'Contact')
        @yield('header')
        @yield('contact')
    @endif

    @if($title == 'Profile')
        @yield('Profile')
    @endif

    @if($title == 'Register')
        @yield('Register')
    @endif

    @if($title == 'chat')
        @yield('chat')
    @endif

    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Address</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Jln.karadenan 07</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+62 89989 95332</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>servix@gmail.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Opening Hours</h4>
                    <h6 class="text-light">Monday - Friday:</h6>
                    <p class="mb-4">09.00 AM - 09.00 PM</p>
                    <h6 class="text-light">Saturday - Sunday:</h6>
                    <p class="mb-0">09.00 AM - 12.00 PM</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Services</h4>
                    <a class="btn btn-link" href="">Diagnostic Test</a>
                    <a class="btn btn-link" href="">Engine Servicing</a>
                    <a class="btn btn-link" href="">Tires Replacement</a>
                    <a class="btn btn-link" href="">Oil Changing</a>
                    <a class="btn btn-link" href="">Vacuam Cleaning</a>
                </div>
               
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">Servix</a>, All Right Reserved.
                        Designed By <span> Nurul Istinafiah </span>
                        <br>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="">Home</a>
                            <a href="">Cookies</a>
                            <a href="">Help</a>
                            <a href="">FQAs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::guard('client')->check())
        <div class="floating-chat-wrapper">
            <a href="{{ route('chat.index') }}" class="floating-chat btn btn-lg btn-primary btn-lg-square">
                <i class="bi bi-chat"></i>
            </a>
            @if(($unreadAdminChatsCount ?? 0) > 0)
                <span class="badge bg-danger floating-chat-badge">
                    {{ $unreadAdminChatsCount > 99 ? '99+' : $unreadAdminChatsCount }}
                </span>
            @endif
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/fe/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('/fe/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('/fe/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('/fe/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('/fe/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('/fe/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('/fe/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('/fe/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('/fe/js/main.js') }}"></script>
</body>

</html>
