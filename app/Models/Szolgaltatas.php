<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Szolgaltatas extends Model
{
    protected $table = 'szolgaltatasok';
    protected $primaryKey = 'Szolgaltatas_ID';

    protected $fillable = ['Tipus'];

    use HasFactory;

    public function ugyfelek()
    {
        return $this->hasMany(Ugyfel::class, 'SzolgID');
    }

    public function munkanaplok()
    {
        return $this->hasMany(Munkanaplo::class, 'Szolgaltatas_ID');
    }
}
