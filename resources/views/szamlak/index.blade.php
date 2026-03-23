@extends('ujlayout')
@section('content')
@include('breadcrumbs')

<style>
/* ── KPI ─────────────────────────────────────────────────────────────────── */
.sz-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}
.sz-kpi-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8edf2;
    padding: 15px 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 13px;
}
.sz-kpi-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; flex-shrink: 0;
}
.sz-kpi-icon.beige  { background: rgba(201,169,122,0.15); color: #a07848; }
.sz-kpi-icon.green  { background: rgba(34,197,94,0.1);   color: #16a34a; }
.sz-kpi-icon.blue   { background: rgba(59,130,246,0.1);  color: #2563eb; }
.sz-kpi-icon.red    { background: rgba(239,68,68,0.1);   color: #dc2626; }
.sz-kpi-icon.orange { background: rgba(249,115,22,0.1);  color: #ea580c; }
.sz-kpi-val { font-size: 20px; font-weight: 800; color: #1e293b; line-height: 1.1; }
.sz-kpi-lbl { font-size: 11px; color: #64748b; margin-top: 2px; font-weight: 500; }

/* ── Típus + filter tab ──────────────────────────────────────────────────── */
.sz-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 14px;
}
.sz-tabs { display: flex; gap: 5px; flex-wrap: wrap; }
.sz-tab {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid #e8edf2;
    color: #64748b;
    background: #fff;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.sz-tab:hover { border-color: #c9a97a; color: #a07848; }
.sz-tab.active { background: #c9a97a; border-color: #c9a97a; color: #fff; }
.sz-tab.active-blue { background: #2563eb; border-color: #2563eb; color: #fff; }
.sz-tab.active-purple { background: #7c3aed; border-color: #7c3aed; color: #fff; }
.sz-tab-count {
    background: rgba(255,255,255,0.3);
    border-radius: 4px;
    padding: 1px 5px;
    font-size: 10px;
}
.sz-tab:not(.active):not(.active-blue):not(.active-purple) .sz-tab-count {
    background: #f1f5f9;
    color: #94a3b8;
}

/* ── Tábla ───────────────────────────────────────────────────────────────── */
.sz-table-wrap { overflow-x: auto; }
.sz-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    border: 1px solid #e8edf2;
}
.sz-table thead tr {
    background: linear-gradient(90deg, rgba(201,169,122,0.08) 0%, rgba(201,169,122,0.02) 100%);
    border-bottom: 1px solid #e8edf2;
}
.sz-table th {
    padding: 11px 14px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #94a3b8;
    text-align: left;
    white-space: nowrap;
}
.sz-table td {
    padding: 11px 14px;
    font-size: 13px;
    color: #334155;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
}
.sz-table tr:last-child td { border-bottom: none; }
.sz-table tbody tr:hover { background: #fafbfc; }
.sz-id { font-size: 11px; font-weight: 700; color: #a07848; background: rgba(201,169,122,0.1); border-radius: 5px; padding: 3px 8px; }
.sz-amount { font-size: 14px; font-weight: 700; color: #1e293b; }
.sz-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 600; border-radius: 5px; padding: 3px 9px;
}
.sz-badge.fuggoben   { background: rgba(59,130,246,0.1); color: #2563eb; }
.sz-badge.fizetve    { background: rgba(34,197,94,0.1);  color: #16a34a; }
.sz-badge.kesedelmes { background: rgba(239,68,68,0.1);  color: #dc2626; }
.sz-badge.stornozva  { background: rgba(100,116,139,0.1); color: #64748b; }
.sz-badge.kozel      { background: rgba(249,115,22,0.1); color: #ea580c; }
.sz-due-ok  { color: #334155; }
.sz-due-warn{ color: #d97706; font-weight: 600; }
.sz-due-err { color: #dc2626; font-weight: 600; }
.sz-tipus-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 600; border-radius: 4px; padding: 2px 7px;
    border: 1px solid;
}
.sz-tipus-szamla   { background: rgba(201,169,122,0.1); color: #a07848; border-color: rgba(201,169,122,0.3); }
.sz-tipus-dijbekero{ background: rgba(59,130,246,0.08); color: #2563eb; border-color: rgba(59,130,246,0.2); }
.sz-tipus-storno   { background: rgba(100,116,139,0.08); color: #64748b; border-color: rgba(100,116,139,0.2); }

@media (max-width: 900px) { .sz-hide-sm { display: none; } }

/* ── Nincs kiállítva jelölés ─────────────────────────────────────────────── */
.sz-table tbody tr.nincs-kiallitva {
    border-left: 3px solid #f59e0b;
    background: rgba(245,158,11,0.03);
}
.sz-table tbody tr.nincs-kiallitva:hover { background: rgba(245,158,11,0.07); }
.sz-nincs-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700; border-radius: 5px; padding: 3px 8px;
    background: rgba(245,158,11,0.12); color: #b45309;
    border: 1px solid rgba(245,158,11,0.3);
    white-space: nowrap;
}
</style>

{{-- Fejléc --}}
<div class="page-header">
    <h1><i class="fas fa-file-invoice"></i> Számlák</h1>
    <div class="page-toolbar">
        <a href="{{ route('szamlak.create') }}" class="btn-save" style="text-decoration:none;">
            <i class="fas fa-plus"></i> Új számla
        </a>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#15803d;font-size:13px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#dc2626;font-size:13px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

{{-- KPI --}}
<div class="sz-kpi-grid">
    <div class="sz-kpi-card">
        <div class="sz-kpi-icon beige"><i class="fas fa-file-invoice"></i></div>
        <div>
            <div class="sz-kpi-val">{{ $kpiFizetve + $kpiFuggoben + $kpiKesedelmes }}</div>
            <div class="sz-kpi-lbl">Összes számla</div>
        </div>
    </div>
    <div class="sz-kpi-card" style="border-color:rgba(34,197,94,0.2);">
        <div class="sz-kpi-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="sz-kpi-val">{{ $kpiFizetve }}</div>
            <div class="sz-kpi-lbl">Fizetve</div>
        </div>
    </div>
    <div class="sz-kpi-card" style="border-color:rgba(59,130,246,0.2);">
        <div class="sz-kpi-icon blue"><i class="fas fa-hourglass-half"></i></div>
        <div>
            <div class="sz-kpi-val">{{ $kpiFuggoben }}</div>
            <div class="sz-kpi-lbl">Függőben</div>
        </div>
    </div>
    <div class="sz-kpi-card" style="border-color:rgba(239,68,68,0.2);">
        <div class="sz-kpi-icon red"><i class="fas fa-clock"></i></div>
        <div>
            <div class="sz-kpi-val">{{ $kpiKesedelmes }}</div>
            <div class="sz-kpi-lbl">Késedelmes</div>
        </div>
    </div>
    <div class="sz-kpi-card" style="border-color:rgba(34,197,94,0.2);">
        <div class="sz-kpi-icon green"><i class="fas fa-wallet"></i></div>
        <div>
            <div class="sz-kpi-val" style="font-size:14px;">{{ number_format($kpiBevertel, 0, ',', ' ') }} Ft</div>
            <div class="sz-kpi-lbl">Befolyt bevétel</div>
        </div>
    </div>
    <div class="sz-kpi-card" style="border-color:rgba(249,115,22,0.2);">
        <div class="sz-kpi-icon orange"><i class="fas fa-coins"></i></div>
        <div>
            <div class="sz-kpi-val" style="font-size:14px;">{{ number_format($kpiVaro, 0, ',', ' ') }} Ft</div>
            <div class="sz-kpi-lbl">Várható bevétel</div>
        </div>
    </div>
</div>

{{-- Típus tabs (Számla / Díjbekérő / Stornó) --}}
<div class="sz-toolbar">
    <div class="sz-tabs">
        <a href="{{ route('szamlak.index', ['tipus' => 'szamla', 'filter' => $filter]) }}"
           class="sz-tab {{ $tipus === 'szamla' ? 'active' : '' }}">
            <i class="fas fa-file-invoice"></i> Számlák
        </a>
        <a href="{{ route('szamlak.index', ['tipus' => 'dijbekero', 'filter' => $filter]) }}"
           class="sz-tab {{ $tipus === 'dijbekero' ? 'active-blue' : '' }}">
            <i class="fas fa-file-alt"></i> Díjbekérők
            <span class="sz-tab-count">{{ $dijbekeroDB }}</span>
        </a>
        <a href="{{ route('szamlak.index', ['tipus' => 'storno', 'filter' => $filter]) }}"
           class="sz-tab {{ $tipus === 'storno' ? 'active-purple' : '' }}">
            <i class="fas fa-ban"></i> Stornók
            <span class="sz-tab-count">{{ $stornoDB }}</span>
        </a>
    </div>

    {{-- Státusz filter --}}
    <div class="sz-tabs">
        @foreach([
            'osszes'     => ['Összes', 'fa-list'],
            'fizetve'    => ['Fizetve', 'fa-check-circle'],
            'varakozik'  => ['Várakozik', 'fa-hourglass-half'],
            'kesedelmes' => ['Késedelmes', 'fa-exclamation-circle'],
        ] as $key => [$label, $icon])
        <a href="{{ route('szamlak.index', ['tipus' => $tipus, 'filter' => $key]) }}"
           class="sz-tab {{ $filter === $key ? 'active' : '' }}">
            <i class="fas {{ $icon }}"></i> {{ $label }}
        </a>
        @endforeach
    </div>
</div>

{{-- Tábla --}}
@if($szamlak->isEmpty())
    <div class="empty-state">
        <i class="fas fa-file-invoice"></i>
        <p>Nincsenek számlák ebben a szűrőben.</p>
    </div>
@else
<div class="sz-table-wrap">
<table class="sz-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Megrendelés</th>
            <th class="sz-hide-sm">Ügyfél</th>
            <th>Bruttó összeg</th>
            <th>Határidő</th>
            <th>Státusz</th>
            <th class="sz-hide-sm">Fizetési mód</th>
            <th class="sz-hide-sm">Billingo</th>
            <th>Műveletek</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($szamlak as $sz)
        @php
            $hatarido = $sz->fizetesi_hatarido;
            $napok    = $hatarido ? $ma->diffInDays($hatarido, false) : null;

            if ($sz->statusz === 'fizetve') {
                $badgeClass = 'fizetve';   $badgeIcon = 'fa-check-circle';    $badgeLabel = 'Fizetve';    $dueCls = 'sz-due-ok';
            } elseif ($sz->statusz === 'stornozva') {
                $badgeClass = 'stornozva'; $badgeIcon = 'fa-ban';             $badgeLabel = 'Stornózva';  $dueCls = 'sz-due-ok';
            } elseif ($hatarido && $hatarido->lt($ma)) {
                $badgeClass = 'kesedelmes';$badgeIcon = 'fa-times-circle';    $badgeLabel = 'Késedelmes'; $dueCls = 'sz-due-err';
            } elseif ($hatarido && $napok <= 3) {
                $badgeClass = 'kozel';     $badgeIcon = 'fa-clock';           $badgeLabel = 'Hamarosan';  $dueCls = 'sz-due-warn';
            } else {
                $badgeClass = 'fuggoben';  $badgeIcon = 'fa-hourglass-half';  $badgeLabel = 'Függőben';   $dueCls = 'sz-due-ok';
            }

            $modLabel = match($sz->fizetesi_mod) {
                'stripe'         => ['fa-credit-card', 'Bankkártya'],
                'banki_atutalas' => ['fa-university',  'Átutalás'],
                'keszpenz'       => ['fa-money-bill',  'Készpénz'],
                default          => ['fa-question',    '–'],
            };
            $nincsKiallitva = empty($sz->billingo_szam) && empty($sz->sajat_pdf_path);
        @endphp
        <tr class="{{ $nincsKiallitva ? 'nincs-kiallitva' : '' }}">
            <td>
                <span class="sz-id">#{{ str_pad($sz->szamla_id, 5, '0', STR_PAD_LEFT) }}</span>
            </td>
            <td style="font-weight:500;">
                @if($sz->megrendeles)
                    <a href="{{ route('megrendeles.show', ['id' => $sz->megrendeles->id]) }}"
                       style="color:#1e293b;text-decoration:none;display:inline-flex;align-items:center;gap:5px;"
                       title="Megrendelés megtekintése">
                        <span style="font-size:10px;color:#a07848;font-weight:700;background:rgba(201,169,122,0.12);border-radius:4px;padding:1px 5px;">
                            MR#{{ str_pad($sz->megrendeles->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                        {{ $sz->megrendeles->megrendeles_nev }}
                    </a>
                @else
                    <span style="color:#94a3b8;">–</span>
                @endif
                <br>
                <span class="sz-tipus-badge sz-tipus-{{ $sz->szamla_tipus }}" style="margin-top:3px;">
                    {{ match($sz->szamla_tipus) { 'szamla' => 'Számla', 'dijbekero' => 'Díjbekérő', 'storno' => 'Stornó', default => $sz->szamla_tipus } }}
                </span>
            </td>
            <td class="sz-hide-sm" style="color:#64748b;">
                {{ $sz->megrendeles->ugyfel->nev ?? '–' }}
            </td>
            <td>
                <span class="sz-amount">{{ number_format($sz->brutto_osszeg, 0, ',', ' ') }} Ft</span>
                <br>
                <span style="font-size:10px;color:#94a3b8;">
                    Nettó: {{ number_format($sz->netto_osszeg, 0, ',', ' ') }} Ft
                </span>
            </td>
            <td>
                @if($hatarido)
                    <span class="{{ $dueCls }}">{{ $hatarido->format('Y. m. d.') }}</span>
                    @if($sz->statusz !== 'fizetve' && $sz->statusz !== 'stornozva' && $napok !== null)
                        <br>
                        <span style="font-size:10px;color:#94a3b8;">
                            @if($napok < 0) {{ abs($napok) }} napja lejárt
                            @elseif($napok == 0) Ma jár le!
                            @else {{ $napok }} nap múlva
                            @endif
                        </span>
                    @endif
                @else
                    <span style="color:#94a3b8;">–</span>
                @endif
            </td>
            <td>
                <span class="sz-badge {{ $badgeClass }}">
                    <i class="fas {{ $badgeIcon }}"></i> {{ $badgeLabel }}
                </span>
            </td>
            <td class="sz-hide-sm">
                <span style="font-size:11px;color:#64748b;display:flex;align-items:center;gap:4px;">
                    <i class="fas {{ $modLabel[0] }}"></i> {{ $modLabel[1] }}
                </span>
            </td>
            <td class="sz-hide-sm">
                @if($sz->billingo_szam)
                    <span style="font-size:11px;font-weight:600;color:#a07848;">{{ $sz->billingo_szam }}</span>
                @elseif($sz->sajat_pdf_path)
                    <span style="font-size:11px;font-weight:600;color:#16a34a;display:inline-flex;align-items:center;gap:4px;">
                        <i class="fas fa-file-pdf"></i> Saját PDF
                    </span>
                @else
                    <span class="sz-nincs-badge">
                        <i class="fas fa-exclamation-triangle"></i> Nincs kiállítva
                    </span>
                @endif
            </td>
            <td>
                <div style="display:flex;gap:5px;flex-wrap:nowrap;">
                    <a href="{{ route('szamlak.show', $sz->szamla_id) }}" class="btn-view" style="font-size:11px;padding:4px 9px;" title="Megtekintés">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if(!in_array($sz->statusz, ['fizetve','stornozva']))
                        <a href="{{ route('szamlak.edit', $sz->szamla_id) }}" class="btn-edit" style="font-size:11px;padding:4px 9px;" title="Szerkesztés">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('szamlak.markAsPaid', $sz->szamla_id) }}" style="display:inline;" onsubmit="return confirm('Megjelöli fizettként?')">
                            @csrf
                            <button type="submit" style="font-size:11px;padding:4px 9px;border-radius:6px;background:rgba(34,197,94,0.1);border:1.5px solid rgba(34,197,94,0.3);color:#16a34a;cursor:pointer;" title="Fizetve jelölés">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    @endif
                    @if($sz->statusz === 'fizetve' && $sz->szamla_tipus === 'szamla' && !$sz->stornoSzamla)
                        <form method="POST" action="{{ route('szamlak.storno', $sz->szamla_id) }}" style="display:inline;" onsubmit="return confirm('Biztosan stornózza a számlát?')">
                            @csrf
                            <button type="submit" style="font-size:11px;padding:4px 9px;border-radius:6px;background:rgba(100,116,139,0.1);border:1.5px solid rgba(100,116,139,0.2);color:#64748b;cursor:pointer;" title="Stornó">
                                <i class="fas fa-ban"></i>
                            </button>
                        </form>
                    @endif
                    @if($sz->billingo_pdf_url)
                        <a href="{{ route('szamlak.download', $sz->szamla_id) }}"
                           style="font-size:11px;padding:4px 9px;border-radius:6px;background:rgba(201,169,122,0.1);border:1.5px solid rgba(201,169,122,0.3);color:#a07848;text-decoration:none;display:inline-flex;align-items:center;"
                           title="PDF letöltés">
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
