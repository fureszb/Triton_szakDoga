<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FizetesAuditLog extends Model
{
    protected $table = 'fizetes_audit_log';

    protected $primaryKey = 'log_id';

    // Audit log soha nem módosítható – csak insert
    public $timestamps = false;

    protected $fillable = [
        'szamla_id',
        'fizetes_id',
        'megrendeles_id',
        'user_id',
        'esemeny',
        'regi_ertek',
        'uj_ertek',
        'megjegyzes',
        'ip_cim',
        'created_at',
    ];

    protected $casts = [
        'regi_ertek' => 'array',
        'uj_ertek' => 'array',
        'created_at' => 'datetime',
    ];

    // ─── Boot – automatikus created_at ────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = now();
        });
    }

    // ─── Kapcsolatok ──────────────────────────────────────────────────────────

    public function szamla()
    {
        return $this->belongsTo(Szamla::class, 'szamla_id', 'szamla_id');
    }

    public function fizetes()
    {
        return $this->belongsTo(Fizetes::class, 'fizetes_id', 'fizetes_id');
    }

    public function megrendeles()
    {
        return $this->belongsTo(Megrendeles::class, 'megrendeles_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ─── Factory metódus – logolás egyszerűsítése ──────────────────────────────

    /**
     * Egyszerű audit log bejegyzés írása.
     *
     * Használat:
     * FizetesAuditLog::naplo($szamla->szamla_id, 'statusz_valtozas', [
     *     'regi' => ['statusz' => 'fuggoben'],
     *     'uj'   => ['statusz' => 'fizetve'],
     * ]);
     */
    public static function naplo(
        ?int $szamlaId,
        string $esemeny,
        array $adatok = [],
        ?int $fizetesId = null,
        ?int $megrendelesId = null
    ): self {
        return static::create([
            'szamla_id' => $szamlaId,
            'fizetes_id' => $fizetesId,
            'megrendeles_id' => $megrendelesId,
            'user_id' => auth()->id(),
            'esemeny' => $esemeny,
            'regi_ertek' => $adatok['regi'] ?? null,
            'uj_ertek' => $adatok['uj'] ?? null,
            'megjegyzes' => $adatok['megjegyzes'] ?? null,
            'ip_cim' => request()->ip(),
        ]);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeEsemeny($query, string $esemeny)
    {
        return $query->where('esemeny', $esemeny);
    }

    public function scopeSzamlahoz($query, int $szamlaId)
    {
        return $query->where('szamla_id', $szamlaId);
    }
}
