<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varos extends Model
{
    use HasFactory;

    protected $table = 'varos';

    // $primaryKey alapértelmezetten 'id'
    protected $fillable = ['nev', 'Irny_szam'];
}
