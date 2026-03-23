<?php

namespace App\Services;

use App\Models\Megrendeles;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /** Ellenőrzi, hogy Stripe be van-e konfigurálva */
    public function isConfigured(): bool
    {
        $key = config('services.stripe.secret', '');

        return ! empty($key) && ! str_starts_with($key, 'REPLACE_');
    }

    /**
     * Stripe Checkout Session létrehozása.
     *
     * @param  float  $bruttoOsszeg  A Szamla.brutto_osszeg értéke (HUF)
     * @return string  Stripe checkout URL
     */
    public function createCheckoutSession(
        Megrendeles $megrendeles,
        float $bruttoOsszeg,
        string $successUrl,
        string $cancelUrl
    ): string {
        $osszegFiller = (int) round($bruttoOsszeg * 100); // HUF fillérben

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'huf',
                    'unit_amount' => $osszegFiller,
                    'product_data' => [
                        'name' => 'Megrendelés #'.str_pad($megrendeles->id, 5, '0', STR_PAD_LEFT)
                                  .' – '.$megrendeles->megrendeles_nev,
                        'description' => 'TRITON SECURITY – Számlázási összeg',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl.'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'megrendeles_id' => $megrendeles->id,
            ],
            'customer_email' => $megrendeles->ugyfel?->email ?? null,
        ]);

        return $session->url;
    }

    /**
     * Stripe Webhook esemény feldolgozása.
     * Visszaadja az esemény típusát és adatát.
     */
    public function constructWebhookEvent(string $payload, string $sigHeader): \Stripe\Event
    {
        return Webhook::constructEvent(
            $payload,
            $sigHeader,
            config('services.stripe.webhook_secret')
        );
    }

    /** Checkout Session lekérése session_id alapján */
    public function retrieveSession(string $sessionId): StripeSession
    {
        return StripeSession::retrieve($sessionId);
    }
}
