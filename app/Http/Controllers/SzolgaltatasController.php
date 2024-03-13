<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Szolgaltatas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SzolgaltatasController extends Controller
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
        return view('szolgaltatasok.index', ['szolgaltatasokKereslete' => $szolgaltatasokKereslete]);
    }
}
