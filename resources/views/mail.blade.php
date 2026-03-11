<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Triton Security – Szerződés</title>
    <style>
        /* DejaVu Sans – teljes UTF-8/Unicode támogatás dompdf-ben */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── Fejléc ───────────────────────────────── */
        .header { background: #0f172a; padding: 14px 24px; width: 100%; }
        .header-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .header-logo { width: 58px; vertical-align: middle; }
        .header-brand { width: 56%; vertical-align: middle; padding-left: 10px; overflow: hidden; }
        .brand-name  { font-size: 15px; font-weight: bold; color: #ffffff; letter-spacing: 2px; }
        .brand-sub   { font-size: 7px; color: #94a3b8; letter-spacing: 2px; margin-top: 1px; }
        .brand-info  { font-size: 7.5px; color: #64748b; line-height: 1.7; margin-top: 4px; word-break: break-word; }
        .header-doc  { width: 40%; vertical-align: middle; text-align: right; padding-left: 6px; word-break: break-word; overflow-wrap: break-word; }
        .doc-title-big { font-size: 18px; font-weight: bold; color: #ed1b24; letter-spacing: 4px; }
        .doc-meta { font-size: 7.5px; color: #94a3b8; line-height: 1.8; margin-top: 3px; word-break: break-word; }

        /* ── Piros csík ──────────────────────────── */
        .stripe { background: #ed1b24; height: 3px; font-size: 0; line-height: 0; }

        /* ── Dokumentum cím ──────────────────────── */
        .doc-heading {
            text-align: center;
            border-bottom: 1.5px solid #ed1b24;
            padding: 12px 0 8px;
            margin-bottom: 4px;
        }
        .doc-heading-main {
            font-size: 14px; font-weight: bold; color: #0f172a;
            letter-spacing: 5px; text-transform: uppercase;
        }
        .doc-heading-sub {
            font-size: 7.5px; color: #64748b; margin-top: 4px; letter-spacing: 1px;
        }

        /* ── Tartalom margó ──────────────────────── */
        .content { padding: 12px 24px; }

        /* ── Szekció ─────────────────────────────── */
        .section { margin-bottom: 12px; page-break-inside: avoid; }
        .section-label {
            font-size: 7.5px; font-weight: bold; color: #64748b;
            text-transform: uppercase; letter-spacing: 2px;
            border-bottom: 1.5px solid #ed1b24;
            padding-bottom: 3px; margin-bottom: 7px;
        }

        /* ── Felek táblázat ──────────────────────── */
        .parties { width: 100%; border-collapse: collapse; }
        .parties td { width: 50%; vertical-align: top; }
        .parties td.left { padding-right: 6px; }
        .parties td.right { padding-left: 6px; }

        .party-box { border: 1px solid #e2e8f0; border-radius: 4px; overflow: hidden; }
        .party-head {
            background: #1e293b; color: #ffffff; padding: 5px 10px;
            font-size: 8px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase;
        }
        .party-body { padding: 7px 10px; background: #f8fafc; }
        .pr { margin-bottom: 4px; }
        .pl { font-size: 7.5px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .pv { font-size: 9.5px; color: #1e293b; font-weight: 500; word-break: break-word; }

        /* ── Info rács ───────────────────────────── */
        .info { width: 100%; border-collapse: collapse; }
        .info td {
            padding: 6px 8px; vertical-align: top;
            border: 1px solid #f1f5f9; font-size: 9px;
            word-break: break-word; word-wrap: break-word;
        }
        .info tr:nth-child(odd) td { background: #f8fafc; }
        .lbl { width: 22%; font-size: 7.5px; color: #64748b; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .val { width: 28%; color: #1e293b; }

        /* ── Anyag táblázat ──────────────────────── */
        .mat { width: 100%; border-collapse: collapse; }
        .mat th {
            background: #1e293b; color: #fff;
            padding: 6px 10px; font-size: 7.5px;
            text-transform: uppercase; letter-spacing: 1px; text-align: left;
        }
        .mat td { padding: 6px 10px; border-bottom: 1px solid #f1f5f9; font-size: 9px; color: #1e293b; }
        .mat tr:nth-child(even) td { background: #f8fafc; }

        /* ── Státusz ─────────────────────────────── */
        .badge { padding: 2px 8px; border-radius: 9px; font-size: 7.5px; font-weight: bold; }
        .b-active { background: #d1fae5; color: #065f46; }
        .b-done   { background: #dbeafe; color: #1e40af; }

        /* ── Jogi doboz ──────────────────────────── */
        .legal {
            border: 1px solid #e2e8f0; border-left: 3px solid #ed1b24;
            padding: 9px 12px; background: #fafafa;
            font-size: 8.5px; line-height: 1.75; color: #475569;
        }

        /* ── Aláírás ─────────────────────────────── */
        .sign-table { width: 100%; border-collapse: collapse; }
        .sign-table td { width: 50%; vertical-align: bottom; text-align: center; padding: 6px 24px; }
        .sign-lbl { font-size: 7.5px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .sign-box {
            width: 200px; height: 88px;
            border: 1.5px solid #cbd5e1; border-radius: 5px;
            background: #fff; padding: 3px; display: inline-block;
        }
        .sign-box img { max-width: 194px; max-height: 82px; }
        .sign-name { border-top: 1.5px solid #334155; margin-top: 6px; padding-top: 4px; font-size: 9px; font-weight: bold; color: #1e293b; }
        .sign-date { font-size: 7.5px; color: #64748b; margin-top: 2px; }

        /* ── Lábléc ──────────────────────────────── */
        .footer { background: #0f172a; padding: 12px 24px; font-size: 7.5px; color: #64748b; line-height: 1.7; }
        .footer-row { width: 100%; border-collapse: collapse; }
        .footer-row td { vertical-align: top; font-size: 7.5px; color: #64748b; }
        .footer-copy {
            text-align: center; margin-top: 8px;
            font-size: 7px; color: #475569; letter-spacing: 2px; text-transform: uppercase;
            border-top: 1px solid #1e293b; padding-top: 7px;
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════
     FEJLÉC
     ══════════════════════════════════════════════════════ --}}
<div class="header">
    @php
        $logoPath = public_path('logo.png');
        $logoB64  = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;
    @endphp
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if($logoB64)
                    <img src="{{ $logoB64 }}" width="48" height="48"
                         alt="logo" style="border-radius:6px; display:block;">
                @endif
            </td>
            <td class="header-brand">
                <div class="brand-name">{{ $cegadat->nev }}</div>
                <div class="brand-sub">Biztonságtechnikai vállalkozás</div>
                <div class="brand-info">
                    Székhely: {{ $cegadat->szekhelycim }}<br>
                    Adószám: {{ $cegadat->adoszam }} &nbsp;|&nbsp; Cégjegyzékszám: {{ $cegadat->cegjegyzekszam }}<br>
                    Tel.: {{ $cegadat->telefon }} &nbsp;|&nbsp; {{ $cegadat->email }}
                </div>
            </td>
            <td class="header-doc">
                <div class="doc-title-big">SZERZŐDÉS</div>
                <div class="doc-meta">
                    Sz. szám:&nbsp;{{ str_pad($megrendeles->Megrendeles_ID, 6, '0', STR_PAD_LEFT) }}/{{ now()->format('Y') }}<br>
                    Kelt:&nbsp;{{ now()->format('Y. m. d.') }}<br>
                    Helyszín:&nbsp;{{ ($megrendeles->varos->Irny_szam ?? '') . ' ' . ($megrendeles->varos->Nev ?? '') }}
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="stripe">&nbsp;</div>

{{-- ══════════════════════════════════════════════════════
     TARTALOM
     ══════════════════════════════════════════════════════ --}}
<div class="content">

    {{-- Dokumentum cím --}}
    <div class="doc-heading">
        <div class="doc-heading-main">Vállalkozási szerződés – Munkalap</div>
        <div class="doc-heading-sub">
            Okosotthon kiépítési és biztonságtechnikai rendszer telepítési megrendelési dokumentum
        </div>
    </div>

    {{-- ── Szerződő felek ──────────────────────────── --}}
    <div class="section">
        <div class="section-label">Szerződő felek</div>
        <table class="parties">
            <tr>
                <td class="left">
                    <div class="party-box">
                        <div class="party-head">Megrendelő (1. fél)</div>
                        <div class="party-body">
                            <div class="pr"><div class="pl">Megrendelő neve</div><div class="pv">{{ $megrendeles->ugyfel->Nev ?? '-' }}</div></div>
                            <div class="pr"><div class="pl">Számlázási név</div><div class="pv">{{ $megrendeles->ugyfel->Szamlazasi_Nev ?? '-' }}</div></div>
                            <div class="pr">
                                <div class="pl">Számlázási cím</div>
                                <div class="pv">{{ ($megrendeles->ugyfel->varos->Irny_szam ?? '') . ' ' . ($megrendeles->ugyfel->varos->Nev ?? '') }}, {{ $megrendeles->ugyfel->Szamlazasi_Cim ?? '-' }}</div>
                            </div>
                            <div class="pr"><div class="pl">Adószám</div><div class="pv">{{ $megrendeles->ugyfel->Adoszam ?? '–' }}</div></div>
                            <div class="pr"><div class="pl">Telefonszám</div><div class="pv">{{ $megrendeles->ugyfel->Telefonszam ?? '-' }}</div></div>
                            <div class="pr"><div class="pl">E-mail cím</div><div class="pv">{{ $megrendeles->ugyfel->Email ?? '-' }}</div></div>
                        </div>
                    </div>
                </td>
                <td class="right">
                    <div class="party-box">
                        <div class="party-head">Vállalkozó (2. fél)</div>
                        <div class="party-body">
                            <div class="pr"><div class="pl">Cégnév</div><div class="pv">Triton Security Kft.</div></div>
                            <div class="pr"><div class="pl">Székhely</div><div class="pv">1234 Budapest, Minta utca 1.</div></div>
                            <div class="pr"><div class="pl">Adószám</div><div class="pv">12345678-2-42</div></div>
                            <div class="pr"><div class="pl">Cégjegyzékszám</div><div class="pv">01-09-123456</div></div>
                            <div class="pr"><div class="pl">Képviseli</div><div class="pv">{{ $megrendeles->munkak->first()?->szerelo?->Nev ?? '–' }}</div></div>
                            <div class="pr"><div class="pl">Telefonszám</div><div class="pv">+36 1 234 5678</div></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── Szerződés tárgya ─────────────────────────── --}}
    <div class="section">
        <div class="section-label">A szerződés tárgya és helyszíne</div>
        <table class="info">
            <tr>
                <td class="lbl">Megrendelés neve</td>
                <td class="val">{{ $megrendeles->Megrendeles_Nev }}</td>
                <td class="lbl">Státusz</td>
                <td class="val">
                    @if($megrendeles->Alairt_e)
                        <span class="badge b-active">Folyamatban</span>
                    @else
                        <span class="badge b-done">Befejezve</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="lbl">Munkavégzés helye</td>
                <td class="val" colspan="3">
                    {{ ($megrendeles->varos->Irny_szam ?? '') . ' ' . ($megrendeles->varos->Nev ?? '') }},
                    {{ $megrendeles->Utca_Hazszam }}
                </td>
            </tr>
        </table>
    </div>

    {{-- ── Elvégzett munkák ─────────────────────────── --}}
    @foreach ($megrendeles->munkak as $munkaIdx => $munka)
    <div class="section">
        <div class="section-label">Elvégzett munka{{ $megrendeles->munkak->count() > 1 ? ' #' . ($munkaIdx + 1) : '' }} részletezése</div>
        <table class="info">
            <tr>
                <td class="lbl">Szolgáltatás típusa</td>
                <td class="val">{{ $munka->szolgaltatas->Tipus ?? '-' }}</td>
                <td class="lbl">Technikus neve</td>
                <td class="val">{{ $munka->szerelo->Nev ?? '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Technikus telefonja</td>
                <td class="val">{{ $munka->szerelo->Telefonszam ?? '-' }}</td>
                <td class="lbl">Munkakezdés</td>
                <td class="val">{{ $munka->Munkakezdes_Idopontja }}</td>
            </tr>
            <tr>
                <td class="lbl">Munkabefejezés</td>
                <td class="val">{{ $munka->Munkabefejezes_Idopontja }}</td>
                <td class="lbl">Munkaidő</td>
                <td class="val">
                    @php
                        try {
                            $k = \Carbon\Carbon::parse($munka->Munkakezdes_Idopontja);
                            $b = \Carbon\Carbon::parse($munka->Munkabefejezes_Idopontja);
                            $d = $k->diff($b);
                            echo ($d->days > 0 ? $d->days . ' nap ' : '') . $d->h . ' ó ' . $d->i . ' perc';
                        } catch(\Exception $e) { echo '–'; }
                    @endphp
                </td>
            </tr>
            @if($munka->Leiras)
            <tr>
                <td class="lbl">Munka leírása</td>
                <td class="val" colspan="3">{{ $munka->Leiras }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endforeach

    {{-- ── Felhasznált anyagok ──────────────────────── --}}
    <div class="section">
        <div class="section-label">Felhasznált anyagok és eszközök</div>
        @if ($megrendeles->felhasznaltAnyagok && count($megrendeles->felhasznaltAnyagok) > 0)
        <table class="mat">
            <thead>
                <tr>
                    <th style="width:55%;">Anyag / Eszköz neve</th>
                    <th style="width:20%;">Mértékegység</th>
                    <th style="width:25%;">Mennyiség</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($megrendeles->felhasznaltAnyagok as $anyag)
                <tr>
                    <td>{{ $anyag->anyag->Nev ?? '-' }}</td>
                    <td>{{ $anyag->anyag->Mertekegyseg ?? '-' }}</td>
                    <td>{{ $anyag->Mennyiseg }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color:#94a3b8; font-size:8.5px; font-style:italic; padding:5px 0;">
            Nincsenek felhasznált anyagok rögzítve.
        </p>
        @endif
    </div>

    {{-- ── Jogi nyilatkozat ────────────────────────── --}}
    <div class="section">
        <div class="section-label">Nyilatkozat és elfogadás</div>
        <div class="legal">
            Jelen szerződés alapján a <strong>Megrendelő</strong> megrendeli, a <strong>Vállalkozó</strong> (Triton Security Kft.)
            pedig elvállalja az I. pontban meghatározott biztonságtechnikai munkák elvégzését a rögzített helyszínen és feltételek szerint.
            A Vállalkozó kijelenti, hogy a munkát szakszerűen, az érvényes műszaki előírásoknak, szabványoknak és
            vonatkozó jogszabályoknak megfelelően végezte el. A Megrendelő a munkát – az aláírással – maradéktalanul átveszi,
            a felhasznált anyagok mennyiségét és minőségét, valamint az elvégzett munkát ellenőrizte, és azokkal szemben
            kifogást nem emel.
            <br><br>
            Jelen munkalap mindkét fél által aláírva jogilag kötelező érvényű vállalkozási szerződésnek minősül a Polgári
            Törvénykönyv (2013. évi V. törvény) 6:238–6:264. §§ alapján. Az aláírással a Megrendelő tudomásul veszi, hogy
            a jelen dokumentumban foglalt munkákat teljesítettnek fogadja el, és a Vállalkozóval szemben ezzel kapcsolatban
            semmilyen további követelése nincs.
        </div>
    </div>

    {{-- ── Aláírások ────────────────────────────────── --}}
    <div class="section">
        <div class="section-label">Aláírások és hitelesítés</div>
        <table class="sign-table">
            <tr>
                {{-- Vállalkozó --}}
                <td>
                    <div class="sign-lbl">Vállalkozó aláírása (Triton Security Kft.)</div>
                    @php
                        $szPath = public_path('alaIrasokSzerelok/' . $imgPathSzerelo);
                        $szB64  = file_exists($szPath)
                            ? 'data:image/png;base64,' . base64_encode(file_get_contents($szPath))
                            : null;
                    @endphp
                    <div class="sign-box">
                        @if($szB64)<img src="{{ $szB64 }}" alt="Vállalkozó aláírása">@endif
                    </div>
                    <div class="sign-name">{{ $megrendeles->munkak->first()?->szerelo?->Nev ?? 'Technikus' }}</div>
                    <div class="sign-date">{{ now()->format('Y. m. d.') }}, {{ ($megrendeles->varos->Nev ?? 'Budapest') }}</div>
                </td>
                {{-- Megrendelő --}}
                <td>
                    <div class="sign-lbl">Megrendelő aláírása</div>
                    @php
                        $ugPath = public_path('alaIrasokUgyfel/alairas.png');
                        $ugB64  = file_exists($ugPath)
                            ? 'data:image/png;base64,' . base64_encode(file_get_contents($ugPath))
                            : null;
                    @endphp
                    <div class="sign-box">
                        @if($ugB64)<img src="{{ $ugB64 }}" alt="Megrendelő aláírása">@endif
                    </div>
                    <div class="sign-name">{{ $megrendeles->ugyfel->Nev }}</div>
                    <div class="sign-date">{{ now()->format('Y. m. d.') }}, {{ ($megrendeles->varos->Nev ?? 'Budapest') }}</div>
                </td>
            </tr>
        </table>
    </div>

</div>{{-- /content --}}

{{-- ══════════════════════════════════════════════════════
     LÁBLÉC
     ══════════════════════════════════════════════════════ --}}
<div class="footer">
    <table class="footer-row">
        <tr>
            <td style="width:60%; padding-right:16px;">
                Jelen dokumentum a Triton Security Kft. által kiállított hivatalos vállalkozási szerződés és munkalap.
                Mindkét fél aláírásával jogilag kötelező érvényűvé válik. Kérjük, megőrzési kötelezettség miatt
                a szerződést biztonságos helyen tartsa.
            </td>
            <td style="width:40%; text-align:right; color:#94a3b8;">
                Triton Security Kft.<br>
                1234 Budapest, Minta utca 1.<br>
                info@tritonsecurity.hu &nbsp;|&nbsp; +36 1 234 5678
            </td>
        </tr>
    </table>
    <div class="footer-copy">
        Triton Security Kft.
        &nbsp;&bull;&nbsp;
        Szerz. szám: {{ str_pad($megrendeles->Megrendeles_ID, 6, '0', STR_PAD_LEFT) }}/{{ now()->format('Y') }}
        &nbsp;&bull;&nbsp;
        Kiállítva: {{ now()->format('Y. m. d.') }}
        &nbsp;&bull;&nbsp;
        &copy; {{ date('Y') }}
    </div>
</div>

</body>
</html>
