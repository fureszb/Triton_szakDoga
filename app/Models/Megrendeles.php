<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Megrendeles extends Model
{
    use HasFactory;

    protected $table = 'megrendeles';

    // $primaryKey alapértelmezetten 'id'
    protected $fillable = [
        'ugyfel_id', 'varos_id', 'megrendeles_nev', 'utca_hazszam', 'statusz', 'pdf_eleresi_ut',
    ];

    public function ugyfel()
    {
        return $this->belongsTo(Ugyfel::class, 'ugyfel_id');
    }

    public function varos()
    {
        return $this->belongsTo(Varos::class, 'varos_id');
    }

    public function munkak()
    {
        return $this->hasMany(Munka::class, 'megrendeles_id', 'id');
    }

    public function felhasznaltAnyagok()
    {
        return $this->hasManyThrough(
            FelhasznaltAnyag::class,
            Munka::class,
            'megrendeles_id',
            'munka_id',
            'id',
            'id'
        );
    }

    public function create()
    {
        $ugyfelek = Ugyfel::all();

        return view('megrendeles.create', compact('ugyfelek'));
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('megrendeles_nev', 'LIKE', '%'.$keyword.'%')
                ->orWhere('id', $keyword);
        });
    }

    // ─── Számlázás & fizetés kapcsolatok ──────────────────────────────────────

    /** A megrendeléshez tartozó számla (1:1) */
    public function szamla()
    {
        return $this->hasOne(Szamla::class, 'megrendeles_id', 'id')
            ->where('szamla_tipus', 'szamla');
    }

    /** Díjbekérő (ha van) */
    public function dijbekero()
    {
        return $this->hasOne(Szamla::class, 'megrendeles_id', 'id')
            ->where('szamla_tipus', 'dijbekero');
    }

    /** Összes számla típus (számla + díjbekérő + stornó) */
    public function osszesSzamla()
    {
        return $this->hasMany(Szamla::class, 'megrendeles_id', 'id');
    }

    /** Összes szamla tipusu dokumentum (hasMany) */
    public function tobbSzamla()
    {
        return $this->hasMany(Szamla::class, 'megrendeles_id', 'id')
            ->where('szamla_tipus', 'szamla');
    }

    /** Összes dijbekero tipusu dokumentum (hasMany) */
    public function tobbDijbekero()
    {
        return $this->hasMany(Szamla::class, 'megrendeles_id', 'id')
            ->where('szamla_tipus', 'dijbekero');
    }

    /** Fizetési tranzakciók */
    public function fizetesek()
    {
        return $this->hasMany(Fizetes::class, 'megrendeles_id', 'id');
    }

    /** Audit log */
    public function auditLog()
    {
        return $this->hasMany(FizetesAuditLog::class, 'megrendeles_id', 'id')
            ->latest('created_at');
    }

    /** Emlékeztetők */
    public function emlekeztetok()
    {
        return $this->hasMany(FizetesEmlekeztetok::class, 'megrendeles_id', 'id');
    }

    // ─── Kényelmi accessor – fizetve van-e? ───────────────────────────────────
    // Csak akkor igaz, ha MINDEN nem-stornó számla/díjbekérő fizetve van (és van legalább egy)
    public function getFizetveAttribute(): bool
    {
        $aktivak = $this->osszesSzamla->where('szamla_tipus', '!=', 'storno');
        return $aktivak->isNotEmpty() && $aktivak->every(fn($sz) => $sz->statusz === 'fizetve');
    }

    // ─── Kényelmi accessor – van-e függőben lévő (bejelentett) fizetés? ───────
    public function getFuggobenFizetesAttribute(): bool
    {
        foreach ($this->osszesSzamla as $szamla) {
            if ($szamla->statusz !== 'fizetve' && $szamla->fizetesek->where('statusz', 'fuggoben')->isNotEmpty()) {
                return true;
            }
        }
        return false;
    }
}
