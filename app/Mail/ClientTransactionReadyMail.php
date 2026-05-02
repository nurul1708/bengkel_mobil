<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientTransactionReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Transaction $transaction)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Servix - Service Selesai, Silakan Lakukan Pembayaran',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client-transaction-ready',
            with: [
                'transaction' => $this->transaction,
                'booking' => $this->transaction->booking,
                'client' => $this->transaction->booking?->user,
                'service' => $this->transaction->service,
            ],
        );
    }
}
