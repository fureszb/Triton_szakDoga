<?php

namespace App\Mail;

use App\Models\Megrendeles;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FizetesiEmlekeztetoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Megrendeles $megrendeles,
        public int $napokHatra,  // hány nap van még a határidőig
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Fizetési emlékeztető – '.$this->napokHatra.' nap múlva lejár',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.fizetesi_emlekeztetо',
        );
    }
}
