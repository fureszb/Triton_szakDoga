@extends('ujlayout')

@section('content')

<div style="max-width:560px;margin:60px auto;text-align:center;">
    @if($atutalas ?? false)
    {{-- Átutalás bejelentve ikon --}}
    <div style="width:80px;height:80px;border-radius:50%;background:rgba(201,169,122,0.12);border:2px solid rgba(201,169,122,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:36px;color:#a07848;">
        <i class="fas fa-university"></i>
    </div>
    <h1 style="font-size:24px;font-weight:800;color:#1e293b;margin:0 0 8px;">Átutalás bejelentve!</h1>
    <p style="font-size:14px;color:#64748b;margin:0 0 20px;line-height:1.7;">
        Köszönjük! Az átutalási szándékodat rögzítettük.<br>
        Kérjük utald át az összeget az alábbi adatokra, a megadott közleménnyel.
    </p>
    {{-- Átutalási adatok --}}
    <div style="background:rgba(201,169,122,0.06);border:1px solid rgba(201,169,122,0.25);border-radius:12px;padding:16px 20px;margin-bottom:20px;text-align:left;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#a07848;margin-bottom:12px;">Utalási adatok</div>
        <div style="display:flex;padding:8px 0;border-bottom:1px solid rgba(201,169,122,0.15);gap:12px;">
            <span style="font-size:11px;font-weight:600;color:#94a3b8;min-width:120px;">Számlaszám</span>
            <span style="font-size:13px;font-weight:700;color:#1e293b;font-family:monospace;">12345678-12345678-12345678</span>
        </div>
        <div style="display:flex;padding:8px 0;border-bottom:1px solid rgba(201,169,122,0.15);gap:12px;">
            <span style="font-size:11px;font-weight:600;color:#94a3b8;min-width:120px;">Összeg</span>
            <span style="font-size:14px;font-weight:800;color:#16a34a;">{{ number_format($osszeg ?? 0, 0, ',', ' ') }} HUF</span>
        </div>
        <div style="display:flex;padding:8px 0;gap:12px;">
            <span style="font-size:11px;font-weight:600;color:#94a3b8;min-width:120px;">Közlemény</span>
            <span style="font-size:13px;font-weight:700;color:#635bff;font-family:monospace;">{{ $kozlemeny ?? '' }}</span>
        </div>
    </div>
    <p style="font-size:12px;color:#94a3b8;margin:0 0 24px;">
        <i class="fas fa-info-circle" style="margin-right:4px;"></i>
        A fizetés manuálisan kerül jóváírásra az utalás beérkezése után (1–2 munkanap).
    </p>
    @else
    {{-- Sikeres online fizetés ikon --}}
    <div style="width:80px;height:80px;border-radius:50%;background:rgba(34,197,94,0.12);border:2px solid rgba(34,197,94,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:36px;color:#16a34a;">
        <i class="fas fa-check-circle"></i>
    </div>
    <h1 style="font-size:24px;font-weight:800;color:#1e293b;margin:0 0 8px;">Sikeres fizetés!</h1>
    <p style="font-size:14px;color:#64748b;margin:0 0 32px;line-height:1.7;">
        Köszönjük! A fizetés sikeresen megérkezett.<br>
        Visszaigazolót küldtünk emailben.
    </p>
    @endif

    {{-- Összefoglaló kártya --}}
    <div style="background:#fff;border-radius:14px;border:1px solid #e8edf2;box-shadow:0 2px 8px rgba(0,0,0,0.06);padding:0;overflow:hidden;margin-bottom:28px;text-align:left;">
        <div style="padding:14px 20px;background:linear-gradient(90deg,rgba(201,169,122,0.07) 0%,rgba(201,169,122,0.01) 100%);border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px;">
            <div style="width:30px;height:30px;border-radius:7px;background:rgba(201,169,122,0.15);color:#a07848;display:flex;align-items:center;justify-content:center;font-size:12px;">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <span style="font-size:12px;font-weight:700;color:#1e293b;">Megrendelés összefoglalója</span>
        </div>
        <div style="padding:0;">
            <div style="display:flex;align-items:center;padding:12px 20px;border-bottom:1px solid #f8fafc;gap:12px;">
                <span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;min-width:120px;">Megrendelés</span>
                <span style="font-size:13px;color:#334155;font-weight:600;">
                    #{{ str_pad($megrendeles->id, 5, '0', STR_PAD_LEFT) }} – {{ $megrendeles->megrendeles_nev }}
                </span>
            </div>
            @if($megrendeles->szamla?->brutto_osszeg)
            <div style="display:flex;align-items:center;padding:12px 20px;border-bottom:1px solid #f8fafc;gap:12px;">
                <span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;min-width:120px;">Fizetett összeg</span>
                <span style="font-size:16px;font-weight:700;color:#a07848;">{{ number_format($megrendeles->szamla->brutto_osszeg, 0, ',', ' ') }} Ft</span>
            </div>
            @endif
            @if($megrendeles->szamla?->billingo_szam)
            <div style="display:flex;align-items:center;padding:12px 20px;gap:12px;">
                <span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;min-width:120px;">Számlaszám</span>
                <span style="font-size:13px;color:#334155;font-weight:600;">{{ $megrendeles->szamla->billingo_szam }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Gombok --}}
    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
        @if(auth()->user()->role === 'Ugyfel')
            <a href="{{ route('ugyfel.megrendelesek') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Vissza a megrendelésekhez
            </a>
        @else
            <a href="{{ route('megrendeles.show', $megrendeles->id) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Megrendelés megtekintése
            </a>
        @endif
        @if($megrendeles->szamla?->billingo_pdf_url)
            <a href="{{ route('szamlak.download', $megrendeles->szamla->szamla_id) }}" class="btn-save" style="text-decoration:none;">
                <i class="fas fa-file-pdf"></i> Számla letöltése
            </a>
        @endif
    </div>
</div>

@endsection
