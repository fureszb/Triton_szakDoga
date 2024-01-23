<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Munka extends Model
{
    protected $table = 'munka';
    protected $primaryKey = 'MunkaID';
    use HasFactory;
}
