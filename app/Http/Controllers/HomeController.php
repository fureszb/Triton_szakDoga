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
        // Nézetbe való átadás
        return view('home.index', ['szolgaltatasokKereslete' => $szolgaltatasokKereslete]);
    }
}
