<?php

namespace App\Http\Controllers;

use App\Mail\SzamlaKiallitasMail;
use App\Models\Fizetes;
use App\Models\FizetesAuditLog;
use App\Models\Megrendeles;
use App\Models\Szamla;
use App\Models\User;
use App\Notifications\AtutalasBejelentveNotification;
use App\Services\BillingoService;
use App\Services\StripeService;
use App\Services\SzamlaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct(
        protected StripeService $stripe,
        protected BillingoService $billingo,
        protected SzamlaService $szamlaService,
    ) {
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FIZETÉSI MÓD VÁLASZTÓ – Megjelenítés
    // ─────────────────────────────────────────────────────────────────────────
    public function checkout(Megrendeles $megrendeles)
    {
        $user = auth()->user();
        if ($user->role === 'Ugyfel') {
            $ugyfel = $user->ugyfel;
            if (! $ugyfel || $ugyfel->id !== $megrendeles->ugyfel_id) {
                abort(403);
            }
        }

        // Ha már van függőben lévő átutalás bejelentés, blokkoljuk az újabb fizetést
        $fuggobenFizetes = $megrendeles->fizetesek()
            ->where('statusz', Fizetes::STATUSZ_FUGGOBEN)
            ->where('fizetes_mod', 'banki_atutalas')
            ->first();

        if ($fuggobenFizetes) {
            // Loop-kockázat megelőzése: ha az előző URL maga a checkout oldal, menj a megrendelesekre
            $prevUrl   = url()->previous();
            $checkoutUrl = route('payment.checkout', $megrendeles->id);
            $safeRedirect = ($prevUrl === $checkoutUrl || empty($prevUrl))
                ? redirect()->route('ugyfel.megrendelesek')
                : back();
            return $safeRedirect->with('info', 'Az átutalás bejelentése már megtörtént. Várj az admin jóváhagyására.');
        }

        $szamla = $megrendeles->tobbDijbekero()->whereNotIn('statusz', ['fizetve', 'stornozva'])->first()
               ?? $megrendeles->tobbSzamla()->whereNotIn('statusz', ['fizetve', 'stornozva'])->first();

        if (! $szamla) {
            $vanBarmilyen = $megrendeles->osszesSzamla()->whereNotIn('szamla_tipus', ['storno'])->exists();
            if ($vanBarmilyen) {
                return redirect()->route('ugyfel.megrendelesek')
                    ->with('info', 'Ehhez a megrendeléshez minden számla ki van fizetve.');
            }
            return redirect()->route('ugyfel.megrendelesek')
                ->with('error', 'Ehhez a megrendeléshez még nincs kiállított számla.');
        }

        $stripeErheto = $this->stripe->isConfigured();

        return view('megrendeles.fizetes_valasztas', compact('megrendeles', 'szamla', 'stripeErheto'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STRIPE CHECKOUT – Online fizetés indítása
    // ─────────────────────────────────────────────────────────────────────────
    public function stripeCheckout(Megrendeles $megrendeles)
    {
        $user = auth()->user();
        if ($user->role === 'Ugyfel') {
            $ugyfel = $user->ugyfel;
            if (! $ugyfel || $ugyfel->id !== $megrendeles->ugyfel_id) {
                abort(403);
            }
        }

        $szamla = $megrendeles->tobbDijbekero()->whereNotIn('statusz', ['fizetve', 'stornozva'])->first()
               ?? $megrendeles->tobbSzamla()->whereNotIn('statusz', ['fizetve', 'stornozva'])->first();

        if (! $szamla) {
            return redirect()->route('payment.checkout', $megrendeles->id)
                ->with('error', 'A fizetés nem indítható.');
        }

        if (! $this->stripe->isConfigured()) {
            return redirect()->route('payment.checkout', $megrendeles->id)
                ->with('error', 'Online fizetés jelenleg nem érhető el.');
        }

        $successUrl = route('payment.success', $megrendeles->id);
        $cancelUrl  = route('payment.cancel', $megrendeles->id);

        try {
            $checkoutUrl = $this->stripe->createCheckoutSession(
                $megrendeles,
                (float) $szamla->brutto_osszeg,
                $successUrl,
                $cancelUrl
            );

            return redirect($checkoutUrl);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout hiba: '.$e->getMessage());

            return redirect()->route('payment.checkout', $megrendeles->id)
                ->with('error', 'Fizetési munkamenet indítása sikertelen. Kérjük próbálja újra.');
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // BANKI ÁTUTALÁS – Szándék rögzítése
    // ─────────────────────────────────────────────────────────────────────────
    public function bankTransfer(Request $request, Megrendeles $megrendeles)
    {
        $user = auth()->user();
        if ($user->role === 'Ugyfel') {
            $ugyfel = $user->ugyfel;
            if (! $ugyfel || $ugyfel->id !== $megrendeles->ugyfel_id) {
                abort(403);
            }
        }

        $request->validate([
            'kozlemeny' => ['required', 'string', 'min:4', 'max:100'],
        ], [
            'kozlemeny.required' => 'A közlemény megadása kötelező.',
            'kozlemeny.min'      => 'A közlemény legalább 4 karakter legyen.',
        ]);

        // Dupla bejelentés blokkolása + Fizetes létrehozása DB tranzakcióban
        // lockForUpdate() megakadályozza hogy két párhuzamos POST kérés
        // egyszerre hozzon létre két fuggoben rekordot (race condition)
        $szamla = null;
        $dupla  = false;

        DB::transaction(function () use ($megrendeles, $request, &$szamla, &$dupla) {

            // Zárolás: ha valaki más is bejelentene most, várakozni fog
            $fuggobenFizetes = $megrendeles->fizetesek()
                ->where('statusz', Fizetes::STATUSZ_FUGGOBEN)
                ->where('fizetes_mod', 'banki_atutalas')
                ->lockForUpdate()
                ->first();

            if ($fuggobenFizetes) {
                $dupla = true;
                return;
            }

            $szamla = $megrendeles->tobbDijbekero()
                          ->whereNotIn('statusz', ['fizetve', 'stornozva'])
                          ->lockForUpdate()
                          ->first()
                   ?? $megrendeles->tobbSzamla()
                          ->whereNotIn('statusz', ['fizetve', 'stornozva'])
                          ->lockForUpdate()
                          ->first();

            if (! $szamla) {
                return;
            }

            Fizetes::create([
                'szamla_id'         => $szamla->szamla_id,
                'megrendeles_id'    => $megrendeles->id,
                'ugyfel_id'         => $megrendeles->ugyfel_id,
                'fizetes_mod'       => 'banki_atutalas',
                'osszeg'            => $szamla->brutto_osszeg,
                'deviza'            => 'HUF',
                'statusz'           => Fizetes::STATUSZ_FUGGOBEN,
                'banki_hivatkozas'  => $request->kozlemeny,
                'fizetes_idopontja' => now(),
                'megjegyzes'        => 'Ügyfél által bejelentett átutalás – manuális jóváhagyás szükséges.',
            ]);

            FizetesAuditLog::naplo(
                $szamla->szamla_id,
                'fizetes_rogzitve',
                ['uj' => ['mod' => 'banki_atutalas', 'kozlemeny' => $request->kozlemeny, 'statusz' => 'fuggoben', 'megjegyzes' => 'Ügyfél által bejelentett átutalás']],
                megrendelesId: $megrendeles->id
            );
        });

        // Dupla bejelentés esetén visszairányítás
        if ($dupla) {
            return redirect()->route('ugyfel.megrendelesek')
                ->with('info', 'Az átutalás bejelentése már korábban megtörtént. Várj az admin jóváhagyására.');
        }

        // Ha nem volt fizethető számla
        if (! $szamla) {
            return redirect()->route('payment.checkout', $megrendeles->id)
                ->with('error', 'A fizetés már rögzítve van vagy a számla nem található.');
        }

        // Admin értesítés küldése minden Admin szerepkörű felhasználónak
        $ugyfelNev = $megrendeles->ugyfel?->nev ?? auth()->user()->name ?? 'Ismeretlen ügyfél';
        $notification = new AtutalasBejelentveNotification(
            $megrendeles,
            $request->kozlemeny,
            (float) $szamla->brutto_osszeg,
            $ugyfelNev,
        );

        User::where('role', 'Admin')->get()->each(
            fn($admin) => $admin->notify($notification)
        );

        return view('megrendeles.fizetes_sikeres', [
            'megrendeles' => $megrendeles,
            'atutalas'    => true,
            'kozlemeny'   => $request->kozlemeny,
            'osszeg'      => $szamla->brutto_osszeg,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STRIPE VISSZATÉRÉS – Sikeres fizetés
    // ─────────────────────────────────────────────────────────────────────────
    public function success(Request $request, Megrendeles $megrendeles)
    {
        $sessionId = $request->query('session_id');

        $veglegSzamla = null;

        // A tranzakció belsejében lockoljuk a számlát, hogy a Stripe webhook
        // és a success() oldal ne hozzon létre duplikált Fizetes rekordot
        // (race condition: webhook firstOrCreate vs. success create – UNIQUE violation)
        DB::transaction(function () use ($megrendeles, $sessionId, &$veglegSzamla) {

            // Fizetett bizonylat megkeresése – FOR UPDATE lock a race condition ellen
            $fizetetBizonylat = $megrendeles->tobbDijbekero()
                                    ->with('fizetesek', 'tetelek')
                                    ->whereNotIn('statusz', ['fizetve', 'stornozva'])
                                    ->lockForUpdate()
                                    ->first()
                             ?? $megrendeles->tobbSzamla()
                                    ->with('fizetesek', 'tetelek')
                                    ->whereNotIn('statusz', ['fizetve', 'stornozva'])
                                    ->lockForUpdate()
                                    ->first();

            // Ha a webhook már feldolgozta (fizetve státusz), nincs teendő
            if (! $fizetetBizonylat) {
                return;
            }

            // 1) Fizetési rekord – firstOrCreate: ha a webhook már létrehozta
            //    (stripe_session_id UNIQUE constraint), nem duplázunk
            $fizetes = Fizetes::firstOrCreate(
                ['stripe_session_id' => $sessionId],
                [
                    'szamla_id'         => $fizetetBizonylat->szamla_id,
                    'megrendeles_id'    => $megrendeles->id,
                    'ugyfel_id'         => $megrendeles->ugyfel_id,
                    'fizetes_mod'       => 'stripe',
                    'osszeg'            => $fizetetBizonylat->brutto_osszeg,
                    'deviza'            => 'HUF',
                    'statusz'           => Fizetes::STATUSZ_FIZETVE,
                    'fizetes_idopontja' => now(),
                ]
            );

            // 2) Bizonylat státusz → fizetve
            $fizetetBizonylat->update(['statusz' => 'fizetve']);

            FizetesAuditLog::naplo(
                $fizetetBizonylat->szamla_id,
                'fizetes_teljesult',
                ['uj' => ['statusz' => 'fizetve', 'mod' => 'stripe', 'session' => $sessionId]],
                $fizetes->fizetes_id,
                $megrendeles->id
            );

            // 3) Ha díjbekérő volt: automatikusan generáljuk a végleges számlát
            if ($fizetetBizonylat->szamla_tipus === Szamla::TIPUS_DIJBEKERO) {
                try {
                    $veglegSzamla = $this->szamlaService->dijbekerobolVeglesSzamla(
                        $fizetetBizonylat, 'stripe'
                    );
                } catch (\Throwable $e) {
                    Log::error('Végleges számla generálás hiba (Stripe success): '.$e->getMessage());
                }
            } else {
                $veglegSzamla = $fizetetBizonylat;
            }

            // 4) Billingo kiállítás a végleges számlára (ha van, és Billingo be van konfigurálva)
            if ($veglegSzamla && ! $veglegSzamla->billingo_id && $this->billingo->isConfigured()) {
                try {
                    $adatok = $this->billingo->createInvoiceFromSzamla($veglegSzamla->fresh());
                    $veglegSzamla->update([
                        'billingo_id' => $adatok['id'],
                        'billingo_szam' => $adatok['invoice_number'],
                        'billingo_pdf_url' => $adatok['pdf_url'],
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Billingo auto-számla hiba (Stripe success): '.$e->getMessage());
                }
            }
        });

        // 5) Értesítő email az ügyfélnek (tranzakción kívül – email hiba ne rollbackelje a fizetést)
        $email = $megrendeles->ugyfel?->email;
        if ($email && $veglegSzamla) {
            try {
                Mail::to($email)->send(new SzamlaKiallitasMail($veglegSzamla->fresh()));
            } catch (\Throwable $e) {
                Log::error('Számla értesítő email hiba: '.$e->getMessage());
            }
        }

        return view('megrendeles.fizetes_sikeres', compact('megrendeles'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STRIPE VISSZATÉRÉS – Megszakított / sikertelen fizetés
    // ─────────────────────────────────────────────────────────────────────────
    public function cancel(Megrendeles $megrendeles)
    {
        return view('megrendeles.fizetes_megsem', compact('megrendeles'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STRIPE WEBHOOK – Háttérben érkező megerősítés
    // ─────────────────────────────────────────────────────────────────────────
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = $this->stripe->constructWebhookEvent($payload, $sigHeader);
        } catch (\Throwable $e) {
            Log::error('Stripe webhook hiba: '.$e->getMessage());

            return response('Webhook error', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $megrendelesId = $session->metadata->megrendeles_id ?? null;

            if ($megrendelesId) {
                $megrendeles = Megrendeles::find($megrendelesId);
                $fizetetBizonylat = $megrendeles?->dijbekero()->first()
                                 ?? $megrendeles?->szamla()->first();

                if ($fizetetBizonylat && $fizetetBizonylat->statusz !== 'fizetve') {
                    $fizetes = Fizetes::firstOrCreate(
                        ['stripe_session_id' => $session->id],
                        [
                            'szamla_id' => $fizetetBizonylat->szamla_id,
                            'megrendeles_id' => $megrendelesId,
                            'ugyfel_id' => $megrendeles->ugyfel_id,
                            'fizetes_mod' => 'stripe',
                            'osszeg' => $fizetetBizonylat->brutto_osszeg,
                            'deviza' => 'HUF',
                            'statusz' => 'fizetve',
                            'stripe_payment_intent_id' => $session->payment_intent,
                            'fizetes_idopontja' => now(),
                        ]
                    );

                    $fizetetBizonylat->update(['statusz' => 'fizetve']);

                    // Végleges számla generálás díjbekérő esetén
                    if ($fizetetBizonylat->szamla_tipus === Szamla::TIPUS_DIJBEKERO) {
                        try {
                            $veglegSzamla = $this->szamlaService->dijbekerobolVeglesSzamla(
                                $fizetetBizonylat->fresh()->load('tetelek'), 'stripe'
                            );
                            if (! $veglegSzamla->billingo_id && $this->billingo->isConfigured()) {
                                $adatok = $this->billingo->createInvoiceFromSzamla($veglegSzamla->fresh());
                                $veglegSzamla->update([
                                    'billingo_id' => $adatok['id'],
                                    'billingo_szam' => $adatok['invoice_number'],
                                    'billingo_pdf_url' => $adatok['pdf_url'],
                                ]);
                            }
                        } catch (\Throwable $e) {
                            Log::error('Webhook végleges számla hiba: '.$e->getMessage());
                        }
                    }
                }
            }
        }

        return response('OK', 200);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MANUÁLIS MEGJELÖLÉS – Admin jelöli fizetve (pl. átutalás esetén)
    // ─────────────────────────────────────────────────────────────────────────
    public function manualMarkPaid(Request $request, Megrendeles $megrendeles)
    {
        // Fizetendő bizonylat: díjbekérő ha van, egyébként végleges számla
        $fizetetBizonylat = $megrendeles->dijbekero()->with('tetelek')->first()
                         ?? $megrendeles->szamla()->with('tetelek')->first();

        if (! $fizetetBizonylat) {
            return back()->with('error', 'Ehhez a megrendeléshez még nincs kiállított számla vagy díjbekérő.');
        }

        if ($fizetetBizonylat->statusz === 'fizetve') {
            return back()->with('info', 'Ez a megrendelés már fizetve van jelölve.');
        }

        $veglegSzamla = null;

        DB::transaction(function () use ($megrendeles, $fizetetBizonylat, &$veglegSzamla) {

            // Ha az ügyfél korábban bejelentett átutalást (fuggoben rekord), azt frissítjük
            // fizettévé ahelyett, hogy új rekordot hoznánk létre – így nincs árva rekord az adatbázisban
            $meglevoFuggoben = $megrendeles->fizetesek()
                ->where('statusz', Fizetes::STATUSZ_FUGGOBEN)
                ->where('fizetes_mod', 'banki_atutalas')
                ->first();

            if ($meglevoFuggoben) {
                $meglevoFuggoben->update([
                    'statusz'           => Fizetes::STATUSZ_FIZETVE,
                    'fizetes_idopontja' => now(),
                    'megjegyzes'        => trim($meglevoFuggoben->megjegyzes . ' – Admin által jóváhagyva.'),
                ]);
                $fizetes = $meglevoFuggoben->fresh();
            } else {
                $fizetes = Fizetes::create([
                    'szamla_id'         => $fizetetBizonylat->szamla_id,
                    'megrendeles_id'    => $megrendeles->id,
                    'ugyfel_id'         => $megrendeles->ugyfel_id,
                    'fizetes_mod'       => 'banki_atutalas',
                    'osszeg'            => $fizetetBizonylat->brutto_osszeg,
                    'deviza'            => 'HUF',
                    'statusz'           => Fizetes::STATUSZ_FIZETVE,
                    'fizetes_idopontja' => now(),
                    'megjegyzes'        => 'Manuálisan rögzítve',
                ]);
            }

            $fizetetBizonylat->update(['statusz' => 'fizetve']);

            FizetesAuditLog::naplo(
                $fizetetBizonylat->szamla_id,
                'manualis_fizetes',
                ['uj' => ['statusz' => 'fizetve', 'mod' => 'banki_atutalas']],
                $fizetes->fizetes_id,
                $megrendeles->id
            );

            // Ha díjbekérő: automatikus végleges számla generálás
            if ($fizetetBizonylat->szamla_tipus === Szamla::TIPUS_DIJBEKERO) {
                try {
                    $veglegSzamla = $this->szamlaService->dijbekerobolVeglesSzamla(
                        $fizetetBizonylat, 'banki_atutalas'
                    );
                } catch (\Throwable $e) {
                    Log::error('Végleges számla generálás hiba (manualMarkPaid): '.$e->getMessage());
                }
            } else {
                $veglegSzamla = $fizetetBizonylat;
            }

            // Billingo kiállítás a végleges számlára
            if ($veglegSzamla && ! $veglegSzamla->billingo_id && $this->billingo->isConfigured()) {
                try {
                    $adatok = $this->billingo->createInvoiceFromSzamla($veglegSzamla->fresh());
                    $veglegSzamla->update([
                        'billingo_id' => $adatok['id'],
                        'billingo_szam' => $adatok['invoice_number'],
                        'billingo_pdf_url' => $adatok['pdf_url'],
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Billingo manuális számla hiba: '.$e->getMessage());
                }
            }
        });

        // Email az ügyfélnek a végleges számlával
        $email = $megrendeles->ugyfel?->email;
        if ($email && $veglegSzamla) {
            try {
                Mail::to($email)->send(new SzamlaKiallitasMail($veglegSzamla->fresh()));
            } catch (\Throwable $e) {
                Log::error('Email hiba (manualMarkPaid): '.$e->getMessage());
            }
        }

        return back()->with('success', 'Megrendelés sikeresen megjelölve fizetve. Számla automatikusan kiállítva.');
    }
}
