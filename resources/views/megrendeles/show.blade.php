@extends('ujlayout')

@section('content')
@php $user = Auth::user(); @endphp

@if ($user->role != 'Ugyfel')
    @include('breadcrumbs')
@endif

<style>
/* ── Page header ────────────────────────────────────────── */
.show-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.show-header-left h1 {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 6px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.show-header-left h1 i { color: #c9a97a; }
.show-order-num {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 6px;
    background: rgba(201,169,122,0.12);
    border: 1px solid rgba(201,169,122,0.3);
    color: #a07848;
    letter-spacing: 0.3px;
}
.show-header-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

/* ── Info cards ──────────────────────────────────────────── */
.show-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
.show-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    overflow: hidden;
}
.show-card-full { grid-column: 1 / -1; }
.show-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 18px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(90deg, rgba(201,169,122,0.07) 0%, rgba(201,169,122,0.01) 100%);
}
.show-card-icon {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    background: rgba(201,169,122,0.15);
    color: #a07848;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}
.show-card-title { font-size: 12px; font-weight: 700; color: #1e293b; }

/* ── Info rows ───────────────────────────────────────────── */
.info-rows { padding: 6px 0; }
.info-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 11px 18px;
    border-bottom: 1px solid #f8fafc;
}
.info-row:last-child { border-bottom: none; }
.info-row-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #94a3b8;
    min-width: 130px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 5px;
    padding-top: 1px;
}
.info-row-label i { color: #c9a97a; font-size: 10px; }
.info-row-val {
    font-size: 13px;
    font-weight: 500;
    color: #334155;
    flex: 1;
}

/* ── Status badges ───────────────────────────────────────── */
.badge-folyamatban {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; border-radius: 5px;
    padding: 3px 10px; background: rgba(59,130,246,0.1); color: #2563eb;
}
.badge-befejezve {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; border-radius: 5px;
    padding: 3px 10px; background: rgba(34,197,94,0.1); color: #16a34a;
}
.badge-munka {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; border-radius: 5px;
    padding: 3px 9px;
}
.badge-munka.done   { background: rgba(34,197,94,0.1);  color: #16a34a; }
.badge-munka.in     { background: rgba(59,130,246,0.1); color: #2563eb; }
.badge-munka.wait   { background: rgba(249,115,22,0.1); color: #ea580c; }

/* ── Munka subcard ───────────────────────────────────────── */
.munka-subcard {
    border: 1px solid #e8edf2;
    border-radius: 10px;
    overflow: hidden;
    margin: 14px 18px;
}
.munka-subcard + .munka-subcard { margin-top: 0; }
.munka-sub-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: linear-gradient(90deg, rgba(201,169,122,0.07) 0%, rgba(201,169,122,0.01) 100%);
    border-bottom: 1px solid rgba(201,169,122,0.1);
}
.munka-sub-icon {
    width: 28px; height: 28px; border-radius: 6px;
    background: rgba(201,169,122,0.15); color: #a07848;
    display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0;
}
.munka-sub-title { font-size: 13px; font-weight: 600; color: #1e293b; flex: 1; }
.munka-num {
    font-size: 10px; color: #94a3b8; background: #f1f5f9;
    border-radius: 4px; padding: 2px 7px;
}

/* ── Anyag tábla ─────────────────────────────────────────── */
.anyag-list { padding: 14px 18px; display: flex; flex-direction: column; gap: 8px; }
.anyag-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #f1f5f9;
}
.anyag-row-name { font-size: 13px; font-weight: 500; color: #334155; flex: 1; }
.anyag-row-qty {
    font-size: 12px; font-weight: 700; color: #a07848;
    background: rgba(201,169,122,0.1); border-radius: 5px;
    padding: 2px 9px;
}

/* ── PDF gomb ────────────────────────────────────────────── */
.btn-pdf-view {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 14px; border-radius: 8px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    background: rgba(201,169,122,0.1); color: #a07848;
    border: 1.5px solid rgba(201,169,122,0.35); transition: all 0.15s;
}
.btn-pdf-view:hover { background: rgba(201,169,122,0.2); }
.btn-pdf-dl {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 14px; border-radius: 8px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    background: #c9a97a; color: #fff; border: 1.5px solid #c9a97a; transition: all 0.15s;
}
.btn-pdf-dl:hover { background: #a07848; border-color: #a07848; color: #fff; }

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 768px) {
    .show-grid { grid-template-columns: 1fr; }
    .show-card-full { grid-column: 1; }
    .show-header { flex-direction: column; }
    .info-row { flex-direction: column; gap: 3px; }
    .info-row-label { min-width: auto; }
}
</style>

@if ($megrendeles)

@php
    $ugyfelId  = $megrendeles->ugyfel->Ugyfel_ID ?? 0;
    $ugyfelNev = rawurlencode($megrendeles->ugyfel->Nev ?? '');
    $szoId     = $munkak->first()?->szolgaltatas?->Szolgaltatas_ID ?? 0;
    $mId       = $megrendeles->Megrendeles_ID;
    $viewUrl   = url('/view-pdf/'     . $ugyfelId . '_' . $ugyfelNev . '_' . $szoId . '_' . $mId);
    $dlUrl     = url('/download-pdf/' . $ugyfelId . '_' . $ugyfelNev . '_' . $szoId . '_' . $mId);
@endphp

{{-- Fejléc --}}
<div class="show-header">
    <div class="show-header-left">
        <h1>
            <i class="fas fa-clipboard-list"></i>
            {{ $megrendeles->Megrendeles_Nev }}
        </h1>
        <span class="show-order-num">#{{ str_pad($mId, 5, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="show-header-actions">
        @if ($user->role != 'Ugyfel')
            <a href="{{ route('megrendeles.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Vissza
            </a>
            <a href="{{ route('megrendeles.edit', ['megrendeles' => $mId]) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Szerkesztés
            </a>
            <form method="POST" action="{{ route('megrendeles.resend-email', $mId) }}" style="display:inline;">
                @csrf
                <button type="submit"
                    style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;background:rgba(37,99,235,0.08);color:#2563eb;border:1.5px solid rgba(37,99,235,0.25);transition:all 0.15s;"
                    onmouseover="this.style.background='rgba(37,99,235,0.15)'"
                    onmouseout="this.style.background='rgba(37,99,235,0.08)'"
                    title="PDF újragenerálása és email újraküldése az ügyfélnek">
                    <i class="fas fa-paper-plane"></i> Email újraküldése
                </button>
            </form>
        @endif
        <a href="{{ $viewUrl }}" target="_blank" class="btn-pdf-view">
            <i class="fas fa-eye"></i> PDF megtekintése
        </a>
        <a href="{{ $dlUrl }}" class="btn-pdf-dl">
            <i class="fas fa-file-pdf"></i> PDF letöltése
        </a>
    </div>
</div>

<div class="show-grid">

    {{-- Általános adatok --}}
    <div class="show-card">
        <div class="show-card-header">
            <div class="show-card-icon"><i class="fas fa-info-circle"></i></div>
            <div class="show-card-title">Általános adatok</div>
        </div>
        <div class="info-rows">
            <div class="info-row">
                <div class="info-row-label"><i class="fas fa-user"></i> Ügyfél</div>
                <div class="info-row-val">{{ $megrendeles->ugyfel->Nev ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-row-label"><i class="fas fa-file-signature"></i> Megrendelő</div>
                <div class="info-row-val">{{ $megrendeles->Megrendeles_Nev }}</div>
            </div>
            <div class="info-row">
                <div class="info-row-label"><i class="fas fa-toggle-on"></i> Státusz</div>
                <div class="info-row-val">
                    @if($megrendeles->Statusz)
                        <span class="badge-folyamatban"><i class="fas fa-spinner"></i> Folyamatban</span>
                    @else
                        <span class="badge-befejezve"><i class="fas fa-check"></i> Befejezve</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Helyszín --}}
    <div class="show-card">
        <div class="show-card-header">
            <div class="show-card-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="show-card-title">Helyszín</div>
        </div>
        <div class="info-rows">
            <div class="info-row">
                <div class="info-row-label"><i class="fas fa-city"></i> Város</div>
                <div class="info-row-val">
                    {{ $megrendeles->varos->Irny_szam ?? '' }}
                    {{ $megrendeles->varos->Nev ?? '—' }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-row-label"><i class="fas fa-road"></i> Utca, hsz.</div>
                <div class="info-row-val">{{ $megrendeles->Utca_Hazszam }}</div>
            </div>
            @if($megrendeles->Pdf_EleresiUt)
            <div class="info-row">
                <div class="info-row-label"><i class="fas fa-file-pdf"></i> PDF útvonal</div>
                <div class="info-row-val" style="font-size:12px;color:#94a3b8;word-break:break-all;">
                    {{ $megrendeles->Pdf_EleresiUt }}
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Munkák (full width) --}}
    <div class="show-card show-card-full">
        <div class="show-card-header">
            <div class="show-card-icon"><i class="fas fa-tools"></i></div>
            <div class="show-card-title">Elvégzett munkák ({{ $munkak->count() }})</div>
        </div>

        @foreach ($munkak as $munka)
            @php
                if (!is_null($munka->Munkabefejezes_Idopontja)) {
                    $mStatus = 'done'; $mLabel = 'Befejezett'; $mIcon = 'fa-check';
                } elseif (!is_null($munka->Munkakezdes_Idopontja)) {
                    $mStatus = 'in';   $mLabel = 'Folyamatban'; $mIcon = 'fa-hard-hat';
                } else {
                    $mStatus = 'wait'; $mLabel = 'Várakozik';   $mIcon = 'fa-clock';
                }
            @endphp
            <div class="munka-subcard" style="{{ $loop->first ? 'margin-top:14px;' : 'margin-top:10px;' }}">
                <div class="munka-sub-header">
                    <div class="munka-sub-icon"><i class="fas fa-wrench"></i></div>
                    <div class="munka-sub-title">{{ $munka->szolgaltatas->Tipus ?? 'Elvégzett munka' }}</div>
                    @if($munkak->count() > 1)
                        <span class="munka-num">{{ $loop->iteration }}. munka</span>
                    @endif
                    <span class="badge-munka {{ $mStatus }}">
                        <i class="fas {{ $mIcon }}"></i> {{ $mLabel }}
                    </span>
                </div>
                <div class="info-rows">
                    <div class="info-row">
                        <div class="info-row-label"><i class="fas fa-hard-hat"></i> Szerelő</div>
                        <div class="info-row-val">{{ $munka->szerelo->Nev ?? '—' }}</div>
                    </div>
                    @if($munka->szerelo?->Telefonszam)
                    <div class="info-row">
                        <div class="info-row-label"><i class="fas fa-phone"></i> Telefon</div>
                        <div class="info-row-val">{{ $munka->szerelo->Telefonszam }}</div>
                    </div>
                    @endif
                    @if($munka->Munkakezdes_Idopontja)
                    <div class="info-row">
                        <div class="info-row-label"><i class="fas fa-calendar-alt"></i> Kezdés</div>
                        <div class="info-row-val">
                            {{ \Carbon\Carbon::parse($munka->Munkakezdes_Idopontja)->format('Y. m. d. H:i') }}
                        </div>
                    </div>
                    @endif
                    @if($munka->Munkabefejezes_Idopontja)
                    <div class="info-row">
                        <div class="info-row-label"><i class="fas fa-calendar-check"></i> Befejezés</div>
                        <div class="info-row-val">
                            {{ \Carbon\Carbon::parse($munka->Munkabefejezes_Idopontja)->format('Y. m. d. H:i') }}
                        </div>
                    </div>
                    @endif
                    @if($munka->Leiras)
                    <div class="info-row">
                        <div class="info-row-label"><i class="fas fa-align-left"></i> Leírás</div>
                        <div class="info-row-val">{{ $munka->Leiras }}</div>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
        <div style="height:14px;"></div>
    </div>

    {{-- ── Fizetési állapot (Szamla modell alapján) ─────────── --}}
    @php $szamla = $megrendeles->szamla; @endphp
    @php
        $sikerFiz = $szamla?->fizetesek->where('statusz','fizetve')->first();
        $lejart   = $szamla && $szamla->fizetesi_hatarido && $szamla->statusz !== 'fizetve'
                    && now()->gt($szamla->fizetesi_hatarido);
        $kozel    = $szamla && $szamla->fizetesi_hatarido && $szamla->statusz !== 'fizetve' && !$lejart
                    && now()->diffInDays($szamla->fizetesi_hatarido) <= 3;
        $fizetve  = $szamla?->statusz === 'fizetve';
    @endphp
    <div class="show-card show-card-full">
        <div class="show-card-header" style="@if($fizetve) background:linear-gradient(90deg,rgba(34,197,94,0.08) 0%,rgba(34,197,94,0.01) 100%); @elseif($lejart) background:linear-gradient(90deg,rgba(239,68,68,0.08) 0%,rgba(239,68,68,0.01) 100%); @else background:linear-gradient(90deg,rgba(201,169,122,0.07) 0%,rgba(201,169,122,0.01) 100%); @endif">
            <div class="show-card-icon" style="@if($fizetve) background:rgba(34,197,94,0.15);color:#16a34a; @elseif($lejart) background:rgba(239,68,68,0.15);color:#dc2626; @endif">
                <i class="fas fa-{{ $fizetve ? 'check-circle' : ($lejart ? 'exclamation-circle' : 'credit-card') }}"></i>
            </div>
            <div class="show-card-title">Fizetési állapot</div>
            <div style="margin-left:auto;display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                @if($fizetve)
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;padding:4px 11px;border-radius:6px;background:rgba(34,197,94,0.12);color:#16a34a;border:1px solid rgba(34,197,94,0.25);">
                        <i class="fas fa-check"></i> Fizetve
                    </span>
                @elseif(!$szamla)
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;padding:4px 11px;border-radius:6px;background:rgba(148,163,184,0.15);color:#64748b;border:1px solid rgba(148,163,184,0.3);">
                        <i class="fas fa-file-invoice"></i> Nincs számla
                    </span>
                @elseif($lejart)
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;padding:4px 11px;border-radius:6px;background:rgba(239,68,68,0.1);color:#dc2626;border:1px solid rgba(239,68,68,0.25);">
                        <i class="fas fa-times-circle"></i> Lejárt határidő
                    </span>
                @elseif($kozel)
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;padding:4px 11px;border-radius:6px;background:rgba(245,158,11,0.1);color:#d97706;border:1px solid rgba(245,158,11,0.25);">
                        <i class="fas fa-clock"></i> Hamarosan lejár
                    </span>
                @else
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;padding:4px 11px;border-radius:6px;background:rgba(59,130,246,0.1);color:#2563eb;border:1px solid rgba(59,130,246,0.2);">
                        <i class="fas fa-hourglass-half"></i> Várakozik
                    </span>
                @endif
                {{-- Admin / Uzletkoto gombok --}}
                @if($user->role !== 'Ugyfel')
                    @if($szamla && !$fizetve)
                        <form method="POST" action="{{ route('payment.manual', $megrendeles->Megrendeles_ID) }}" style="display:inline;">
                            @csrf
                            <button type="submit" style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:7px;background:#f0fdf4;border:1.5px solid rgba(34,197,94,0.3);color:#16a34a;font-size:12px;font-weight:600;cursor:pointer;">
                                <i class="fas fa-check"></i> Megjelölés fizetve
                            </button>
                        </form>
                    @endif
                    @if($szamla)
                        <a href="{{ route('szamlak.show', $szamla->szamla_id) }}"
                           style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:7px;background:rgba(201,169,122,0.1);border:1.5px solid rgba(201,169,122,0.35);color:#a07848;font-size:12px;font-weight:600;text-decoration:none;">
                            <i class="fas fa-file-invoice"></i> Számla megtekintése
                        </a>
                        @if($szamla->billingo_pdf_url)
                            <a href="{{ route('szamlak.download', $szamla->szamla_id) }}"
                               style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:7px;background:rgba(201,169,122,0.1);border:1.5px solid rgba(201,169,122,0.35);color:#a07848;font-size:12px;font-weight:600;text-decoration:none;">
                                <i class="fas fa-download"></i> PDF letöltése
                            </a>
                        @endif
                    @else
                        <a href="{{ route('szamlak.create', ['megrendeles_id' => $megrendeles->Megrendeles_ID]) }}"
                           style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:7px;background:rgba(201,169,122,0.1);border:1.5px solid rgba(201,169,122,0.35);color:#a07848;font-size:12px;font-weight:600;text-decoration:none;">
                            <i class="fas fa-plus"></i> Számla kiállítása
                        </a>
                    @endif
                @endif
            </div>
        </div>
        <div class="info-rows">
            @if($szamla?->brutto_osszeg)
            <div class="info-row">
                <div class="info-row-label"><i class="fas fa-coins"></i> Bruttó összeg</div>
                <div class="info-row-val" style="font-size:15px;font-weight:700;color:#a07848;">
                    {{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft
                    <span style="font-size:11px;font-weight:400;color:#94a3b8;margin-left:6px;">(Nettó: {{ number_format($szamla->netto_osszeg, 0, ',', ' ') }} Ft)</span>
                </div>
            </div>
            @endif
            @if($szamla?->fizetesi_hatarido)
            <div class="info-row">
                <div class="info-row-label"><i class="fas fa-calendar-times"></i> Határidő</div>
                <div class="info-row-val" style="color:{{ $lejart ? '#dc2626' : ($kozel ? '#d97706' : '#334155') }};font-weight:{{ $lejart || $kozel ? '600' : '500' }};">
                    {{ $szamla->fizetesi_hatarido->format('Y. m. d.') }}
                    @if(!$fizetve)
                        @if($lejart)
                            <span style="font-size:11px;color:#dc2626;margin-left:6px;">(lejárt)</span>
                        @else
                            <span style="font-size:11px;color:#94a3b8;margin-left:6px;">({{ now()->diffInDays($szamla->fizetesi_hatarido) }} nap múlva)</span>
                        @endif
                    @endif
                </div>
            </div>
            @endif
            @if($sikerFiz)
            <div class="info-row">
                <div class="info-row-label"><i class="fas fa-check-circle"></i> Fizetve</div>
                <div class="info-row-val">{{ $sikerFiz->fizetes_idopontja?->format('Y. m. d. H:i') ?? '—' }}
                    <span style="font-size:11px;color:#94a3b8;margin-left:8px;">
                        ({{ $sikerFiz->fizetes_mod === 'stripe' ? 'Bankkártyával' : 'Átutalással' }})
                    </span>
                </div>
            </div>
            @endif
            @if($szamla?->billingo_szam)
            <div class="info-row">
                <div class="info-row-label"><i class="fas fa-file-invoice"></i> Számlaszám</div>
                <div class="info-row-val" style="font-weight:600;">{{ $szamla->billingo_szam }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Felhasznált anyagok --}}
    <div class="show-card show-card-full">
        <div class="show-card-header">
            <div class="show-card-icon"><i class="fas fa-boxes"></i></div>
            <div class="show-card-title">Felhasznált anyagok</div>
        </div>
        @if ($felhasznaltAnyagok && count($felhasznaltAnyagok) > 0)
            <div class="anyag-list">
                @foreach ($felhasznaltAnyagok as $fa)
                    <div class="anyag-row">
                        <i class="fas fa-cube" style="color:#c9a97a;font-size:12px;flex-shrink:0;"></i>
                        <div class="anyag-row-name">
                            {{ $fa->anyag->Nev ?? '—' }}
                            <span style="color:#94a3b8;font-weight:400;font-size:11px;">
                                ({{ $fa->anyag->Mertekegyseg ?? '' }})
                            </span>
                        </div>
                        <div class="anyag-row-qty">{{ $fa->Mennyiseg }} db</div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="padding:20px 18px;color:#94a3b8;font-size:13px;">
                <i class="fas fa-info-circle" style="margin-right:6px;"></i>
                Nincsenek felhasznált anyagok rögzítve.
            </div>
        @endif
    </div>

</div>

@else
    <div class="empty-state">
        <i class="fas fa-clipboard-list"></i>
        <p>A megrendelés nem található.</p>
    </div>
@endif

@endsection
