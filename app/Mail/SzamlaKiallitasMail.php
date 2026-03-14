<?php

namespace App\Mail;

use App\Models\Szamla;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SzamlaKiallitasMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Szamla $szamla) {}

    public function envelope(): Envelope
    {
        $szamlaNum = $this->szamla->billingo_szam
            ?? 'TRITON-' . str_pad($this->szamla->szamla_id, 6, '0', STR_PAD_LEFT);

        return new Envelope(
            subject: "Számla kiállítva – {$szamlaNum}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.szamla_kiallitas',
        );
    }
}
