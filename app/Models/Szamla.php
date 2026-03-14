<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Szamla extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'szamlak';
    protected $primaryKey = 'szamla_id';

    protected $fillable = [
        'megrendeles_id',
        'ugyfel_id',
        'szamla_tipus',
        'storno_eredeti_id',
        'dijbekero_szamla_id',
        'billingo_id',
        'billingo_szam',
        'billingo_pdf_url',
        'sajat_pdf_path',
        'kiallitas_datum',
        'teljesites_datum',
        'fizetesi_hatarido',
        'fizetesi_mod',
        'netto_osszeg',
        'afa_osszeg',
        'brutto_osszeg',
        'statusz',
        'megjegyzes',
    ];

    protected $casts = [
        'kiallitas_datum'   => 'date',
        'teljesites_datum'  => 'date',
        'fizetesi_hatarido' => 'date',
        'netto_osszeg'      => 'decimal:2',
        'afa_osszeg'        => 'decimal:2',
        'brutto_osszeg'     => 'decimal:2',
        'billingo_id'       => 'integer',
    ];

    // ─── Státusz konstansok ────────────────────────────────────────────────────
    const STATUSZ_FUGGOBEN   = 'fuggoben';
    const STATUSZ_FIZETVE    = 'fizetve';
    const STATUSZ_KESEDELMES = 'kesedelmes';
    const STATUSZ_STORNOZVA  = 'stornozva';

    const TIPUS_DIJBEKERO = 'dijbekero';
    const TIPUS_SZAMLA    = 'szamla';
    const TIPUS_STORNO    = 'storno';

    // ─── Kapcsolatok ──────────────────────────────────────────────────────────

    public function megrendeles()
    {
        return $this->belongsTo(Megrendeles::class, 'megrendeles_id', 'Megrendeles_ID');
    }

    public function ugyfel()
    {
        return $this->belongsTo(Ugyfel::class, 'ugyfel_id', 'Ugyfel_ID');
    }

    /** Tételek (anyagok, munkaóra, egyéb díjak) */
    public function tetelek()
    {
        return $this->hasMany(SzamlaTetel::class, 'szamla_id', 'szamla_id')
                    ->orderBy('sorrend');
    }

    /** Fizetési tranzakciók ehhez a számlához */
    public function fizetesek()
    {
        return $this->hasMany(Fizetes::class, 'szamla_id', 'szamla_id');
    }

    /** Legutóbbi sikeres fizetés */
    public function sikeresFlzetes()
    {
        return $this->hasOne(Fizetes::class, 'szamla_id', 'szamla_id')
                    ->where('statusz', 'fizetve')
                    ->latest('fizetes_idopontja');
    }

    /** Audit log bejegyzések */
    public function auditLog()
    {
        return $this->hasMany(FizetesAuditLog::class, 'szamla_id', 'szamla_id')
                    ->latest('created_at');
    }

    /** Emlékeztetők */
    public function emlekeztetok()
    {
        return $this->hasMany(FizetesEmlekeztetok::class, 'szamla_id', 'szamla_id')
                    ->latest('kuldes_idopontja');
    }

    /** Az a számla, amelyet ez a stornó érvénytelenít */
    public function stornoEredeti()
    {
        return $this->belongsTo(Szamla::class, 'storno_eredeti_id', 'szamla_id');
    }

    /** A stornó számla, amely ezt a számlát érvényteleníti (ha van) */
    public function stornoSzamla()
    {
        return $this->hasOne(Szamla::class, 'storno_eredeti_id', 'szamla_id');
    }

    /** A díjbekérő, amelyből ez a számla keletkezett */
    public function dijbekero()
    {
        return $this->belongsTo(Szamla::class, 'dijbekero_szamla_id', 'szamla_id');
    }

    /** A számla, amelyre ez a díjbekérő konvertálódott */
    public function veglegSzamla()
    {
        return $this->hasOne(Szamla::class, 'dijbekero_szamla_id', 'szamla_id');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /** Hány nap van a határidőig (negatív = lejárt) */
    public function getHatarIdoNapokAttribute(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->fizetesi_hatarido, false);
    }

    /** Fizetve van-e? */
    public function getFizetve(): bool
    {
        return $this->statusz === self::STATUSZ_FIZETVE;
    }

    /** Késedelmes-e? */
    public function getKesedelmes(): bool
    {
        return $this->statusz === self::STATUSZ_KESEDELMES ||
               ($this->statusz === self::STATUSZ_FUGGOBEN && $this->hatarido_napok < 0);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeFuggoben($query)
    {
        return $query->where('statusz', self::STATUSZ_FUGGOBEN);
    }

    public function scopeFizetve($query)
    {
        return $query->where('statusz', self::STATUSZ_FIZETVE);
    }

    public function scopeKesedelmes($query)
    {
        return $query->where('statusz', self::STATUSZ_KESEDELMES);
    }

    public function scopeValodiSzamla($query)
    {
        return $query->where('szamla_tipus', self::TIPUS_SZAMLA);
    }

    // ─── Összeg újraszámítás (tételek alapján) ────────────────────────────────

    public function osszegekUjraszamit(): void
    {
        $this->netto_osszeg  = $this->tetelek->sum('netto_osszeg');
        $this->afa_osszeg    = $this->tetelek->sum('afa_osszeg');
        $this->brutto_osszeg = $this->tetelek->sum('brutto_osszeg');
        $this->save();
    }
}
