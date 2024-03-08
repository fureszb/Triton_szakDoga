<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Szolgaltatas extends Model
{
    protected $table = 'szolgaltatas';
    protected $primaryKey = 'Szolgaltatas_ID';

    protected $fillable = ['Tipus'];

    use HasFactory;


    public function szerelok()
    {
        return $this->belongsToMany(Szerelo::class, 'szerelo_szolgaltatas', 'Szolgaltatas_ID', 'Szerelo_ID');
    }
}
