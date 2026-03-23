<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FelhasznaltAnyag extends Pivot
{
    protected $table = 'felhasznalt_anyag';

    protected $primaryKey = ['munka_id', 'anyag_id']; // összetett PK

    public $incrementing = false;

    protected $fillable = ['munka_id', 'anyag_id', 'mennyiseg'];

    use HasFactory;

    public function anyag()
    {
        return $this->belongsTo(Anyag::class, 'anyag_id');
    }
}
