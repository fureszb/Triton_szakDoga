<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index()
    {

        $szolgaltatasokKereslete = DB::table('szolgaltatas as S')
            ->join('munka as M', 'S.Szolgaltatas_ID', '=', 'M.Szolgaltatas_ID')
            ->select('S.Szolgaltatas_ID', 'S.Tipus', DB::raw('COUNT(M.Szolgaltatas_ID) AS Kereslet'))
            ->groupBy('S.Szolgaltatas_ID', 'S.Tipus')
            ->orderBy('Kereslet', 'desc')
            ->get();

        $results = DB::table('varos as V')
            ->join('megrendeles as M', 'V.Varos_ID', '=', 'M.Varos_ID')
            ->select('V.Varos_ID', 'V.Nev', DB::raw('COUNT(M.Megrendeles_ID) as MegrendelesekSzama'))
            ->groupBy('V.Varos_ID', 'V.Nev')
            ->get();

        return view('home.index', [
            'szolgaltatasokKereslete' => $szolgaltatasokKereslete,
            'statistics' => $results
        ]);
    }
}
