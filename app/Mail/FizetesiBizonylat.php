<?php

namespace App\Mail;

use App\Models\Megrendeles;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FizetesiBizonylat extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Megrendeles $megrendeles)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Fizetési visszaigazolás – Megrendelés #'
                .str_pad($this->megrendeles->id, 5, '0', STR_PAD_LEFT),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.fizetesi_bizonylat',
        );
    }
}
