<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ugyfel extends Model
{
    protected $table = 'ugyfel';

    // $primaryKey alapértelmezetten 'id'
    protected $fillable = ['varos_id', 'user_id', 'nev', 'email', 'telefonszam', 'szamlazasi_nev', 'szamlazasi_cim', 'adoszam'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('nev', 'LIKE', '%'.$keyword.'%')
                ->orWhere('id', $keyword);
        });
    }

    public function megrendelesek()
    {
        return $this->hasMany(Megrendeles::class, 'ugyfel_id', 'id');
    }

    public function varos()
    {
        return $this->belongsTo(Varos::class, 'varos_id');
    }
}
