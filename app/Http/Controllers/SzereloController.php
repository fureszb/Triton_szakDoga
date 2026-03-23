<?php

namespace App\Http\Controllers;

use App\Models\Szerelo;
use App\Models\Szolgaltatas;
use Illuminate\Http\Request;

class SzereloController extends Controller
{
    public function index()
    {
        $szerelok = Szerelo::paginate(5);

        return view('szerelok.index', compact('szerelok'));
    }

    public function create()
    {
        $szolgaltatasok = Szolgaltatas::all();

        return view('szerelok.create', compact('szolgaltatasok'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nev' => 'required',
            'telefonszam' => 'required',
        ], [
            'nev.required' => 'A név megadása kötelező.',
            'telefonszam.required' => 'A telefonszám megadása kötelező.',
        ]);

        $szerelo = new Szerelo();
        $szerelo->nev = $request->nev;
        $szerelo->telefonszam = $request->telefonszam;
        $szerelo->save();

        $temporaryFileName = $szerelo->nev.'.png';
        $this->renameImage($temporaryFileName, $szerelo->id, $szerelo->nev);

        $szolgaltatasIds = Szolgaltatas::pluck('id')->toArray();
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

        $selectedSzolgaltatasok = $szerelo->szolgaltatasok()->pluck('szolgaltatas.id')->toArray();

        return view('szerelok.edit', compact('szerelo', 'szolgaltatasok', 'selectedSzolgaltatasok'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nev' => 'required',
            'telefonszam' => 'required',
        ], [
            'nev.required' => 'A név megadása kötelező.',
            'telefonszam.required' => 'A telefonszám megadása kötelező.',
        ]);

        $szerelo = Szerelo::findOrFail($id);
        $szerelo->nev = $request->nev;
        $szerelo->telefonszam = $request->telefonszam;
        $szerelo->save();

        $szolgaltatasIds = Szolgaltatas::pluck('id')->toArray();
        $szerelo->szolgaltatasok()->sync($szolgaltatasIds);

        return redirect()->route('szerelok.index')->with('success', 'Szerelő sikeresen frissítve!');
    }

    public function destroy($id)
    {
        $szerelo = Szerelo::findOrFail($id);
        $fileName = $szerelo->id.'_'.$szerelo->nev.'.png';
        $filePath = public_path('alaIrasokSzerelok').'/'.$fileName;

        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $szerelo->szolgaltatasok()->detach();
        $szerelo->delete();

        return redirect()->route('szerelok.index')->with('success', 'Szerelő sikeresen törölve!');
    }

    public function saveImage(Request $request)
    {
        $szereloNev = $request->szereloNev;
        $signatureDataURL = $request->signatureDataURL;
        $signatureDataURL = str_replace('data:image/png;base64,', '', $signatureDataURL);
        $signatureDataURL = base64_decode($signatureDataURL);

        $fileName = $szereloNev.'.png';
        $folderPath = public_path('alaIrasokSzerelok');

        file_put_contents($folderPath.'/'.$fileName, $signatureDataURL);

        return response()->json(['message' => 'Sikeres mentés!']);
    }

    public function renameImage($originalFileName, $newSzereloId, $szereloNev)
    {
        $folderPath = public_path('alaIrasokSzerelok');
        $originalFilePath = $folderPath.'/'.$originalFileName;
        $newFileName = $newSzereloId.'_'.$szereloNev.'.png';
        $newFilePath = $folderPath.'/'.$newFileName;

        if (file_exists($originalFilePath)) {
            rename($originalFilePath, $newFilePath);

            return $newFileName;
        }
    }
}
