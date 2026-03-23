<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $szolgaltatasokKereslete = DB::table('szolgaltatas as S')
            ->join('munka as M', 'S.id', '=', 'M.szolgaltatas_id')
            ->select('S.id', 'S.tipus', DB::raw('COUNT(M.szolgaltatas_id) AS Kereslet'))
            ->groupBy('S.id', 'S.tipus')
            ->orderBy('Kereslet', 'desc')
            ->get();

        $results = DB::table('varos as V')
            ->join('megrendeles as M', 'V.id', '=', 'M.varos_id')
            ->select('V.id', 'V.nev', DB::raw('COUNT(M.id) as MegrendelesekSzama'))
            ->groupBy('V.id', 'V.nev')
            ->get();

        $ugyfelekSzama = DB::table('ugyfel')->count();
        $aktivMegrendelesek = DB::table('megrendeles')->where('statusz', false)->count();
        $alairtvaMegrendelesek = DB::table('megrendeles')->where('statusz', true)->count();
        $szerelokSzama = DB::table('szerelo')->count();
        $anyagokSzama = DB::table('anyag')->count();

        return view('home.index', [
            'szolgaltatasokKereslete' => $szolgaltatasokKereslete,
            'statistics' => $results,
            'ugyfelekSzama' => $ugyfelekSzama,
            'aktivMegrendelesek' => $aktivMegrendelesek,
            'alairtvaMegrendelesek' => $alairtvaMegrendelesek,
            'szerelokSzama' => $szerelokSzama,
            'anyagokSzama' => $anyagokSzama,
        ]);
    }
}
