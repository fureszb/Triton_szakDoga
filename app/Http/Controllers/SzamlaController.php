<?php

namespace App\Http\Controllers;

use App\Mail\SzamlaKiallitasMail;
use App\Models\FizetesAuditLog;
use App\Models\Fizetes;
use App\Models\Megrendeles;
use App\Models\Szamla;
use App\Models\SzamlaTetel;
use App\Services\BillingoService;
use App\Services\SajatSzamlaService;
use App\Services\SzamlaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SzamlaController extends Controller
{
    public function __construct(
        protected BillingoService    $billingo,
        protected SajatSzamlaService $sajatPdf,
        protected SzamlaService      $szamlaService,
    ) {}

    // ─── Index ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'osszes');
        $tipus  = $request->get('tipus', 'szamla');
        $ma     = Carbon::today();

        $query = Szamla::with(['megrendeles.ugyfel', 'megrendeles.varos'])
            ->where('szamla_tipus', $tipus);

        switch ($filter) {
            case 'fizetve':
                $query->where('statusz', 'fizetve');
                break;
            case 'varakozik':
                $query->where('statusz', 'fuggoben')
                      ->whereDate('fizetesi_hatarido', '>=', $ma);
                break;
            case 'kesedelmes':
                $query->where(function ($q) use ($ma) {
                    $q->where('statusz', 'kesedelmes')
                      ->orWhere(function ($q2) use ($ma) {
                          $q2->where('statusz', 'fuggoben')
                             ->whereDate('fizetesi_hatarido', '<', $ma);
                      });
                });
                break;
            case 'stornozva':
                $query->where('statusz', 'stornozva');
                break;
        }

        $szamlak = $query->orderBy('kiallitas_datum', 'desc')->get();

        // KPI – mindig az összes 'szamla' típusú
        $osszes        = Szamla::where('szamla_tipus', 'szamla')->get();
        $kpiFizetve    = $osszes->where('statusz', 'fizetve')->count();
        $kpiFuggoben   = $osszes->where('statusz', 'fuggoben')->count();
        $kpiKesedelmes = $osszes->filter(fn ($s) =>
            $s->statusz === 'kesedelmes' ||
            ($s->statusz === 'fuggoben' && $s->fizetesi_hatarido && $s->fizetesi_hatarido->lt($ma))
        )->count();
        $kpiBevertel   = $osszes->where('statusz', 'fizetve')->sum('brutto_osszeg');
        $kpiVaro       = $osszes->whereIn('statusz', ['fuggoben', 'kesedelmes'])->sum('brutto_osszeg');
        $dijbekeroDB   = Szamla::where('szamla_tipus', 'dijbekero')->count();
        $stornoDB      = Szamla::where('szamla_tipus', 'storno')->count();

        return view('szamlak.index', compact(
            'szamlak', 'filter', 'tipus', 'ma',
            'kpiFizetve', 'kpiFuggoben', 'kpiKesedelmes', 'kpiBevertel', 'kpiVaro',
            'dijbekeroDB', 'stornoDB'
        ));
    }

    // ─── Show ─────────────────────────────────────────────────────────────────

    public function show(Szamla $szamla)
    {
        $szamla->load([
            'tetelek',
            'fizetesek',
            'megrendeles.ugyfel',
            'megrendeles.varos',
            'auditLog.user',
            'stornoEredeti',
            'stornoSzamla',
            'dijbekero',
        ]);

        return view('szamlak.show', compact('szamla'));
    }

    // ─── Create form ──────────────────────────────────────────────────────────

    public function createForm(Request $request)
    {
        $megrendeles = null;
        if ($request->has('megrendeles_id')) {
            $megrendeles = Megrendeles::with('ugyfel')->find($request->megrendeles_id);
        }

        $megrendelesek = Megrendeles::with('ugyfel')
            ->whereDoesntHave('osszesSzamla', fn ($q) => $q->where('szamla_tipus', 'szamla'))
            ->orderBy('Megrendeles_ID', 'desc')
            ->get();

        return view('szamlak.create', compact('megrendelesek', 'megrendeles'));
    }

    // ─── Store ────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'megrendeles_id'            => 'required|exists:megrendeles,Megrendeles_ID',
            'szamla_tipus'              => 'required|in:dijbekero,szamla',
            'kiallitas_datum'           => 'required|date',
            'teljesites_datum'          => 'required|date',
            'fizetesi_hatarido'         => 'required|date|after_or_equal:kiallitas_datum',
            'fizetesi_mod'              => 'required|in:stripe,banki_atutalas,keszpenz',
            'megjegyzes'                => 'nullable|string|max:1000',
            'tetelek'                   => 'required|array|min:1',
            'tetelek.*.tetel_tipus'     => 'required|in:anyag,munkaora,egyeb',
            'tetelek.*.nev'             => 'required|string|max:255',
            'tetelek.*.mennyiseg'       => 'required|numeric|min:0.001',
            'tetelek.*.mertekegyseg'    => 'required|string|max:20',
            'tetelek.*.egyseg_netto_ar' => 'required|numeric|min:0',
            'tetelek.*.afa_kulcs'       => 'required|in:0,5,27',
        ], [
            'megrendeles_id.required'            => 'A megrendelés kiválasztása kötelező.',
            'tetelek.required'                   => 'Legalább egy tételt meg kell adni.',
            'tetelek.*.nev.required'             => 'Minden tétel neve kötelező.',
            'tetelek.*.mennyiseg.required'       => 'Minden tétel mennyisége kötelező.',
            'tetelek.*.egyseg_netto_ar.required' => 'Minden egységár megadása kötelező.',
            'fizetesi_hatarido.after_or_equal'   => 'A fizetési határidő nem lehet korábbi a kiállítás dátumánál.',
        ]);

        $megrendeles = Megrendeles::findOrFail($request->megrendeles_id);

        $szamla = DB::transaction(function () use ($request, $megrendeles) {
            $szamla = Szamla::create([
                'megrendeles_id'    => $megrendeles->Megrendeles_ID,
                'ugyfel_id'         => $megrendeles->Ugyfel_ID,
                'szamla_tipus'      => $request->szamla_tipus,
                'kiallitas_datum'   => $request->kiallitas_datum,
                'teljesites_datum'  => $request->teljesites_datum,
                'fizetesi_hatarido' => $request->fizetesi_hatarido,
                'fizetesi_mod'      => $request->fizetesi_mod,
                'megjegyzes'        => $request->megjegyzes,
                'statusz'           => 'fuggoben',
            ]);

            foreach ($request->tetelek as $i => $tetel) {
                SzamlaTetel::create(array_merge(
                    SzamlaTetel::szamitOsszegek($tetel),
                    ['szamla_id' => $szamla->szamla_id, 'sorrend' => $i]
                ));
            }

            $szamla->osszegekUjraszamit();

            FizetesAuditLog::naplo(
                $szamla->szamla_id,
                $request->szamla_tipus === 'dijbekero' ? 'dijbekero_kiallitva' : 'szamla_kiallitva',
                ['uj' => ['statusz' => 'fuggoben', 'brutto' => $szamla->brutto_osszeg]],
                megrendelesId: $megrendeles->Megrendeles_ID
            );

            return $szamla;
        });

        return redirect()->route('szamlak.show', $szamla)
            ->with('success', 'Számla sikeresen létrehozva.');
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────

    public function edit(Szamla $szamla)
    {
        if (in_array($szamla->statusz, ['fizetve', 'stornozva'])) {
            return back()->with('error', 'Lezárt számla nem módosítható.');
        }
        $szamla->load('tetelek');
        return view('szamlak.edit', compact('szamla'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update(Request $request, Szamla $szamla)
    {
        if (in_array($szamla->statusz, ['fizetve', 'stornozva'])) {
            return back()->with('error', 'Lezárt számla nem módosítható.');
        }

        $request->validate([
            'teljesites_datum'  => 'nullable|date',
            'fizetesi_hatarido' => 'nullable|date',
            'fizetesi_mod'      => 'nullable|in:stripe,banki_atutalas,keszpenz',
            'megjegyzes'        => 'nullable|string|max:1000',
        ]);

        $fields = ['teljesites_datum', 'fizetesi_hatarido', 'fizetesi_mod', 'megjegyzes'];
        $regi   = $szamla->only($fields);
        $szamla->update($request->only($fields));

        FizetesAuditLog::naplo(
            $szamla->szamla_id,
            'statusz_valtozas',
            ['regi' => $regi, 'uj' => $szamla->fresh()->only($fields), 'megjegyzes' => 'Számla adatai frissítve'],
            megrendelesId: $szamla->megrendeles_id
        );

        return redirect()->route('szamlak.show', $szamla)
            ->with('success', 'Számla sikeresen frissítve.');
    }

    // ─── Manuális fizetve jelölés ──────────────────────────────────────────────

    public function markAsPaid(Request $request, Szamla $szamla)
    {
        if ($szamla->statusz === 'fizetve') {
            return back()->with('info', 'A számla már fizetve van.');
        }

        $fizetesiMod  = $request->get('mod', 'banki_atutalas');
        $veglegSzamla = null;

        DB::transaction(function () use ($request, $szamla, $fizetesiMod, &$veglegSzamla) {
            $regi = $szamla->statusz;
            $szamla->update(['statusz' => 'fizetve']);

            Fizetes::create([
                'szamla_id'         => $szamla->szamla_id,
                'megrendeles_id'    => $szamla->megrendeles_id,
                'ugyfel_id'         => $szamla->ugyfel_id,
                'fizetes_mod'       => $fizetesiMod,
                'osszeg'            => $szamla->brutto_osszeg,
                'deviza'            => 'HUF',
                'statusz'           => 'fizetve',
                'fizetes_idopontja' => now(),
                'banki_hivatkozas'  => $request->get('hivatkozas'),
                'megjegyzes'        => 'Manuálisan rögzítve',
            ]);

            FizetesAuditLog::naplo(
                $szamla->szamla_id,
                'manualis_fizetes',
                ['regi' => ['statusz' => $regi], 'uj' => ['statusz' => 'fizetve']],
                megrendelesId: $szamla->megrendeles_id
            );

            // Ha díjbekérő: automatikus végleges számla generálás
            if ($szamla->szamla_tipus === Szamla::TIPUS_DIJBEKERO) {
                try {
                    $szamla->load('tetelek');
                    $veglegSzamla = $this->szamlaService->dijbekerobolVeglesSzamla($szamla, $fizetesiMod);
                } catch (\Throwable $e) {
                    Log::error('Végleges számla generálás hiba (markAsPaid): ' . $e->getMessage());
                }
            } else {
                $veglegSzamla = $szamla;
            }

            // Billingo kiállítás a végleges számlára
            if ($veglegSzamla && ! $veglegSzamla->billingo_id && $this->billingo->isConfigured()) {
                try {
                    $adatok = $this->billingo->createInvoiceFromSzamla($veglegSzamla->fresh());
                    $veglegSzamla->update([
                        'billingo_id'      => $adatok['id'],
                        'billingo_szam'    => $adatok['invoice_number'],
                        'billingo_pdf_url' => $adatok['pdf_url'],
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Billingo markAsPaid hiba: ' . $e->getMessage());
                }
            }
        });

        // Email az ügyfélnek a végleges számlával
        $email = $szamla->ugyfel?->Email ?? $szamla->megrendeles?->ugyfel?->Email;
        if ($email && $veglegSzamla) {
            try {
                Mail::to($email)->send(new SzamlaKiallitasMail($veglegSzamla->fresh()));
            } catch (\Throwable $e) {
                Log::error('Email hiba (markAsPaid): ' . $e->getMessage());
            }
        }

        $uzenet = $szamla->szamla_tipus === Szamla::TIPUS_DIJBEKERO
            ? 'Díjbekérő fizetve jelölve és végleges számla automatikusan kiállítva.'
            : 'Számla megjelölve fizettként.';

        return back()->with('success', $uzenet);
    }

    // ─── Stornó ────────────────────────────────────────────────────────────────

    public function storno(Szamla $szamla)
    {
        if ($szamla->statusz === 'stornozva' || $szamla->szamla_tipus === 'storno') {
            return back()->with('error', 'Ez a számla már stornózva van.');
        }

        DB::transaction(function () use ($szamla) {
            $szamla->load('tetelek');
            $szamla->update(['statusz' => 'stornozva']);

            $storno = Szamla::create([
                'megrendeles_id'    => $szamla->megrendeles_id,
                'ugyfel_id'         => $szamla->ugyfel_id,
                'szamla_tipus'      => 'storno',
                'storno_eredeti_id' => $szamla->szamla_id,
                'kiallitas_datum'   => now()->toDateString(),
                'teljesites_datum'  => now()->toDateString(),
                'fizetesi_hatarido' => now()->toDateString(),
                'fizetesi_mod'      => $szamla->fizetesi_mod,
                'netto_osszeg'      => -abs($szamla->netto_osszeg),
                'afa_osszeg'        => -abs($szamla->afa_osszeg),
                'brutto_osszeg'     => -abs($szamla->brutto_osszeg),
                'statusz'           => 'stornozva',
                'megjegyzes'        => 'Stornó – eredeti számla #' . $szamla->szamla_id,
            ]);

            foreach ($szamla->tetelek as $t) {
                SzamlaTetel::create([
                    'szamla_id'       => $storno->szamla_id,
                    'tetel_tipus'     => $t->tetel_tipus,
                    'nev'             => $t->nev . ' (stornó)',
                    'mennyiseg'       => -abs($t->mennyiseg),
                    'mertekegyseg'    => $t->mertekegyseg,
                    'egyseg_netto_ar' => $t->egyseg_netto_ar,
                    'afa_kulcs'       => $t->afa_kulcs,
                    'netto_osszeg'    => -abs($t->netto_osszeg),
                    'afa_osszeg'      => -abs($t->afa_osszeg),
                    'brutto_osszeg'   => -abs($t->brutto_osszeg),
                    'sorrend'         => $t->sorrend,
                ]);
            }

            FizetesAuditLog::naplo(
                $szamla->szamla_id,
                'storno_kiallitva',
                ['regi' => ['statusz' => 'fizetve'], 'uj' => ['statusz' => 'stornozva', 'storno_id' => $storno->szamla_id]],
                megrendelesId: $szamla->megrendeles_id
            );
        });

        return back()->with('success', 'Stornó számla sikeresen kiállítva.');
    }

    // ─── Billingo kiállítás ────────────────────────────────────────────────────

    public function billingoKiallitas(Szamla $szamla)
    {
        if (!$this->billingo->isConfigured()) {
            return back()->with('error', 'Billingo API nincs konfigurálva.');
        }

        if ($szamla->billingo_id) {
            return back()->with('info', 'Már ki van állítva Billingo számla (' . $szamla->billingo_szam . ').');
        }

        try {
            $megrendeles = $szamla->megrendeles;
            $adatok = $this->billingo->createInvoice($megrendeles);

            $szamla->update([
                'billingo_id'      => $adatok['id'],
                'billingo_szam'    => $adatok['invoice_number'],
                'billingo_pdf_url' => $adatok['pdf_url'],
            ]);

            FizetesAuditLog::naplo(
                $szamla->szamla_id,
                'billingo_szinkron',
                ['uj' => ['billingo_szam' => $adatok['invoice_number']]],
                megrendelesId: $szamla->megrendeles_id
            );

            $email = $megrendeles->ugyfel?->Email;
            if ($email) {
                try {
                    Mail::to($email)->send(new SzamlaKiallitasMail($szamla->fresh()));
                } catch (\Throwable $e) {
                    Log::error('Számla email hiba: ' . $e->getMessage());
                }
            }

            return back()->with('success', 'Billingo számla kiállítva: ' . ($adatok['invoice_number'] ?? ''));
        } catch (\Throwable $e) {
            Log::error('Billingo hiba: ' . $e->getMessage());
            return back()->with('error', 'Billingo kiállítás sikertelen: ' . $e->getMessage());
        }
    }

    // ─── PDF letöltés ─────────────────────────────────────────────────────────

    public function download(Szamla $szamla)
    {
        // Ügyfél csak a saját számláját töltheti le
        $this->authorizeUgyfelDownload($szamla);

        if (!$szamla->billingo_pdf_url && $szamla->billingo_id) {
            $url = $this->billingo->getInvoicePdfUrl($szamla->billingo_id);
            if ($url) {
                $szamla->update(['billingo_pdf_url' => $url]);
                return redirect($url);
            }
        }

        if ($szamla->billingo_pdf_url) {
            return redirect($szamla->billingo_pdf_url);
        }

        return back()->with('error', 'A számla PDF nem elérhető jelenleg.');
    }

    // ─── Saját PDF kiállítás ───────────────────────────────────────────────────

    public function sajatKiallitas(Szamla $szamla)
    {
        if ($szamla->sajat_pdf_path && Storage::exists($szamla->sajat_pdf_path)) {
            return back()->with('info', 'Saját számla már ki van állítva. Töltsd le, vagy állíts ki újat.');
        }

        try {
            $path = $this->sajatPdf->generate($szamla);

            $szamla->update(['sajat_pdf_path' => $path]);

            FizetesAuditLog::naplo(
                $szamla->szamla_id,
                'billingo_szinkron',
                ['uj' => ['sajat_pdf' => $path, 'forrás' => 'saját sablon']],
                megrendelesId: $szamla->megrendeles_id
            );

            return back()->with('success', 'Saját számla sikeresen legenerálva. Letöltheted az alábbi gombbal.');
        } catch (\Throwable $e) {
            Log::error('Saját PDF hiba: ' . $e->getMessage());
            return back()->with('error', 'PDF generálás sikertelen: ' . $e->getMessage());
        }
    }

    // ─── Saját PDF letöltés ────────────────────────────────────────────────────

    public function sajatLetoltes(Szamla $szamla)
    {
        // Ügyfél csak a saját számláját töltheti le
        $this->authorizeUgyfelDownload($szamla);

        if ($szamla->sajat_pdf_path && Storage::exists($szamla->sajat_pdf_path)) {
            return Storage::download(
                $szamla->sajat_pdf_path,
                'szamla-' . str_pad($szamla->szamla_id, 5, '0', STR_PAD_LEFT) . '.pdf',
                ['Content-Type' => 'application/pdf']
            );
        }

        // Ha nincs mentett, generáljuk menet közben
        return $this->sajatPdf->stream($szamla);
    }

    // ─── Segéd: ügyfél-letöltés jogosultság-ellenőrzés ───────────────────────

    private function authorizeUgyfelDownload(Szamla $szamla): void
    {
        $user = Auth::user();
        if (in_array($user->role, ['Admin', 'Uzletkoto'])) {
            return; // Admin és Uzletkoto bármit letölthet
        }
        // Ügyfél csak a saját számláját töltheti le
        $ugyfelId = $user->ugyfel?->Ugyfel_ID;
        if (!$ugyfelId || $szamla->ugyfel_id !== $ugyfelId) {
            abort(403, 'Nincs jogosultságod ehhez a számlához.');
        }
    }

    // ─── Teszt PDF letöltés (mentés nélkül, vízjellel) ────────────────────────

    public function tesztLetoltes(Szamla $szamla)
    {
        try {
            return $this->sajatPdf->tesztStream($szamla);
        } catch (\Throwable $e) {
            Log::error('Teszt PDF hiba: ' . $e->getMessage());
            return back()->with('error', 'Teszt PDF generálás sikertelen: ' . $e->getMessage());
        }
    }

    // ─── Legacy Billingo (régi route-okhoz – backward compat) ────────────────

    public function legacyBillingoCreate(Megrendeles $megrendeles)
    {
        // Átirányítás az új számlára, ha van
        $szamla = $megrendeles->szamla;
        if ($szamla) {
            return $this->billingoKiallitas($szamla);
        }

        return back()->with('error', 'Ehhez a megrendeléshez nincs létrehozva számla az új rendszerben.');
    }

    public function legacyDownload(Megrendeles $megrendeles)
    {
        $szamla = $megrendeles->szamla;
        if ($szamla) {
            return $this->download($szamla);
        }

        return back()->with('error', 'Ehhez a megrendeléshez nincs számla.');
    }
}
