<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Szolgaltatas extends Model
{
    protected $table = 'szolgaltatas';

    // $primaryKey alapértelmezetten 'id'
    protected $fillable = ['tipus'];

    use HasFactory;

    public function szerelok()
    {
        return $this->belongsToMany(Szerelo::class, 'szerelo_szolgaltatas', 'szolgaltatas_id', 'szerelo_id');
    }

    public function munkak()
    {
        return $this->hasMany(Munka::class, 'szolgaltatas_id', 'id');
    }

    public function megrendeles()
    {
        return $this->belongsTo(Megrendeles::class, 'megrendeles_id');
    }
}
