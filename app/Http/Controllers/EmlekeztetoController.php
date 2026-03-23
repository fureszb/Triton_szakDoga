<?php

namespace App\Http\Controllers;

use App\Mail\ManualisEmlekeztetoMail;
use App\Models\FizetesAuditLog;
use App\Models\FizetesEmlekeztetok;
use App\Models\Szamla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmlekeztetoController extends Controller
{
    /**
     * Emlékeztető küldő GUI főoldala.
     * Mutatja a függőben/késedelmes számlákat és díjbekérőket, + küldési előzményeket.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'esedékes');

        // Alaplekérdezés: fizetve és stornózottak kizárva
        $query = Szamla::whereIn('szamla_tipus', ['szamla', 'dijbekero'])
            ->whereIn('statusz', ['fuggoben', 'kesedelmes'])
            ->with(['ugyfel', 'megrendeles', 'emlekeztetok' => fn ($q) => $q->orderBy('created_at', 'desc')])
            ->orderBy('fizetesi_hatarido', 'asc');

        $szamlak = match ($filter) {
            'lejart' => (clone $query)->where('fizetesi_hatarido', '<', now()->startOfDay())->get(),
            'kozel' => (clone $query)->whereBetween('fizetesi_hatarido', [now()->startOfDay(), now()->addDays(3)->endOfDay()])->get(),
            default => $query->get(),   // 'esedékes' = összes függőben/késedelmes
        };

        // KPI statisztikák
        $stats = [
            'osszes' => Szamla::whereIn('szamla_tipus', ['szamla', 'dijbekero'])->whereIn('statusz', ['fuggoben', 'kesedelmes'])->count(),
            'lejart' => Szamla::whereIn('szamla_tipus', ['szamla', 'dijbekero'])->whereIn('statusz', ['fuggoben', 'kesedelmes'])->where('fizetesi_hatarido', '<', now()->startOfDay())->count(),
            'harom_napon' => Szamla::whereIn('szamla_tipus', ['szamla', 'dijbekero'])->whereIn('statusz', ['fuggoben', 'kesedelmes'])->whereBetween('fizetesi_hatarido', [now()->startOfDay(), now()->addDays(3)->endOfDay()])->count(),
            'kuldott_ma' => FizetesEmlekeztetok::whereDate('created_at', today())->count(),
        ];

        // Utolsó 20 kiküldött emlékeztető (előzmény panel)
        $elozmeny = FizetesEmlekeztetok::with(['szamla', 'ugyfel'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('emlekeztetok.index', compact('szamlak', 'stats', 'elozmeny', 'filter'));
    }

    /**
     * Manuális emlékeztető küldése egy adott számlához.
     */
    public function kuldes(Request $request, Szamla $szamla)
    {
        $request->validate([
            'egyedi_uzenet' => ['nullable', 'string', 'max:1000'],
        ]);

        // Ügyfél email cím meghatározása
        $szamla->load(['ugyfel', 'megrendeles.ugyfel']);
        $email = $szamla->ugyfel?->email
               ?? $szamla->megrendeles?->ugyfel?->email
               ?? null;

        if (! $email) {
            return back()->with('error', 'Az ügyfélhez nem tartozik email cím. Emlékeztető nem küldhető.');
        }

        $egyediUzenet = trim($request->input('egyedi_uzenet', ''));

        $statusz = 'sikeres';
        $hibaUzenet = null;

        try {
            Mail::to($email)->send(new ManualisEmlekeztetoMail($szamla, $egyediUzenet));
        } catch (\Exception $e) {
            $statusz = 'sikertelen';
            $hibaUzenet = $e->getMessage();
        }

        // Naplózás a fizetes_emlekeztetok táblába
        FizetesEmlekeztetok::create([
            'szamla_id' => $szamla->szamla_id,
            'megrendeles_id' => $szamla->megrendeles_id,
            'ugyfel_id' => $szamla->ugyfel_id,
            'email_cim' => $email,
            'tipus' => 'manualis',
            'statusz' => $statusz,
            'hiba_uzenet' => $hibaUzenet,
        ]);

        // Audit log
        FizetesAuditLog::naplo(
            szamlaId: $szamla->szamla_id,
            esemeny: 'emlekeztetokuldve',
            adatok: [
                'megjegyzes' => "Email: {$email} | Státusz: {$statusz}".($egyediUzenet ? " | Üzenet: {$egyediUzenet}" : ''),
            ],
            megrendelesId: $szamla->megrendeles_id,
        );

        if ($statusz === 'sikeres') {
            return back()->with('success', "Emlékeztető sikeresen elküldve: {$email}");
        }

        return back()->with('error', "Emlékeztető küldése sikertelen: {$hibaUzenet}");
    }
}
