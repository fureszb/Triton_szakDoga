<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SzamlaTetel extends Model
{
    use HasFactory;

    protected $table      = 'szamla_tetelek';
    protected $primaryKey = 'tetel_id';

    protected $fillable = [
        'szamla_id',
        'tetel_tipus',
        'anyag_id',
        'munka_id',
        'nev',
        'mennyiseg',
        'mertekegyseg',
        'egyseg_netto_ar',
        'afa_kulcs',
        'netto_osszeg',
        'afa_osszeg',
        'brutto_osszeg',
        'sorrend',
    ];

    protected $casts = [
        'mennyiseg'       => 'decimal:3',
        'egyseg_netto_ar' => 'decimal:2',
        'netto_osszeg'    => 'decimal:2',
        'afa_osszeg'      => 'decimal:2',
        'brutto_osszeg'   => 'decimal:2',
        'afa_kulcs'       => 'integer',
        'sorrend'         => 'integer',
    ];

    // ─── Kapcsolatok ──────────────────────────────────────────────────────────

    public function szamla()
    {
        return $this->belongsTo(Szamla::class, 'szamla_id', 'szamla_id');
    }

    public function anyag()
    {
        return $this->belongsTo(Anyag::class, 'anyag_id', 'Anyag_ID');
    }

    public function munka()
    {
        return $this->belongsTo(Munka::class, 'munka_id', 'Munka_ID');
    }

    // ─── Összeg számítás ──────────────────────────────────────────────────────

    /**
     * Automatikusan kiszámítja és beállítja az összegeket a megadott adatokból.
     * Használat: SzamlaTetel::szamitOsszegek(['mennyiseg'=>2, 'egyseg_netto_ar'=>10000, 'afa_kulcs'=>27])
     */
    public static function szamitOsszegek(array $adatok): array
    {
        $mennyiseg      = $adatok['mennyiseg'] ?? 1;
        $egysegNettoAr  = $adatok['egyseg_netto_ar'] ?? 0;
        $afaKulcs       = $adatok['afa_kulcs'] ?? 27;

        $netto   = round($mennyiseg * $egysegNettoAr, 2);
        $afa     = round($netto * ($afaKulcs / 100), 2);
        $brutto  = round($netto + $afa, 2);

        return array_merge($adatok, [
            'netto_osszeg'  => $netto,
            'afa_osszeg'    => $afa,
            'brutto_osszeg' => $brutto,
        ]);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeAnyag($query)
    {
        return $query->where('tetel_tipus', 'anyag');
    }

    public function scopeMunkaora($query)
    {
        return $query->where('tetel_tipus', 'munkaora');
    }

    public function scopeEgyeb($query)
    {
        return $query->where('tetel_tipus', 'egyeb');
    }
}
