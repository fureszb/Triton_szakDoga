<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anyag extends Model
{
    protected $table = 'anyagok'; // Táblanév többes számú formában
    protected $primaryKey = 'Anyag_ID';
    use HasFactory;

    public function felhasznalt_anyagok()
    {
        return $this->hasMany(FelhasznaltAnyag::class, 'Anyag_ID');
    }
}
