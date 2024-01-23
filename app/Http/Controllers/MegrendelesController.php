<?php

namespace App\Http\Controllers;

use App\Models\Megrendeles;
use Illuminate\Http\Request;

class MegrendelesController extends Controller
{
    public function index()
    {
        $megrendelesek = Megrendeles::all();
        return view('megrendeles.index', compact('megrendelesek'));
    }

    public function create()
    {
        return view('megrendeles.create');
    }

    public function show($id)
{
    $megrendeles = Megrendeles::find($id);

    if (!$megrendeles) {
        return redirect()->route('megrendeles.index')->with('error', 'A megrendelés nem található');
    }

    return view('megrendeles.show', compact('megrendeles'));
}


    public function edit($id)
    {
        $megrendeles = Megrendeles::find($id);
        return view('megrendeles.edit', compact('megrendeles'));
    }

    public function destroy($id)
    {
        $megrendeles = Megrendeles::find($id);
        $megrendeles->delete();
        return redirect()->route('megrendeles.index')->with('success', 'Megrendelés sikeresen törölve');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Megrendeles_Nev' => 'required',
            'Objektum_Cim' => 'required',
            
        ]);

        $megrendeles = new Megrendeles();
        $megrendeles->Megrendeles_Nev = $request->Megrendeles_Nev;
        $megrendeles->Objektum_Cim = $request->Objektum_Cim;
      
        $megrendeles->save();

        return redirect()->route('megrendeles.index')->with('success', 'Megrendelés sikeresen létrehozva.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Megrendeles_Nev' => 'required',
            'Objektum_Cim' => 'required',
          
        ]);

        $megrendeles = Megrendeles::find($id);
        $megrendeles->Megrendeles_Nev = $request->Megrendeles_Nev;
        $megrendeles->Objektum_Cim = $request->Objektum_Cim;

        $megrendeles->save();

        return redirect()->route('megrendeles.index')->with('success', 'Megrendelés sikeresen frissítve.');
    }
}
