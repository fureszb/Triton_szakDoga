<?php

namespace App\Http\Controllers;

use App\Models\Anyag;
use Illuminate\Http\Request;

class AnyagController extends Controller
{
    public function create()
    {
        return view('anyagok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'leiras' => 'required',
        ]);

        $anyag = new Anyag();
        $anyag->leiras = $request->leiras;
        $anyag->save();


    }
}
