<?php

namespace App\Http\Controllers;

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
        $ugyfelek = Ugyfel::whereNull('user_id')->get()->pluck('nev', 'id');

        return view('users.create', compact('ugyfelek'));
    }

    public function edit($id)
    {
        $user = User::with('ugyfel')->findOrFail($id);
        $ugyfelek = Ugyfel::whereNull('user_id')
            ->orWhere('id', optional($user->ugyfel)->id)
            ->get();

        return view('users.edit', compact('user', 'ugyfelek'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'role' => 'required',
            'password' => 'required',
            'ugyfel_id' => 'nullable|exists:ugyfel,id',
        ]);

        $user = new User;
        if (! is_null($request->ugyfel_id)) {
            $ugyfel = Ugyfel::findOrFail($request->ugyfel_id);
            $user->nev = $ugyfel->nev;
            $user->email = $ugyfel->email;
        } else {
            $user->nev = $request->nev;
            $user->email = $request->email;
        }
        $user->role = $request->role;
        $user->password = bcrypt($request->password);
        $user->save();

        if (! is_null($validatedData['ugyfel_id'])) {
            $ugyfel = Ugyfel::findOrFail($validatedData['ugyfel_id']);
            $ugyfel->user_id = $user->id;
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
            'ugyfel_id' => 'nullable|exists:ugyfel,id',
        ]);

        $user = User::findOrFail($id);
        $user->nev = $validatedData['nev'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];
        $user->save();

        if ($user->ugyfel) {
            $user->ugyfel->update(['user_id' => null]);
        }

        if (! is_null($validatedData['ugyfel_id'])) {
            $ugyfel = Ugyfel::findOrFail($validatedData['ugyfel_id']);
            $ugyfel->user_id = $user->id;
            $ugyfel->save();
        }

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        Ugyfel::where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Felhasználó és kapcsolódó ügyfelek sikeresen törölve.');
    }
}
