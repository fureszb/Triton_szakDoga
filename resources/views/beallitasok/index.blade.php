@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<style>
.bs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
    margin-top: 4px;
}
.bs-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    overflow: hidden;
}
.bs-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(90deg, rgba(201,169,122,0.07) 0%, rgba(201,169,122,0.01) 100%);
}
.bs-card-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
}
.bs-card-icon.ok  { background: rgba(34,197,94,0.12);  color: #16a34a; }
.bs-card-icon.err { background: rgba(239,68,68,0.1);   color: #dc2626; }
.bs-card-icon.warn{ background: rgba(249,115,22,0.1);  color: #ea580c; }
.bs-card-icon.def { background: rgba(201,169,122,0.15);color: #a07848; }
.bs-card-title  { font-size: 14px; font-weight: 700; color: #1e293b; }
.bs-card-sub    { font-size: 11px; color: #94a3b8; margin-top: 1px; }
.bs-status-badge {
    margin-left: auto;
    font-size: 11px; font-weight: 700;
    padding: 4px 11px; border-radius: 6px;
    display: inline-flex; align-items: center; gap: 5px;
}
.bs-status-badge.ok   { background: rgba(34,197,94,0.1);  color: #16a34a; border: 1px solid rgba(34,197,94,0.25); }
.bs-status-badge.err  { background: rgba(239,68,68,0.1);  color: #dc2626; border: 1px solid rgba(239,68,68,0.2); }
.bs-status-badge.warn { background: rgba(249,115,22,0.1); color: #ea580c; border: 1px solid rgba(249,115,22,0.2); }
.bs-status-badge.test { background: rgba(59,130,246,0.1); color: #2563eb; border: 1px solid rgba(59,130,246,0.2); }

.bs-body { padding: 6px 0; }
.bs-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 20px;
    border-bottom: 1px solid #f8fafc;
}
.bs-row:last-child { border-bottom: none; }
.bs-row-label {
    font-size: 11px; font-weight: 600; text-transform: uppercase;
    letter-spacing: 0.6px; color: #94a3b8; min-width: 120px;
    flex-shrink: 0; display: flex; align-items: center; gap: 4px; padding-top: 1px;
}
.bs-row-label i { color: #c9a97a; font-size: 10px; }
.bs-row-val { font-size: 12px; font-weight: 500; color: #334155; flex: 1; word-break: break-all; }
.bs-row-val.mono { font-family: 'Courier New', monospace; background: #f8fafc; padding: 3px 8px; border-radius: 5px; }

.bs-env-hint {
    margin: 0 20px 16px;
    padding: 12px 14px;
    background: rgba(201,169,122,0.06);
    border: 1px solid rgba(201,169,122,0.18);
    border-radius: 8px;
    font-size: 11px;
    color: #64748b;
    line-height: 1.6;
}
.bs-env-hint code {
    background: rgba(201,169,122,0.12);
    color: #a07848;
    padding: 1px 5px;
    border-radius: 4px;
    font-size: 11px;
    font-family: monospace;
}

.bs-full { grid-column: 1 / -1; }
</style>

<div class="page-header">
    <h1><i class="fas fa-sliders-h"></i> Beállítások</h1>
</div>

<div class="bs-grid">

    {{-- ── STRIPE ── --}}
    <div class="bs-card">
        <div class="bs-card-header">
            <div class="bs-card-icon {{ $stripeConfigured && !$stripeTestMode ? 'ok' : ($stripeConfigured ? 'test' : 'err') }}">
                <i class="fas fa-credit-card"></i>
            </div>
            <div>
                <div class="bs-card-title">Stripe – Online fizetés</div>
                <div class="bs-card-sub">Bankkártyás fizetés Stripe Checkout-on át</div>
            </div>
            <span class="bs-status-badge {{ $stripeConfigured && !$stripeTestMode ? 'ok' : ($stripeConfigured ? 'test' : 'err') }}">
                @if($stripeConfigured && !$stripeTestMode)
                    <i class="fas fa-check"></i> Éles mód
                @elseif($stripeConfigured)
                    <i class="fas fa-flask"></i> Teszt mód
                @else
                    <i class="fas fa-times"></i> Nincs beállítva
                @endif
            </span>
        </div>
        <div class="bs-body">
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-key"></i> Publikus kulcs</div>
                <div class="bs-row-val mono">{{ $stripeKey ? (substr($stripeKey,0,12).'••••••••••') : '— nincs beállítva —' }}</div>
            </div>
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-lock"></i> Titkos kulcs</div>
                <div class="bs-row-val mono">{{ $stripeMasked ?? '— nincs beállítva —' }}</div>
            </div>
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-info-circle"></i> Mód</div>
                <div class="bs-row-val">
                    @if($stripeTestMode)
                        <span style="color:#2563eb;font-weight:600;"><i class="fas fa-flask"></i> Teszt (4242 4242 4242 4242)</span>
                    @elseif($stripeConfigured)
                        <span style="color:#16a34a;font-weight:600;"><i class="fas fa-check-circle"></i> Éles</span>
                    @else
                        <span style="color:#dc2626;">Nem konfigurált</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="bs-env-hint">
            <strong>.env beállítás:</strong><br>
            <code>STRIPE_KEY=pk_test_...</code><br>
            <code>STRIPE_SECRET=sk_test_...</code><br>
            <code>STRIPE_WEBHOOK_SECRET=whsec_...</code><br>
            <br>Teszt kártya: <code>4242 4242 4242 4242</code>, bármilyen jövőbeli dátum, bármilyen CVC
        </div>
    </div>

    {{-- ── BILLINGO ── --}}
    <div class="bs-card">
        <div class="bs-card-header">
            <div class="bs-card-icon {{ $billingoConfigured ? 'ok' : 'err' }}">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <div class="bs-card-title">Billingo – Számlázás</div>
                <div class="bs-card-sub">Automatikus számla kiállítás Billingo API-n át</div>
            </div>
            <span class="bs-status-badge {{ $billingoConfigured ? 'ok' : 'err' }}">
                @if($billingoConfigured)
                    <i class="fas fa-check"></i> Beállítva
                @else
                    <i class="fas fa-times"></i> Nincs beállítva
                @endif
            </span>
        </div>
        <div class="bs-body">
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-key"></i> API kulcs</div>
                <div class="bs-row-val mono">{{ $billingoMasked ?? '— nincs beállítva —' }}</div>
            </div>
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-layer-group"></i> Tömbszám ID</div>
                <div class="bs-row-val mono">{{ $billingoBlockId ?? '— nincs beállítva —' }}</div>
            </div>
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-info-circle"></i> Megjegyzés</div>
                <div class="bs-row-val" style="color:#64748b;font-size:11px;">
                    Számla kiállítás opcionális – ha nincs beállítva, a fizetés és emlékeztető akkor is működik.
                </div>
            </div>
        </div>
        <div class="bs-env-hint">
            <strong>.env beállítás:</strong><br>
            <code>BILLINGO_API_KEY=...</code><br>
            <code>BILLINGO_BLOCK_ID=...</code><br>
            <br>API kulcsot a <a href="https://app.billingo.hu/api-key" target="_blank" style="color:#a07848;">Billingo</a> fiókban lehet generálni (Beállítások → API).
        </div>
    </div>

    {{-- ── EMAIL ── --}}
    <div class="bs-card">
        <div class="bs-card-header">
            <div class="bs-card-icon {{ $mailConfigured ? 'ok' : 'warn' }}">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <div class="bs-card-title">Email – Értesítések</div>
                <div class="bs-card-sub">Fizetési bizonylat, emlékeztető, számlaértesítő</div>
            </div>
            <span class="bs-status-badge {{ $mailConfigured ? 'ok' : 'warn' }}">
                @if($mailConfigured)
                    <i class="fas fa-check"></i> Beállítva
                @else
                    <i class="fas fa-exclamation-triangle"></i> Hiányos
                @endif
            </span>
        </div>
        <div class="bs-body">
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-server"></i> SMTP host</div>
                <div class="bs-row-val mono">{{ $mailHost ?: '— nincs beállítva —' }}</div>
            </div>
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-user"></i> Felhasználó</div>
                <div class="bs-row-val mono">{{ $mailUser ? (substr($mailUser,0,4).'••••') : '— nincs beállítva —' }}</div>
            </div>
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-at"></i> Feladó cím</div>
                <div class="bs-row-val">{{ $mailFrom ?: '—' }}</div>
            </div>
        </div>
        <div class="bs-env-hint">
            <strong>.env beállítás (Mailtrap teszt):</strong><br>
            <code>MAIL_MAILER=smtp</code><br>
            <code>MAIL_HOST=sandbox.smtp.mailtrap.io</code><br>
            <code>MAIL_PORT=2525</code><br>
            <code>MAIL_USERNAME=...</code><br>
            <code>MAIL_PASSWORD=...</code><br>
            <code>MAIL_FROM_ADDRESS=noreply@tritonsecurity.hu</code>
        </div>
    </div>

    {{-- ── EMLÉKEZTETŐ SCHEDULER ── --}}
    <div class="bs-card">
        <div class="bs-card-header">
            <div class="bs-card-icon ok">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="bs-card-title">Fizetési emlékeztető</div>
                <div class="bs-card-sub">Automatikus emailküldés határidő előtt</div>
            </div>
            <span class="bs-status-badge ok">
                <i class="fas fa-check"></i> Kész
            </span>
        </div>
        <div class="bs-body">
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-calendar-alt"></i> Ütemezés</div>
                <div class="bs-row-val" style="font-weight:600;">Minden nap 09:00</div>
            </div>
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-bell"></i> Mikor küld?</div>
                <div class="bs-row-val">3 nappal és 1 nappal a határidő előtt</div>
            </div>
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-terminal"></i> Parancs</div>
                <div class="bs-row-val mono">php artisan fizetesi:emlekeztetok</div>
            </div>
        </div>
        <div class="bs-env-hint">
            <strong>Laravel Scheduler aktiválása (Windows):</strong><br>
            Task Scheduler → <code>php artisan schedule:run</code> percenként<br>
            <br>
            <strong>Azonnali tesztelés:</strong><br>
            <code>php artisan fizetesi:emlekeztetok</code>
        </div>
    </div>

    {{-- ── STRIPE WEBHOOK ── --}}
    <div class="bs-card bs-full" style="max-width:720px;">
        <div class="bs-card-header">
            <div class="bs-card-icon def">
                <i class="fas fa-plug"></i>
            </div>
            <div>
                <div class="bs-card-title">Stripe Webhook beállítás</div>
                <div class="bs-card-sub">Automatikus fizetésmegerősítés Stripe visszahíváson át</div>
            </div>
        </div>
        <div class="bs-body">
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-link"></i> Webhook URL</div>
                <div class="bs-row-val mono">{{ url('/stripe/webhook') }}</div>
            </div>
            <div class="bs-row">
                <div class="bs-row-label"><i class="fas fa-shield-alt"></i> Esemény</div>
                <div class="bs-row-val"><code style="background:#f1f5f9;padding:2px 7px;border-radius:4px;font-size:11px;">checkout.session.completed</code></div>
            </div>
        </div>
        <div class="bs-env-hint">
            <strong>Helyi fejlesztéshez (Stripe CLI):</strong><br>
            <code>stripe listen --forward-to {{ url('/stripe/webhook') }}</code><br>
            <br>
            <strong>Éles környezethez:</strong> A Stripe Dashboard → Developers → Webhooks oldalon add hozzá a fenti URL-t, és másold be a <code>STRIPE_WEBHOOK_SECRET</code>-et a .env fájlba.
        </div>
    </div>

</div>

@endsection
