<?php

namespace App\Http\Controllers;

use App\Models\FelhasznaltAnyag;
use App\Models\Megrendeles;
use App\Models\Munkanaplo;
use App\Models\Szerelo;
use App\Models\Szolgaltatas;
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
        $szolgaltatasok = Szolgaltatas::all();
        $szerelok = Szerelo::all();
        $anyagok = FelhasznaltAnyag::all();
        return view('megrendeles.create', compact('ugyfelek', 'szolgaltatasok', 'szerelok', 'anyagok'));
    }






    public function show($id)
    {
        $megrendeles = Megrendeles::with(['ugyfel', 'szolgaltatas', 'szerelo', 'anyagok'])->find($id);

        if (!$megrendeles) {
            return redirect()->route('megrendeles.index')->with('error', 'A megrendelés nem található.');
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
            'Ugyfel_ID' => 'required|exists:ugyfel,Ugyfel_ID',
            'Szolgaltatas_ID' => 'required|exists:szolgaltatasok,Szolgaltatas_ID',
            'Szerelo_ID' => 'required|exists:szerelok,Szerelo_ID',
            'Leiras' => 'nullable',
            'Munkakezdes_Idopontja' => 'required|date',
            'Munkabefejezes_Idopontja' => 'required|date|after:Munkakezdes_Idopontja',
            'Anyag_ID.*' => 'required|exists:anyagok,Anyag_ID',
            'Mennyiseg.*' => 'required|numeric|min:1'
        ]);

        // Megrendeles létrehozása
        $megrendeles = new Megrendeles();
        $megrendeles->fill($request->only([
            'Megrendeles_Nev', 'Objektum_Cim', 'Ugyfel_ID', 'Szolgaltatas_ID'
        ]));
        $megrendeles->save();

        // Munka létrehozása
        $munka = new Munkanaplo([
            'Megrendeles_ID' => $megrendeles->Megrendeles_ID,
            'Szerelo_ID' => $request->Szerelo_ID,
            'Szolgaltatas_ID' => $request->Szolgaltatas_ID,
            'Leiras' => $request->Leiras,
            'Munkakezdes_Idopontja' => $request->Munkakezdes_Idopontja,
            'Munkabefejezes_Idopontja' => $request->Munkabefejezes_Idopontja
        ]);
        $munka->save();


        $anyagok = $request->input('Anyag_ID');
        $mennyisegek = $request->input('Mennyiseg');

        $felhasznaltAnyag = new FelhasznaltAnyag([
            'Munka_ID' => $munka->Munka_ID,
            'Anyag_ID' => $anyagok->Anyag_ID,
            'Mennyiseg' => $mennyisegek
        ]);

        $felhasznaltAnyag->save();





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
