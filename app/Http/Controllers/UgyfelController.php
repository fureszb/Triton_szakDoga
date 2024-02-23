<?php

namespace App\Http\Controllers;

use App\Models\Anyag;
use App\Models\FelhasznaltAnyag;
use App\Models\Megrendeles;
use App\Models\Munka;
use App\Models\Munkanaplo;
use App\Models\Szerelo;
use App\Models\Szolgaltatas;
use App\Models\Ugyfel;
use App\Models\Varos;
use Illuminate\Http\Request;

class UgyfelController extends Controller
{

    public function index()
    {
        $sort_by = request()->query('sort_by', 'Ugyfel_ID');
        $sort_dir = request()->query('sort_dir', 'asc');
        $keyword = request()->input('search');

        $query = Ugyfel::orderBy($sort_by, $sort_dir);

        if ($keyword) {
            $query->where('Nev', 'like', "%$keyword%")
                ->orWhere('Ugyfel_ID', 'like', "%$keyword%");
        }

        $ugyfelek = Ugyfel::with('megrendelesek')->get();
        $ugyfel = $query->paginate(9);

        return view('ugyfel.index', compact('ugyfel'));
    }







    public function create()
    {
        $varosok = Varos::all();
        return view('ugyfel.create', compact('varosok'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'Ugyfel_ID' => ['required'],
            'nev' => ['required', 'regex:/^[\p{L} -]+$/u', 'min:3'],
            'email' => ['required', 'regex:/^\S+@\S+\.\S+$/', 'min:3'],
            'telefon' => ['required', 'regex:/^(\+36|06)?[0-9]{9}$/'],
            'szamnev' => ['required', 'regex:/^[A-Za-záéíóöőúüűÁÉÍÓÖŐÚÜŰ\s]{3,}$/'],
            'szamcim' => ['required', 'min:3'],
            'adoszam' => ['nullable', 'between:8,11'],
            'Varos_ID' => ['required', 'exists:varos,Varos_ID']
        ], [
            'nev.required' => 'A név megadása kötelező.',
            'nev.regex' => 'A név csak betűket és szóközöket tartalmazhat, magyar betűket is elfogadva.',
            'nev.min' => 'A név legalább 3 karakter hosszú legyen.',
            'email.required' => 'Az email megadása kötelező.',
            'email.regex' => 'Érvénytelen email cím.',
            'email.min' => 'A email legalább 3 karakter hosszú legyen.',
            'telefon.required' => 'A telefonszám megadása kötelező.',
            'telefon.regex' => 'A telefonszám formátuma érvénytelen.',
            'szamnev.required' => 'A számlázási név megadása kötelező.',
            'szamnev.regex' => 'A számlázási név csak betűket és szóközöket tartalmazhat, magyar betűket is elfogadva.',
            'szamnev.min' => 'A számlázási név legalább 3 karakter hosszú legyen.',
            'szamcim.required' => 'A számlázási cím megadása kötelező.',
            'szamcim.regex' => 'A számlázási cím érvénytelen karaktereket tartalmaz.',
            'szamcim.min' => 'A számlázási cím legalább 3 karakter hosszúnak kell lennie.',
            'Ugyfel_ID.required' => 'Az Ugyfel_ID kitöltése kötelező',
            'Varos_ID.required' => 'A város kiválasztása kötelező.',
            'Varos_ID.exists' => 'A kiválasztott város nem létezik.'
        ]);



        $ugyfel = new Ugyfel();
        $ugyfel->Ugyfel_ID = $request->Ugyfel_ID;
        $ugyfel->Nev = $request->nev;
        $ugyfel->Email = $request->email;
        $ugyfel->Telefonszam = $request->telefon;
        $ugyfel->Szamlazasi_Nev = $request->szamnev;
        $ugyfel->Varos_ID = $request->Varos_ID;
        $ugyfel->Szamlazasi_Cim = $request->szamcim;
        $ugyfel->Adoszam = $request->adoszam;

        $ugyfel->save();

        return redirect()->route('ugyfel.index')->with('success', 'Ügyfél sikeresen létrehozva!');
    }

    public function show($id)
    {
        $ugyfel = Ugyfel::find($id);
        $varos = Varos::where('Varos_ID', $ugyfel->Varos_ID)->first();
        return view('ugyfel.show', compact('ugyfel', 'varos'));
    }



