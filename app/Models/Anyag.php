<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anyag extends Model
{
    protected $table = 'anyag';
    protected $primaryKey = 'Anyag_ID';
    protected $fillable = ['Nev'];
    use HasFactory;
}
