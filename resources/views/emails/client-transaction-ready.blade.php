<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Transaksi Servis</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7fb; margin: 0; padding: 24px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 12px 40px rgba(15, 23, 42, 0.08);">
        <div style="background: #0d6efd; color: #ffffff; padding: 24px 32px;">
            <h1 style="margin: 0; font-size: 24px;">Service Anda Sudah Selesai</h1>
            <p style="margin: 8px 0 0; font-size: 14px; opacity: 0.92;">Transaksi service kendaraan Anda sudah dibuat.</p>
        </div>

        <div style="padding: 32px;">
            <p style="margin-top: 0;">Halo {{ $client->name ?? 'Customer' }},</p>
            <p>Kami ingin memberi tahu bahwa service kendaraan Anda telah selesai.</p>
            <p>Admin kami sudah membuat transaksi untuk service tersebut. Silakan hubungi admin atau datang ke bengkel untuk informasi pembayaran dan pengambilan kendaraan.</p>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 24px 0;">
                <p style="margin: 0 0 10px;"><strong>ID Booking:</strong> #{{ $booking->id ?? '-' }}</p>
                <p style="margin: 0 0 10px;"><strong>Layanan:</strong> {{ $service->service_name ?? '-' }}</p>
                <p style="margin: 0 0 10px;"><strong>Status Booking:</strong> {{ ucfirst(str_replace('_', ' ', $booking->status ?? 'completed')) }}</p>
                <p style="margin: 0;"><strong>Total Tagihan:</strong> Rp {{ number_format($transaction->grand_total ?? 0, 0, ',', '.') }}</p>
            </div>

            <p style="margin-bottom: 0;">Terima kasih,<br>Tim Servix</p>
        </div>
    </div>
</body>
</html>
