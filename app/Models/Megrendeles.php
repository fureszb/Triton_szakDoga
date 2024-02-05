<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugyfel;

class Megrendeles extends Model
{
    use HasFactory;

    protected $table = 'megrendeles';
    protected $primaryKey = 'Megrendeles_ID';
    protected $fillable = ['Ugyfel_ID','Megrendeles_Nev', 'Objektum_Cim', 'Alairt_e', 'Pdf_EleresiUt'];



    // ... (Egyéb modell definíciók)

    /**
     * Scope a query to only include orders where the name or address matches the keyword.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('Megrendeles_Nev', 'LIKE', '%' . $keyword . '%')
                ->orWhere('Objektum_Cim', 'LIKE', '%' . $keyword . '%');
        });
    }

    public function create()
    {
        $ugyfelek = Ugyfel::all(); // Az összes ügyfél betöltése
        return view('megrendeles.create', compact('ugyfelek'));
    }
}
