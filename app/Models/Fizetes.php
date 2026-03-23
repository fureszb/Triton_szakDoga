<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fizetes extends Model
{
    use HasFactory;

    protected $table = 'fizetesek';

    protected $primaryKey = 'fizetes_id';

    protected $fillable = [
        'szamla_id',
        'megrendeles_id',
        'ugyfel_id',
        'fizetes_mod',
        'osszeg',
        'deviza',
        'statusz',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'banki_hivatkozas',
        'fizetes_idopontja',
        'megjegyzes',
    ];

    protected $casts = [
        'osszeg' => 'decimal:2',
        'fizetes_idopontja' => 'datetime',
    ];

    // ─── Státusz konstansok ────────────────────────────────────────────────────
    const STATUSZ_FUGGOBEN = 'fuggoben';

    const STATUSZ_FIZETVE = 'fizetve';

    const STATUSZ_SIKERTELEN = 'sikertelen';

    const STATUSZ_VISSZATERITVE = 'visszateritve';

    // ─── Kapcsolatok ──────────────────────────────────────────────────────────

    public function szamla()
    {
        return $this->belongsTo(Szamla::class, 'szamla_id', 'szamla_id');
    }

    public function megrendeles()
    {
        return $this->belongsTo(Megrendeles::class, 'megrendeles_id', 'id');
    }

    public function ugyfel()
    {
        return $this->belongsTo(Ugyfel::class, 'ugyfel_id', 'id');
    }

    public function auditLog()
    {
        return $this->hasMany(FizetesAuditLog::class, 'fizetes_id', 'fizetes_id')
            ->latest('created_at');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getSikeresAttribute(): bool
    {
        return $this->statusz === self::STATUSZ_FIZETVE;
    }

    public function getStripeAllapotAttribute(): ?string
    {
        if ($this->fizetes_mod !== 'stripe') {
            return null;
        }

        return $this->statusz;
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

    public function scopeStripe($query)
    {
        return $query->where('fizetes_mod', 'stripe');
    }

    public function scopeBanki($query)
    {
        return $query->where('fizetes_mod', 'banki_atutalas');
    }
}
