<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varos extends Model
{
    use HasFactory;
    protected $table = 'varos';
    protected $primaryKey = 'Varos_ID';
    protected $fillable = ['Nev', 'Irny_szam'];

}
