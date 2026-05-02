<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login Admin | SerVix</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" />

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    
    <!-- Third Party Plugins -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('be/assets/css/adminlte.css') }}" />

    <style>
      :root {
        --primary-color: #007bff;
        --secondary-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        --accent-gradient: linear-gradient(135deg, #072197 0%, #5b91e2 100%);
      }

      body.login-page {
        background: var(--accent-gradient); /* Warna background utama */
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .login-box {
        width: 400px;
        animation: fadeInDown 0.8s ease-out;
      }

      .login-logo a {
        color: #ffffff !important;
        font-weight: 700;
        letter-spacing: 1px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      }

      .card {
        border-radius: 15px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        border: none !important;
        overflow: hidden;
      }

      .login-card-body {
        padding: 2.5rem 2rem;
      }

      .login-box-msg {
        font-weight: 600;
        color: #4a5568;
        font-size: 1.1rem;
        padding-bottom: 20px;
      }

      .input-group {
        background: #f8fafc;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
      }

      .input-group:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        background: #fff;
      }

      .input-group .form-control {
        background: transparent;
        border: none;
        padding: 12px 15px;
      }

      .input-group .form-control:focus {
        box-shadow: none;
      }

      .input-group-text {
        background: transparent;
        border: none;
        color: #a0aec0;
      }

      .btn-primary {
        background: var(--accent-gradient);
        border: none;
        border-radius: 10px;
        padding: 10px;
        font-weight: 600;
        transition: transform 0.2s;
      }

      .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
      }

      .back-to-web {
        transition: color 0.3s;
      }

      .back-to-web:hover {
        color: var(--primary-color) !important;
      }

      @keyframes fadeInDown {
        from {
          opacity: 0;
          transform: translateY(-20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @media (max-width: 480px) {
        .login-box {
          width: 90%;
        }
      }
    </style>
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo mb-4">
        <a href="/" class="text-decoration-none">
          <i class="bi bi-gear-wide-connected"></i> <b>SerVix</b> Admin
        </a>
      </div>
      
      <div class="card shadow-lg">
        <div class="card-body login-card-body">
          <p class="login-box-msg text-center">Selamat Datang Kembali!</p>

          @if(session('error'))
            <div class="alert alert-danger small border-0 mb-4 animate__animated animate__shakeX" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            </div>
          @endif

          <form action="/admin/login" method="post">
            @csrf
            <div class="input-group mb-3">
              <input type="email" name="email" class="form-control" placeholder="Alamat Email" required autofocus />
              <div class="input-group-text">
                <span class="bi bi-envelope"></span>
              </div>
            </div>
            
            <div class="input-group mb-4">
              <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required />
              <div class="input-group-text">
                <span class="bi bi-lock-fill"></span>
              </div>
            </div>

            <div class="row align-items-center mb-3">
              <div class="col-7">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="flexCheckDefault" />
                  <label class="form-check-label text-secondary small" for="flexCheckDefault"> 
                    Ingat Saya 
                  </label>
                </div>
              </div>
              <div class="col-5">
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Masuk <i class="bi bi-arrow-right-short"></i></button>
                </div>
              </div>
            </div>
          </form>

          <div class="mt-4 pt-3 border-top text-center">
            <a href="/" class="text-secondary small text-decoration-none back-to-web">
              <i class="bi bi-house-door me-1"></i> Kembali ke Beranda Utama
            </a>
          </div>

        </div>
      </div>
      
      <div class="text-center mt-3">
        <p class="text-white-50 small">&copy; {{ date('Y') }} SerVix Pro - All Rights Reserved</p>
      </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('be/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('be/assets/js/adminlte.js') }}"></script>
  </body>
</html>