<?php

namespace App\Http\Controllers;

use App\Models\Varos;
use Illuminate\Http\Request;

class VarosController extends Controller
{
    /**
     * Új város/irányítószám felvétele AJAX-on keresztül.
     * Visszaadja a létrehozott rekordot JSON-ben.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Irny_szam' => 'required|integer|min:1000|max:9999',
            'nev' => 'required|string|min:2|max:100',
        ]);

        $varos = Varos::create($validated);

        return response()->json([
            'id' => $varos->id,
            'Irny_szam' => $varos->Irny_szam,
            'nev' => $varos->nev,
        ], 201);
    }
}
