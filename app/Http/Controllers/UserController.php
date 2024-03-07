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
            'Name' => 'required',
            'Email' => 'required|email',
            'Password' => 'required',
            'Role' => 'required',
            'Ugyfel_ID' => 'nullable|exists:ugyfel,Ugyfel_ID'
        ]);

        $user = new User($validatedData);
        $user->save();

        return redirect()->route('users.index');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'Name' => 'required',
            'Email' => 'required|email',
            'Password' => 'required',
            'Role' => 'required',
            'Ugyfel_ID' => 'nullable|exists:ugyfel,Ugyfel_ID'
        ]);

        $user = User::findOrFail($id);
        $user->update($validatedData);

        return redirect()->route('users.index');
    }
}
