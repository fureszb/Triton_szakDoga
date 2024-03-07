<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ugyfel;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('ugyfel')->get()->map(function ($user) {
            $user->ugyfelStatus = $user->ugyfel ? 'Van ügyfél' : 'Nincs ügyfél';
            return $user;
        });

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('ugyfel')->findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function create()
    {
        $ugyfelek = Ugyfel::whereNull('User_ID')->get()->pluck('Nev', 'Ugyfel_ID');
        return view('users.create', compact('ugyfelek'));
    }

    public function edit($id)
    {
        $user = User::with('ugyfel')->findOrFail($id); // Kapcsolat betöltése
        $ugyfelek = Ugyfel::whereNull('User_ID')->orWhere('Ugyfel_ID', optional($user->ugyfel)->Ugyfel_ID)->get();

        return view('users.edit', compact('user', 'ugyfelek'));
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Role' => 'required',
            'Password' => 'required',
            'Ugyfel_ID' => 'nullable|exists:ugyfel,Ugyfel_ID'
        ]);

        $user = new User;
        if (!is_null($request->Ugyfel_ID)) {
            $ugyfel = Ugyfel::findOrFail($request->Ugyfel_ID);
            $user->nev = $ugyfel->Nev;
            $user->Email = $ugyfel->Email;
        } else {
            $user->nev = $request->nev;
            $user->Email = $request->Email;
        }
        $user->Role = $request->Role;
        $user->Password = bcrypt($request->Password);
        $user->save();

        if (!is_null($validatedData['Ugyfel_ID'])) {
            $ugyfel = Ugyfel::findOrFail($validatedData['Ugyfel_ID']);
            $ugyfel->User_ID = $user->User_ID;
            $ugyfel->save();
        }

        return redirect()->route('users.index');
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nev' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'Ugyfel_ID' => 'nullable|exists:ugyfel,Ugyfel_ID'
        ]);

        $user = User::findOrFail($id);

        // A User modell frissítése
        $user->nev = $validatedData['nev'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];
        $user->save();

        // Előző ügyfél User_ID mezőjének nullázása, ha volt
        if ($user->ugyfel) {
            $user->ugyfel->update(['User_ID' => null]);
        }

        // Az új ügyfél User_ID mezőjének beállítása
        if (!is_null($validatedData['Ugyfel_ID'])) {
            $ugyfel = Ugyfel::findOrFail($validatedData['Ugyfel_ID']);
            $ugyfel->User_ID = $user->User_ID;
            $ugyfel->save();
        }

        return redirect()->route('users.index');
    }
    public function destroy($id)
    {

        $user = User::findOrFail($id);

        Ugyfel::where('User_ID', $user->User_ID)->delete();

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Felhasználó és kapcsolódó ügyfelek sikeresen törölve.');
    }
}
