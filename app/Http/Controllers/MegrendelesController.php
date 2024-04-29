<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\Models\Anyag;
use App\Models\FelhasznaltAnyag;
use App\Models\Megrendeles;
use App\Models\Munka;
use App\Models\Objektum;
use App\Models\Szerelo;
use App\Models\Szolgaltatas;
use App\Models\Ugyfel;
use App\Models\Varos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Psy\Readline\Hoa\Console;
use PDF;

class MegrendelesController extends Controller
{
    public function index()
    {
        $megrendelesek = Megrendeles::all();

        $sort_by = request()->query('sort_by', 'Megrendeles_ID');
        $sort_dir = request()->query('sort_dir', 'asc');
        $keyword = request()->input('search');

        $query = Megrendeles::orderBy($sort_by, $sort_dir);

        if ($keyword) {
            $query->where('Megrendeles_Nev', 'like', "%$keyword%")
                ->orWhere('Megrendeles_ID', 'like', "%$keyword%");
        }


        $megrendelesek = $query->paginate(9);

        return view('megrendeles.index', compact('megrendelesek'));
    }

    public function create()
    {
        $ugyfelek = Ugyfel::all();
        $szolgaltatasok = Szolgaltatas::all();
        $szerelok = Szerelo::all();
        $anyagok = Anyag::all();
        $felhasznaltAnyag = FelhasznaltAnyag::all();
        $varosok = Varos::all();


        return view('megrendeles.create', compact('ugyfelek', 'varosok', 'szolgaltatasok', 'szerelok', 'anyagok'));
    }


    public function show($id)
    {

        $megrendeles = Megrendeles::with(['ugyfel', 'szolgaltatas', 'szerelo', 'felhasznaltAnyagok', 'felhasznaltAnyagok.anyag'])->find($id);


        if (!$megrendeles) {
            return redirect()->route('megrendeles.index')->with('error', 'A megrendelés nem található.');
        }

        $munkak = Munka::where('Megrendeles_ID', $megrendeles->Megrendeles_ID)->get();
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
        $megrendeles = Megrendeles::with('felhasznaltAnyagok')->findOrFail($id);
        $ugyfelek = Ugyfel::all();
        $szolgaltatasok = Szolgaltatas::all();
        $szerelok = Szerelo::all();
        $anyagok = Anyag::all();
        $varosok = Varos::all();
        $munka = Munka::where('Megrendeles_ID', $megrendeles->Megrendeles_ID)->firstOrFail();

        return view('megrendeles.edit', compact('megrendeles', 'ugyfelek', 'szolgaltatasok', 'szerelok', 'anyagok', 'varosok', 'munka'));
    }

    public function destroy($id)
    {
        $megrendeles = Megrendeles::findOrFail($id);

        $munkaIDs = Munka::where('Megrendeles_ID', $megrendeles->Megrendeles_ID)->pluck('Munka_ID');

        foreach ($munkaIDs as $munkaID) {
            FelhasznaltAnyag::where('Munka_ID', $munkaID)->delete();
        }

        Munka::where('Megrendeles_ID', $megrendeles->Megrendeles_ID)->delete();

        $megrendeles->delete();

        return redirect()->route('megrendeles.index')->with('success', 'Megrendelés sikeresen törölve');
    }


