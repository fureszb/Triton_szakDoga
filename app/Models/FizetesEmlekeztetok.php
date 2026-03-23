<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FizetesEmlekeztetok extends Model
{
    protected $table = 'fizetes_emlekeztetok';

    protected $primaryKey = 'emlekeztetok_id';

    // Emlékeztető rekord immutable – nincs updated_at
    public $timestamps = false;

    protected $fillable = [
        'szamla_id',
        'megrendeles_id',
        'ugyfel_id',
        'email_cim',
        'tipus',
        'statusz',
        'kuldes_idopontja',
        'hiba_uzenet',
        'created_at',
    ];

    protected $casts = [
        'kuldes_idopontja' => 'datetime',
        'created_at' => 'datetime',
    ];

    // ─── Boot – automatikus created_at ────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (! $model->created_at) {
                $model->created_at = now();
            }
            if (! $model->kuldes_idopontja) {
                $model->kuldes_idopontja = now();
            }
        });
    }

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

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeSikeres($query)
    {
        return $query->where('statusz', 'sikeres');
    }

    public function scopeHaromNapos($query)
    {
        return $query->where('tipus', 'harom_napos');
    }

    public function scopeEgyNapos($query)
    {
        return $query->where('tipus', 'egy_napos');
    }

    public function scopeLejart($query)
    {
        return $query->where('tipus', 'lejart');
    }
}
