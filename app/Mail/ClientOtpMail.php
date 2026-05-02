<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public int $expiresInMinutes
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Servix - Kode OTP Verifikasi Akun',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client-otp',
            with: [
                'user' => $this->user,
                'otpCode' => $this->user->otp_code,
                'otpExpiresAt' => $this->user->otp_expires_at,
                'expiresInMinutes' => $this->expiresInMinutes,
            ],
        );
    }
}
