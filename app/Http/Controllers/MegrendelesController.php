<?php

namespace App\Http\Controllers;

use App\Models\Megrendeles;
use App\Models\Ugyfel;
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
        $ugyfelek = Ugyfel::all(); 
        return view('megrendeles.create', compact('ugyfelek')); 
    }





    public function show($id)
    {
        $megrendeles = Megrendeles::find($id);

        /*if (!$megrendeles) {
            return redirect()->route('megrendeles.index')->with('error', 'A megrendelés nem található.');
        }*/

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
            'Ugyfel_ID' => 'required|exists:ugyfel,Ugyfel_ID',
        ]);

        $megrendeles = new Megrendeles();
        $megrendeles->Megrendeles_Nev = $request->Megrendeles_Nev;
        $megrendeles->Objektum_Cim = $request->Objektum_Cim;
        $megrendeles->Ugyfel_ID = $request->Ugyfel_ID;

        $megrendeles->save();

        return redirect()->route('megrendeles.index')->with('success', 'Megrendelés sikeresen létrehozva.');
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'Megrendeles_Nev' => 'required',
            'Objektum_Cim' => 'required',
            'Alairt_e' => 'required',
            'Pdf_EleresiUt' => 'nullable',
        ]);

        $megrendeles = Megrendeles::find($id);
        $megrendeles->Megrendeles_Nev = $request->input('Megrendeles_Nev');
        $megrendeles->Objektum_Cim = $request->input('Objektum_Cim');
        $megrendeles->Alairt_e = $request->input('Alairt_e');
        $megrendeles->Pdf_EleresiUt = $request->input('Pdf_EleresiUt');

        $megrendeles->save();

        return redirect()->route('megrendeles.index')->with('success', 'Megrendelés sikeresen frissítve.');
    }
}
