<?php

namespace App\Http\Controllers;

use App\Models\FelhasznaltAnyag;
use App\Models\Megrendeles;
use App\Models\Munka;
use App\Models\Szamla;
use App\Models\Ugyfel;
use App\Models\User;
use App\Models\Varos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UgyfelController extends Controller
{
    public function index()
    {
        $sort_by = request()->query('sort_by', 'id');
        $sort_dir = request()->query('sort_dir', 'asc');
        $keyword = request()->input('search');

        $query = Ugyfel::orderBy($sort_by, $sort_dir);

        if ($keyword) {
            $query->where('nev', 'like', "%$keyword%")
                ->orWhere('id', 'like', "%$keyword%");
        }

        $ugyfel = $query->paginate(9);

        return view('ugyfel.index', compact('ugyfel'));
    }

    public function create()
    {
        $varosok = Varos::orderBy('Irny_szam')->get();

        return view('ugyfel.create', compact('varosok'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nev' => ['required', 'regex:/^[\p{L} -]+$/u', 'min:3'],
            'email' => ['required', 'regex:/^\S+@\S+\.\S+$/', 'min:3', 'email', 'unique:users'],
            'telefon' => ['required', 'regex:/^(\+36|06)?[0-9]{9}$/'],
            'szamnev' => ['required', 'regex:/^[A-Za-záéíóöőúüűÁÉÍÓÖŐÚÜŰ\s]{3,}$/'],
            'szamcim' => ['required', 'min:3'],
            'adoszam' => ['nullable', 'between:8,11'],
            'varos_id' => ['required', 'exists:varos,id'],
        ], [
            'nev.required' => 'A név megadása kötelező.',
            'nev.regex' => 'A név csak betűket és szóközöket tartalmazhat.',
            'nev.min' => 'A név legalább 3 karakter hosszú legyen.',
            'email.required' => 'Az email megadása kötelező.',
            'email.email' => 'Az email legyen email formátumú.',
            'email.unique' => 'Az email cím már létezik a rendszerünkben.',
            'telefon.required' => 'A telefonszám megadása kötelező.',
            'telefon.regex' => 'A telefonszám formátuma érvénytelen.',
            'szamnev.required' => 'A számlázási név megadása kötelező.',
            'szamcim.required' => 'A számlázási cím megadása kötelező.',
            'szamcim.min' => 'A számlázási cím legalább 3 karakter.',
            'varos_id.required' => 'A város kiválasztása kötelező.',
            'varos_id.exists' => 'A kiválasztott város nem létezik.',
        ]);

        $user = User::create([
            'nev' => $request->nev,
            'email' => $request->email,
            'password' => Hash::make('1122'),
        ]);

        $ugyfel = new Ugyfel();
        $ugyfel->user_id = $user->id;
        $ugyfel->nev = $request->nev;
        $ugyfel->email = $request->email;
        $ugyfel->telefonszam = $request->telefon;
        $ugyfel->szamlazasi_nev = $request->szamnev;
        $ugyfel->varos_id = $request->varos_id;
        $ugyfel->szamlazasi_cim = $request->szamcim;
        $ugyfel->adoszam = $request->adoszam;
        $ugyfel->save();

        return redirect()->route('ugyfel.index')->with('success', 'Ügyfél sikeresen létrehozva!');
    }

    public function show($id)
    {
        $ugyfel = Ugyfel::find($id);
        $varos = Varos::where('id', $ugyfel->varos_id)->first();

        return view('ugyfel.show', compact('ugyfel', 'varos'));
    }

    public function edit($id)
    {
        $ugyfel = Ugyfel::find($id);
        $varosok = Varos::orderBy('Irny_szam')->get();

        return view('ugyfel.edit', compact('ugyfel', 'varosok'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'ugyfel_id' => ['required'],
            'nev' => ['required', 'regex:/^[\p{L} -]+$/u', 'min:3'],
            'email' => ['required', 'regex:/^\S+@\S+\.\S+$/', 'min:3', 'email'],
            'telefon' => ['required', 'regex:/^(\+36|06)?[0-9]{9}$/'],
            'szamnev' => ['required', 'regex:/^[A-Za-záéíóöőúüűÁÉÍÓÖŐÚÜŰ\s]{3,}$/'],
            'szamcim' => ['required', 'min:3'],
            'adoszam' => ['nullable', 'between:8,11'],
            'varos_id' => ['required', 'exists:varos,id'],
        ]);

        $ugyfel = Ugyfel::find($id);
        $ugyfel->id = $request->ugyfel_id;
        $ugyfel->nev = $request->nev;
        $ugyfel->email = $request->email;
        $ugyfel->telefonszam = $request->telefon;
        $ugyfel->szamlazasi_nev = $request->szamnev;
        $ugyfel->szamlazasi_cim = $request->szamcim;
        $ugyfel->adoszam = $request->adoszam;
        $ugyfel->varos_id = $request->varos_id;
        $ugyfel->save();

        $user = User::findOrFail($ugyfel->user_id);
        $user->update([
            'nev' => $request->nev,
            'email' => $request->email,
        ]);

        return redirect()->route('ugyfel.index')->with('success', 'Ügyfél sikeresen módosítva!');
    }

    public function destroy($id)
    {
        $ugyfel = Ugyfel::findOrFail($id);

        $megrendelesIDs = $ugyfel->megrendelesek()->pluck('id');

        foreach ($megrendelesIDs as $megrendelesID) {
            $munkaIDs = Munka::where('megrendeles_id', $megrendelesID)->pluck('id');

            foreach ($munkaIDs as $munkaID) {
                FelhasznaltAnyag::where('munka_id', $munkaID)->delete();
            }

            Munka::where('megrendeles_id', $megrendelesID)->delete();
            Megrendeles::where('id', $megrendelesID)->delete();
        }

        $ugyfel->delete();

        return redirect()->route('ugyfel.index')->with('success', 'Ügyfél sikeresen törölve');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $ugyfelek = Ugyfel::where('nev', 'like', "%$keyword%")
            ->orWhere('id', $keyword)
            ->paginate(9);

        return view('ugyfel.index', compact('ugyfelek'));
    }

    public function megrendelesek()
    {
        $ugyfelId = Auth::user()->ugyfel->id;
        $megrendelesek = Megrendeles::where('ugyfel_id', $ugyfelId)
            ->with([
                'munkak.szolgaltatas',
                'munkak.szerelo',
                'munkak.felhasznalt_anyagok.anyag',
                'varos',
                'ugyfel',
                // osszesSzamla.fizetesek KÖTELEZŐ: a getFizetveAttribute() és
                // getFuggobenFizetesAttribute() accessorok ezt a hasMany relation-t
                // használják – nélküle N+1 query keletkezik kártyánként
                'osszesSzamla.fizetesek',
            ])
            ->get();

        return view('ugyfel.megrendelesek', compact('megrendelesek'));
    }

    public function szamlak()
    {
        $ugyfelId = Auth::user()->ugyfel->id;

        $szamlak = Szamla::where('ugyfel_id', $ugyfelId)
            ->whereIn('szamla_tipus', ['szamla', 'dijbekero'])
            ->whereIn('statusz', ['fizetve', 'fuggoben', 'kesedelmes'])
            ->with(['megrendeles.varos', 'fizetesek'])
            ->orderBy('kiallitas_datum', 'desc')
            ->get();

        return view('ugyfel.szamlak', compact('szamlak'));
    }

    public function adataim()
    {
        $ugyfel = Auth::user()->ugyfel()->with('varos')->first();
        $varosok = Varos::orderBy('Irny_szam')->get();

        return view('ugyfel.adataim', compact('ugyfel', 'varosok'));
    }

    public function updateAdataim(Request $request)
    {
        $section = $request->input('section');
        $ugyfel = Auth::user()->ugyfel;

        if ($section === 'szemelyes') {
            $request->validate([
                'nev' => ['required', 'regex:/^[\p{L} \-]+$/u', 'min:3'],
                'szamlazasi_nev' => ['required', 'min:3'],
                'szamlazasi_cim' => ['required', 'min:3'],
                'adoszam' => ['nullable', 'min:8', 'max:11'],
                'varos_id' => ['required', 'exists:varos,id'],
            ], [
                'nev.required' => 'A név megadása kötelező.',
                'szamlazasi_nev.required' => 'A számlázási név megadása kötelező.',
                'szamlazasi_cim.required' => 'A számlázási cím megadása kötelező.',
                'varos_id.required' => 'A város kiválasztása kötelező.',
            ]);

            $ugyfel->nev = $request->nev;
            $ugyfel->szamlazasi_nev = $request->szamlazasi_nev;
            $ugyfel->szamlazasi_cim = $request->szamlazasi_cim;
            $ugyfel->adoszam = $request->adoszam;
            $ugyfel->varos_id = $request->varos_id;
            $ugyfel->save();

            $ugyfel->user->update(['nev' => $request->nev]);

        } elseif ($section === 'kapcsolat') {
            $request->validate([
                'telefonszam' => ['required', 'regex:/^(\+36|06)?[0-9]{9}$/'],
            ], [
                'telefonszam.required' => 'A telefonszám megadása kötelező.',
                'telefonszam.regex' => 'A telefonszám formátuma érvénytelen.',
            ]);

            $ugyfel->telefonszam = $request->telefonszam;
            $ugyfel->save();
        }

        return redirect()->route('ugyfel.adataim')->with('success', 'Adataid sikeresen frissítve!');
    }
}
