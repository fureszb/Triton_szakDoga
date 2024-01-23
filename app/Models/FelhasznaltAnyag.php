<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FelhasznaltAnyag extends Pivot
{
    protected $table = 'felhasznalt_anyagok';
    public $incrementing = true;
    protected $primaryKey = ['Munka_ID', 'Anyag_ID'];
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
