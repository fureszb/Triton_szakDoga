<?php

namespace App\Console\Commands;

use App\Mail\FizetesiEmlekeztetoMail;
use App\Models\Megrendeles;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class FizetesiEmlekeztetoKuldes extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fizetesi:emlekeztetok';

    /**
     * The console command description.
     */
    protected $description = 'Fizetési emlékeztető emailek küldése 3 és 1 nappal a határidő előtt';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $ma = Carbon::today();
        $harom = $ma->copy()->addDays(3);
        $egy = $ma->copy()->addDays(1);

        $megrendelesek = Megrendeles::with('ugyfel')
            ->where('Fizetve', false)
            ->whereNotNull('Vegosszeg')
            ->whereNotNull('FizetesiHatarido')
            ->where(function ($q) use ($harom, $egy) {
                $q->whereDate('FizetesiHatarido', $harom)
                    ->orWhereDate('FizetesiHatarido', $egy);
            })
            ->get();

        if ($megrendelesek->isEmpty()) {
            $this->info('Nincs küldendő emlékeztető ma.');

            return self::SUCCESS;
        }

        $kuldve = 0;

        foreach ($megrendelesek as $megrendeles) {
            $ugyfel = $megrendeles->ugyfel;
            $email = $ugyfel?->email ?? null;

            if (! $email) {
                $this->warn("Megrendelés #{$megrendeles->id}: nincs email cím, kihagyva.");

                continue;
            }

            $hatarido = Carbon::parse($megrendeles->FizetesiHatarido);
            $napokHatra = (int) $ma->diffInDays($hatarido, false);

            try {
                Mail::to($email)->send(new FizetesiEmlekeztetoMail($megrendeles, $napokHatra));
                $this->info("Emlékeztető elküldve: {$email} (#{$megrendeles->id}, {$napokHatra} nap)");
                $kuldve++;
            } catch (\Exception $e) {
                $this->error("Hiba küldésnél ({$email}): ".$e->getMessage());
            }
        }

        $this->info("Összesen {$kuldve} emlékeztető elküldve.");

        return self::SUCCESS;
    }
}
