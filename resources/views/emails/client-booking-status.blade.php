<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Booking Servix</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7fb; margin: 0; padding: 24px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 12px 40px rgba(15, 23, 42, 0.08);">
        <div style="background: {{ $statusColor }}; color: #ffffff; padding: 24px 32px;">
            <h1 style="margin: 0; font-size: 24px;">Booking Anda {{ $statusLabel }}</h1>
            <p style="margin: 8px 0 0; font-size: 14px; opacity: 0.92;">Informasi terbaru untuk booking service kendaraan Anda di Servix.</p>
        </div>

        <div style="padding: 32px;">
            <p style="margin-top: 0;">Halo {{ $client->name ?? 'Customer' }},</p>
            <p>{{ $messageBody }}</p>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 24px 0;">
                <p style="margin: 0 0 10px;"><strong>ID Booking:</strong> #{{ $booking->id }}</p>
                <p style="margin: 0 0 10px;"><strong>Layanan:</strong> {{ $service->service_name ?? '-' }}</p>
                <p style="margin: 0 0 10px;"><strong>Kendaraan:</strong> {{ ($vehicle->brand ?? '-') }} {{ ($vehicle->model ?? '') }}</p>
                <p style="margin: 0 0 10px;"><strong>Jadwal:</strong> {{ $booking->booking_date_label }} {{ $booking->booking_time_label !== '-' ? $booking->booking_time_label : '' }}</p>
                <p style="margin: 0;"><strong>Status:</strong> {{ $statusLabel }}</p>
            </div>

            @if(!empty($booking->complaint))
                <p style="margin: 0 0 20px;"><strong>Keluhan:</strong> {{ $booking->complaint }}</p>
            @endif

            <p style="margin-bottom: 0;">Terima kasih,<br>Tim Servix</p>
        </div>
    </div>
</body>
</html>
