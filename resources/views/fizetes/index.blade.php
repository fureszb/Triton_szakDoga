@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<style>
/* KPI */
.pay-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}
.pay-kpi-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8edf2;
    padding: 16px 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 14px;
}
.pay-kpi-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
}
.pay-kpi-icon.green  { background: rgba(34,197,94,0.1);    color: #16a34a; }
.pay-kpi-icon.blue   { background: rgba(59,130,246,0.1);   color: #2563eb; }
.pay-kpi-icon.red    { background: rgba(239,68,68,0.1);    color: #dc2626; }
.pay-kpi-icon.beige  { background: rgba(201,169,122,0.15); color: #a07848; }
.pay-kpi-icon.orange { background: rgba(249,115,22,0.1);   color: #ea580c; }
.pay-kpi-val { font-size: 20px; font-weight: 800; color: #1e293b; line-height: 1.1; }
.pay-kpi-lbl { font-size: 11px; color: #64748b; margin-top: 2px; font-weight: 500; }

/* Filter tabs */
.pay-tabs {
    display: flex;
    gap: 6px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}
.pay-tab {
    padding: 7px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid #e8edf2;
    color: #64748b;
    background: #fff;
    transition: all 0.15s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.pay-tab:hover { border-color: #c9a97a; color: #a07848; }
.pay-tab.active { background: #c9a97a; border-color: #c9a97a; color: #fff; }
.pay-tab .tab-count {
    background: rgba(255,255,255,0.3);
    border-radius: 4px;
    padding: 1px 6px;
    font-size: 10px;
}
.pay-tab:not(.active) .tab-count { background: #f1f5f9; color: #94a3b8; }

/* Table */
.pay-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    border: 1px solid #e8edf2;
}
.pay-table thead tr {
    background: linear-gradient(90deg, rgba(201,169,122,0.08) 0%, rgba(201,169,122,0.02) 100%);
    border-bottom: 1px solid #e8edf2;
}
.pay-table th {
    padding: 11px 14px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #94a3b8;
    text-align: left;
    white-space: nowrap;
}
.pay-table td {
    padding: 12px 14px;
    font-size: 13px;
    color: #334155;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
}
.pay-table tr:last-child td { border-bottom: none; }
.pay-table tbody tr:hover { background: #fafbfc; }
.pay-id { font-size: 11px; font-weight: 700; color: #a07848; background: rgba(201,169,122,0.1); border-radius: 5px; padding: 3px 8px; }
.pay-amount { font-size: 14px; font-weight: 700; color: #1e293b; }
.pay-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 600; border-radius: 5px; padding: 3px 9px;
}
.pay-badge.fizetve   { background: rgba(34,197,94,0.1);    color: #16a34a; }
.pay-badge.varakozik { background: rgba(59,130,246,0.1);   color: #2563eb; }
.pay-badge.lejart    { background: rgba(239,68,68,0.1);    color: #dc2626; }
.pay-badge.kozel     { background: rgba(249,115,22,0.1);   color: #ea580c; }
.pay-badge.stornozva { background: rgba(148,163,184,0.15); color: #64748b; }
.pay-due-ok   { color: #334155; }
.pay-due-warn { color: #d97706; font-weight: 600; }
.pay-due-err  { color: #dc2626; font-weight: 600; }
.pay-mod { font-size: 11px; color: #94a3b8; display: flex; align-items: center; gap: 4px; }

@media (max-width: 900px) {
    .pay-table-wrap { overflow-x: auto; }
    .pay-hide-sm { display: none; }
}
</style>

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <h1><i class="fas fa-coins"></i> Fizetések</h1>
    <a href="{{ route('szamlak.index') }}" class="btn-view" style="font-size:13px;">
        <i class="fas fa-file-invoice-dollar"></i> Számlák kezelése
    </a>
</div>

{{-- KPI kártyák --}}
<div class="pay-kpi-grid">
    <div class="pay-kpi-card">
        <div class="pay-kpi-icon beige"><i class="fas fa-receipt"></i></div>
        <div>
            <div class="pay-kpi-val">{{ $kpiFizetve + $kpiVarakozik + $kpiLejart }}</div>
            <div class="pay-kpi-lbl">Összes számla</div>
        </div>
    </div>
    <div class="pay-kpi-card" style="border-color:rgba(34,197,94,0.2);">
        <div class="pay-kpi-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="pay-kpi-val">{{ $kpiFizetve }}</div>
            <div class="pay-kpi-lbl">Fizetve</div>
        </div>
    </div>
    <div class="pay-kpi-card" style="border-color:rgba(59,130,246,0.2);">
        <div class="pay-kpi-icon blue"><i class="fas fa-hourglass-half"></i></div>
        <div>
            <div class="pay-kpi-val">{{ $kpiVarakozik }}</div>
            <div class="pay-kpi-lbl">Várakozik</div>
        </div>
    </div>
    <div class="pay-kpi-card" style="border-color:rgba(239,68,68,0.2);">
        <div class="pay-kpi-icon red"><i class="fas fa-times-circle"></i></div>
        <div>
            <div class="pay-kpi-val">{{ $kpiLejart }}</div>
            <div class="pay-kpi-lbl">Lejárt határidő</div>
        </div>
    </div>
    <div class="pay-kpi-card" style="border-color:rgba(34,197,94,0.2);">
        <div class="pay-kpi-icon green"><i class="fas fa-wallet"></i></div>
        <div>
            <div class="pay-kpi-val" style="font-size:15px;">{{ number_format($kpiBevertel, 0, ',', ' ') }} Ft</div>
            <div class="pay-kpi-lbl">Befolyt bevétel</div>
        </div>
    </div>
    <div class="pay-kpi-card" style="border-color:rgba(249,115,22,0.2);">
        <div class="pay-kpi-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <div class="pay-kpi-val" style="font-size:15px;">{{ number_format($kpiVaroBevertel, 0, ',', ' ') }} Ft</div>
            <div class="pay-kpi-lbl">Várható bevétel</div>
        </div>
    </div>
</div>

{{-- Filter tabs --}}
<div class="pay-tabs">
    <a href="{{ route('fizetes.index') }}"
       class="pay-tab {{ $filter === 'osszes' ? 'active' : '' }}">
        <i class="fas fa-list"></i> Összes
        <span class="tab-count">{{ $kpiFizetve + $kpiVarakozik + $kpiLejart }}</span>
    </a>
    <a href="{{ route('fizetes.index', ['filter' => 'fizetve']) }}"
       class="pay-tab {{ $filter === 'fizetve' ? 'active' : '' }}">
        <i class="fas fa-check-circle"></i> Fizetve
        <span class="tab-count">{{ $kpiFizetve }}</span>
    </a>
    <a href="{{ route('fizetes.index', ['filter' => 'varakozik']) }}"
       class="pay-tab {{ $filter === 'varakozik' ? 'active' : '' }}">
        <i class="fas fa-hourglass-half"></i> Várakozik
        <span class="tab-count">{{ $kpiVarakozik }}</span>
    </a>
    <a href="{{ route('fizetes.index', ['filter' => 'lejart']) }}"
       class="pay-tab {{ $filter === 'lejart' ? 'active' : '' }}">
        <i class="fas fa-exclamation-circle"></i> Lejárt
        <span class="tab-count">{{ $kpiLejart }}</span>
    </a>
</div>

{{-- Táblázat --}}
@if($szamlak->isEmpty())
    <div class="empty-state">
        <i class="fas fa-coins"></i>
        <p>Nincsenek számlák ebben a szűrőben.</p>
    </div>
@else
<div class="pay-table-wrap">
<table class="pay-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Megrendelés</th>
            <th class="pay-hide-sm">Ügyfél</th>
            <th>Bruttó összeg</th>
            <th>Határidő</th>
            <th>Státusz</th>
            <th class="pay-hide-sm">Fizetve</th>
            <th class="pay-hide-sm">Módja</th>
            <th>Műveletek</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($szamlak as $szamla)
        @php
            $hatarido = $szamla->fizetesi_hatarido;
            $napok    = $hatarido ? $ma->diffInDays($hatarido, false) : null;
            $sikerFiz = $szamla->fizetesek->where('statusz', 'fizetve')->first();

            if ($szamla->statusz === 'fizetve') {
                $badgeClass = 'fizetve';   $badgeIcon = 'fa-check-circle';   $badgeLabel = 'Fizetve';   $dueCls = 'pay-due-ok';
            } elseif ($szamla->statusz === 'stornozva') {
                $badgeClass = 'stornozva'; $badgeIcon = 'fa-ban';             $badgeLabel = 'Stornózva'; $dueCls = 'pay-due-ok';
            } elseif ($hatarido && $hatarido->lt($ma)) {
                $badgeClass = 'lejart';    $badgeIcon = 'fa-times-circle';   $badgeLabel = 'Lejárt';    $dueCls = 'pay-due-err';
            } elseif ($hatarido && $napok !== null && $napok <= 3) {
                $badgeClass = 'kozel';     $badgeIcon = 'fa-clock';          $badgeLabel = 'Hamarosan'; $dueCls = 'pay-due-warn';
            } else {
                $badgeClass = 'varakozik'; $badgeIcon = 'fa-hourglass-half'; $badgeLabel = 'Várakozik'; $dueCls = 'pay-due-ok';
            }
        @endphp
        <tr>
            <td>
                <span class="pay-id">#{{ str_pad($szamla->szamla_id, 5, '0', STR_PAD_LEFT) }}</span>
                @if($szamla->billingo_szam)
                    <br><span style="font-size:10px;color:#94a3b8;">{{ $szamla->billingo_szam }}</span>
                @endif
            </td>
            <td style="font-weight:500;">
                {{ $szamla->megrendeles->Megrendeles_Nev ?? '—' }}
                <br><span style="font-size:10px;color:#94a3b8;">MR#{{ str_pad($szamla->megrendeles_id, 5, '0', STR_PAD_LEFT) }}</span>
            </td>
            <td class="pay-hide-sm" style="color:#64748b;">
                {{ $szamla->megrendeles->ugyfel->Nev ?? '—' }}
            </td>
            <td>
                <span class="pay-amount">{{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft</span>
                <br><span style="font-size:10px;color:#94a3b8;">Nettó: {{ number_format($szamla->netto_osszeg, 0, ',', ' ') }} Ft</span>
            </td>
            <td>
                @if($hatarido)
                    <span class="{{ $dueCls }}">{{ $hatarido->format('Y. m. d.') }}</span>
                    @if(!in_array($szamla->statusz, ['fizetve','stornozva']) && $napok !== null)
                        <br><span style="font-size:10px;color:#94a3b8;">
                            @if($napok < 0) {{ abs((int)$napok) }} napja lejárt
                            @elseif($napok == 0) Ma lejár!
                            @else {{ (int)$napok }} nap múlva
                            @endif
                        </span>
                    @endif
                @else
                    <span style="color:#94a3b8;">—</span>
                @endif
            </td>
            <td>
                <span class="pay-badge {{ $badgeClass }}">
                    <i class="fas {{ $badgeIcon }}"></i> {{ $badgeLabel }}
                </span>
            </td>
            <td class="pay-hide-sm">
                @if($sikerFiz)
                    <span style="font-size:12px;color:#334155;">{{ $sikerFiz->fizetes_idopontja?->format('Y. m. d.') }}</span>
                @else
                    <span style="color:#94a3b8;">—</span>
                @endif
            </td>
            <td class="pay-hide-sm">
                @if($szamla->fizetesi_mod)
                    <span class="pay-mod">
                        <i class="fas {{ $szamla->fizetesi_mod === 'stripe' ? 'fa-credit-card' : 'fa-university' }}"></i>
                        {{ $szamla->fizetesi_mod === 'stripe' ? 'Bankkártya' : 'Átutalás' }}
                    </span>
                @else
                    <span style="color:#94a3b8;font-size:12px;">—</span>
                @endif
            </td>
            <td>
                <div style="display:flex;gap:6px;flex-wrap:nowrap;">
                    <a href="{{ route('szamlak.show', $szamla->szamla_id) }}"
                       class="btn-view" style="font-size:11px;padding:4px 10px;" title="Megtekintés">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if(in_array($szamla->statusz, ['fuggoben','kesedelmes']))
                    <form method="POST" action="{{ route('szamlak.markAsPaid', $szamla->szamla_id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-save"
                            style="font-size:11px;padding:4px 10px;margin-top:0;border-radius:6px;"
                            title="Megjelölés fizetve">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @endif
                    @if($szamla->billingo_pdf_url)
                    <a href="{{ route('szamlak.download', $szamla->szamla_id) }}"
                       style="font-size:11px;padding:4px 10px;border-radius:6px;background:rgba(201,169,122,0.1);border:1.5px solid rgba(201,169,122,0.35);color:#a07848;text-decoration:none;display:inline-flex;align-items:center;"
                       title="Számla letöltése">
                        <i class="fas fa-download"></i>
                    </a>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endif

@endsection
