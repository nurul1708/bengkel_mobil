<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Servix</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }

        .register-container {
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

        .preview-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }

         /* Close Button Styling */
.close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    text-decoration: none;
    color: rgba(10, 10, 10, 0.5);
    font-size: 20px;
    font-weight: bold;
    transition: 0.3s;
    line-height: 1;
}

.close-btn:hover {
    color: #ff6b6b; /* Matches your register link color */
    transform: scale(1.1);
}
    </style>
</head>
<body>

<div class="container-fluid register-container">
    <div class="row h-100">

        <!-- LEFT -->
        <div class="col-md-6 d-none d-md-flex flex-column justify-content-center left-side">
            <div class="left-content">
                <h1>Welcome to Servix!</h1>
                <p class="mt-3">
                    Bengkel modern untuk kendaraan kamu  
                    Daftar sekarang dan nikmati layanan terbaik!
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-md-6 d-flex align-items-center justify-content-center right-side">
            <div class="w-75">

                <h3 class="mb-4 text-center text-danger">Register</h3>
                <a href="{{ url('/') }}" class="close-btn" title="Back to Home">&times;</a>
    

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

        
                <form action="{{ route('client.register.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Ulangi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>No HP</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat') }}</textarea>
                    </div>

                    <div class="mb-3 text-center">
                        <img id="preview" src="https://via.placeholder.com/70" class="preview-img mb-2">
                        <input type="file" name="foto" class="form-control" onchange="previewImage(event)">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </div>

                    <div class="text-center mt-3">
                        Sudah punya akun?
                        <a href="{{ route('client.loginForm') }}" class="text-danger">Login</a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
