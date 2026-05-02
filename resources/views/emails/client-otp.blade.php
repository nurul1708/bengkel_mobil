<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Verifikasi Akun</title>
</head>
<body style="margin:0; padding:24px; background-color:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <div style="max-width:560px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(15, 23, 42, 0.08);">
        <div style="background:#dc3545; padding:24px 32px; color:#ffffff;">
            <h1 style="margin:0; font-size:24px;">Verifikasi Akun Servix</h1>
            <p style="margin:8px 0 0; font-size:14px; opacity:0.92;">Satu langkah lagi untuk menyelesaikan pendaftaran akun Anda.</p>
        </div>

        <div style="padding:32px;">
            <p style="margin-top:0;">Halo {{ $user->name }},</p>
            <p>Kami menerima permintaan verifikasi akun untuk email ini. Gunakan kode OTP berikut:</p>

            <div style="margin:24px 0; padding:18px 20px; background:#fff5f5; border:1px dashed #dc3545; border-radius:14px; text-align:center;">
                <div style="font-size:32px; font-weight:700; letter-spacing:8px; color:#dc3545;">{{ $otpCode }}</div>
            </div>

            <p style="margin-bottom:8px;">Kode ini berlaku selama {{ $expiresInMinutes }} menit.</p>
            <p style="margin-top:0; margin-bottom:24px;">Batas waktu: {{ optional($otpExpiresAt)->format('d M Y H:i') }}.</p>

            <p style="margin-bottom:0; font-size:14px; color:#6b7280;">
                Jika Anda tidak merasa melakukan pendaftaran, abaikan email ini.
            </p>
        </div>
    </div>
</body>
</html>
