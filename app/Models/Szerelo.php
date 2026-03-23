<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Szerelo extends Model
{
    protected $table = 'szerelo';

    // $primaryKey alapértelmezetten 'id'
    protected $fillable = ['nev', 'telefonszam'];

    use HasFactory;

    public function ugyfel()
    {
        return $this->hasMany(Ugyfel::class, 'id');
    }

    public function szolgaltatasok()
    {
        return $this->belongsToMany(Szolgaltatas::class, 'szerelo_szolgaltatas', 'szerelo_id', 'szolgaltatas_id');
    }

    public static function getLatestIfRecent()
    {
        $halfMinuteAgo = now()->subSeconds(30);

        return self::where('created_at', '>', $halfMinuteAgo)->latest()->first();
    }
}
