<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SzereloSzolgaltatas extends Model
{
    use HasFactory;

    protected $table = 'szerelo_szolgaltatas';

    protected $primaryKey = ['Szerelo_ID', 'Szolgaltatas_ID'];
    protected $fillable = ['Szerelo_ID', 'Szolgaltatas_ID'];

}