    public function store(Request $request)
    {
        $request->validate([
            'Megrendeles_Nev' => 'required',
            'Varos_ID' => 'required|exists:varos,Varos_ID',
            'Utca_Hazszam' => 'required',
            'Ugyfel_ID' => 'required|exists:ugyfel,Ugyfel_ID',
            'Szolgaltatas_ID' => 'required|exists:szolgaltatas,Szolgaltatas_ID',
            'Szerelo_ID' => 'required|exists:szerelo,Szerelo_ID',
            'Leiras' => 'nullable',
            'Munkakezdes_Idopontja' => 'required|date',
            'Munkabefejezes_Idopontja' => 'required|date|after:Munkakezdes_Idopontja',
            'Anyag_ID' => 'required|exists:anyag,Anyag_ID',
            'Mennyiseg' => 'required|min:1'
        ], [
            'Megrendeles_Nev.required' => 'A megrendelés nevének megadása kötelező.',
            'Varos_ID.required' => 'A város kiválasztása kötelező.',
            'Varos_ID.exists' => 'A kiválasztott város nem létezik az adatbázisban.',
            'Utca_Hazszam.required' => 'Az utca és házszám megadása kötelező.',
            'Ugyfel_ID.required' => 'Az ügyfél azonosítójának megadása kötelező.',
            'Ugyfel_ID.exists' => 'A megadott ügyfél azonosító nem létezik az adatbázisban.',
            'Szolgaltatas_ID.required' => 'A szolgáltatás kiválasztása kötelező.',
            'Szolgaltatas_ID.exists' => 'A kiválasztott szolgáltatás nem létezik az adatbázisban.',
            'Szerelo_ID.required' => 'A szerelő kiválasztása kötelező.',
            'Szerelo_ID.exists' => 'A kiválasztott szerelő nem létezik az adatbázisban.',
            'Munkakezdes_Idopontja.required' => 'A munkakezdés időpontjának megadása kötelező.',
            'Munkakezdes_Idopontja.date' => 'A munkakezdés időpontja dátum formátumú kell legyen.',
            'Munkabefejezes_Idopontja.required' => 'A munka befejezésének időpontja kötelező.',
            'Munkabefejezes_Idopontja.date' => 'A munka befejezésének időpontja dátum formátumú kell legyen.',
            'Munkabefejezes_Idopontja.after' => 'A munka befejezés időpontja nem lehet korábbi, mint a munkakezdés időpontja.',
            'Anyag_ID.required' => 'Az anyag kiválasztása kötelező.',
            'Anyag_ID.exists' => 'A kiválasztott anyag nem létezik az adatbázisban.',
            'Mennyiseg.required' => 'A mennyiség megadása kötelező.',
            'Mennyiseg.min' => 'A mennyiségnek legalább 1-nek kell lennie.'
        ]);

        $megrendeles = new Megrendeles([
            'Megrendeles_Nev' => $request->input('Megrendeles_Nev'),
            'Ugyfel_ID' => $request->Ugyfel_ID,
            'Utca_Hazszam' => $request->input('Utca_Hazszam'),
            'Szolgaltatas_ID' => $request->input('Szolgaltatas_ID'),
            'Varos_ID' => $request->input('Varos_ID'),
        ]);
        $megrendeles->save();


        $ugyfel = Ugyfel::find($request->Ugyfel_ID);
        Session::put('ugyfelData', [
            'Ugyfel_ID' => $ugyfel->Ugyfel_ID,
            'Nev' => $ugyfel->Nev
        ]);




        $munka = new Munka([
            'Megrendeles_ID' => $megrendeles->Megrendeles_ID,
            'Szerelo_ID' => $request->Szerelo_ID,
            'Szolgaltatas_ID' => $request->Szolgaltatas_ID,
            'Leiras' => $request->Leiras,
            'Munkakezdes_Idopontja' => $request->Munkakezdes_Idopontja,
            'Munkabefejezes_Idopontja' => $request->Munkabefejezes_Idopontja
        ]);
        $munka->save();



        $szerelo = Szerelo::find($request->Szerelo_ID);
        Session::put('szereloData', [
            'Szerelo_ID' => $szerelo->Szerelo_ID,
            'Nev' => $szerelo->Nev,
            'Szolgaltatas_ID' => $request->Szolgaltatas_ID
        ]);

        $anyagok = $request->input('Anyag_ID');
        $mennyisegek = $request->input('Mennyiseg');


        if (is_array($anyagok) && is_array($mennyisegek)) {
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

        return redirect('/send-mail')->with('success', 'Az email sikeresen el lett küldve!');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'Megrendeles_Nev' => 'required',
            'Munka_ID' => 'required',
            'Utca_Hazszam' => 'required',
            'Varos_ID' => 'required|exists:varos,Varos_ID',
            'Ugyfel_ID' => 'required|exists:ugyfel,Ugyfel_ID',
            'Szolgaltatas_ID' => 'required|exists:szolgaltatas,Szolgaltatas_ID',
            'Szerelo_ID' => 'required|exists:szerelo,Szerelo_ID',
            'Leiras' => 'nullable',
            'Munkakezdes_Idopontja' => 'required|date',
            'Munkabefejezes_Idopontja' => 'required|date|after_or_equal:Munkakezdes_Idopontja',
            'Alairt_e' => 'required|boolean',
            'Pdf_EleresiUt' => 'nullable',
            'Anyag_ID' => 'required|exists:anyag,Anyag_ID',
            'Mennyiseg' => 'required|min:1',
        ], [
            'Megrendeles_Nev.required' => 'A megrendelés nevének megadása kötelező.',
            'Varos_ID.required' => 'A város kiválasztása kötelező.',
            'Varos_ID.exists' => 'A kiválasztott város nem létezik az adatbázisban.',
            'Utca_Hazszam.required' => 'Az utca és házszám megadása kötelező.',
            'Ugyfel_ID.required' => 'Az ügyfél azonosítójának megadása kötelező.',
            'Ugyfel_ID.exists' => 'A megadott ügyfél azonosító nem létezik az adatbázisban.',
            'Szolgaltatas_ID.required' => 'A szolgáltatás kiválasztása kötelező.',
            'Szolgaltatas_ID.exists' => 'A kiválasztott szolgáltatás nem létezik az adatbázisban.',
            'Szerelo_ID.required' => 'A szerelő kiválasztása kötelező.',
            'Szerelo_ID.exists' => 'A kiválasztott szerelő nem létezik az adatbázisban.',
            'Munkakezdes_Idopontja.required' => 'A munkakezdés időpontjának megadása kötelező.',
            'Munkakezdes_Idopontja.date' => 'A munkakezdés időpontja dátum formátumú kell legyen.',
            'Munkabefejezes_Idopontja.required' => 'A munka befejezésének időpontja kötelező.',
            'Munkabefejezes_Idopontja.date' => 'A munka befejezésének időpontja dátum formátumú kell legyen.',
            'Munkabefejezes_Idopontja.after' => 'A munka befejezés időpontja nem lehet korábbi, mint a munkakezdés időpontja.',
            'Anyag_ID.required' => 'Az anyag kiválasztása kötelező.',
            'Anyag_ID.exists' => 'A kiválasztott anyag nem létezik az adatbázisban.',
            'Mennyiseg.required' => 'A mennyiség megadása kötelező.',
            'Mennyiseg.min' => 'A mennyiségnek legalább 1-nek kell lennie.'
        ]);

