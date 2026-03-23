@php
    $fontDir = str_replace('\\', '/', base_path('vendor/dompdf/dompdf/lib/fonts'));
@endphp
<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
    @font-face {
        font-family: 'DejaVu Sans';
        font-style: normal;
        font-weight: normal;
        src: url('file:///{{ $fontDir }}/DejaVuSans.ttf') format('truetype');
    }
    @font-face {
        font-family: 'DejaVu Sans';
        font-style: normal;
        font-weight: bold;
        src: url('file:///{{ $fontDir }}/DejaVuSans-Bold.ttf') format('truetype');
    }
    @font-face {
        font-family: 'DejaVu Sans';
        font-style: normal;
        font-weight: 900;
        src: url('file:///{{ $fontDir }}/DejaVuSans-Bold.ttf') format('truetype');
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }

    @page {
        margin: 0;
        size: A4 portrait;
    }

    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 9pt;
        color: #1a1a1a;
        background: #fff;
    }

    /* ── Fejléc sáv ────────────────────────────────── */
    .header {
        background: #0f1209;
        padding: 28px 36px 22px;
        position: relative;
        overflow: hidden;
    }
    .header-inner {
        display: table;
        width: 100%;
    }
    .header-left {
        display: table-cell;
        vertical-align: middle;
        width: 55%;
    }
    .header-right {
        display: table-cell;
        vertical-align: middle;
        text-align: right;
        width: 45%;
    }

    /* Logo */
    .logo-row {
        display: table;
        margin-bottom: 6px;
    }
    .logo-icon {
        display: table-cell;
        vertical-align: middle;
        width: 42px; height: 42px;
        background: linear-gradient(135deg, #16a34a, #c9a97a);
        border-radius: 10px;
        text-align: center;
        line-height: 42px;
        font-size: 20px;
        color: #fff;
        font-weight: 900;
        margin-right: 10px;
    }
    .logo-text {
        display: table-cell;
        vertical-align: middle;
        padding-left: 10px;
    }
    .logo-name {
        font-size: 15pt;
        font-weight: 900;
        color: #e8d5b7;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .logo-name span { color: #4ade80; }
    .logo-tagline {
        font-size: 7.5pt;
        color: #8a9478;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    /* Dokumentum típus */
    .doc-type-badge {
        display: inline-block;
        background: #4ade80;
        color: #0a0d08;
        font-size: 8.5pt;
        font-weight: 900;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 4px;
        margin-top: 8px;
    }
    .doc-type-badge.teszt {
        background: #f59e0b;
    }
    .doc-type-badge.dijbekero {
        background: #c9a97a;
    }

    /* Számla szám a fejlécben */
    .header-szam {
        color: #4ade80;
        font-size: 20pt;
        font-weight: 900;
        letter-spacing: 0.5px;
    }
    .header-szam-label {
        color: #8a9478;
        font-size: 7.5pt;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 2px;
    }

    /* ── Teszt vízjel ──────────────────────────────── */
    .watermark {
        position: fixed;
        top: 38%;
        left: 15%;
        font-size: 70pt;
        font-weight: 900;
        color: rgba(245,158,11,0.08);
        transform: rotate(-35deg);
        letter-spacing: 8px;
        z-index: -1;
        text-transform: uppercase;
    }

    /* ── Zöld sáv a fejléc alatt ───────────────────── */
    .accent-bar {
        height: 4px;
        background: linear-gradient(90deg, #16a34a, #4ade80, #c9a97a, #e8d5b7);
    }

    /* ── Tartalom ──────────────────────────────────── */
    .content {
        padding: 28px 36px;
    }

    /* Cím + dátumok sáv */
    .meta-row {
        display: table;
        width: 100%;
        margin-bottom: 22px;
        border-bottom: 1px solid #e8edf2;
        padding-bottom: 18px;
    }
    .meta-col {
        display: table-cell;
        vertical-align: top;
        width: 25%;
    }
    .meta-label {
        font-size: 7pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #94a3b8;
        margin-bottom: 3px;
    }
    .meta-value {
        font-size: 9pt;
        font-weight: 700;
        color: #1e293b;
    }
    .meta-value.green { color: #16a34a; }

    /* Kiállító + Vevő */
    .parties-row {
        display: table;
        width: 100%;
        margin-bottom: 22px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e8edf2;
        overflow: hidden;
    }
    .party-col {
        display: table-cell;
        vertical-align: top;
        width: 50%;
        padding: 16px 18px;
    }
    .party-col:first-child {
        border-right: 1px solid #e8edf2;
    }
    .party-role {
        font-size: 7pt;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #16a34a;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .party-name {
        font-size: 11pt;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 5px;
    }
    .party-line {
        font-size: 8.5pt;
        color: #475569;
        line-height: 1.7;
    }
    .party-line span {
        color: #94a3b8;
        font-weight: 600;
        font-size: 7.5pt;
    }

    /* ── Tételek táblázat ──────────────────────────── */
    .tetel-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 8.5pt;
    }
    .tetel-table thead tr {
        background: #0f1209;
        color: #8a9478;
    }
    .tetel-table thead th {
        padding: 8px 10px;
        font-size: 7pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        text-align: left;
    }
    .tetel-table thead th:last-child,
    .tetel-table thead th:nth-last-child(2),
    .tetel-table thead th:nth-last-child(3) {
        text-align: right;
    }
    .tetel-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
    }
    .tetel-table tbody tr:nth-child(even) {
        background: #f8fafc;
    }
    .tetel-table tbody td {
        padding: 9px 10px;
        color: #334155;
        vertical-align: middle;
    }
    .tetel-table tbody td:last-child,
    .tetel-table tbody td:nth-last-child(2),
    .tetel-table tbody td:nth-last-child(3) {
        text-align: right;
    }
    .tetel-badge {
        display: inline-block;
        font-size: 6.5pt;
        font-weight: 700;
        border-radius: 3px;
        padding: 1px 5px;
        text-transform: uppercase;
    }
    .tetel-badge.anyag    { background: rgba(201,169,122,0.15); color: #a07848; }
    .tetel-badge.munkaora { background: rgba(59,130,246,0.1);   color: #2563eb; }
    .tetel-badge.egyeb    { background: rgba(100,116,139,0.1);  color: #64748b; }

    /* Összesítő */
    .osszeg-table {
        width: 52%;
        margin-left: 48%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }
    .osszeg-table td {
        padding: 6px 10px;
        font-size: 8.5pt;
        border-bottom: 1px solid #f1f5f9;
    }
    .osszeg-table td:first-child { color: #64748b; }
    .osszeg-table td:last-child  { text-align: right; font-weight: 600; color: #334155; }
    .osszeg-table .total-row td {
        background: #0f1209;
        color: #e8d5b7;
        font-size: 10.5pt;
        font-weight: 900;
        border-bottom: none;
        padding: 10px 10px;
    }
    .osszeg-table .total-row td:last-child { color: #4ade80; }

    /* ── Megjegyzés ────────────────────────────────── */
    .megjegyzes-box {
        background: rgba(74,222,128,0.05);
        border: 1px solid rgba(74,222,128,0.2);
        border-left: 3px solid #4ade80;
        border-radius: 0 6px 6px 0;
        padding: 10px 14px;
        font-size: 8.5pt;
        color: #334155;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    .megjegyzes-label {
        font-size: 7pt;
        font-weight: 700;
        color: #16a34a;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 4px;
    }

    /* ── Fizetési infó ─────────────────────────────── */
    .fizetes-box {
        display: table;
        width: 100%;
        background: rgba(201,169,122,0.07);
        border: 1px solid rgba(201,169,122,0.25);
        border-radius: 8px;
        padding: 14px 18px;
        margin-bottom: 24px;
    }
    .fizetes-col {
        display: table-cell;
        vertical-align: middle;
        width: 33.3%;
    }
    .fizetes-label {
        font-size: 7pt;
        font-weight: 700;
        color: #8a9478;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 3px;
    }
    .fizetes-value {
        font-size: 9pt;
        font-weight: 700;
        color: #1e293b;
    }
    .fizetes-value.highlight { color: #16a34a; }

    /* ── Lábléc ────────────────────────────────────── */
    .footer {
        background: #0f1209;
        padding: 14px 36px;
        display: table;
        width: 100%;
        margin-top: 30px;
    }
    .footer-left  { display: table-cell; vertical-align: middle; width: 50%; }
    .footer-right { display: table-cell; vertical-align: middle; width: 50%; text-align: right; }
    .footer-text  { font-size: 7.5pt; color: #8a9478; line-height: 1.7; }
    .footer-brand { font-size: 9pt; font-weight: 900; color: #4ade80; letter-spacing: 1px; margin-bottom: 2px; }
    .footer-circuit {
        font-size: 7pt;
        color: rgba(74,222,128,0.25);
        letter-spacing: 2px;
        margin-top: 4px;
    }

    /* ── Állapot bélyegző ──────────────────────────── */
    .stamp {
        position: fixed;
        top: 135px;
        right: 36px;
        font-size: 28pt;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 3px;
        opacity: 0.12;
        transform: rotate(-20deg);
        border: 8px solid currentColor;
        padding: 4px 12px;
        border-radius: 8px;
    }
    .stamp.fizetve    { color: #16a34a; }
    .stamp.fuggoben   { color: #2563eb; }
    .stamp.kesedelmes { color: #dc2626; }
    .stamp.stornozva  { color: #64748b; }
</style>
</head>
<body>

@if($teszt ?? false)
<div class="watermark">TESZT</div>
@endif

@php
    $tipusLabel = match($szamla->szamla_tipus) {
        'dijbekero' => 'Díjbekérő',
        'storno'    => 'Stornó számla',
        default     => 'Számla',
    };
    $szamlaNum = $szamla->billingo_szam
        ?? 'TRITON-' . str_pad($szamla->szamla_id, 6, '0', STR_PAD_LEFT);
    $megrendeles = $szamla->megrendeles;
    $ugyfel      = $megrendeles?->ugyfel;
    $fizetesiMod = match($szamla->fizetesi_mod) {
        'stripe'         => 'Online bankkártya',
        'banki_atutalas' => 'Banki átutalás',
        'keszpenz'       => 'Készpénz',
        default          => $szamla->fizetesi_mod,
    };
@endphp

{{-- ─── FEJLÉC ─────────────────────────── --}}
<div class="header">
    <div class="header-inner">
        <div class="header-left">
            <div class="logo-row">
                <div class="logo-icon">T</div>
                <div class="logo-text">
                    <div class="logo-name">TRITON <span>SECURITY</span></div>
                    <div class="logo-tagline">Okos otthon automatizáció &amp; AI biztonsági rendszerek</div>
                </div>
            </div>
            <div>
                <span class="doc-type-badge {{ $szamla->szamla_tipus === 'dijbekero' ? 'dijbekero' : '' }} {{ ($teszt ?? false) ? 'teszt' : '' }}">
                    {{ ($teszt ?? false) ? 'TESZT – ' : '' }}{{ $tipusLabel }}
                </span>
            </div>
        </div>
        <div class="header-right">
            <div class="header-szam-label">Szám</div>
            <div class="header-szam">{{ $szamlaNum }}</div>
        </div>
    </div>
</div>
<div class="accent-bar"></div>

{{-- ─── TARTALOM ────────────────────────── --}}
<div class="content">

    {{-- Dátumok --}}
    <div class="meta-row">
        <div class="meta-col">
            <div class="meta-label">Kiállítás dátuma</div>
            <div class="meta-value">{{ $szamla->kiallitas_datum?->format('Y. m. d.') }}</div>
        </div>
        <div class="meta-col">
            <div class="meta-label">Teljesítés dátuma</div>
            <div class="meta-value">{{ $szamla->teljesites_datum?->format('Y. m. d.') }}</div>
        </div>
        <div class="meta-col">
            <div class="meta-label">Fizetési határidő</div>
            <div class="meta-value {{ $szamla->getKesedelmes() ? '' : 'green' }}">
                {{ $szamla->fizetesi_hatarido?->format('Y. m. d.') }}
            </div>
        </div>
        <div class="meta-col">
            <div class="meta-label">Deviza</div>
            <div class="meta-value">HUF</div>
        </div>
    </div>

    {{-- Kiállító + Vevő --}}
    <div class="parties-row">
        <div class="party-col">
            <div class="party-role">Kiállító</div>
            <div class="party-name">TRITON SECURITY KFT.</div>
            <div class="party-line"><span>Cím:</span> 1234 Budapest, Minta utca 1.</div>
            <div class="party-line"><span>Adószám:</span> 12345678-2-42</div>
            <div class="party-line"><span>Cégjsz.:</span> 01-09-123456</div>
            <div class="party-line"><span>Bankszámla:</span> 12345678-12345678-12345678</div>
            <div class="party-line"><span>Email:</span> info@tritonsecurity.hu</div>
            <div class="party-line"><span>Tel.:</span> +36 1 234 5678</div>
        </div>
        <div class="party-col">
            <div class="party-role">Vevő</div>
            <div class="party-name">
                {{ $ugyfel?->szamlazasi_nev ?: ($ugyfel?->nev ?? 'Ismeretlen ügyfél') }}
            </div>
            @if($ugyfel?->szamlazasi_cim)
            <div class="party-line"><span>Számlázási cím:</span> {{ $ugyfel->szamlazasi_cim }}</div>
            @endif
            @if($megrendeles?->varos)
            <div class="party-line"><span>Város:</span> {{ $megrendeles->varos->Irny_szam }} {{ $megrendeles->varos->nev }}</div>
            @endif
            @if($megrendeles?->utca_hazszam)
            <div class="party-line"><span>Utca/hsz.:</span> {{ $megrendeles->utca_hazszam }}</div>
            @endif
            @if($ugyfel?->adoszam)
            <div class="party-line"><span>Adószám:</span> {{ $ugyfel->adoszam }}</div>
            @endif
            @if($ugyfel?->email)
            <div class="party-line"><span>Email:</span> {{ $ugyfel->email }}</div>
            @endif
            @if($ugyfel?->telefonszam)
            <div class="party-line"><span>Tel.:</span> {{ $ugyfel->telefonszam }}</div>
            @endif
        </div>
    </div>

    {{-- Tételek --}}
    <table class="tetel-table">
        <thead>
            <tr>
                <th style="width:4%">#</th>
                <th style="width:38%">Megnevezés</th>
                <th style="width:8%">Típus</th>
                <th style="width:8%">Menny.</th>
                <th style="width:7%">Egység</th>
                <th style="width:11%">Egységár (nettó)</th>
                <th style="width:6%">ÁFA %</th>
                <th style="width:9%">Nettó</th>
                <th style="width:9%">Bruttó</th>
            </tr>
        </thead>
        <tbody>
            @foreach($szamla->tetelek as $i => $tetel)
            <tr>
                <td style="color:#94a3b8;">{{ $i + 1 }}</td>
                <td style="font-weight:600;">{{ $tetel->nev }}</td>
                <td>
                    <span class="tetel-badge {{ $tetel->tetel_tipus }}">
                        {{ match($tetel->tetel_tipus) { 'anyag' => 'Anyag', 'munkaora' => 'Munka', default => 'Egyéb' } }}
                    </span>
                </td>
                <td>{{ number_format($tetel->mennyiseg, 2, ',', ' ') }}</td>
                <td>{{ $tetel->mertekegyseg }}</td>
                <td>{{ number_format($tetel->egyseg_netto_ar, 0, ',', ' ') }} Ft</td>
                <td>{{ $tetel->afa_kulcs }}%</td>
                <td>{{ number_format($tetel->netto_osszeg, 0, ',', ' ') }} Ft</td>
                <td style="font-weight:700;">{{ number_format($tetel->brutto_osszeg, 0, ',', ' ') }} Ft</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Összesítő --}}
    <table class="osszeg-table">
        <tr>
            <td>Nettó összeg:</td>
            <td>{{ number_format($szamla->netto_osszeg, 0, ',', ' ') }} Ft</td>
        </tr>
        <tr>
            <td>ÁFA összeg:</td>
            <td>{{ number_format($szamla->afa_osszeg, 0, ',', ' ') }} Ft</td>
        </tr>
        <tr class="total-row">
            <td>Fizetendő összeg:</td>
            <td>{{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft</td>
        </tr>
    </table>

    {{-- Fizetési mód --}}
    <div class="fizetes-box">
        <div class="fizetes-col">
            <div class="fizetes-label">Fizetési mód</div>
            <div class="fizetes-value highlight">{{ $fizetesiMod }}</div>
        </div>
        @if($szamla->fizetesi_mod === 'banki_atutalas')
        <div class="fizetes-col">
            <div class="fizetes-label">Bankszámlaszám</div>
            <div class="fizetes-value">12345678-12345678-12345678</div>
        </div>
        <div class="fizetes-col">
            <div class="fizetes-label">Közlemény</div>
            <div class="fizetes-value">{{ $szamlaNum }}</div>
        </div>
        @else
        <div class="fizetes-col">
            <div class="fizetes-label">Megrendelés</div>
            <div class="fizetes-value">{{ $megrendeles?->megrendeles_nev ?? '#' . $szamla->megrendeles_id }}</div>
        </div>
        @endif
    </div>

    {{-- Megjegyzés --}}
    @if($szamla->megjegyzes)
    <div class="megjegyzes-box">
        <div class="megjegyzes-label">Megjegyzés</div>
        {{ $szamla->megjegyzes }}
    </div>
    @endif

    @if($teszt ?? false)
    <div style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.3);border-radius:6px;padding:10px 14px;font-size:8pt;color:#b45309;text-align:center;margin-bottom:8px;">
        [!] Ez egy TESZT példány – könyvelési célra nem érvényes.
    </div>
    @endif

</div>

{{-- ─── LÁBLÉC ──────────────────────────── --}}
<div class="footer">
    <div class="footer-left">
        <div class="footer-brand">TRITON SECURITY KFT.</div>
        <div class="footer-text">
            1234 Budapest, Minta utca 1. &nbsp;·&nbsp; +36 1 234 5678 &nbsp;·&nbsp; info@tritonsecurity.hu<br>
            Adószám: 12345678-2-42 &nbsp;·&nbsp; Cégjegyzékszám: 01-09-123456
        </div>
        <div class="footer-circuit">01010100 01010010 01001001 01010100 01001111 01001110</div>
    </div>
    <div class="footer-right">
        <div class="footer-text" style="color:#4ade80;font-weight:700;">www.tritonsecurity.hu</div>
        <div class="footer-text" style="margin-top:4px;">
            Kiállítva: {{ now()->format('Y. m. d. H:i') }}<br>
            Dok. ID: {{ $szamlaNum }}
        </div>
    </div>
</div>

</body>
</html>
