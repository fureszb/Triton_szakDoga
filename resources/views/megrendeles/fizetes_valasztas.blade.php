@extends('ujlayout')
@section('content')
@php $user = Auth::user(); @endphp

<style>
.fv-backdrop {
    position: fixed; inset: 0;
    background: rgba(15,18,9,0.55);
    backdrop-filter: blur(3px);
    z-index: 1000;
    display: flex; align-items: center; justify-content: center;
    padding: 16px;
}
.fv-modal {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.22);
    width: 100%; max-width: 520px;
    overflow: hidden;
    animation: fv-in 0.25s cubic-bezier(.34,1.56,.64,1);
}
@keyframes fv-in {
    from { opacity:0; transform: scale(0.92) translateY(20px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
}
.fv-header {
    background: linear-gradient(135deg, #0f1209 0%, #1a2010 100%);
    padding: 22px 26px 18px;
    position: relative;
}
.fv-header-top {
    display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;
}
.fv-title { font-size: 17px; font-weight: 800; color: #e8d5b7; }
.fv-close {
    width: 30px; height: 30px; border-radius: 8px;
    background: rgba(255,255,255,0.08); border: none; cursor: pointer;
    color: #8a9478; font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    text-decoration: none; transition: background 0.15s;
}
.fv-close:hover { background: rgba(255,255,255,0.15); color: #e8d5b7; }
.fv-amount-row {
    display: flex; align-items: baseline; gap: 8px; margin-top: 4px;
}
.fv-amount { font-size: 28px; font-weight: 900; color: #4ade80; }
.fv-amount-label { font-size: 12px; color: #8a9478; }
.fv-megr { font-size: 11px; color: #8a9478; margin-top: 4px; }

.fv-body { padding: 22px 26px 26px; }
.fv-section-title {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.8px; color: #94a3b8; margin-bottom: 12px;
}

/* Fizetési módok */
.fv-methods { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
.fv-method {
    border: 2px solid #e8edf2; border-radius: 12px; padding: 14px 16px;
    cursor: pointer; transition: all 0.18s; background: #fff;
    display: flex; align-items: center; gap: 14px;
}
.fv-method:hover { border-color: #c9a97a; background: rgba(201,169,122,0.04); }
.fv-method.selected { border-color: #c9a97a; background: rgba(201,169,122,0.07); }
.fv-method.stripe-method:hover,
.fv-method.stripe-method.selected { border-color: #635bff; background: rgba(99,91,255,0.05); }
.fv-method-icon {
    width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 18px;
}
.fv-method-icon.bank   { background: rgba(201,169,122,0.15); color: #a07848; }
.fv-method-icon.stripe { background: rgba(99,91,255,0.12);   color: #635bff; }
.fv-method-body { flex: 1; }
.fv-method-name { font-size: 14px; font-weight: 700; color: #1e293b; }
.fv-method-desc { font-size: 11px; color: #64748b; margin-top: 2px; line-height: 1.4; }
.fv-method-badges { display: flex; gap: 5px; margin-top: 6px; flex-wrap: wrap; }
.fv-badge {
    font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    padding: 2px 7px; border-radius: 4px;
}
.fv-badge.purple { background: rgba(99,91,255,0.1); color: #635bff; }
.fv-badge.gray   { background: #f1f5f9; color: #64748b; }
.fv-badge.green  { background: rgba(34,197,94,0.1); color: #16a34a; }
.fv-method-radio {
    width: 18px; height: 18px; border-radius: 50%; border: 2px solid #e2e8f0;
    flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    transition: all 0.15s;
}
.fv-method.selected .fv-method-radio { border-color: #c9a97a; background: #c9a97a; }
.fv-method.stripe-method.selected .fv-method-radio { border-color: #635bff; background: #635bff; }
.fv-method-radio::after {
    content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff; display: none;
}
.fv-method.selected .fv-method-radio::after { display: block; }

/* Banki átutalás panel */
.fv-bank-panel {
    display: none;
    background: #f8fafc; border-radius: 10px; border: 1px solid #e8edf2;
    padding: 16px; margin-bottom: 16px;
}
.fv-bank-panel.open { display: block; }
.fv-bank-info { margin-bottom: 14px; }
.fv-bank-row {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 12px;
}
.fv-bank-row:last-child { border-bottom: none; }
.fv-bank-row-label { color: #94a3b8; font-weight: 600; min-width: 110px; }
.fv-bank-row-val { color: #1e293b; font-weight: 700; font-family: monospace; font-size: 13px; }
.fv-input-label { font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
.fv-input {
    width: 100%; padding: 9px 12px; border-radius: 8px;
    border: 1.5px solid #e2e8f0; font-size: 13px; color: #1e293b;
    background: #fff; box-sizing: border-box;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.fv-input:focus { outline: none; border-color: #c9a97a; box-shadow: 0 0 0 3px rgba(201,169,122,0.12); }
.fv-input-hint { font-size: 10px; color: #94a3b8; margin-top: 4px; }

/* Submit gombok */
.fv-submit-stripe {
    width: 100%; padding: 13px; border-radius: 10px; border: none; cursor: pointer;
    font-size: 14px; font-weight: 700; letter-spacing: 0.3px;
    background: linear-gradient(135deg, #635bff 0%, #7c74ff 100%);
    color: #fff; display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: opacity 0.15s, transform 0.1s; box-shadow: 0 4px 14px rgba(99,91,255,0.3);
}
.fv-submit-stripe:hover { opacity: 0.92; transform: translateY(-1px); }
.fv-submit-bank {
    width: 100%; padding: 13px; border-radius: 10px; border: none; cursor: pointer;
    font-size: 14px; font-weight: 700;
    background: linear-gradient(135deg, #c9a97a 0%, #a07848 100%);
    color: #fff; display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: opacity 0.15s, transform 0.1s; box-shadow: 0 4px 14px rgba(160,120,72,0.3);
}
.fv-submit-bank:hover { opacity: 0.92; transform: translateY(-1px); }
.fv-disabled { opacity: 0.4; pointer-events: none; }

.fv-secure {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    font-size: 10px; color: #94a3b8; margin-top: 14px;
}
</style>

<div class="fv-backdrop">
    <div class="fv-modal">

        {{-- Fejléc --}}
        <div class="fv-header">
            <div class="fv-header-top">
                <div class="fv-title"><i class="fas fa-credit-card" style="color:#c9a97a;margin-right:8px;"></i>Fizetési mód választása</div>
                <a href="{{ url()->previous() }}" class="fv-close" title="Bezárás">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <div class="fv-amount-row">
                <div class="fv-amount">{{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft</div>
                <div class="fv-amount-label">fizetendő</div>
            </div>
            <div class="fv-megr">
                <i class="fas fa-clipboard-list" style="margin-right:4px;"></i>
                {{ $megrendeles->megrendeles_nev }}
                &nbsp;·&nbsp; #{{ str_pad($megrendeles->id, 5, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        {{-- Tartalom --}}
        <div class="fv-body">

            @if(session('error'))
                <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:8px;padding:10px 14px;font-size:12px;color:#dc2626;margin-bottom:16px;">
                    <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i>{{ session('error') }}
                </div>
            @endif

            <div class="fv-section-title">Válassz fizetési módot</div>

            <div class="fv-methods">

                {{-- Online fizetés (Stripe) --}}
                @if($stripeErheto)
                <div class="fv-method stripe-method selected" id="method-stripe" onclick="selectMethod('stripe')">
                    <div class="fv-method-icon stripe"><i class="fas fa-bolt"></i></div>
                    <div class="fv-method-body">
                        <div class="fv-method-name">Online fizetés</div>
                        <div class="fv-method-desc">Bankkártyával, Google Pay vagy Apple Pay segítségével – azonnal jóváírva</div>
                        <div class="fv-method-badges">
                            <span class="fv-badge purple">Stripe</span>
                            <span class="fv-badge purple">Google Pay</span>
                            <span class="fv-badge purple">Apple Pay</span>
                            <span class="fv-badge green">Azonnali</span>
                        </div>
                    </div>
                    <div class="fv-method-radio"></div>
                </div>
                @endif

                {{-- Banki átutalás --}}
                <div class="fv-method {{ !$stripeErheto ? 'selected' : '' }}" id="method-bank" onclick="selectMethod('bank')">
                    <div class="fv-method-icon bank"><i class="fas fa-university"></i></div>
                    <div class="fv-method-body">
                        <div class="fv-method-name">Banki átutalás</div>
                        <div class="fv-method-desc">Utalj a megadott számlaszámra, a közleményt kötelező feltüntetni</div>
                        <div class="fv-method-badges">
                            <span class="fv-badge gray">1–2 munkanap</span>
                            <span class="fv-badge gray">Manuális jóváhagyás</span>
                        </div>
                    </div>
                    <div class="fv-method-radio"></div>
                </div>

            </div>

            {{-- Stripe form --}}
            @if($stripeErheto)
            <form id="form-stripe" method="POST" action="{{ route('payment.stripe', $megrendeles->id) }}">
                @csrf
                <button type="submit" class="fv-submit-stripe">
                    <i class="fas fa-lock"></i>
                    Tovább a biztonságos fizetéshez
                </button>
            </form>
            @endif

            {{-- Banki átutalás panel + form --}}
            <div class="fv-bank-panel {{ !$stripeErheto ? 'open' : '' }}" id="bank-panel">
                <div class="fv-bank-info">
                    <div class="fv-bank-row">
                        <span class="fv-bank-row-label"><i class="fas fa-university" style="margin-right:5px;color:#c9a97a;"></i>Számlaszám</span>
                        <span class="fv-bank-row-val">12345678-12345678-12345678</span>
                    </div>
                    <div class="fv-bank-row">
                        <span class="fv-bank-row-label"><i class="fas fa-building" style="margin-right:5px;color:#c9a97a;"></i>Kedvezményezett</span>
                        <span class="fv-bank-row-val">TRITON SECURITY KFT.</span>
                    </div>
                    <div class="fv-bank-row">
                        <span class="fv-bank-row-label"><i class="fas fa-coins" style="margin-right:5px;color:#c9a97a;"></i>Összeg</span>
                        <span class="fv-bank-row-val" style="color:#16a34a;">{{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} HUF</span>
                    </div>
                </div>
                <form id="form-bank" method="POST" action="{{ route('payment.bank_transfer', $megrendeles->id) }}">
                    @csrf
                    <div class="fv-input-label">Közlemény (azonosítód az utaláshoz) *</div>
                    <input type="text" name="kozlemeny" class="fv-input"
                           placeholder="pl. Kovács János – MR#{{ str_pad($megrendeles->id, 5, '0', STR_PAD_LEFT) }}"
                           value="{{ old('kozlemeny', ($user->nev ?? '') . ' – MR#' . str_pad($megrendeles->id, 5, '0', STR_PAD_LEFT)) }}"
                           required>
                    <div class="fv-input-hint">
                        <i class="fas fa-info-circle"></i>
                        Ezt a szöveget tüntesd fel az átutalás közleményénél – így tudjuk beazonosítani a befizetésedet.
                    </div>
                    @error('kozlemeny')
                        <div style="color:#dc2626;font-size:11px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="fv-submit-bank" style="margin-top:14px;">
                        <i class="fas fa-paper-plane"></i>
                        Átutalást bejelentem
                    </button>
                </form>
            </div>

            <div class="fv-secure">
                <i class="fas fa-shield-alt"></i>
                Biztonságos kapcsolat · TRITON SECURITY KFT.
            </div>

        </div>
    </div>
</div>

<script>
const stripeAvailable = {{ $stripeErheto ? 'true' : 'false' }};
let selected = stripeAvailable ? 'stripe' : 'bank';

function selectMethod(method) {
    selected = method;

    document.querySelectorAll('.fv-method').forEach(el => el.classList.remove('selected'));
    document.getElementById('method-' + method)?.classList.add('selected');

    const bankPanel = document.getElementById('bank-panel');
    const formStripe = document.getElementById('form-stripe');

    if (method === 'bank') {
        bankPanel.classList.add('open');
        if (formStripe) formStripe.style.display = 'none';
    } else {
        bankPanel.classList.remove('open');
        if (formStripe) formStripe.style.display = 'block';
    }
}

// Kezdeti állapot
selectMethod(selected);
</script>

@endsection
