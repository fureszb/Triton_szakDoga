<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objektum extends Model
{
    use HasFactory;
    protected $table = 'objektum';
    protected $primaryKey = 'Objektum_ID';
    protected $fillable = ['Varos'];
   
}
