<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SzereloSzolgaltatas extends Model
{
    use HasFactory;

    protected $table = 'szerelo_szolgaltatas';

    protected $primaryKey = ['szerelo_id', 'szolgaltatas_id'];

    public $incrementing = false;

    protected $fillable = ['szerelo_id', 'szolgaltatas_id'];
}
