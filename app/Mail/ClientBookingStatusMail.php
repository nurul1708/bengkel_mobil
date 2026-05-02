<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientBookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->booking->status === 'confirmed'
            ? 'Servix - Booking Anda Diterima'
            : 'Servix - Booking Anda Ditolak';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client-booking-status',
            with: [
                'booking' => $this->booking,
                'client' => $this->booking->user,
                'vehicle' => $this->booking->vehicle,
                'service' => $this->booking->service,
                'statusLabel' => $this->booking->status === 'confirmed' ? 'Diterima' : 'Ditolak',
                'statusColor' => $this->booking->status === 'confirmed' ? '#198754' : '#dc3545',
                'messageBody' => $this->booking->status === 'confirmed'
                    ? 'Booking service Anda sudah kami terima. Silakan datang sesuai jadwal yang telah dipilih.'
                    : 'Mohon maaf, booking service Anda belum dapat kami proses pada jadwal tersebut. Silakan lakukan booking ulang dengan jadwal lain.',
            ],
        );
    }
}
