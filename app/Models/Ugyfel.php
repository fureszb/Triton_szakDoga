<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ugyfel extends Model
{
    protected $table = 'ugyfel';
    protected $primaryKey = 'Ugyfel_ID';
    protected $fillable = ['Ugyfel_ID',  'Nev', 'Email', 'Telefonszam', 'Szamlazasi_Nev', 'Szamlazasi_Cim', 'Adoszam'];


    use HasFactory;

    public function szerelo()
    {
        return $this->belongsTo(Szerelo::class, 'Ugyfel_ID');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('nev', 'LIKE', '%' . $keyword . '%')
                ->orWhere('Ugyfel_ID', $keyword);
        });
    }
    public function megrendelesek()
    {
        return $this->hasMany(Megrendeles::class, 'Ugyfel_ID', 'Ugyfel_ID');
    }
}
