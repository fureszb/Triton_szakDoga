<?php

namespace App\Services;

use App\Models\FizetesAuditLog;
use App\Models\Szamla;
use App\Models\SzamlaTetel;
use Illuminate\Support\Facades\DB;

class SzamlaService
{
    /**
     * Díjbekérő kifizetése után automatikusan létrehoz egy végleges számlát.
     *
     * - Ha már létezik végleges számla ehhez a díjbekérőhöz → visszaadja azt (idempotent)
     * - Másolja az összes tételt a díjbekérőről
     * - statusz = 'fizetve' (már ki van fizetve)
     * - dijbekero_szamla_id = eredeti díjbekérő szamla_id
     *
     * @throws \Throwable
     */
    public function dijbekerobolVeglesSzamla(Szamla $dijbekero, string $fizetesiMod = 'stripe'): Szamla
    {
        return DB::transaction(function () use ($dijbekero, $fizetesiMod) {
            // Idempotencia check LOCK-kal a tranzakción BELÜL:
            // Két párhuzamos hívás (pl. Stripe success + webhook) ne hozzon létre
            // két végleges számlát ugyanabból a díjbekérőből (race condition)
            $dijbekero->load('tetelek');
            $locked = Szamla::where('szamla_id', $dijbekero->szamla_id)
                ->lockForUpdate()
                ->first();

            // Ellenőrizzük a friss adatot a lock után
            $meglevo = Szamla::where('dijbekero_szamla_id', $locked->szamla_id)
                ->where('szamla_tipus', Szamla::TIPUS_SZAMLA)
                ->first();

            if ($meglevo) {
                return $meglevo;
            }

            // Végleges számla létrehozása
            $szamla = Szamla::create([
                'megrendeles_id' => $dijbekero->megrendeles_id,
                'ugyfel_id' => $dijbekero->ugyfel_id,
                'szamla_tipus' => Szamla::TIPUS_SZAMLA,
                'dijbekero_szamla_id' => $dijbekero->szamla_id,
                'kiallitas_datum' => now()->toDateString(),
                'teljesites_datum' => now()->toDateString(),
                'fizetesi_hatarido' => now()->toDateString(),
                'fizetesi_mod' => $fizetesiMod,
                'netto_osszeg' => $dijbekero->netto_osszeg,
                'afa_osszeg' => $dijbekero->afa_osszeg,
                'brutto_osszeg' => $dijbekero->brutto_osszeg,
                'statusz' => Szamla::STATUSZ_FIZETVE,
                'megjegyzes' => 'Automatikusan generált számla — díjbekérő #'
                                         .str_pad($dijbekero->szamla_id, 5, '0', STR_PAD_LEFT)
                                         .' kifizetésekor.',
            ]);

            // Tételek másolása díjbekérőről
            foreach ($dijbekero->tetelek as $tetel) {
                SzamlaTetel::create([
                    'szamla_id' => $szamla->szamla_id,
                    'tetel_tipus' => $tetel->tetel_tipus,
                    'nev' => $tetel->nev,
                    'mennyiseg' => $tetel->mennyiseg,
                    'mertekegyseg' => $tetel->mertekegyseg,
                    'egyseg_netto_ar' => $tetel->egyseg_netto_ar,
                    'afa_kulcs' => $tetel->afa_kulcs,
                    'netto_osszeg' => $tetel->netto_osszeg,
                    'afa_osszeg' => $tetel->afa_osszeg,
                    'brutto_osszeg' => $tetel->brutto_osszeg,
                    'sorrend' => $tetel->sorrend,
                ]);
            }

            FizetesAuditLog::naplo(
                szamlaId: $szamla->szamla_id,
                esemeny: 'szamla_kiallitva',
                adatok: [
                    'uj' => [
                        'statusz' => 'fizetve',
                        'dijbekero_id' => $dijbekero->szamla_id,
                        'brutto_osszeg' => $szamla->brutto_osszeg,
                        'forrás' => 'automatikus díjbekérő konverzió',
                    ],
                ],
                megrendelesId: $dijbekero->megrendeles_id,
            );

            return $szamla;
        });
    }
}
