<?php

namespace App\Http\Controllers;

use App\Models\Szamla;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FizetesController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'osszes');
        $ma = Carbon::today();

        // Csak 'szamla' típusú rekordokat mutatunk (nem díjbekérő, nem stornó)
        $query = Szamla::with(['megrendeles.ugyfel', 'megrendeles.varos', 'fizetesek'])
            ->where('szamla_tipus', 'szamla');

        switch ($filter) {
            case 'fizetve':
                $query->where('statusz', 'fizetve');
                break;
            case 'varakozik':
                $query->where('statusz', 'fuggoben')
                    ->whereDate('fizetesi_hatarido', '>=', $ma);
                break;
            case 'lejart':
                $query->where(function ($q) use ($ma) {
                    $q->where('statusz', 'kesedelmes')
                        ->orWhere(function ($q2) use ($ma) {
                            $q2->where('statusz', 'fuggoben')
                                ->whereDate('fizetesi_hatarido', '<', $ma);
                        });
                });
                break;
        }

        $szamlak = $query->orderBy('kiallitas_datum', 'desc')->get();

        // KPI – mindig az összes szamla típusú
        $osszes = Szamla::where('szamla_tipus', 'szamla')->get();

        $kpiFizetve = $osszes->where('statusz', 'fizetve')->count();
        $kpiVarakozik = $osszes->where('statusz', 'fuggoben')
            ->filter(fn ($s) => ! $s->fizetesi_hatarido || $s->fizetesi_hatarido->gte($ma))->count();
        $kpiLejart = $osszes->filter(fn ($s) => $s->statusz === 'kesedelmes' ||
            ($s->statusz === 'fuggoben' && $s->fizetesi_hatarido && $s->fizetesi_hatarido->lt($ma))
        )->count();
        $kpiBevertel = $osszes->where('statusz', 'fizetve')->sum('brutto_osszeg');
        $kpiVaroBevertel = $osszes->whereIn('statusz', ['fuggoben', 'kesedelmes'])->sum('brutto_osszeg');

        return view('fizetes.index', compact(
            'szamlak', 'filter', 'ma',
            'kpiFizetve', 'kpiVarakozik', 'kpiLejart', 'kpiBevertel', 'kpiVaroBevertel'
        ));
    }
}
