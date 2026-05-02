<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP - Servix</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }

        .verify-container {
            height: 100vh;
        }

        .left-side {
            position: relative;
            background: url('{{ asset("fe/img/bg.jpg") }}') no-repeat center center/cover;
            color: white;
            padding: 50px;
        }

        .left-side::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 1;
        }

        .left-content {
            position: relative;
            z-index: 2;
        }

        .right-side {
            background: white;
            padding: 40px;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            background: #dc3545;
            border: none;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background: #b02a37;
        }

        .otp-input {
            letter-spacing: 10px;
            font-size: 24px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container-fluid verify-container">
    <div class="row h-100">

        <!-- LEFT -->
        <div class="col-md-6 d-none d-md-flex flex-column justify-content-center left-side">
            <div class="left-content">
                <h1>Verifikasi Akun</h1>
                <p class="mt-3">
                    Masukkan kode OTP yang telah dikirim ke email Anda
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-md-6 d-flex align-items-center justify-content-center right-side">
            <div class="w-75">

                <h3 class="mb-4 text-center text-danger">Verifikasi OTP</h3>

                <div class="alert alert-light border">
                    Kode OTP sudah dikirim ke email {{ $maskedEmail }}.
                    Berlaku sampai {{ optional($otpExpiresAt)->format('H:i') }} WIB
                    atau {{ $otpExpiresMinutes }} menit sejak dikirim.
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info">
                        {{ session('info') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('client.verifyOTP') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Kode OTP (6 digit)</label>
                        <input type="text" name="otp_code" class="form-control otp-input" 
                               maxlength="6" placeholder="000000" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Verifikasi</button>
                    </div>

                </form>

                <div class="text-center mt-3">
                    <form action="{{ route('client.resendOTP') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-link text-danger">Kirim Ulang OTP</button>
                    </form>
                </div>

                <div class="text-center mt-2">
                    <a href="{{ route('client.register') }}" class="text-secondary">Daftar Ulang</a>
                </div>

            </div>
        </div>

    </div>
</div>

</body>
</html>
