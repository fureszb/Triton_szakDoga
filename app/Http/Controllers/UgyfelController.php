<?php

namespace App\Http\Controllers;

use App\Models\Anyag;
use App\Models\Megrendeles;
use App\Models\Munkanaplo;
use App\Models\Szerelo;
use App\Models\Szolgaltatas;
use App\Models\Ugyfel;
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

        return view('ugyfel.index', compact('ugyfelek'));

        //return view('ugyfel.index', compact('ugyfelek'));
    }







    public function create()
    {
        return view('ugyfel.create');
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
            'Ugyfel_ID' => 'Az Ugyfel_ID kitöltése kötelező'
        ]);



        $ugyfel = new Ugyfel();
        $ugyfel->Ugyfel_ID = $request->Ugyfel_ID;
        $ugyfel->Nev = $request->nev;
        $ugyfel->Email = $request->email;
        $ugyfel->Telefonszam = $request->telefon;
        $ugyfel->Szamlazasi_Nev = $request->szamnev;
        $ugyfel->Szamlazasi_Cim = $request->szamcim;
        $ugyfel->Adoszam = $request->adoszam;

        $ugyfel->save();

        return redirect()->route('ugyfel.index')->with('success', 'Ügyfél sikeresen létrehozva!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ugyfel = Ugyfel::find($id);
        return view('ugyfel.show', compact('ugyfel'));
    }
    

    // Edit metódus egy adott ügyfél szerkesztésére
    public function edit($id)
    {
        $ugyfel = Ugyfel::find($id);

        return view('ugyfel.edit', compact('ugyfel'));
    }


    /**
     * Update the specified resource in storage.
     */
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
            'Ugyfel_ID' => 'Az Ugyfel_ID kitöltése kötelező'
        ]);




        $ugyfel = Ugyfel::find($id);
        $ugyfel->Ugyfel_ID = $request->Ugyfel_ID;
        $ugyfel->Nev = $request->nev;
        $ugyfel->Email = $request->email;
        $ugyfel->Telefonszam = $request->telefon;
        $ugyfel->Szamlazasi_Nev = $request->szamnev;
        $ugyfel->Szamlazasi_Cim = $request->szamcim;
        $ugyfel->Adoszam = $request->adoszam;

        /*
        $szerelo = $request->input('szerelo');
        $szolgaltatas = $request->input('szolgaltatas');
        $munka = $request->input('munka');
        if ($szerelo && $szolgaltatas && $munka) {
            $ugyfel->SzereloID = $szerelo;
            $ugyfel->SzolgID = $szolgaltatas;
            $ugyfel->MunkaID = $munka;
        }
        $ugyfel->FelhasznaltAnyagok = $request->felhasznalt_anyagok;*/
        $ugyfel->save();
        return redirect()->route('ugyfel.index')->with('success', 'Ügyfél sikeresen módosítva!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ugyfel = Ugyfel::find($id);
        $ugyfel->delete();

        return redirect()->route('ugyfel.index')->with('success', 'Ügyfél sikeresen törölve');
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $ugyfelek = Ugyfel::where('Nev', 'like', "%$keyword%")
            ->orWhere('Ugyfel_ID', $keyword)
            ->paginate(9);

        return view('ugyfel.index', compact('ugyfelek'));
    }
}
