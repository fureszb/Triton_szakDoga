@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<style>
/* ── KPI kártyák ──────────────────────────────────────────── */
.em-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}
.em-kpi {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8edf2;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.em-kpi-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.em-kpi-num  { font-size: 26px; font-weight: 800; color: #1e293b; line-height: 1; }
.em-kpi-lbl  { font-size: 11px; color: #94a3b8; margin-top: 3px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }

/* ── Filter tab sáv ──────────────────────────────────────── */
.em-tabs {
    display: flex; gap: 6px; margin-bottom: 18px;
}
.em-tab {
    padding: 7px 16px; border-radius: 8px; font-size: 12px; font-weight: 600;
    text-decoration: none; color: #64748b;
    background: #fff; border: 1px solid #e2e8f0;
    transition: all 0.15s;
}
.em-tab:hover { color: #334155; border-color: #c9a97a; }
.em-tab.active { background: #1e293b; color: #fff; border-color: #1e293b; }
.em-tab .badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 18px; height: 18px; padding: 0 5px;
    border-radius: 9px; font-size: 10px; font-weight: 700;
    background: rgba(255,255,255,0.2); margin-left: 5px;
}
.em-tab:not(.active) .badge { background: #f1f5f9; color: #64748b; }

/* ── Főtáblázat ──────────────────────────────────────────── */
.em-table-wrap {
    background: #fff; border-radius: 14px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden; margin-bottom: 24px;
}
.em-table { width: 100%; border-collapse: collapse; }
.em-table th {
    background: #f8fafc; padding: 11px 16px;
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.8px; color: #64748b; text-align: left;
    border-bottom: 1px solid #e8edf2;
}
.em-table td {
    padding: 13px 16px; border-bottom: 1px solid #f1f5f9;
    font-size: 13px; color: #334155; vertical-align: middle;
}
.em-table tr:last-child td { border-bottom: none; }
.em-table tr:hover td { background: #fafbfc; }

/* ── Típus + státusz badge-ek ────────────────────────────── */
.tip-szamla   { background: rgba(34,197,94,0.09);  color: #15803d; border: 1px solid rgba(34,197,94,0.25); padding:3px 8px; border-radius:5px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; }
.tip-dijbekero{ background: rgba(124,58,237,0.09); color: #6d28d9; border: 1px solid rgba(124,58,237,0.25);padding:3px 8px; border-radius:5px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; }
.hat-lejart   { color: #dc2626; font-weight: 700; }
.hat-kozel    { color: #d97706; font-weight: 600; }
.hat-ok       { color: #334155; }

/* ── Küldés gomb / mező ──────────────────────────────────── */
.em-send-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: 7px; font-size: 11px; font-weight: 700;
    background: linear-gradient(135deg,#1e293b,#334155);
    color: #fff; border: none; cursor: pointer;
    transition: all 0.15s; white-space: nowrap;
}
.em-send-btn:hover { background: linear-gradient(135deg,#0f172a,#1e293b); transform: translateY(-1px); }
.em-send-btn.sent  { background: linear-gradient(135deg,#15803d,#16a34a); }

/* ── Inline emlékeztető panel ────────────────────────────── */
.em-inline-form {
    display: none;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 16px;
    margin-top: 8px;
}
.em-inline-form textarea {
    width: 100%; box-sizing: border-box;
    border: 1px solid #e2e8f0; border-radius: 7px;
    padding: 9px 12px; font-size: 12px; color: #334155;
    resize: vertical; min-height: 70px; font-family: inherit;
    background: #fff;
}
.em-inline-form textarea:focus { outline: none; border-color: #c9a97a; box-shadow: 0 0 0 3px rgba(201,169,122,0.15); }
.em-inline-actions { display: flex; gap: 8px; margin-top: 8px; justify-content: flex-end; }
.btn-cancel-em {
    padding: 6px 13px; border-radius: 7px; font-size: 11px; font-weight: 600;
    background: #fff; border: 1px solid #e2e8f0; color: #64748b; cursor: pointer;
}
.btn-cancel-em:hover { background: #f1f5f9; }

/* ── Előzmény panel ──────────────────────────────────────── */
.em-history-wrap {
    background: #fff; border-radius: 14px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.em-history-head {
    padding: 14px 20px; border-bottom: 1px solid #f1f5f9;
    font-size: 12px; font-weight: 700; color: #334155;
    display: flex; align-items: center; gap: 8px;
}
.em-history-row {
    display: flex; align-items: center; gap: 12px;
    padding: 11px 20px; border-bottom: 1px solid #f8fafc; font-size: 12px;
}
.em-history-row:last-child { border-bottom: none; }
.em-hist-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
}
.em-hist-dot.sikeres   { background: #16a34a; }
.em-hist-dot.sikertelen{ background: #dc2626; }

@media (max-width: 900px) {
    .em-kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

{{-- Flash üzenetek --}}
@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
@endif

{{-- ── Oldal fejléc ─────────────────────────────────────────────────────── --}}
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-bell" style="color:#c9a97a;"></i> Fizetési emlékeztetők</h1>
        <p style="font-size:12px;color:#94a3b8;margin:4px 0 0;">Manuális emlékeztető emailek küldése függőben lévő számlákhoz és díjbekérőkhöz</p>
    </div>
</div>

{{-- ── KPI kártyák ──────────────────────────────────────────────────────── --}}
<div class="em-kpi-grid">
    <div class="em-kpi">
        <div class="em-kpi-icon" style="background:rgba(30,41,59,0.08);color:#1e293b;"><i class="fas fa-list"></i></div>
        <div><div class="em-kpi-num">{{ $stats['osszes'] }}</div><div class="em-kpi-lbl">Összes függőben</div></div>
    </div>
    <div class="em-kpi">
        <div class="em-kpi-icon" style="background:rgba(220,38,38,0.08);color:#dc2626;"><i class="fas fa-exclamation-triangle"></i></div>
        <div><div class="em-kpi-num" style="color:#dc2626;">{{ $stats['lejart'] }}</div><div class="em-kpi-lbl">Lejárt határidő</div></div>
    </div>
    <div class="em-kpi">
        <div class="em-kpi-icon" style="background:rgba(217,119,6,0.08);color:#d97706;"><i class="fas fa-clock"></i></div>
        <div><div class="em-kpi-num" style="color:#d97706;">{{ $stats['harom_napon'] }}</div><div class="em-kpi-lbl">3 napon belül lejár</div></div>
    </div>
    <div class="em-kpi">
        <div class="em-kpi-icon" style="background:rgba(34,197,94,0.08);color:#16a34a;"><i class="fas fa-paper-plane"></i></div>
        <div><div class="em-kpi-num" style="color:#16a34a;">{{ $stats['kuldott_ma'] }}</div><div class="em-kpi-lbl">Ma elküldve</div></div>
    </div>
</div>

{{-- ── Filter tab-ok ────────────────────────────────────────────────────── --}}
<div class="em-tabs">
    <a href="{{ route('emlekeztetok.index', ['filter' => 'esedékes']) }}"
       class="em-tab {{ $filter === 'esedékes' ? 'active' : '' }}">
        <i class="fas fa-list"></i> Összes függőben
        <span class="badge">{{ $stats['osszes'] }}</span>
    </a>
    <a href="{{ route('emlekeztetok.index', ['filter' => 'lejart']) }}"
       class="em-tab {{ $filter === 'lejart' ? 'active' : '' }}">
        <i class="fas fa-exclamation-triangle"></i> Lejártak
        <span class="badge">{{ $stats['lejart'] }}</span>
    </a>
    <a href="{{ route('emlekeztetok.index', ['filter' => 'kozel']) }}"
       class="em-tab {{ $filter === 'kozel' ? 'active' : '' }}">
        <i class="fas fa-clock"></i> 3 napon belül lejár
        <span class="badge">{{ $stats['harom_napon'] }}</span>
    </a>
</div>

{{-- ── Fő táblázat ──────────────────────────────────────────────────────── --}}
@if($szamlak->isEmpty())
    <div class="em-table-wrap">
        <div class="empty-state" style="padding:50px 20px;">
            <i class="fas fa-check-circle" style="color:#16a34a;"></i>
            <p>Nincs függőben lévő számla vagy díjbekérő ebben a szűrőben.</p>
        </div>
    </div>
@else
<div class="em-table-wrap">
    <table class="em-table">
        <thead>
            <tr>
                <th>Szám / Típus</th>
                <th>Ügyfél</th>
                <th>Összeg</th>
                <th>Fizetési határidő</th>
                <th>Utolsó emlékeztető</th>
                <th style="text-align:right;">Küldés</th>
            </tr>
        </thead>
        <tbody>
        @foreach($szamlak as $sz)
        @php
            $hatarido   = $sz->fizetesi_hatarido;
            $napok      = $hatarido ? (int) now()->diffInDays($hatarido, false) : null;
            $ugyfelNev  = $sz->ugyfel?->nev ?? $sz->megrendeles?->ugyfel?->nev ?? '—';
            $ugyfelEmail= $sz->ugyfel?->email ?? $sz->megrendeles?->ugyfel?->email ?? null;
            $szamlaNum  = $sz->billingo_szam ?? 'TRITON-' . str_pad($sz->szamla_id, 6, '0', STR_PAD_LEFT);
            $utolsoEm   = $sz->emlekeztetok->first();   // már desc sorrendben van
            $kuldve     = $sz->emlekeztetok->where('statusz','sikeres')->count();
        @endphp
        <tr>
            {{-- Szám / Típus --}}
            <td>
                <div style="font-weight:700;font-size:13px;color:#1e293b;">{{ $szamlaNum }}</div>
                <div style="margin-top:4px;">
                    @if($sz->szamla_tipus === 'dijbekero')
                        <span class="tip-dijbekero">Díjbekérő</span>
                    @else
                        <span class="tip-szamla">Számla</span>
                    @endif
                </div>
            </td>

            {{-- Ügyfél --}}
            <td>
                <div style="font-weight:600;">{{ $ugyfelNev }}</div>
                @if($ugyfelEmail)
                    <div style="font-size:11px;color:#94a3b8;margin-top:2px;">
                        <i class="fas fa-envelope" style="font-size:9px;"></i> {{ $ugyfelEmail }}
                    </div>
                @else
                    <div style="font-size:11px;color:#ef4444;margin-top:2px;">
                        <i class="fas fa-exclamation-triangle"></i> Nincs email cím
                    </div>
                @endif
                @if($sz->megrendeles)
                    <div style="font-size:11px;color:#94a3b8;margin-top:2px;">
                        <i class="fas fa-file-contract" style="font-size:9px;"></i>
                        {{ $sz->megrendeles->megrendeles_nev }}
                    </div>
                @endif
            </td>

            {{-- Összeg --}}
            <td>
                <div style="font-weight:800;font-size:14px;color:#1e293b;">
                    {{ number_format($sz->brutto_osszeg, 0, ',', ' ') }} Ft
                </div>
                <div style="font-size:10px;color:#94a3b8;margin-top:2px;">bruttó</div>
            </td>

            {{-- Határidő --}}
            <td>
                @if($hatarido)
                    <div class="{{ $napok < 0 ? 'hat-lejart' : ($napok <= 3 ? 'hat-kozel' : 'hat-ok') }}">
                        {{ $hatarido->format('Y. m. d.') }}
                    </div>
                    <div style="font-size:11px;margin-top:3px;">
                        @if($napok < 0)
                            <span style="background:#fee2e2;color:#dc2626;padding:2px 7px;border-radius:4px;font-weight:700;">
                                {{ abs($napok) }} napja lejárt
                            </span>
                        @elseif($napok === 0)
                            <span style="background:#fef3c7;color:#d97706;padding:2px 7px;border-radius:4px;font-weight:700;">
                                MA jár le
                            </span>
                        @elseif($napok <= 3)
                            <span style="background:#fff7ed;color:#d97706;padding:2px 7px;border-radius:4px;font-weight:600;">
                                {{ $napok }} nap múlva
                            </span>
                        @else
                            <span style="color:#94a3b8;">{{ $napok }} nap múlva</span>
                        @endif
                    </div>
                @else
                    <span style="color:#94a3b8;">—</span>
                @endif
            </td>

            {{-- Utolsó emlékeztető --}}
            <td>
                @if($utolsoEm)
                    <div style="font-size:12px;color:#334155;">
                        {{ $utolsoEm->created_at?->format('Y. m. d. H:i') ?? '—' }}
                    </div>
                    <div style="font-size:11px;margin-top:3px;">
                        @if($utolsoEm->statusz === 'sikeres')
                            <span style="color:#16a34a;"><i class="fas fa-check-circle"></i> Sikeres</span>
                        @else
                            <span style="color:#dc2626;"><i class="fas fa-times-circle"></i> Sikertelen</span>
                        @endif
                        @if($kuldve > 1)
                            <span style="color:#94a3b8;margin-left:6px;">(összesen {{ $kuldve }}×)</span>
                        @endif
                    </div>
                @else
                    <span style="font-size:11px;color:#94a3b8;"><i class="fas fa-minus"></i> Még nem küldtünk</span>
                @endif
            </td>

            {{-- Küldés gomb + inline form --}}
            <td style="text-align:right;">
                @if($ugyfelEmail)
                    <div>
                        <button type="button" class="em-send-btn {{ $kuldve ? 'sent' : '' }}"
                                onclick="toggleEmForm({{ $sz->szamla_id }})">
                            <i class="fas fa-paper-plane"></i>
                            {{ $kuldve ? 'Újra küldés' : 'Emlékeztető küldése' }}
                        </button>
                    </div>
                    {{-- Inline form --}}
                    <div id="em-form-{{ $sz->szamla_id }}" class="em-inline-form">
                        <form action="{{ route('emlekeztetok.kuldes', $sz->szamla_id) }}" method="POST">
                            @csrf
                            <label style="font-size:11px;font-weight:600;color:#64748b;display:block;margin-bottom:5px;">
                                <i class="fas fa-edit"></i> Egyedi üzenet (opcionális):
                            </label>
                            <textarea name="egyedi_uzenet"
                                      placeholder="Pl: Kérjük, rendezze a fennálló összeget... (üresen hagyható)"></textarea>
                            <div class="em-inline-actions">
                                <button type="button" class="btn-cancel-em"
                                        onclick="toggleEmForm({{ $sz->szamla_id }})">
                                    Mégse
                                </button>
                                <button type="submit" class="em-send-btn">
                                    <i class="fas fa-paper-plane"></i> Küldés: {{ $ugyfelEmail }}
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <span style="font-size:11px;color:#ef4444;background:#fff5f5;padding:5px 10px;border-radius:6px;border:1px solid #fecaca;">
                        <i class="fas fa-ban"></i> Nincs email
                    </span>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- ── Küldési előzmények panel ─────────────────────────────────────────── --}}
<div class="em-history-wrap">
    <div class="em-history-head">
        <i class="fas fa-history" style="color:#c9a97a;"></i>
        Legutóbbi emlékeztetők (utolsó 20)
    </div>
    @if($elozmeny->isEmpty())
        <div style="padding:24px 20px;text-align:center;font-size:13px;color:#94a3b8;">
            Még nem lett emlékeztető küldve.
        </div>
    @else
        @foreach($elozmeny as $em)
        @php
            $szNev = $em->szamla
                ? ('TRITON-' . str_pad($em->szamla->szamla_id, 6, '0', STR_PAD_LEFT))
                : ('Számla #' . $em->szamla_id);
            $uNev  = $em->ugyfel?->nev ?? '—';
            $tipus = match($em->tipus) {
                'harom_napos' => '3 napos',
                'egy_napos'   => '1 napos',
                'lejart'      => 'Lejárt',
                'manualis'    => 'Manuális',
                default       => $em->tipus,
            };
        @endphp
        <div class="em-history-row">
            <div class="em-hist-dot {{ $em->statusz }}"></div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:12px;color:#334155;">
                    {{ $szNev }} — {{ $uNev }}
                </div>
                <div style="font-size:11px;color:#94a3b8;margin-top:2px;">
                    {{ $em->email_cim }} &nbsp;·&nbsp;
                    <span style="background:#f1f5f9;padding:1px 6px;border-radius:4px;font-size:10px;font-weight:600;text-transform:uppercase;">{{ $tipus }}</span>
                </div>
            </div>
            <div style="flex-shrink:0;text-align:right;">
                <div style="font-size:11px;color:#94a3b8;">{{ $em->created_at?->format('Y. m. d. H:i') }}</div>
                @if($em->statusz === 'sikeres')
                    <div style="font-size:11px;color:#16a34a;font-weight:600;margin-top:2px;">
                        <i class="fas fa-check"></i> Elküldve
                    </div>
                @else
                    <div style="font-size:11px;color:#dc2626;font-weight:600;margin-top:2px;">
                        <i class="fas fa-times"></i> Sikertelen
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    @endif
</div>

<script>
function toggleEmForm(id) {
    const form = document.getElementById('em-form-' + id);
    if (!form) return;
    const isOpen = form.style.display === 'block';
    // Zárjuk be az összes nyitott formot
    document.querySelectorAll('.em-inline-form').forEach(f => f.style.display = 'none');
    // Ha nem volt nyitva, nyissuk meg
    if (!isOpen) {
        form.style.display = 'block';
        form.querySelector('textarea')?.focus();
    }
}
</script>

@endsection
