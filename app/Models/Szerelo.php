<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Szerelo extends Model
{
    protected $table = 'szerelek'; // Táblanév többes számú formában
    protected $primaryKey = 'Szerelo_ID';
    use HasFactory;

    public function ugyfelek()
    {
        return $this->hasMany(Ugyfel::class, 'SzereloID');
    }

    public function munkanaplok()
    {
        return $this->hasMany(Munkanaplo::class, 'Szerelo_ID');
    }
}
