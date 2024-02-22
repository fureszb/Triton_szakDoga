<?php

namespace App\Http\Controllers;

use App\Models\Anyag;
use App\Models\FelhasznaltAnyag;
use App\Models\Megrendeles;
use App\Models\Munkanaplo;
use App\Models\Objektum;
use App\Models\Szerelo;
use App\Models\Szolgaltatas;
use App\Models\Ugyfel;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;

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
        $anyagok = Anyag::all();
        $objektumok = Objektum::all();
        $felhasznaltAnyag = FelhasznaltAnyag::all();

        return view('megrendeles.create', compact('ugyfelek', 'objektumok', 'szolgaltatasok', 'szerelok', 'anyagok'));
    }


    public function show($id)
    {

        $megrendeles = Megrendeles::with(['ugyfel', 'szolgaltatas', 'szerelo', 'felhasznaltAnyagok', 'felhasznaltAnyagok.anyag'])->find($id);


        if (!$megrendeles) {
            return redirect()->route('megrendeles.index')->with('error', 'A megrendelés nem található.');
        }

        $munkak = Munkanaplo::where('Megrendeles_ID', $megrendeles->Megrendeles_ID)->get();
        $felhasznaltAnyagok = [];
        foreach ($munkak as $munka) {
            $felhasznaltAnyagokMunkankent = FelhasznaltAnyag::where('Munka_ID', $munka->Munka_ID)->get();
            foreach ($felhasznaltAnyagokMunkankent as $anyag) {
                array_push($felhasznaltAnyagok, $anyag);
            }
        }

        return view('megrendeles.show', compact('megrendeles', 'munkak', 'felhasznaltAnyagok'));
    }

    public function edit($id)
    {
        $megrendeles = Megrendeles::find($id);
        $ugyfelek = Ugyfel::all();
        $szolgaltatasok = Szolgaltatas::all();
        $szerelok = Szerelo::all();
        $anyagok = Anyag::all();
        $objektumok = Objektum::all();
        $felhasznaltAnyag = FelhasznaltAnyag::all();

        return view('megrendeles.edit', compact('megrendeles','ugyfelek', 'objektumok', 'szolgaltatasok', 'szerelok', 'anyagok'));
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
            'Objektum_ID' => 'required|exists:objektum,Objektum_ID',
            'Utca_Hazszam' => 'required',
            'Ugyfel_ID' => 'required|exists:ugyfel,Ugyfel_ID',
            'Szolgaltatas_ID' => 'required|exists:szolgaltatas,Szolgaltatas_ID',
            'Szerelo_ID' => 'required|exists:szerelo,Szerelo_ID',
            'Leiras' => 'nullable',
            'Munkakezdes_Idopontja' => 'required|date',
            'Munkabefejezes_Idopontja' => 'required|date|after:Munkakezdes_Idopontja',
            'Anyag_ID' => 'required|exists:anyag,Anyag_ID',
            'Mennyiseg' => 'required|min:1'
        ]);

        // Megrendeles létrehozása
        $megrendeles = new Megrendeles();
        $megrendeles->fill($request->only([
            'Megrendeles_Nev', 'Utca_Hazszam', 'Ugyfel_ID', 'Szolgaltatas_ID', 'Objektum_ID'
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


        // A megrendelés és az első munka létrehozása után
        /*$szereloIDs = $request->input('Szerelo_ID');
        $szolgaltatasIDs = $request->input('Szolgaltatas_ID');
        $eredetiLeiras = $request->input('Leiras');
        $eredetiMunkakezdes = $request->input('Munkakezdes_Idopontja');
        $eredetiMunkabefejezes = $request->input('Munkabefejezes_Idopontja');

        foreach ($szereloIDs as $index => $szereloID) {
            if (!empty($szereloID) && !empty($szolgaltatasIDs[$index])) {
                $munka = new Munkanaplo([
                    'Megrendeles_ID' => $megrendeles->id,
                    'Szerelo_ID' => $szereloID,
                    'Szolgaltatas_ID' => $szolgaltatasIDs[$index],
                    'Leiras' => $eredetiLeiras, // Az eredeti leírás használata minden munkánál
                    'Munkakezdes_Idopontja' => $eredetiMunkakezdes, // Az eredeti munkakezdés használata
                    'Munkabefejezes_Idopontja' => $eredetiMunkabefejezes, // Az eredeti munkabefejezés használata
                ]);
                $munka->save();
            }
        }*/




        $anyagok = $request->input('Anyag_ID');
        $mennyisegek = $request->input('Mennyiseg');


        if (is_array($anyagok) && is_array($mennyisegek)) {
            //dd($anyagok, $mennyisegek);
            foreach ($anyagok as $index => $Anyag_ID) {
                if (isset($mennyisegek[$index])) {
                    $felhasznaltAnyag = new FelhasznaltAnyag([
                        'Munka_ID' => $munka->Munka_ID,
                        'Anyag_ID' => $Anyag_ID,
                        'Mennyiseg' => $mennyisegek[$index]
                    ]);
                    $felhasznaltAnyag->save();
                }
            }
        }


        return redirect()->route('megrendeles.index')->with('success', 'Megrendelés sikeresen létrehozva.');
    }


    public function update(Request $request, $id)
{
    $request->validate([
        'Megrendeles_Nev' => 'required',
        'Utca_Hazszam' => 'required',
        'Objektum_ID' => 'required|exists:objektum,Objektum_ID',
        'Ugyfel_ID' => 'required|exists:ugyfel,Ugyfel_ID',
        'Szolgaltatas_ID' => 'required|exists:szolgaltatas,Szolgaltatas_ID',
        'Szerelo_ID' => 'required|exists:szerelo,Szerelo_ID',
        'Leiras' => 'nullable',
        'Munkakezdes_Idopontja' => 'required|date',
        'Munkabefejezes_Idopontja' => 'required|date|after_or_equal:Munkakezdes_Idopontja',
        'Alairt_e' => 'required|boolean',
        'Pdf_EleresiUt' => 'nullable',
        // Feltételezve, hogy van egy "Anyagok" mező a formban, ami tömbként jön
        'Anyagok.*.Anyag_ID' => 'required|exists:anyag,Anyag_ID',
        'Anyagok.*.Mennyiseg' => 'required|numeric|min:0.01',
    ]);

    $megrendeles = Megrendeles::find($id);
    if (!$megrendeles) {
        return redirect()->route('megrendeles.index')->with('error', 'A megrendelés nem található.');
    }

    // Megrendeles adatok frissítése
    $megrendeles->update($request->only([
        'Megrendeles_Nev',
        'Utca_Hazszam',
        'Objektum_ID',
        'Ugyfel_ID',
        'Szolgaltatas_ID',
        'Szerelo_ID',
        'Leiras',
        'Munkakezdes_Idopontja',
        'Munkabefejezes_Idopontja',
        'Alairt_e',
        'Pdf_EleresiUt',
    ]));

    // Kapcsolódó FelhasznaltAnyagok kezelése
    // Először töröljük a meglévő kapcsolódó rekordokat, hogy frissíthessük az újakkal
    foreach ($megrendeles->felhasznaltAnyagok as $anyag) {
        $anyag->delete();
    }

    // Új kapcsolódó anyagok hozzáadása
    foreach ($request->Anyagok as $anyagData) {
        $megrendeles->felhasznaltAnyagok()->create([
            'Anyag_ID' => $anyagData['Anyag_ID'],
            'Mennyiseg' => $anyagData['Mennyiseg'],
            // További mezők, ha szükséges
        ]);
    }

    return redirect()->route('megrendeles.index')->with('success', 'Megrendelés sikeresen frissítve.');
}


    public function getSzerelokBySzolgaltatas($szolgaltatasId)
    {
        $szerelo = Szerelo::whereHas('szolgaltatasok', function ($query) use ($szolgaltatasId) {
            $query->where('szolgaltatas.Szolgaltatas_ID', $szolgaltatasId);
        })->get(['Szerelo_ID', 'Nev']);

        return response()->json($szerelo);
    }
}
