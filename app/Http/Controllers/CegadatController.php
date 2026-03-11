<?php

namespace App\Http\Controllers;

use App\Models\Cegadat;
use Illuminate\Http\Request;

class CegadatController extends Controller
{
    public function edit()
    {
        $cegadat = Cegadat::get();
        return view('cegadatok.edit', compact('cegadat'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nev'            => 'required|string|max:255',
            'szekhelycim'    => 'required|string|max:255',
            'adoszam'        => 'required|string|max:100',
            'cegjegyzekszam' => 'required|string|max:100',
            'telefon'        => 'required|string|max:100',
            'email'          => 'required|email|max:255',
            'bankszamlaszam' => 'nullable|string|max:255',
            'web'            => 'nullable|string|max:255',
        ]);

        $cegadat = Cegadat::get();
        $cegadat->update($request->only([
            'nev', 'szekhelycim', 'adoszam', 'cegjegyzekszam',
            'telefon', 'email', 'bankszamlaszam', 'web',
        ]));

        return redirect()->route('cegadatok.edit')->with('success', 'Cégadatok sikeresen frissítve.');
    }
}
