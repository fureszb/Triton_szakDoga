<?php

namespace App\Http\Controllers;

use App\Models\Szerelo;
use App\Models\Szolgaltatas;
use Illuminate\Http\Request;

class SzereloController extends Controller
{
    public function index()
    {
        $szerelok = Szerelo::all();
        return view('szerelok.index', compact('szerelok'));
    }

    public function create()
    {
        $szolgaltatasok = Szolgaltatas::all();
        return view('szerelok.create', compact('szolgaltatasok'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Nev' => 'required',
            'Telefonszam' => 'required',
        ]);

        $szerelo = new Szerelo();
        $szerelo->Nev = $request->Nev;
        $szerelo->Telefonszam = $request->Telefonszam;
        $szerelo->save();

        // Hozzárendeljük az összes Szolgaltatas entitást a frissen létrehozott Szerelo entitáshoz
        $szolgaltatasIds = Szolgaltatas::pluck('Szolgaltatas_ID')->toArray();
        $szerelo->szolgaltatasok()->sync($szolgaltatasIds);

        return redirect()->route('szerelok.index')->with('success', 'Szerelő sikeresen létrehozva!');
    }

    public function show($id)
    {
        $szerelo = Szerelo::findOrFail($id);
        return view('szerelok.show', compact('szerelo'));
    }

    public function edit($id)
    {
        $szerelo = Szerelo::findOrFail($id);
        $szolgaltatasok = Szolgaltatas::all();

        // Egyértelműen hivatkozunk a táblára és az oszlopra a pluck metódusban
        $selectedSzolgaltatasok = $szerelo->szolgaltatasok()->pluck('szolgaltatas.Szolgaltatas_ID')->toArray();

        return view('szerelok.edit', compact('szerelo', 'szolgaltatasok', 'selectedSzolgaltatasok'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'Nev' => 'required',
            'Telefonszam' => 'required',
        ]);

        $szerelo = Szerelo::findOrFail($id);
        $szerelo->Nev = $request->Nev;
        $szerelo->Telefonszam = $request->Telefonszam;
        $szerelo->save();

        // Hozzárendeljük az összes Szolgaltatas entitást a frissített Szerelo entitáshoz
        $szolgaltatasIds = Szolgaltatas::pluck('Szolgaltatas_ID')->toArray();
        $szerelo->szolgaltatasok()->sync($szolgaltatasIds);

        return redirect()->route('szerelok.index')->with('success', 'Szerelő sikeresen frissítve!');
    }

    public function destroy($id)
    {
        $szerelo = Szerelo::findOrFail($id);
        $szerelo->szolgaltatasok()->detach();
        $szerelo->delete();

        return redirect()->route('szerelok.index')->with('success', 'Szerelő sikeresen törölve!');
    }
}
