<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Szerelo extends Model
{
    protected $table = 'szerelo';
    protected $primaryKey = 'Szerelo_ID';
    protected $fillable = ['Nev', 'Telefonszam'];

    use HasFactory;

    public function ugyfel()
    {
        return $this->hasMany(Ugyfel::class, 'Szerelo_ID');
    }

    public function szolgaltatasok()
    {
        return $this->belongsToMany(Szolgaltatas::class, 'szerelo_szolgaltatas', 'Szerelo_ID', 'Szolgaltatas_ID');
    }

    // Szerelo modellben
    public static function getLatestIfRecent()
    {
        $halfMinuteAgo = now()->subSeconds(30);
        return self::where('created_at', '>', $halfMinuteAgo)->latest()->first();
    }
}