    public function edit($id)
    {
        $ugyfel = Ugyfel::find($id);
        $varosok = Varos::all();
        return view('ugyfel.edit', compact('ugyfel', 'varosok'));
    }



    public function update(Request $request, string $id)
    {

        $request->validate([
            'Ugyfel_ID' => ['required'],
            'nev' => ['required', 'regex:/^[\p{L} -]+$/u', 'min:3'],
            'email' => ['required', 'regex:/^\S+@\S+\.\S+$/', 'min:3'],
            'telefon' => ['required', 'regex:/^(\+36|06)?[0-9]{9}$/'],
            'szamnev' => ['required', 'regex:/^[A-Za-záéíóöőúüűÁÉÍÓÖŐÚÜŰ\s]{3,}$/'],
            'szamcim' => ['required', 'min:3'],
            'adoszam' => ['nullable', 'between:8,11'],
            'Varos_ID' => ['required', 'exists:varos,Varos_ID']
        ], [
            'nev.required' => 'A név megadása kötelező.',
            'nev.regex' => 'A név csak betűket és szóközöket tartalmazhat, magyar betűket is elfogadva.',
            'nev.min' => 'A név legalább 3 karakter hosszú legyen.',
            'email.required' => 'Az email megadása kötelező.',
            'email.regex' => 'Érvénytelen email cím.',
            'email.min' => 'A email legalább 3 karakter hosszú legyen.',
            'telefon.required' => 'A telefonszám megadása kötelező.',
            'telefon.regex' => 'A telefonszám formátuma érvénytelen.',
            'szamnev.required' => 'A számlázási név megadása kötelező.',
            'szamnev.regex' => 'A számlázási név csak betűket és szóközöket tartalmazhat, magyar betűket is elfogadva.',
            'szamnev.min' => 'A számlázási név legalább 3 karakter hosszú legyen.',
            'szamcim.required' => 'A számlázási cím megadása kötelező.',
            'szamcim.regex' => 'A számlázási cím érvénytelen karaktereket tartalmaz.',
            'szamcim.min' => 'A számlázási cím legalább 3 karakter hosszúnak kell lennie.',
            'adoszam.between' => 'Az adószám hossza 8 és 11 karakter között lehet.',
            'Ugyfel_ID' => 'Az Ugyfel_ID kitöltése kötelező',
            'Varos_ID.required' => 'A város kiválasztása kötelező.',
            'Varos_ID.exists' => 'A kiválasztott város nem létezik.'
        ]);




        $ugyfel = Ugyfel::find($id);
        $ugyfel->Ugyfel_ID = $request->Ugyfel_ID;
        $ugyfel->Nev = $request->nev;
        $ugyfel->Email = $request->email;
        $ugyfel->Telefonszam = $request->telefon;
        $ugyfel->Szamlazasi_Nev = $request->szamnev;
        $ugyfel->Szamlazasi_Cim = $request->szamcim;
        $ugyfel->Adoszam = $request->adoszam;
        $ugyfel->Varos_ID = $request->Varos_ID;
        $ugyfel->save();
        return redirect()->route('ugyfel.index')->with('success', 'Ügyfél sikeresen módosítva!');
    }

    public function destroy($id)
    {
        $ugyfel = Ugyfel::findOrFail($id);

        $megrendelesIDs = $ugyfel->megrendelesek()->pluck('Megrendeles_ID');

        foreach ($megrendelesIDs as $megrendelesID) {
            $munkaIDs = Munka::where('Megrendeles_ID', $megrendelesID)->pluck('Munka_ID');


            foreach ($munkaIDs as $munkaID) {
                FelhasznaltAnyag::where('Munka_ID', $munkaID)->delete();
            }

            Munka::where('Megrendeles_ID', $megrendelesID)->delete();

            Megrendeles::where('Megrendeles_ID', $megrendelesID)->delete();
        }

        $ugyfel->delete();

        return redirect()->route('ugyfel.index')->with('success', 'Ügyfél sikeresen törölve');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $ugyfelek = Ugyfel::where('Nev', 'like', "%$keyword%")
            ->orWhere('Ugyfel_ID', $keyword)
            ->paginate(9);

        return view('ugyfel.index', compact('ugyfel'));
    }
}
