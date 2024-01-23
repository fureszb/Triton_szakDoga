<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Munkanaplo extends Model
{
    protected $table = 'munkanaplo'; // Táblanév többes számú formában
    protected $primaryKey = 'Munka_ID';
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
