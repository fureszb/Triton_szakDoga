<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ugyfel extends Model
{
    protected $table = 'ugyfel';
    protected $primaryKey = 'UgyfelID';
    use HasFactory;

    public function szerelo()
    {
        return $this->belongsTo(Szerelo::class, 'SzereloID');
    }

    public function szolgaltatas()
    {
        return $this->belongsTo(Szolgaltatas::class, 'SzolgID');
    }

    public function munka()
    {
        return $this->belongsTo(Munka::class, 'MunkaID')->select(['MunkaID', 'Jelleg', 'Leiras']);
    }
    public function scopeSearch($query, $keyword)
{
    return $query->where(function ($query) use ($keyword) {
        $query->where('nev', 'LIKE', '%' . $keyword . '%')
              ->orWhere('UgyfelID', $keyword);
    });
}
}