        $megrendeles = Megrendeles::findOrFail($id);
        $megrendeles->update($request->only(['Megrendeles_Nev', 'Ugyfel_ID', 'Varos_ID', 'Utca_Hazszam', 'Alairt_e', 'Pdf_EleresiUt']));

       $munka = Munka::findOrFail($request->input('Munka_ID'));
        $munka->update($request->only(['Szerelo_ID', 'Szolgaltatas_ID', 'Leiras', 'Munkakezdes_Idopontja', 'Munkabefejezes_Idopontja']));

        FelhasznaltAnyag::where('Munka_ID', $munka->Munka_ID)->delete();

        $anyagok = $request->input('Anyag_ID');
        $mennyisegek = $request->input('Mennyiseg');
        foreach ($anyagok as $index => $anyagId) {
            if (!empty($anyagId) && isset($mennyisegek[$index])) {
                FelhasznaltAnyag::create([
                    'Munka_ID' => $munka->Munka_ID,
                    'Anyag_ID' => $anyagId,
                    'Mennyiseg' => $mennyisegek[$index],
                ]);
            }
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
    public function downloadPdf(Request $request, $ugyfelId, $ugyfelNev, $szolgaltatasId, $Megrendeles_ID)
    {
         $megrendeles = Megrendeles::with(['ugyfel', 'munkak'])
            ->whereHas('ugyfel', function ($query) use ($ugyfelId, $ugyfelNev) {
                $query->where('Ugyfel_ID', $ugyfelId)
                    ->where('Nev', $ugyfelNev);
            })
            ->whereHas('munkak', function ($query) use ($szolgaltatasId) {
                $query->where('Szolgaltatas_ID', $szolgaltatasId);
            })
            ->first();

        if (!$megrendeles) {
            return response()->json(['error' => 'A dokumentum nem található.'], 404);
        }

        $pdfFileName = $ugyfelId . '_' . $ugyfelNev . '_' . $szolgaltatasId . '_' . $Megrendeles_ID . '.pdf';
        $pdfFilePath = storage_path('app/public/' . $pdfFileName);

        if (File::exists($pdfFilePath)) {
             return response()->download($pdfFilePath, $pdfFileName);
        } else {
            return response()->json(['error' => 'A fájl nem található.'], 404);
        }
    }
}
