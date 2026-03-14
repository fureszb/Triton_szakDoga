@extends('ujlayout')

@section('content')

<style>
/* ── Welcome sáv ────────────────────────────────────────── */
.ugyfel-welcome {
    background: linear-gradient(135deg, #c9a97a 0%, #8c6a3a 55%, #3a2510 100%);
    border-radius: 14px;
    padding: 22px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}
.ugyfel-welcome::before {
    content: 'TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON';
    position: absolute;
    inset: -10px;
    font-size: 16px;
    font-weight: 900;
    color: rgba(255,255,255,0.05);
    letter-spacing: 20px;
    line-height: 2.5;
    word-break: break-all;
    pointer-events: none;
    transform: rotate(-10deg);
    white-space: normal;
}
.ugyfel-welcome h2 {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 4px;
}
.ugyfel-welcome p {
    font-size: 12px;
    color: rgba(255,255,255,0.7);
    margin: 0;
}
.ugyfel-welcome-right {
    flex-shrink: 0;
    display: flex;
    gap: 10px;
}
.ugyfel-welcome-pill {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 8px;
    padding: 8px 14px;
    text-align: center;
    backdrop-filter: blur(4px);
    min-width: 80px;
}
.ugyfel-welcome-pill .pill-val {
    font-size: 20px;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}
.ugyfel-welcome-pill .pill-lbl {
    font-size: 10px;
    color: rgba(255,255,255,0.65);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 3px;
}

/* ── KPI cards ──────────────────────────────────────────── */
.u-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 12px;
    margin-bottom: 24px;
}
.u-kpi-card {
    background: #fff;
    border-radius: 11px;
    padding: 16px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 12px;
}
.u-kpi-icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}
.u-kpi-icon.beige  { background: rgba(201,169,122,0.15); color: #a07848; }
.u-kpi-icon.blue   { background: rgba(59,130,246,0.1);   color: #2563eb; }
.u-kpi-icon.green  { background: rgba(34,197,94,0.1);    color: #16a34a; }
.u-kpi-icon.orange { background: rgba(249,115,22,0.1);   color: #ea580c; }
.u-kpi-icon.red    { background: rgba(239,68,68,0.1);    color: #dc2626; }
.u-kpi-text {}
.u-kpi-val {
    font-size: 22px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}
.u-kpi-lbl {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
    font-weight: 500;
}

/* ── Szekció cím ────────────────────────────────────────── */
.u-section-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.8px;
    color: #94a3b8;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.u-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e8edf2;
}

/* ── Megrendelés kártya ─────────────────────────────────── */
.m-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    margin-bottom: 14px;
    overflow: hidden;
    transition: box-shadow 0.15s;
}
.m-card:hover {
    box-shadow: 0 3px 12px rgba(201,169,122,0.15);
}
.m-card-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
}
.m-card-header.clickable {
    cursor: pointer;
    user-select: none;
}
.m-card-header.clickable:hover {
    background: #fafbfc;
}
.m-order-num {
    font-size: 11px;
    font-weight: 700;
    color: #a07848;
    background: rgba(201,169,122,0.12);
    border: 1px solid rgba(201,169,122,0.3);
    border-radius: 6px;
    padding: 4px 9px;
    flex-shrink: 0;
    letter-spacing: 0.3px;
}
.m-card-title-wrap {
    flex: 1;
    min-width: 0;
}
.m-card-name {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.m-card-meta-inline {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 3px;
}
.m-card-meta-inline span {
    font-size: 11px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 4px;
}
.m-card-meta-inline i {
    font-size: 10px;
    color: #c9a97a;
}
.m-card-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}
.m-pdf-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #a07848;
    background: rgba(201,169,122,0.1);
    border: 1px solid rgba(201,169,122,0.3);
    text-decoration: none;
    transition: all 0.15s;
}
.m-pdf-btn:hover {
    background: rgba(201,169,122,0.2);
    color: #7a5830;
}
.m-chevron {
    color: #94a3b8;
    font-size: 12px;
    transition: transform 0.25s ease;
    width: 20px;
    text-align: center;
}
.m-chevron.open { transform: rotate(180deg); }

/* ── Megrendelés helyszín ───────────────────────────────── */
.m-location {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    font-size: 12px;
    color: #64748b;
}
.m-location i { color: #c9a97a; font-size: 12px; }

/* ── Munkák szekció ─────────────────────────────────────── */
.m-munkak {
    padding: 14px 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.m-munka-card {
    border-radius: 10px;
    border: 1px solid #e8edf2;
    overflow: hidden;
}
.m-munka-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: linear-gradient(90deg, rgba(201,169,122,0.08) 0%, rgba(201,169,122,0.02) 100%);
    border-bottom: 1px solid rgba(201,169,122,0.12);
}
.m-munka-icon {
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
.m-munka-title {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
}
.m-munka-sorszam {
    font-size: 10px;
    color: #94a3b8;
    background: #f1f5f9;
    border-radius: 4px;
    padding: 2px 6px;
    margin-left: 4px;
}
.m-munka-status {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 5px;
    padding: 3px 8px;
}
.m-munka-status.folyamatban { background: rgba(59,130,246,0.1); color: #2563eb; }
.m-munka-status.befejezett  { background: rgba(34,197,94,0.1);  color: #16a34a; }
.m-munka-status.varakozik   { background: rgba(249,115,22,0.1); color: #ea580c; }
.m-munka-body {
    padding: 12px 14px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 10px;
}
.m-detail-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.m-detail-item.wide {
    grid-column: 1 / -1;
}
.m-detail-label {
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 4px;
}
.m-detail-label i { color: #c9a97a; font-size: 10px; }
.m-detail-val {
    font-size: 13px;
    font-weight: 500;
    color: #334155;
}

/* ── Collapse ───────────────────────────────────────────── */
.m-collapse {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}
.m-collapse.open {
    max-height: none;
}

/* ── Badge ──────────────────────────────────────────────── */
.badge-aktiv    { background: rgba(59,130,246,0.1);  color: #2563eb; font-size: 11px; font-weight: 600; border-radius: 5px; padding: 3px 9px; }
.badge-befej    { background: rgba(34,197,94,0.1);   color: #16a34a; font-size: 11px; font-weight: 600; border-radius: 5px; padding: 3px 9px; }

/* ── Fizetés sáv ────────────────────────────────────────── */
.m-payment-bar {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 10px 20px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap;
}
.m-payment-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    flex-wrap: wrap;
}
.m-payment-amount {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 5px;
}
.m-payment-amount i { color: #c9a97a; font-size: 12px; }
.m-payment-due {
    font-size: 11px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
}
.m-payment-due i { font-size: 10px; color: #c9a97a; }
.m-pay-badge {
    font-size: 11px; font-weight: 600; border-radius: 5px; padding: 3px 9px;
    display: inline-flex; align-items: center; gap: 4px;
}
.m-pay-badge.fizetve  { background: rgba(34,197,94,0.1);   color: #16a34a; }
.m-pay-badge.varakozik { background: rgba(59,130,246,0.1);  color: #2563eb; }
.m-pay-badge.lejart   { background: rgba(239,68,68,0.1);   color: #dc2626; }
.m-pay-badge.kozel    { background: rgba(249,115,22,0.1);  color: #ea580c; }
.m-fizet-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 15px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: none;
    text-decoration: none;
    transition: all 0.15s;
    cursor: pointer;
    letter-spacing: 0.2px;
}
.m-fizet-btn:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(239,68,68,0.35);
}

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 640px) {
    .ugyfel-welcome { flex-direction: column; align-items: flex-start; }
    .ugyfel-welcome-right { width: 100%; }
    .ugyfel-welcome-pill { flex: 1; }
    .u-kpi-grid { grid-template-columns: 1fr 1fr; }
    .m-munka-body { grid-template-columns: 1fr 1fr; }
}
</style>

@php
    $totalMegr     = $megrendelesek->count();
    $aktivMegr     = $megrendelesek->where('Statusz', 1)->count();
    $befejMegr     = $megrendelesek->where('Statusz', 0)->count();
    $osszesMunka   = $megrendelesek->flatMap->munkak;
    $befejMunka    = $osszesMunka->filter(fn($m) => !is_null($m->Munkabefejezes_Idopontja))->count();
    $folyMunka     = $osszesMunka->filter(fn($m) => !is_null($m->Munkakezdes_Idopontja) && is_null($m->Munkabefejezes_Idopontja))->count();
    $varakozMunka  = $osszesMunka->filter(fn($m) => is_null($m->Munkakezdes_Idopontja))->count();
    $fizetetlenMegr = $megrendelesek->filter(fn($mr) => !$mr->Fizetve && $mr->Vegosszeg)->count();
@endphp

@if (session('info'))
    <div class="alert alert-warning"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
@elseif (session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Welcome sáv --}}
<div class="ugyfel-welcome">
    <div>
        <h2>Megrendeléseim</h2>
        <p>TRITON SECURITY &mdash; Smart home kiépítési munkák</p>
    </div>
    <div class="ugyfel-welcome-right">
        <div class="ugyfel-welcome-pill">
            <div class="pill-val">{{ $aktivMegr }}</div>
            <div class="pill-lbl">Aktív</div>
        </div>
        <div class="ugyfel-welcome-pill">
            <div class="pill-val">{{ $befejMegr }}</div>
            <div class="pill-lbl">Befejezett</div>
        </div>
    </div>
</div>

@if ($megrendelesek->isEmpty())
    <div class="empty-state" style="margin-top:40px;">
        <i class="fas fa-clipboard-check"></i>
        <p>Nincsenek megrendeléseid.</p>
    </div>
@else

    {{-- KPI kártyák --}}
    <div class="u-kpi-grid">
        <div class="u-kpi-card">
            <div class="u-kpi-icon beige"><i class="fas fa-clipboard-list"></i></div>
            <div class="u-kpi-text">
                <div class="u-kpi-val">{{ $totalMegr }}</div>
                <div class="u-kpi-lbl">Összes megrendelés</div>
            </div>
        </div>
        <div class="u-kpi-card">
            <div class="u-kpi-icon blue"><i class="fas fa-spinner"></i></div>
            <div class="u-kpi-text">
                <div class="u-kpi-val">{{ $aktivMegr }}</div>
                <div class="u-kpi-lbl">Aktív megrendelés</div>
            </div>
        </div>
        <div class="u-kpi-card">
            <div class="u-kpi-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="u-kpi-text">
                <div class="u-kpi-val">{{ $befejMunka }}</div>
                <div class="u-kpi-lbl">Elvégzett munka</div>
            </div>
        </div>
        <div class="u-kpi-card">
            <div class="u-kpi-icon blue"><i class="fas fa-hard-hat"></i></div>
            <div class="u-kpi-text">
                <div class="u-kpi-val">{{ $folyMunka }}</div>
                <div class="u-kpi-lbl">Folyamatban</div>
            </div>
        </div>
        <div class="u-kpi-card">
            <div class="u-kpi-icon orange"><i class="fas fa-clock"></i></div>
            <div class="u-kpi-text">
                <div class="u-kpi-val">{{ $varakozMunka }}</div>
                <div class="u-kpi-lbl">Várakozó munka</div>
            </div>
        </div>
        @if($fizetetlenMegr > 0)
        <div class="u-kpi-card" style="border-color:rgba(239,68,68,0.25);">
            <div class="u-kpi-icon red"><i class="fas fa-exclamation-circle"></i></div>
            <div class="u-kpi-text">
                <div class="u-kpi-val" style="color:#dc2626;">{{ $fizetetlenMegr }}</div>
                <div class="u-kpi-lbl">Fizetésre vár</div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── AKTÍV MEGRENDELÉSEK ── --}}
    @php $aktivak = $megrendelesek->where('Statusz', 1); @endphp
    @if($aktivak->count())
        <div class="u-section-title"><i class="fas fa-spinner" style="color:#2563eb"></i> Aktív megrendelések</div>
        @foreach ($aktivak as $megrendeles)
            @php
                $elsoMunka = $megrendeles->munkak->first();
                $ugyfelId  = $megrendeles->ugyfel->Ugyfel_ID ?? 0;
                $ugyfelNev = rawurlencode($megrendeles->ugyfel->Nev ?? '');
                $szoId     = $elsoMunka?->szolgaltatas?->Szolgaltatas_ID ?? 0;
                $mId       = $megrendeles->Megrendeles_ID;
                $szolgTipus = $megrendeles->munkak->map(fn($m) => $m->szolgaltatas?->Tipus)->filter()->unique()->implode(', ');
                // Fizetés
                $ma = \Carbon\Carbon::today();
                $hatarido = $megrendeles->FizetesiHatarido ? \Carbon\Carbon::parse($megrendeles->FizetesiHatarido) : null;
                $napokHatra = $hatarido ? $ma->diffInDays($hatarido, false) : null;
                if ($megrendeles->Fizetve) {
                    $payBadgeClass = 'fizetve'; $payBadgeIcon = 'fa-check-circle'; $payBadgeLabel = 'Fizetve';
                } elseif ($hatarido && $napokHatra < 0) {
                    $payBadgeClass = 'lejart'; $payBadgeIcon = 'fa-exclamation-circle'; $payBadgeLabel = 'Lejárt';
                } elseif ($hatarido && $napokHatra <= 3) {
                    $payBadgeClass = 'kozel'; $payBadgeIcon = 'fa-clock'; $payBadgeLabel = 'Hamarosan lejár';
                } else {
                    $payBadgeClass = 'varakozik'; $payBadgeIcon = 'fa-hourglass-half'; $payBadgeLabel = 'Fizetésre vár';
                }
            @endphp
            <div class="m-card">
                <div class="m-card-header">
                    <div class="m-order-num">#{{ str_pad($mId, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="m-card-title-wrap">
                        <div class="m-card-name">{{ $megrendeles->Megrendeles_Nev }}</div>
                        <div class="m-card-meta-inline">
                            @if($szolgTipus)
                                <span><i class="fas fa-wrench"></i> {{ $szolgTipus }}</span>
                            @endif
                            @if($megrendeles->varos)
                                <span><i class="fas fa-map-marker-alt"></i>
                                    {{ $megrendeles->varos->Irny_szam ?? '' }}
                                    {{ $megrendeles->varos->Nev ?? '' }},
                                    {{ $megrendeles->Utca_Hazszam }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="m-card-actions">
                        <a href="{{ url('/download-pdf/' . $ugyfelId . '_' . $ugyfelNev . '_' . $szoId . '_' . $mId) }}"
                           class="m-pdf-btn">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <span class="badge-aktiv">Aktív</span>
                    </div>
                </div>

                @if($megrendeles->Vegosszeg)
                <div class="m-payment-bar">
                    <div class="m-payment-info">
                        <div class="m-payment-amount">
                            <i class="fas fa-coins"></i>
                            {{ number_format($megrendeles->Vegosszeg, 0, ',', ' ') }} Ft
                        </div>
                        @if($hatarido)
                        <div class="m-payment-due">
                            <i class="fas fa-calendar-alt"></i>
                            Határidő: {{ $hatarido->format('Y. m. d.') }}
                            @if(!$megrendeles->Fizetve)
                                @if($napokHatra > 0)
                                    <span style="color:#ea580c;font-weight:600;">({{ $napokHatra }} nap)</span>
                                @elseif($napokHatra == 0)
                                    <span style="color:#dc2626;font-weight:600;">(ma lejár!)</span>
                                @else
                                    <span style="color:#dc2626;font-weight:600;">({{ abs($napokHatra) }} napja lejárt)</span>
                                @endif
                            @endif
                        </div>
                        @endif
                        <span class="m-pay-badge {{ $payBadgeClass }}">
                            <i class="fas {{ $payBadgeIcon }}"></i> {{ $payBadgeLabel }}
                        </span>
                        @if($megrendeles->Fizetve && $megrendeles->Fizetve_Idopontja)
                            <span style="font-size:11px;color:#94a3b8;">
                                <i class="fas fa-check" style="color:#16a34a;"></i>
                                {{ \Carbon\Carbon::parse($megrendeles->Fizetve_Idopontja)->format('Y. m. d.') }}
                            </span>
                        @endif
                    </div>
                    @if(!$megrendeles->Fizetve)
                    <a href="{{ route('payment.checkout', $mId) }}" class="m-fizet-btn">
                        <i class="fas fa-credit-card"></i> Fizetek
                    </a>
                    @endif
                </div>
                @endif

                <div class="m-munkak">
                    @foreach($megrendeles->munkak as $munka)
                        @php
                            if (!is_null($munka->Munkabefejezes_Idopontja)) {
                                $munkaStatus = 'befejezett';
                                $munkaStatusLabel = 'Befejezett';
                                $munkaStatusIcon = 'fa-check';
                            } elseif (!is_null($munka->Munkakezdes_Idopontja)) {
                                $munkaStatus = 'folyamatban';
                                $munkaStatusLabel = 'Folyamatban';
                                $munkaStatusIcon = 'fa-hard-hat';
                            } else {
                                $munkaStatus = 'varakozik';
                                $munkaStatusLabel = 'Várakozik';
                                $munkaStatusIcon = 'fa-clock';
                            }
                        @endphp
                        <div class="m-munka-card">
                            <div class="m-munka-header">
                                <div class="m-munka-icon"><i class="fas fa-wrench"></i></div>
                                <div class="m-munka-title">
                                    {{ $munka->szolgaltatas->Tipus ?? 'Elvégzett munka' }}
                                    @if($megrendeles->munkak->count() > 1)
                                        <span class="m-munka-sorszam">{{ $loop->iteration }}. munka</span>
                                    @endif
                                </div>
                                <span class="m-munka-status {{ $munkaStatus }}">
                                    <i class="fas {{ $munkaStatusIcon }}"></i> {{ $munkaStatusLabel }}
                                </span>
                            </div>
                            <div class="m-munka-body">
                                @if($munka->Munkakezdes_Idopontja)
                                <div class="m-detail-item">
                                    <div class="m-detail-label"><i class="fas fa-calendar-alt"></i> Munkakezdés</div>
                                    <div class="m-detail-val">{{ \Carbon\Carbon::parse($munka->Munkakezdes_Idopontja)->format('Y. m. d.') }}</div>
                                </div>
                                @endif
                                @if($munka->Munkabefejezes_Idopontja)
                                <div class="m-detail-item">
                                    <div class="m-detail-label"><i class="fas fa-calendar-check"></i> Befejezés</div>
                                    <div class="m-detail-val">{{ \Carbon\Carbon::parse($munka->Munkabefejezes_Idopontja)->format('Y. m. d.') }}</div>
                                </div>
                                @endif
                                @if($munka->szerelo)
                                <div class="m-detail-item">
                                    <div class="m-detail-label"><i class="fas fa-hard-hat"></i> Szerelő</div>
                                    <div class="m-detail-val">{{ $munka->szerelo->Nev }}</div>
                                </div>
                                @endif
                                @if($munka->felhasznalt_anyagok->count())
                                <div class="m-detail-item wide">
                                    <div class="m-detail-label"><i class="fas fa-boxes"></i> Felhasznált anyagok</div>
                                    <div class="m-detail-val">
                                        @foreach($munka->felhasznalt_anyagok as $fa)
                                            {{ $fa->anyag->Nev ?? '?' }} ({{ $fa->Mennyiseg }} {{ $fa->anyag->Mertekegyseg ?? '' }})@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @if($munka->Leiras)
                                <div class="m-detail-item wide">
                                    <div class="m-detail-label"><i class="fas fa-align-left"></i> Leírás</div>
                                    <div class="m-detail-val">{{ $munka->Leiras }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif

    {{-- ── BEFEJEZETT MEGRENDELÉSEK ── --}}
    @php $befejezettek = $megrendelesek->where('Statusz', 0); @endphp
    @if($befejezettek->count())
        <div class="u-section-title" style="margin-top:8px;"><i class="fas fa-check-circle" style="color:#16a34a"></i> Befejezett megrendelések</div>
        @foreach ($befejezettek as $megrendeles)
            @php
                $elsoMunka   = $megrendeles->munkak->first();
                $ugyfelId    = $megrendeles->ugyfel->Ugyfel_ID ?? 0;
                $ugyfelNev   = rawurlencode($megrendeles->ugyfel->Nev ?? '');
                $szoId       = $elsoMunka?->szolgaltatas?->Szolgaltatas_ID ?? 0;
                $mId         = $megrendeles->Megrendeles_ID;
                $collapseId  = 'mc-' . $mId;
                $szolgTipus  = $megrendeles->munkak->map(fn($m) => $m->szolgaltatas?->Tipus)->filter()->unique()->implode(', ');
                $befDatum    = $megrendeles->munkak->map(fn($m) => $m->Munkabefejezes_Idopontja)->filter()->last();
                $befDatumFmt = $befDatum ? \Carbon\Carbon::parse($befDatum)->format('Y. m. d.') : null;
                // Fizetés
                $ma2 = \Carbon\Carbon::today();
                $hatarido2 = $megrendeles->FizetesiHatarido ? \Carbon\Carbon::parse($megrendeles->FizetesiHatarido) : null;
                $napokHatra2 = $hatarido2 ? $ma2->diffInDays($hatarido2, false) : null;
                if ($megrendeles->Fizetve) {
                    $payBadgeClass2 = 'fizetve'; $payBadgeIcon2 = 'fa-check-circle'; $payBadgeLabel2 = 'Fizetve';
                } elseif ($hatarido2 && $napokHatra2 < 0) {
                    $payBadgeClass2 = 'lejart'; $payBadgeIcon2 = 'fa-exclamation-circle'; $payBadgeLabel2 = 'Lejárt';
                } elseif ($hatarido2 && $napokHatra2 <= 3) {
                    $payBadgeClass2 = 'kozel'; $payBadgeIcon2 = 'fa-clock'; $payBadgeLabel2 = 'Hamarosan lejár';
                } else {
                    $payBadgeClass2 = 'varakozik'; $payBadgeIcon2 = 'fa-hourglass-half'; $payBadgeLabel2 = 'Fizetésre vár';
                }
            @endphp
            <div class="m-card">
                <div class="m-card-header clickable"
                     onclick="toggleM('{{ $collapseId }}')"
                     role="button" aria-expanded="false">
                    <div class="m-order-num" style="color:#16a34a;background:rgba(34,197,94,0.08);border-color:rgba(34,197,94,0.25);">
                        #{{ str_pad($mId, 5, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="m-card-title-wrap">
                        <div class="m-card-name">{{ $megrendeles->Megrendeles_Nev }}</div>
                        <div class="m-card-meta-inline">
                            @if($szolgTipus)
                                <span><i class="fas fa-wrench"></i> {{ $szolgTipus }}</span>
                            @endif
                            @if($befDatumFmt)
                                <span><i class="fas fa-calendar-check"></i> {{ $befDatumFmt }}</span>
                            @endif
                            @if($megrendeles->varos)
                                <span><i class="fas fa-map-marker-alt"></i>
                                    {{ $megrendeles->varos->Nev ?? '' }},
                                    {{ $megrendeles->Utca_Hazszam }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="m-card-actions">
                        <a href="{{ url('/download-pdf/' . $ugyfelId . '_' . $ugyfelNev . '_' . $szoId . '_' . $mId) }}"
                           class="m-pdf-btn"
                           onclick="event.stopPropagation()">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <span class="badge-befej">Befejezve</span>
                        <span class="m-chevron" id="chevron-{{ $collapseId }}"><i class="fas fa-chevron-down"></i></span>
                    </div>
                </div>

                @if($megrendeles->Vegosszeg)
                <div class="m-payment-bar" onclick="event.stopPropagation()">
                    <div class="m-payment-info">
                        <div class="m-payment-amount">
                            <i class="fas fa-coins"></i>
                            {{ number_format($megrendeles->Vegosszeg, 0, ',', ' ') }} Ft
                        </div>
                        @if($hatarido2)
                        <div class="m-payment-due">
                            <i class="fas fa-calendar-alt"></i>
                            Határidő: {{ $hatarido2->format('Y. m. d.') }}
                            @if(!$megrendeles->Fizetve)
                                @if($napokHatra2 > 0)
                                    <span style="color:#ea580c;font-weight:600;">({{ $napokHatra2 }} nap)</span>
                                @elseif($napokHatra2 == 0)
                                    <span style="color:#dc2626;font-weight:600;">(ma lejár!)</span>
                                @else
                                    <span style="color:#dc2626;font-weight:600;">({{ abs($napokHatra2) }} napja lejárt)</span>
                                @endif
                            @endif
                        </div>
                        @endif
                        <span class="m-pay-badge {{ $payBadgeClass2 }}">
                            <i class="fas {{ $payBadgeIcon2 }}"></i> {{ $payBadgeLabel2 }}
                        </span>
                        @if($megrendeles->Fizetve && $megrendeles->Fizetve_Idopontja)
                            <span style="font-size:11px;color:#94a3b8;">
                                <i class="fas fa-check" style="color:#16a34a;"></i>
                                {{ \Carbon\Carbon::parse($megrendeles->Fizetve_Idopontja)->format('Y. m. d.') }}
                            </span>
                        @endif
                    </div>
                    @if(!$megrendeles->Fizetve)
                    <a href="{{ route('payment.checkout', $mId) }}" class="m-fizet-btn">
                        <i class="fas fa-credit-card"></i> Fizetek
                    </a>
                    @endif
                </div>
                @endif

                <div class="m-collapse" id="{{ $collapseId }}">
                    <div class="m-munkak">
                        @foreach($megrendeles->munkak as $munka)
                            <div class="m-munka-card">
                                <div class="m-munka-header">
                                    <div class="m-munka-icon"><i class="fas fa-check"></i></div>
                                    <div class="m-munka-title">
                                        {{ $munka->szolgaltatas->Tipus ?? 'Elvégzett munka' }}
                                        @if($megrendeles->munkak->count() > 1)
                                            <span class="m-munka-sorszam">{{ $loop->iteration }}. munka</span>
                                        @endif
                                    </div>
                                    @if($munka->Munkabefejezes_Idopontja)
                                        <span class="m-munka-status befejezett"><i class="fas fa-check"></i> Befejezett</span>
                                    @endif
                                </div>
                                <div class="m-munka-body">
                                    @if($munka->Munkakezdes_Idopontja)
                                    <div class="m-detail-item">
                                        <div class="m-detail-label"><i class="fas fa-calendar-alt"></i> Munkakezdés</div>
                                        <div class="m-detail-val">{{ \Carbon\Carbon::parse($munka->Munkakezdes_Idopontja)->format('Y. m. d.') }}</div>
                                    </div>
                                    @endif
                                    @if($munka->Munkabefejezes_Idopontja)
                                    <div class="m-detail-item">
                                        <div class="m-detail-label"><i class="fas fa-calendar-check"></i> Befejezés</div>
                                        <div class="m-detail-val">{{ \Carbon\Carbon::parse($munka->Munkabefejezes_Idopontja)->format('Y. m. d.') }}</div>
                                    </div>
                                    @endif
                                    @if($munka->szerelo)
                                    <div class="m-detail-item">
                                        <div class="m-detail-label"><i class="fas fa-hard-hat"></i> Szerelő</div>
                                        <div class="m-detail-val">{{ $munka->szerelo->Nev }}</div>
                                    </div>
                                    @endif
                                    @if($munka->felhasznalt_anyagok->count())
                                    <div class="m-detail-item wide">
                                        <div class="m-detail-label"><i class="fas fa-boxes"></i> Felhasznált anyagok</div>
                                        <div class="m-detail-val">
                                            @foreach($munka->felhasznalt_anyagok as $fa)
                                                {{ $fa->anyag->Nev ?? '?' }} ({{ $fa->Mennyiseg }} {{ $fa->anyag->Mertekegyseg ?? '' }})@if(!$loop->last), @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    @if($munka->Leiras)
                                    <div class="m-detail-item wide">
                                        <div class="m-detail-label"><i class="fas fa-align-left"></i> Leírás</div>
                                        <div class="m-detail-val">{{ $munka->Leiras }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endif

@endif

<script>
function toggleM(id) {
    const panel   = document.getElementById(id);
    const chevron = document.getElementById('chevron-' + id);
    const header  = panel.previousElementSibling;
    const isOpen  = panel.classList.contains('open');
    if (isOpen) {
        panel.style.maxHeight = panel.scrollHeight + 'px';
        requestAnimationFrame(() => { panel.style.maxHeight = '0'; });
        panel.classList.remove('open');
        chevron.classList.remove('open');
        header.setAttribute('aria-expanded', 'false');
    } else {
        panel.classList.add('open');
        chevron.classList.add('open');
        header.setAttribute('aria-expanded', 'true');
        panel.style.maxHeight = panel.scrollHeight + 'px';
        panel.addEventListener('transitionend', function h() {
            if (panel.classList.contains('open')) panel.style.maxHeight = 'none';
            panel.removeEventListener('transitionend', h);
        });
    }
}
</script>

@endsection
