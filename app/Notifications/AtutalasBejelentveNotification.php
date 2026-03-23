<?php

namespace App\Notifications;

use App\Models\Megrendeles;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AtutalasBejelentveNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Megrendeles $megrendeles,
        public readonly string $kozlemeny,
        public readonly float $osszeg,
        public readonly string $ugyfelNev,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'megrendeles_id'  => $this->megrendeles->id,
            'megrendeles_nev' => $this->megrendeles->megrendeles_nev,
            'ugyfel_nev'      => $this->ugyfelNev,
            'kozlemeny'       => $this->kozlemeny,
            'osszeg'          => $this->osszeg,
            'url'             => route('fizetes.index'),
        ];
    }
}
