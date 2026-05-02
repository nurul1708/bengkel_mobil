<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Servix</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            background: url('{{ asset("fe/img/bg.jpg") }}') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* overlay gelap */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
        }

        .login-box {
            position: relative;
            z-index: 2;
            width: 350px;
            padding: 40px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }

        .login-box h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .input-group input::placeholder {
            color: #ccc;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: #861d0a;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #531307;
        }

        .brand {
            text-align: center;
            color: #861d0a;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .error {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Close Button Styling */
.close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    text-decoration: none;
    color: rgba(255, 255, 255, 0.5);
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

<div class="login-box">
    <a href="{{ url('/') }}" class="close-btn" title="Back to Home">&times;</a>
    
    <div class="brand">SerVix</div>
    <h2>Login</h2>
    


    <form method="POST" action="{{ route('client.login') }}">
        @csrf

        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-login">Login</button>
        <p style="text-align: center; margin-top: 15px; color: #fff;">
            Don't have an account? <a href="{{ route('client.register') }}" style="color: #ff6b6b;">Register</a>
        </p>
    </form>
</div>

</body>
</html>