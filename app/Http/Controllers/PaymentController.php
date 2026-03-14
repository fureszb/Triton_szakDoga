<?php

namespace App\Http\Controllers;

use App\Mail\FizetesiBizonylat;
use App\Mail\SzamlaKiallitasMail;
use App\Models\Fizetes;
use App\Models\FizetesAuditLog;
use App\Models\Megrendeles;
use App\Models\Szamla;
use App\Services\BillingoService;
use App\Services\SzamlaService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct(
        protected StripeService   $stripe,
        protected BillingoService $billingo,
        protected SzamlaService   $szamlaService,
    ) {}

    // ─────────────────────────────────────────────────────────────────────────
    // STRIPE CHECKOUT – Ügyfél megnyomja a "Fizetek" gombot
    // ─────────────────────────────────────────────────────────────────────────
    public function checkout(Megrendeles $megrendeles)
    {
        // Csak saját megrendelést fizethet az ügyfél
        $user = auth()->user();
        if ($user->role === 'Ugyfel') {
            $ugyfel = $user->ugyfel;
            if (! $ugyfel || $ugyfel->Ugyfel_ID !== $megrendeles->Ugyfel_ID) {
                abort(403);
            }
        }

        // Fizetendő bizonylat: díjbekérő ha van, egyébként végleges számla
        $szamla = $megrendeles->dijbekero()->first()
               ?? $megrendeles->szamla()->first();

        if (! $szamla) {
            return back()->with('error', 'Ehhez a megrendeléshez még nincs kiállított számla vagy díjbekérő.');
        }

        if ($szamla->statusz === 'fizetve') {
            return back()->with('info', 'Ez a megrendelés már ki van fizetve.');
        }

        if (! $szamla->brutto_osszeg || $szamla->brutto_osszeg <= 0) {
            return back()->with('error', 'A bizonylatón nincs érvényes fizetendő összeg.');
        }

        if (! $this->stripe->isConfigured()) {
            return back()->with('error', 'Online fizetés jelenleg nem érhető el (Stripe nincs konfigurálva).');
        }

        $successUrl = route('payment.success', $megrendeles->Megrendeles_ID);
        $cancelUrl  = route('payment.cancel',  $megrendeles->Megrendeles_ID);

        try {
            $checkoutUrl = $this->stripe->createCheckoutSession(
                $megrendeles,
                (float) $szamla->brutto_osszeg,
                $successUrl,
                $cancelUrl
            );
            return redirect($checkoutUrl);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout hiba: ' . $e->getMessage());
            return back()->with('error', 'Fizetési munkamenet indítása sikertelen. Kérjük próbálja újra.');
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STRIPE VISSZATÉRÉS – Sikeres fizetés
    // ─────────────────────────────────────────────────────────────────────────
    public function success(Request $request, Megrendeles $megrendeles)
    {
        $sessionId = $request->query('session_id');

        // Fizetett bizonylat: díjbekérő ha van, egyébként végleges számla
        $fizetetBizonylat = $megrendeles->dijbekero()->with('fizetesek', 'tetelek')->first()
                         ?? $megrendeles->szamla()->with('fizetesek', 'tetelek')->first();

        $veglegSzamla = null;

        if ($fizetetBizonylat && $fizetetBizonylat->statusz !== 'fizetve') {
            DB::transaction(function () use ($megrendeles, $fizetetBizonylat, $sessionId, &$veglegSzamla) {

                // 1) Fizetési tranzakció rögzítése
                $fizetes = Fizetes::create([
                    'szamla_id'         => $fizetetBizonylat->szamla_id,
                    'megrendeles_id'    => $megrendeles->Megrendeles_ID,
                    'ugyfel_id'         => $megrendeles->Ugyfel_ID,
                    'fizetes_mod'       => 'stripe',
                    'osszeg'            => $fizetetBizonylat->brutto_osszeg,
                    'deviza'            => 'HUF',
                    'statusz'           => 'fizetve',
                    'stripe_session_id' => $sessionId,
                    'fizetes_idopontja' => now(),
                ]);

                // 2) Bizonylat státusz → fizetve
                $fizetetBizonylat->update(['statusz' => 'fizetve']);

                FizetesAuditLog::naplo(
                    $fizetetBizonylat->szamla_id,
                    'fizetes_teljesult',
                    ['uj' => ['statusz' => 'fizetve', 'mod' => 'stripe', 'session' => $sessionId]],
                    $fizetes->fizetes_id,
                    $megrendeles->Megrendeles_ID
                );

                // 3) Ha díjbekérő volt: automatikusan generáljuk a végleges számlát
                if ($fizetetBizonylat->szamla_tipus === Szamla::TIPUS_DIJBEKERO) {
                    try {
                        $veglegSzamla = $this->szamlaService->dijbekerobolVeglesSzamla(
                            $fizetetBizonylat, 'stripe'
                        );
                    } catch (\Throwable $e) {
                        Log::error('Végleges számla generálás hiba (Stripe success): ' . $e->getMessage());
                    }
                } else {
                    $veglegSzamla = $fizetetBizonylat;
                }

                // 4) Billingo kiállítás a végleges számlára (ha van, és Billingo be van konfigurálva)
                if ($veglegSzamla && ! $veglegSzamla->billingo_id && $this->billingo->isConfigured()) {
                    try {
                        $adatok = $this->billingo->createInvoiceFromSzamla($veglegSzamla->fresh());
                        $veglegSzamla->update([
                            'billingo_id'      => $adatok['id'],
                            'billingo_szam'    => $adatok['invoice_number'],
                            'billingo_pdf_url' => $adatok['pdf_url'],
                        ]);
                    } catch (\Throwable $e) {
                        Log::error('Billingo auto-számla hiba (Stripe success): ' . $e->getMessage());
                    }
                }
            });

            // 5) Értesítő email az ügyfélnek (a végleges számlával)
            $email = $megrendeles->ugyfel?->Email;
            if ($email && $veglegSzamla) {
                try {
                    Mail::to($email)->send(new SzamlaKiallitasMail($veglegSzamla->fresh()));
                } catch (\Throwable $e) {
                    Log::error('Számla értesítő email hiba: ' . $e->getMessage());
                }
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
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = $this->stripe->constructWebhookEvent($payload, $sigHeader);
        } catch (\Throwable $e) {
            Log::error('Stripe webhook hiba: ' . $e->getMessage());
            return response('Webhook error', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session       = $event->data->object;
            $megrendelesId = $session->metadata->megrendeles_id ?? null;

            if ($megrendelesId) {
                $megrendeles    = Megrendeles::find($megrendelesId);
                $fizetetBizonylat = $megrendeles?->dijbekero()->first()
                                 ?? $megrendeles?->szamla()->first();

                if ($fizetetBizonylat && $fizetetBizonylat->statusz !== 'fizetve') {
                    $fizetes = Fizetes::firstOrCreate(
                        ['stripe_session_id' => $session->id],
                        [
                            'szamla_id'                => $fizetetBizonylat->szamla_id,
                            'megrendeles_id'           => $megrendelesId,
                            'ugyfel_id'                => $megrendeles->Ugyfel_ID,
                            'fizetes_mod'              => 'stripe',
                            'osszeg'                   => $fizetetBizonylat->brutto_osszeg,
                            'deviza'                   => 'HUF',
                            'statusz'                  => 'fizetve',
                            'stripe_payment_intent_id' => $session->payment_intent,
                            'fizetes_idopontja'        => now(),
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
                                    'billingo_id'      => $adatok['id'],
                                    'billingo_szam'    => $adatok['invoice_number'],
                                    'billingo_pdf_url' => $adatok['pdf_url'],
                                ]);
                            }
                        } catch (\Throwable $e) {
                            Log::error('Webhook végleges számla hiba: ' . $e->getMessage());
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
            $fizetes = Fizetes::create([
                'szamla_id'         => $fizetetBizonylat->szamla_id,
                'megrendeles_id'    => $megrendeles->Megrendeles_ID,
                'ugyfel_id'         => $megrendeles->Ugyfel_ID,
                'fizetes_mod'       => 'banki_atutalas',
                'osszeg'            => $fizetetBizonylat->brutto_osszeg,
                'deviza'            => 'HUF',
                'statusz'           => 'fizetve',
                'fizetes_idopontja' => now(),
                'megjegyzes'        => 'Manuálisan rögzítve',
            ]);

            $fizetetBizonylat->update(['statusz' => 'fizetve']);

            FizetesAuditLog::naplo(
                $fizetetBizonylat->szamla_id,
                'manualis_fizetes',
                ['uj' => ['statusz' => 'fizetve', 'mod' => 'banki_atutalas']],
                $fizetes->fizetes_id,
                $megrendeles->Megrendeles_ID
            );

            // Ha díjbekérő: automatikus végleges számla generálás
            if ($fizetetBizonylat->szamla_tipus === Szamla::TIPUS_DIJBEKERO) {
                try {
                    $veglegSzamla = $this->szamlaService->dijbekerobolVeglesSzamla(
                        $fizetetBizonylat, 'banki_atutalas'
                    );
                } catch (\Throwable $e) {
                    Log::error('Végleges számla generálás hiba (manualMarkPaid): ' . $e->getMessage());
                }
            } else {
                $veglegSzamla = $fizetetBizonylat;
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
                    Log::error('Billingo manuális számla hiba: ' . $e->getMessage());
                }
            }
        });

        // Email az ügyfélnek a végleges számlával
        $email = $megrendeles->ugyfel?->Email;
        if ($email && $veglegSzamla) {
            try {
                Mail::to($email)->send(new SzamlaKiallitasMail($veglegSzamla->fresh()));
            } catch (\Throwable $e) {
                Log::error('Email hiba (manualMarkPaid): ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Megrendelés sikeresen megjelölve fizetve. Számla automatikusan kiállítva.');
    }
}
