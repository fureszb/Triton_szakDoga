<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Munka extends Model
{
    protected $table = 'munka';
    protected $primaryKey = 'Munka_ID';
    protected $fillable = ['Megrendeles_ID', 'Szerelo_ID','Szolgaltatas_ID','Leiras', 'Munkakezdes_Idopontja', 'Munkabefejezes_Idopontja'];
    use HasFactory;

    public function szerelo()
    {
        return $this->belongsTo(Szerelo::class, 'Szerelo_ID');
    }

    public function szolgaltatas()
    {
        return $this->belongsTo(Szolgaltatas::class, 'Szolgaltatas_ID');
    }

    public function ugyfel()
    {
        return $this->belongsTo(Ugyfel::class, 'Ugyfel_ID');
    }

    public function felhasznalt_anyagok()
    {
        return $this->hasMany(FelhasznaltAnyag::class, 'Munka_ID');
    }
}
