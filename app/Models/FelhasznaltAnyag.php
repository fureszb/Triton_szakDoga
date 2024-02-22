<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FelhasznaltAnyag extends Pivot
{
    protected $table = 'felhasznalt_anyag';
    protected $primaryKey = ['Munka_ID', 'Anyag_ID'];
    protected $fillable = ['Munka_ID', 'Anyag_ID', 'Mennyiseg'];

    use HasFactory;

    public function munkanaplo()
    {
        return $this->belongsTo(Munkanaplo::class, 'Munka_ID');
    }

    public function anyag()
    {
        return $this->belongsTo(Anyag::class, 'Anyag_ID');
    }
}
