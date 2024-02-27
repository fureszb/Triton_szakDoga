<?php

namespace App\Http\Controllers;

use App\Models\Anyag;
use Illuminate\Http\Request;

class AnyagController extends Controller
{
    public function create()
    {
        return view('anyagok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nev' => 'required',
            'Mertekegyseg' => 'required',
        ]);

        $anyag = new Anyag();
        $anyag->Nev = $request->Nev;
        $anyag->Mertekegyseg = $request->Mertekegyseg;
        $anyag->save();

        return redirect()->route('ugyfel.index')->with('success', 'Anyag sikeresen létrehozva!');
    }
}
