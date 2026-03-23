<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anyag extends Model
{
    protected $table = 'anyag';

    // $primaryKey alapértelmezetten 'id'
    protected $fillable = ['nev', 'mertekegyseg'];

    use HasFactory;
}
