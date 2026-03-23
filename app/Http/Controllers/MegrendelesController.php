<?php

namespace App\Http\Controllers;

use App\Models\Anyag;
use App\Models\FelhasznaltAnyag;
use App\Models\Megrendeles;
use App\Models\Munka;
use App\Models\Szerelo;
use App\Models\Szolgaltatas;
use App\Models\Ugyfel;
use App\Models\Varos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class MegrendelesController extends Controller
{
    public function index()
    {
        $sort_by = request()->query('sort_by', 'id');
        $sort_dir = request()->query('sort_dir', 'asc');
        $keyword = request()->input('search');

        $query = Megrendeles::with(['ugyfel', 'varos', 'tobbSzamla', 'tobbDijbekero'])->orderBy($sort_by, $sort_dir);

        if ($keyword) {
            $query->where('megrendeles_nev', 'like', "%$keyword%")
                ->orWhere('id', 'like', "%$keyword%");
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
        $varosok = Varos::orderBy('Irny_szam')->get();

        return view('megrendeles.create', compact('ugyfelek', 'varosok', 'szolgaltatasok', 'szerelok', 'anyagok'));
    }

    public function show($id)
    {
        $megrendeles = Megrendeles::with([
            'ugyfel', 'szolgaltatas', 'szerelo',
            'felhasznaltAnyagok', 'felhasznaltAnyagok.anyag',
            'szamla.fizetesek',
        ])->find($id);

        if (! $megrendeles) {
            return redirect()->route('megrendeles.index')->with('error', 'A megrendelés nem található.');
        }

        $munkak = Munka::where('megrendeles_id', $megrendeles->id)->get();
        $felhasznaltAnyagok = [];
        foreach ($munkak as $munka) {
            $fa = FelhasznaltAnyag::where('munka_id', $munka->id)->get();
            foreach ($fa as $anyag) {
                $felhasznaltAnyagok[] = $anyag;
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
        $varosok = Varos::orderBy('Irny_szam')->get();
        $munka = Munka::where('megrendeles_id', $megrendeles->id)->firstOrFail();

        return view('megrendeles.edit', compact(
            'megrendeles', 'ugyfelek', 'szolgaltatasok', 'szerelok', 'anyagok', 'varosok', 'munka'
        ));
    }

    public function destroy($id)
    {
        $megrendeles = Megrendeles::findOrFail($id);

        $munkaIDs = Munka::where('megrendeles_id', $megrendeles->id)->pluck('id');

        foreach ($munkaIDs as $munkaID) {
            FelhasznaltAnyag::where('munka_id', $munkaID)->delete();
        }

        Munka::where('megrendeles_id', $megrendeles->id)->delete();
        $megrendeles->delete();

        return redirect()->route('megrendeles.index')->with('success', 'Megrendelés sikeresen törölve');
    }

    public function store(Request $request)
    {
        $request->validate([
            'megrendeles_nev' => 'required',
            'varos_id' => 'required|exists:varos,id',
            'utca_hazszam' => 'required',
            'ugyfel_id' => 'required|exists:ugyfel,id',
            'szolgaltatas_id' => 'required|exists:szolgaltatas,id',
            'szerelo_id' => 'required|exists:szerelo,id',
            'leiras' => 'nullable',
            'munkakezdes_idopontja' => 'required|date',
            'munkabefejezes_idopontja' => 'required|date|after:munkakezdes_idopontja',
            'anyag_id' => 'required|exists:anyag,id',
            'mennyiseg' => 'required|min:1',
        ], [
            'megrendeles_nev.required' => 'A megrendelés nevének megadása kötelező.',
            'varos_id.required' => 'A város kiválasztása kötelező.',
            'varos_id.exists' => 'A kiválasztott város nem létezik az adatbázisban.',
            'utca_hazszam.required' => 'Az utca és házszám megadása kötelező.',
            'ugyfel_id.required' => 'Az ügyfél azonosítójának megadása kötelező.',
            'ugyfel_id.exists' => 'A megadott ügyfél azonosító nem létezik az adatbázisban.',
            'szolgaltatas_id.required' => 'A szolgáltatás kiválasztása kötelező.',
            'szolgaltatas_id.exists' => 'A kiválasztott szolgáltatás nem létezik az adatbázisban.',
            'szerelo_id.required' => 'A szerelő kiválasztása kötelező.',
            'szerelo_id.exists' => 'A kiválasztott szerelő nem létezik az adatbázisban.',
            'munkakezdes_idopontja.required' => 'A munkakezdés időpontjának megadása kötelező.',
            'munkakezdes_idopontja.date' => 'A munkakezdés időpontja dátum formátumú kell legyen.',
            'munkabefejezes_idopontja.required' => 'A munka befejezésének időpontja kötelező.',
            'munkabefejezes_idopontja.date' => 'A munka befejezésének időpontja dátum formátumú kell legyen.',
            'munkabefejezes_idopontja.after' => 'A munka befejezés időpontja nem lehet korábbi, mint a munkakezdés.',
            'anyag_id.required' => 'Az anyag kiválasztása kötelező.',
            'anyag_id.exists' => 'A kiválasztott anyag nem létezik az adatbázisban.',
            'mennyiseg.required' => 'A mennyiség megadása kötelező.',
            'mennyiseg.min' => 'A mennyiségnek legalább 1-nek kell lennie.',
        ]);

        $megrendeles = new Megrendeles([
            'megrendeles_nev' => $request->input('megrendeles_nev'),
            'ugyfel_id' => $request->ugyfel_id,
            'utca_hazszam' => $request->input('utca_hazszam'),
            'varos_id' => $request->input('varos_id'),
            'statusz' => $request->input('statusz', 1),
        ]);
        $megrendeles->save();

        $ugyfel = Ugyfel::find($request->ugyfel_id);
        Session::put('ugyfelData', [
            'ugyfel_id' => $ugyfel->id,
            'nev' => $ugyfel->nev,
        ]);

        $munka = new Munka([
            'megrendeles_id' => $megrendeles->id,
            'szerelo_id' => $request->szerelo_id,
            'szolgaltatas_id' => $request->szolgaltatas_id,
            'leiras' => $request->leiras,
            'munkakezdes_idopontja' => $request->munkakezdes_idopontja,
            'munkabefejezes_idopontja' => $request->munkabefejezes_idopontja,
        ]);
        $munka->save();

        $szerelo = Szerelo::find($request->szerelo_id);
        Session::put('szereloData', [
            'szerelo_id' => $szerelo->id,
            'nev' => $szerelo->nev,
            'szolgaltatas_id' => $request->szolgaltatas_id,
        ]);

        $anyagok = $request->input('anyag_id');
        $mennyisegek = $request->input('mennyiseg');

        if (is_array($anyagok) && is_array($mennyisegek)) {
            foreach ($anyagok as $index => $anyagId) {
                if (isset($mennyisegek[$index])) {
                    FelhasznaltAnyag::create([
                        'munka_id' => $munka->id,
                        'anyag_id' => $anyagId,
                        'mennyiseg' => $mennyisegek[$index],
                    ]);
                }
            }
        }

        return redirect('/send-mail')->with('success', 'Az email sikeresen el lett küldve!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'megrendeles_nev' => 'required',
            'munka_id' => 'required',
            'utca_hazszam' => 'required',
            'varos_id' => 'required|exists:varos,id',
            'ugyfel_id' => 'required|exists:ugyfel,id',
            'szolgaltatas_id' => 'required|exists:szolgaltatas,id',
            'szerelo_id' => 'required|exists:szerelo,id',
            'leiras' => 'nullable',
            'munkakezdes_idopontja' => 'required|date',
            'munkabefejezes_idopontja' => 'required|date|after_or_equal:munkakezdes_idopontja',
            'statusz' => 'required|boolean',
            'pdf_eleresi_ut' => 'nullable',
            'anyag_id' => 'required|exists:anyag,id',
            'mennyiseg' => 'required|min:1',
        ], [
            'megrendeles_nev.required' => 'A megrendelés nevének megadása kötelező.',
            'varos_id.required' => 'A város kiválasztása kötelező.',
            'utca_hazszam.required' => 'Az utca és házszám megadása kötelező.',
            'ugyfel_id.required' => 'Az ügyfél azonosítójának megadása kötelező.',
            'szolgaltatas_id.required' => 'A szolgáltatás kiválasztása kötelező.',
            'szerelo_id.required' => 'A szerelő kiválasztása kötelező.',
            'munkakezdes_idopontja.required' => 'A munkakezdés időpontjának megadása kötelező.',
            'munkabefejezes_idopontja.required' => 'A munka befejezésének időpontja kötelező.',
            'anyag_id.required' => 'Az anyag kiválasztása kötelező.',
            'mennyiseg.required' => 'A mennyiség megadása kötelező.',
        ]);

        $megrendeles = Megrendeles::findOrFail($id);
        $megrendeles->update($request->only([
            'megrendeles_nev', 'ugyfel_id', 'varos_id', 'utca_hazszam', 'statusz', 'pdf_eleresi_ut',
        ]));

        $munka = Munka::findOrFail($request->input('munka_id'));
        $munka->update($request->only([
            'szerelo_id', 'szolgaltatas_id', 'leiras', 'munkakezdes_idopontja', 'munkabefejezes_idopontja',
        ]));

        FelhasznaltAnyag::where('munka_id', $munka->id)->delete();

        $anyagok = $request->input('anyag_id');
        $mennyisegek = $request->input('mennyiseg');
        foreach ($anyagok as $index => $anyagId) {
            if (! empty($anyagId) && isset($mennyisegek[$index])) {
                FelhasznaltAnyag::create([
                    'munka_id' => $munka->id,
                    'anyag_id' => $anyagId,
                    'mennyiseg' => $mennyisegek[$index],
                ]);
            }
        }

        return redirect()->route('megrendeles.index')->with('success', 'Megrendelés sikeresen frissítve.');
    }

    public function getSzerelokBySzolgaltatas($szolgaltatasId)
    {
        $szerelok = Szerelo::whereHas('szolgaltatasok', function ($query) use ($szolgaltatasId) {
            $query->where('szolgaltatas.id', $szolgaltatasId);
        })->get(['id', 'nev']);

        return response()->json($szerelok);
    }

    public function downloadPdf(Request $request, $ugyfelId, $ugyfelNev, $szolgaltatasId, $megrendelesId)
    {
        $megrendeles = Megrendeles::with(['ugyfel', 'munkak'])
            ->whereHas('ugyfel', function ($query) use ($ugyfelId, $ugyfelNev) {
                $query->where('id', $ugyfelId)->where('nev', $ugyfelNev);
            })
            ->whereHas('munkak', function ($query) use ($szolgaltatasId) {
                $query->where('szolgaltatas_id', $szolgaltatasId);
            })
            ->first();

        if (! $megrendeles) {
            return response()->json(['error' => 'A dokumentum nem található.'], 404);
        }

        $pdfFileName = $ugyfelId.'_'.$ugyfelNev.'_'.$szolgaltatasId.'_'.$megrendelesId.'.pdf';
        $pdfFilePath = storage_path('app/public/'.$pdfFileName);

        if (File::exists($pdfFilePath)) {
            return response()->download($pdfFilePath, $pdfFileName);
        }

        return response()->json(['error' => 'A fájl nem található.'], 404);
    }

    public function viewPdf(Request $request, $ugyfelId, $ugyfelNev, $szolgaltatasId, $megrendelesId)
    {
        $megrendeles = Megrendeles::with(['ugyfel', 'munkak'])
            ->whereHas('ugyfel', function ($query) use ($ugyfelId, $ugyfelNev) {
                $query->where('id', $ugyfelId)->where('nev', $ugyfelNev);
            })
            ->whereHas('munkak', function ($query) use ($szolgaltatasId) {
                $query->where('szolgaltatas_id', $szolgaltatasId);
            })
            ->first();

        if (! $megrendeles) {
            return response()->json(['error' => 'A dokumentum nem található.'], 404);
        }

        $pdfFileName = $ugyfelId.'_'.$ugyfelNev.'_'.$szolgaltatasId.'_'.$megrendelesId.'.pdf';
        $pdfFilePath = storage_path('app/public/'.$pdfFileName);

        if (File::exists($pdfFilePath)) {
            return response()->file($pdfFilePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$pdfFileName.'"',
            ]);
        }

        return response()->json(['error' => 'A fájl nem található.'], 404);
    }
}
