<?php

namespace App\Http\Controllers;

use App\Models\Anyag;
use Illuminate\Http\Request;

class AnyagController extends Controller
{
    public function index()
    {
        $anyagok = Anyag::all();
        return view('anyagok.index', compact('anyagok'));
    }

    public function show($id)
    {
        $anyag = Anyag::findOrFail($id);
        return view('anyagok.show', compact('anyag'));
    }
    public function edit($id)
    {
        $anyag = Anyag::findOrFail($id);
        return view('anyagok.edit', compact('anyag'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nev' => 'required',
            'Mertekegyseg' => 'required',
        ]);

        $anyag = Anyag::findOrFail($id);
        $anyag->Nev = $request->Nev;
        $anyag->Mertekegyseg = $request->Mertekegyseg;
        $anyag->save();

        return redirect()->route('anyagok.index')->with('success', 'Anyag sikeresen frissítve!');
    }
    public function destroy($id)
    {
        $anyag = Anyag::findOrFail($id);
        $anyag->delete();

        return redirect()->route('anyagok.index')->with('success', 'Anyag sikeresen törölve!');
    }


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
