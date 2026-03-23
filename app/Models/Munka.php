<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Munka extends Model
{
    protected $table = 'munka';

    // $primaryKey alapértelmezetten 'id'
    protected $fillable = ['megrendeles_id', 'szerelo_id', 'szolgaltatas_id', 'leiras', 'munkakezdes_idopontja', 'munkabefejezes_idopontja'];

    use HasFactory;

    public function szerelo()
    {
        return $this->belongsTo(Szerelo::class, 'szerelo_id');
    }

    public function szolgaltatas()
    {
        return $this->belongsTo(Szolgaltatas::class, 'szolgaltatas_id');
    }

    public function ugyfel()
    {
        return $this->belongsTo(Ugyfel::class, 'ugyfel_id');
    }

    public function felhasznalt_anyagok()
    {
        return $this->hasMany(FelhasznaltAnyag::class, 'munka_id');
    }
}
