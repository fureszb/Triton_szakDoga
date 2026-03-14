<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BeallitasokController extends Controller
{
    public function index()
    {
        // Stripe
        $stripeKey    = config('services.stripe.key', '');
        $stripeSecret = config('services.stripe.secret', '');
        $stripeConfigured = $stripeSecret && !str_contains($stripeSecret, 'teszt') && strlen($stripeSecret) > 20;
        $stripeTestMode   = str_starts_with($stripeKey, 'pk_test_') || str_starts_with($stripeSecret, 'sk_test_');
        $stripeMasked     = $stripeSecret ? substr($stripeSecret, 0, 7) . '••••••••••••••••' . substr($stripeSecret, -4) : null;

        // Billingo
        $billingoKey = config('services.billingo.api_key', '');
        $billingoBlockId = config('services.billingo.block_id', '');
        $billingoConfigured = $billingoKey && !str_contains($billingoKey, 'teszt_api') && strlen($billingoKey) > 10;
        $billingoMasked = $billingoKey ? substr($billingoKey, 0, 4) . '••••••••' . substr($billingoKey, -4) : null;

        // Mail
        $mailMailer = config('mail.default', 'smtp');
        $mailHost   = config('mail.mailers.smtp.host', '');
        $mailUser   = config('mail.mailers.smtp.username', '');
        $mailFrom   = config('mail.from.address', '');
        $mailConfigured = $mailHost && $mailUser && !str_contains($mailHost, 'example');

        return view('beallitasok.index', compact(
            'stripeConfigured', 'stripeTestMode', 'stripeMasked', 'stripeKey',
            'billingoConfigured', 'billingoMasked', 'billingoBlockId',
            'mailMailer', 'mailHost', 'mailUser', 'mailFrom', 'mailConfigured'
        ));
    }
}
