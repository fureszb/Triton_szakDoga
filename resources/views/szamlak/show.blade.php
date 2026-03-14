@extends('ujlayout')
@section('content')
@include('breadcrumbs')

<style>
.sz-show-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    gap: 16px; margin-bottom: 22px; flex-wrap: wrap;
}
.sz-show-title { font-size: 19px; font-weight: 700; color: #1e293b; margin: 0 0 6px; display: flex; align-items: center; gap: 10px; }
.sz-show-title i { color: #c9a97a; }
.sz-show-id {
    display: inline-block; font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 6px; background: rgba(201,169,122,0.12); border: 1px solid rgba(201,169,122,0.3); color: #a07848;
}
.sz-show-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
/* Cards */
.sz-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px; }
.sz-card { background: #fff; border-radius: 12px; border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.05); overflow: hidden; }
.sz-card-full { grid-column: 1 / -1; }
.sz-card-header {
    display: flex; align-items: center; gap: 10px; padding: 12px 18px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(90deg, rgba(201,169,122,0.07) 0%, rgba(201,169,122,0.01) 100%);
}
.sz-card-icon { width: 30px; height: 30px; border-radius: 7px; background: rgba(201,169,122,0.15); color: #a07848; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }
.sz-card-title { font-size: 12px; font-weight: 700; color: #1e293b; }
.sz-card-badge { margin-left: auto; }
/* Info rows */
.sz-info-rows { padding: 4px 0; }
.sz-info-row { display: flex; align-items: flex-start; gap: 12px; padding: 10px 18px; border-bottom: 1px solid #f8fafc; }
.sz-info-row:last-child { border-bottom: none; }
.sz-info-lbl { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; min-width: 130px; flex-shrink: 0; display: flex; align-items: center; gap: 5px; padding-top: 1px; }
.sz-info-lbl i { color: #c9a97a; font-size: 10px; }
.sz-info-val { font-size: 13px; font-weight: 500; color: #334155; flex: 1; }
/* Badges */
.sz-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; border-radius: 5px; padding: 3px 9px; }
.sz-badge.fuggoben   { background: rgba(59,130,246,0.1); color: #2563eb; }
.sz-badge.fizetve    { background: rgba(34,197,94,0.1);  color: #16a34a; }
.sz-badge.kesedelmes { background: rgba(239,68,68,0.1);  color: #dc2626; }
.sz-badge.stornozva  { background: rgba(100,116,139,0.1); color: #64748b; }
/* Tételek tábla */
.sz-tetel-table { width: 100%; border-collapse: collapse; }
.sz-tetel-table th { padding: 9px 14px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: #94a3b8; text-align: left; border-bottom: 1px solid #f1f5f9; background: rgba(201,169,122,0.04); }
.sz-tetel-table td { padding: 10px 14px; font-size: 12px; color: #334155; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
.sz-tetel-table tr:last-child td { border-bottom: none; }
.sz-tetel-table tfoot td { padding: 10px 14px; font-size: 12px; font-weight: 600; border-top: 2px solid #e8edf2; }
.sz-tetel-tip { display: inline-flex; align-items: center; gap: 3px; font-size: 10px; font-weight: 600; border-radius: 4px; padding: 2px 6px; }
.sz-tetel-tip.anyag    { background: rgba(201,169,122,0.1); color: #a07848; }
.sz-tetel-tip.munkaora { background: rgba(59,130,246,0.1);  color: #2563eb; }
.sz-tetel-tip.egyeb    { background: rgba(100,116,139,0.1); color: #64748b; }
/* Audit log */
.sz-audit-item { display: flex; gap: 12px; padding: 10px 18px; border-bottom: 1px solid #f8fafc; }
.sz-audit-item:last-child { border-bottom: none; }
.sz-audit-dot { width: 8px; height: 8px; border-radius: 50%; background: #c9a97a; flex-shrink: 0; margin-top: 5px; }
.sz-audit-evt { font-size: 12px; font-weight: 600; color: #1e293b; }
.sz-audit-meta { font-size: 11px; color: #94a3b8; margin-top: 2px; }
/* Fizetési tranzakciók */
.sz-pay-row { display: flex; align-items: center; gap: 12px; padding: 10px 18px; border-bottom: 1px solid #f8fafc; }
.sz-pay-row:last-child { border-bottom: none; }
.sz-pay-dot { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }
.sz-pay-dot.fizetve  { background: rgba(34,197,94,0.1);  color: #16a34a; }
.sz-pay-dot.fuggoben { background: rgba(59,130,246,0.1); color: #2563eb; }
.sz-pay-dot.sikertelen { background: rgba(239,68,68,0.1); color: #dc2626; }

@media (max-width: 768px) { .sz-grid { grid-template-columns: 1fr; } .sz-card-full { grid-column: 1; } }
</style>

@php
    $statuszLabel = match($szamla->statusz) {
        'fuggoben'   => ['fuggoben',   'fa-hourglass-half',  'Függőben'],
        'fizetve'    => ['fizetve',    'fa-check-circle',    'Fizetve'],
        'kesedelmes' => ['kesedelmes', 'fa-exclamation-circle','Késedelmes'],
        'stornozva'  => ['stornozva',  'fa-ban',             'Stornózva'],
        default      => ['fuggoben',   'fa-question',        $szamla->statusz],
    };
    $ma = \Carbon\Carbon::today();
    $napok = $szamla->fizetesi_hatarido ? $ma->diffInDays($szamla->fizetesi_hatarido, false) : null;
@endphp

{{-- Fejléc --}}
<div class="sz-show-header">
    <div>
        <div class="sz-show-title">
            <i class="fas fa-file-invoice"></i>
            {{ $szamla->megrendeles->Megrendeles_Nev ?? 'Számla' }}
        </div>
        <span class="sz-show-id">#{{ str_pad($szamla->szamla_id, 5, '0', STR_PAD_LEFT) }}</span>
        &nbsp;
        <span class="sz-badge {{ $statuszLabel[0] }}">
            <i class="fas {{ $statuszLabel[1] }}"></i> {{ $statuszLabel[2] }}
        </span>
    </div>
    <div class="sz-show-actions">
        <a href="{{ route('szamlak.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
        @if(!in_array($szamla->statusz, ['fizetve','stornozva']))
            <a href="{{ route('szamlak.edit', $szamla->szamla_id) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Szerkesztés
            </a>
            <form method="POST" action="{{ route('szamlak.markAsPaid', $szamla->szamla_id) }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-save" style="border:none;cursor:pointer;">
                    <i class="fas fa-check"></i> Megjelölés fizetve
                </button>
            </form>
        @endif
        @if($szamla->statusz === 'fizetve' && $szamla->szamla_tipus === 'szamla' && !$szamla->stornoSzamla)
            <form method="POST" action="{{ route('szamlak.storno', $szamla->szamla_id) }}" style="display:inline;"
                  onsubmit="return confirm('Biztosan stornózza a számlát? Ez a művelet visszafordíthatatlan.')">
                @csrf
                <button type="submit" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;background:rgba(100,116,139,0.1);border:1.5px solid rgba(100,116,139,0.25);color:#64748b;font-size:12px;font-weight:600;cursor:pointer;">
                    <i class="fas fa-ban"></i> Stornó kiállítása
                </button>
            </form>
        @endif
        {{-- ── Kiállítás típus választó ─────────────────────────────────── --}}
        <div style="position:relative;display:inline-block;" id="kiallitasDropdown">
            <button onclick="toggleKiallitas(event)"
                style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;
                       background:linear-gradient(135deg,rgba(74,222,128,0.12),rgba(201,169,122,0.08));
                       border:1.5px solid rgba(74,222,128,0.3);color:#16a34a;font-size:12px;font-weight:700;cursor:pointer;">
                <i class="fas fa-file-invoice"></i> Számla kiállítása
                <i class="fas fa-chevron-down" id="kiallitasChevron" style="font-size:9px;transition:transform 0.2s;"></i>
            </button>
            <div id="kiallitasMenu" style="display:none;position:absolute;right:0;top:calc(100% + 8px);
                 background:#fff;border:1px solid #e2e8f0;border-radius:10px;min-width:260px;
                 box-shadow:0 8px 30px rgba(0,0,0,0.12);z-index:200;overflow:hidden;animation:dropIn 0.15s ease;">

                {{-- Saját számla ── --}}
                <div style="padding:10px 16px;background:rgba(74,222,128,0.04);border-bottom:1px solid #f1f5f9;">
                    <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.6px;margin-bottom:6px;">
                        <i class="fas fa-building" style="color:#16a34a;"></i> Saját sablon
                    </div>
                    @if($szamla->sajat_pdf_path && \Illuminate\Support\Facades\Storage::exists($szamla->sajat_pdf_path))
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            <form method="POST" action="{{ route('szamlak.sajat', $szamla->szamla_id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" title="Új PDF generálása (felülírja a régit)"
                                    style="font-size:11px;font-weight:600;padding:6px 10px;border-radius:6px;border:1px solid rgba(74,222,128,0.3);background:rgba(74,222,128,0.08);color:#16a34a;cursor:pointer;">
                                    <i class="fas fa-sync-alt"></i> Újragenerálás
                                </button>
                            </form>
                            <a href="{{ route('szamlak.sajat.letoltes', $szamla->szamla_id) }}"
                               style="font-size:11px;font-weight:600;padding:6px 10px;border-radius:6px;border:1px solid rgba(74,222,128,0.3);background:rgba(74,222,128,0.08);color:#16a34a;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                <i class="fas fa-download"></i> Letöltés
                            </a>
                        </div>
                        <div style="font-size:10px;color:#94a3b8;margin-top:5px;">
                            <i class="fas fa-check-circle" style="color:#16a34a;"></i> Saját számla generálva
                        </div>
                    @else
                        <form method="POST" action="{{ route('szamlak.sajat', $szamla->szamla_id) }}" style="display:block;">
                            @csrf
                            <button type="submit"
                                style="width:100%;font-size:12px;font-weight:600;padding:8px 12px;border-radius:7px;
                                       border:1.5px solid rgba(74,222,128,0.3);background:rgba(74,222,128,0.08);
                                       color:#16a34a;cursor:pointer;text-align:left;display:flex;align-items:center;gap:8px;">
                                <i class="fas fa-file-pdf"></i>
                                <span>
                                    <span style="display:block;">Saját sablon generálása</span>
                                    <span style="font-size:10px;font-weight:400;color:#94a3b8;">Triton Security design, dompdf</span>
                                </span>
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Billingo ── --}}
                <div style="padding:10px 16px;background:rgba(201,169,122,0.04);border-bottom:1px solid #f1f5f9;">
                    <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.6px;margin-bottom:6px;">
                        <i class="fas fa-cloud" style="color:#a07848;"></i> Billingo
                    </div>
                    @if($szamla->billingo_id)
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            <a href="{{ route('szamlak.download', $szamla->szamla_id) }}"
                               style="font-size:11px;font-weight:600;padding:6px 10px;border-radius:6px;border:1px solid rgba(201,169,122,0.3);background:rgba(201,169,122,0.08);color:#a07848;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                <i class="fas fa-download"></i> PDF letöltés
                            </a>
                        </div>
                        <div style="font-size:10px;color:#94a3b8;margin-top:5px;">
                            <i class="fas fa-check-circle" style="color:#a07848;"></i>
                            Billingo szám: <strong>{{ $szamla->billingo_szam }}</strong>
                        </div>
                    @else
                        <form method="POST" action="{{ route('szamlak.billingo', $szamla->szamla_id) }}" style="display:block;">
                            @csrf
                            <button type="submit"
                                style="width:100%;font-size:12px;font-weight:600;padding:8px 12px;border-radius:7px;
                                       border:1.5px solid rgba(201,169,122,0.3);background:rgba(201,169,122,0.08);
                                       color:#a07848;cursor:pointer;text-align:left;display:flex;align-items:center;gap:8px;">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>
                                    <span style="display:block;">Billingo kiállítás</span>
                                    <span style="font-size:10px;font-weight:400;color:#94a3b8;">Küldés a Billingo API-ra</span>
                                </span>
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Teszt PDF ── --}}
                <div style="padding:10px 16px;">
                    <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.6px;margin-bottom:6px;">
                        <i class="fas fa-flask" style="color:#f59e0b;"></i> Teszt
                    </div>
                    <a href="{{ route('szamlak.teszt', $szamla->szamla_id) }}" target="_blank"
                       style="display:flex;align-items:center;gap:8px;width:100%;font-size:12px;font-weight:600;
                              padding:8px 12px;border-radius:7px;border:1.5px solid rgba(245,158,11,0.3);
                              background:rgba(245,158,11,0.06);color:#b45309;text-decoration:none;">
                        <i class="fas fa-eye"></i>
                        <span>
                            <span style="display:block;">Teszt PDF előnézet</span>
                            <span style="font-size:10px;font-weight:400;color:#94a3b8;">Vízjeles, mentés nélkül – új lapon nyílik</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <style>
        @keyframes dropIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
        </style>
        <script>
        function toggleKiallitas(e) {
            e.stopPropagation();
            const menu    = document.getElementById('kiallitasMenu');
            const chevron = document.getElementById('kiallitasChevron');
            const open    = menu.style.display === 'block';
            menu.style.display = open ? 'none' : 'block';
            chevron.style.transform = open ? '' : 'rotate(180deg)';
        }
        document.addEventListener('click', function() {
            const menu    = document.getElementById('kiallitasMenu');
            const chevron = document.getElementById('kiallitasChevron');
            if (menu) { menu.style.display = 'none'; chevron.style.transform = ''; }
        });
        document.getElementById('kiallitasMenu')?.addEventListener('click', e => e.stopPropagation());
        </script>
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

{{-- Stornó / kapcsolt számla figyelmeztetés --}}
@if($szamla->stornoSzamla)
    <div style="background:rgba(100,116,139,0.06);border:1px solid rgba(100,116,139,0.2);border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:13px;display:flex;align-items:center;gap:10px;color:#64748b;">
        <i class="fas fa-ban" style="color:#94a3b8;"></i>
        Ez a számla stornózva lett.
        <a href="{{ route('szamlak.show', $szamla->stornoSzamla->szamla_id) }}" style="color:#a07848;font-weight:600;text-decoration:none;">
            → Stornó számla megtekintése (#{{ $szamla->stornoSzamla->szamla_id }})
        </a>
    </div>
@endif
@if($szamla->stornoEredeti)
    <div style="background:rgba(100,116,139,0.06);border:1px solid rgba(100,116,139,0.2);border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:13px;display:flex;align-items:center;gap:10px;color:#64748b;">
        <i class="fas fa-info-circle"></i>
        Ez egy stornó számla. Eredeti:
        <a href="{{ route('szamlak.show', $szamla->stornoEredeti->szamla_id) }}" style="color:#a07848;font-weight:600;text-decoration:none;">
            → Eredeti számla megtekintése (#{{ $szamla->stornoEredeti->szamla_id }})
        </a>
    </div>
@endif

<div class="sz-grid">

    {{-- Alap adatok --}}
    <div class="sz-card">
        <div class="sz-card-header">
            <div class="sz-card-icon"><i class="fas fa-info-circle"></i></div>
            <div class="sz-card-title">Számla adatai</div>
        </div>
        <div class="sz-info-rows">
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-tag"></i> Típus</div>
                <div class="sz-info-val">
                    {{ match($szamla->szamla_tipus) { 'szamla' => 'Számla', 'dijbekero' => 'Díjbekérő', 'storno' => 'Stornó', default => $szamla->szamla_tipus } }}
                </div>
            </div>
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-calendar-alt"></i> Kiállítás</div>
                <div class="sz-info-val">{{ $szamla->kiallitas_datum->format('Y. m. d.') }}</div>
            </div>
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-calendar-check"></i> Teljesítés</div>
                <div class="sz-info-val">{{ $szamla->teljesites_datum->format('Y. m. d.') }}</div>
            </div>
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-calendar-times"></i> Határidő</div>
                <div class="sz-info-val" style="@if($napok !== null && $napok < 0 && $szamla->statusz !== 'fizetve') color:#dc2626;font-weight:600; @endif">
                    {{ $szamla->fizetesi_hatarido?->format('Y. m. d.') ?? '–' }}
                    @if($napok !== null && $szamla->statusz === 'fuggoben')
                        <span style="font-size:11px;color:#94a3b8;margin-left:6px;">
                            @if($napok < 0) ({{ abs($napok) }} napja lejárt)
                            @elseif($napok == 0) (ma jár le!)
                            @else ({{ $napok }} nap múlva)
                            @endif
                        </span>
                    @endif
                </div>
            </div>
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-credit-card"></i> Fizetési mód</div>
                <div class="sz-info-val">
                    {{ match($szamla->fizetesi_mod) { 'stripe' => 'Bankkártya (Stripe)', 'banki_atutalas' => 'Banki átutalás', 'keszpenz' => 'Készpénz', default => $szamla->fizetesi_mod } }}
                </div>
            </div>
            @if($szamla->billingo_szam)
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-file-invoice"></i> Számlaszám</div>
                <div class="sz-info-val" style="font-weight:600;color:#a07848;">{{ $szamla->billingo_szam }}</div>
            </div>
            @endif
            @if($szamla->megjegyzes)
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-comment"></i> Megjegyzés</div>
                <div class="sz-info-val" style="color:#64748b;font-size:12px;">{{ $szamla->megjegyzes }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Megrendelés / Ügyfél --}}
    <div class="sz-card">
        <div class="sz-card-header">
            <div class="sz-card-icon"><i class="fas fa-user"></i></div>
            <div class="sz-card-title">Megrendelő</div>
        </div>
        <div class="sz-info-rows">
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-file-signature"></i> Megrendelés</div>
                <div class="sz-info-val">
                    <a href="{{ route('megrendeles.show', $szamla->megrendeles_id) }}" style="color:#a07848;font-weight:600;text-decoration:none;">
                        {{ $szamla->megrendeles->Megrendeles_Nev ?? '–' }}
                    </a>
                </div>
            </div>
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-user"></i> Ügyfél neve</div>
                <div class="sz-info-val">{{ $szamla->megrendeles->ugyfel->Nev ?? '–' }}</div>
            </div>
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-envelope"></i> Email</div>
                <div class="sz-info-val">{{ $szamla->megrendeles->ugyfel->Email ?? '–' }}</div>
            </div>
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-phone"></i> Telefon</div>
                <div class="sz-info-val">{{ $szamla->megrendeles->ugyfel->Telefonszam ?? '–' }}</div>
            </div>
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-map-marker-alt"></i> Helyszín</div>
                <div class="sz-info-val">
                    {{ $szamla->megrendeles->varos->Irny_szam ?? '' }}
                    {{ $szamla->megrendeles->varos->Nev ?? '' }}
                    {{ $szamla->megrendeles->Utca_Hazszam ?? '' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Összegzés --}}
    <div class="sz-card">
        <div class="sz-card-header">
            <div class="sz-card-icon"><i class="fas fa-coins"></i></div>
            <div class="sz-card-title">Összegzés</div>
        </div>
        <div class="sz-info-rows">
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-calculator"></i> Nettó összeg</div>
                <div class="sz-info-val">{{ number_format($szamla->netto_osszeg, 0, ',', ' ') }} Ft</div>
            </div>
            <div class="sz-info-row">
                <div class="sz-info-lbl"><i class="fas fa-percent"></i> ÁFA összeg</div>
                <div class="sz-info-val">{{ number_format($szamla->afa_osszeg, 0, ',', ' ') }} Ft</div>
            </div>
            <div class="sz-info-row" style="background:rgba(201,169,122,0.04);">
                <div class="sz-info-lbl" style="color:#a07848;"><i class="fas fa-money-bill-wave"></i> Bruttó összeg</div>
                <div class="sz-info-val" style="font-size:18px;font-weight:800;color:#a07848;">
                    {{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft
                </div>
            </div>
        </div>
    </div>

    {{-- Fizetési tranzakciók --}}
    <div class="sz-card">
        <div class="sz-card-header">
            <div class="sz-card-icon"><i class="fas fa-exchange-alt"></i></div>
            <div class="sz-card-title">Fizetési tranzakciók ({{ $szamla->fizetesek->count() }})</div>
        </div>
        @if($szamla->fizetesek->isEmpty())
            <div style="padding:16px 18px;color:#94a3b8;font-size:13px;">
                <i class="fas fa-info-circle" style="margin-right:6px;"></i> Még nincs rögzített tranzakció.
            </div>
        @else
            @foreach($szamla->fizetesek as $f)
            <div class="sz-pay-row">
                <div class="sz-pay-dot {{ $f->statusz }}">
                    <i class="fas {{ $f->statusz === 'fizetve' ? 'fa-check' : ($f->statusz === 'sikertelen' ? 'fa-times' : 'fa-hourglass-half') }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:13px;font-weight:600;color:#1e293b;">
                        {{ number_format($f->osszeg, 0, ',', ' ') }} {{ $f->deviza }}
                    </div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:2px;">
                        {{ $f->fizetes_idopontja?->format('Y. m. d. H:i') ?? 'Folyamatban' }}
                        · {{ match($f->fizetes_mod) { 'stripe' => 'Bankkártya', 'banki_atutalas' => 'Átutalás', default => $f->fizetes_mod } }}
                        @if($f->banki_hivatkozas) · Ref: {{ $f->banki_hivatkozas }} @endif
                    </div>
                </div>
                <span class="sz-badge {{ $f->statusz }}" style="font-size:10px;">
                    {{ match($f->statusz) { 'fizetve' => 'Fizetve', 'fuggoben' => 'Folyamatban', 'sikertelen' => 'Sikertelen', 'visszateritve' => 'Visszatérítve', default => $f->statusz } }}
                </span>
            </div>
            @endforeach
        @endif
    </div>

    {{-- Tételek --}}
    <div class="sz-card sz-card-full">
        <div class="sz-card-header">
            <div class="sz-card-icon"><i class="fas fa-list-ul"></i></div>
            <div class="sz-card-title">Tételek ({{ $szamla->tetelek->count() }})</div>
        </div>
        @if($szamla->tetelek->isEmpty())
            <div style="padding:16px 18px;color:#94a3b8;font-size:13px;">Nincsenek tételek rögzítve.</div>
        @else
        <table class="sz-tetel-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Megnevezés</th>
                    <th>Típus</th>
                    <th style="text-align:right;">Menny.</th>
                    <th>Me.</th>
                    <th style="text-align:right;">Egységár (nettó)</th>
                    <th style="text-align:center;">ÁFA</th>
                    <th style="text-align:right;">Nettó</th>
                    <th style="text-align:right;">ÁFA összeg</th>
                    <th style="text-align:right;">Bruttó</th>
                </tr>
            </thead>
            <tbody>
                @foreach($szamla->tetelek as $t)
                <tr>
                    <td style="color:#94a3b8;font-size:11px;">{{ $loop->iteration }}</td>
                    <td style="font-weight:500;">{{ $t->nev }}</td>
                    <td>
                        <span class="sz-tetel-tip {{ $t->tetel_tipus }}">
                            {{ match($t->tetel_tipus) { 'anyag' => 'Anyag', 'munkaora' => 'Munkaóra', 'egyeb' => 'Egyéb', default => $t->tetel_tipus } }}
                        </span>
                    </td>
                    <td style="text-align:right;font-weight:600;">{{ number_format($t->mennyiseg, 0, ',', ' ') }}</td>
                    <td style="color:#94a3b8;">{{ $t->mertekegyseg }}</td>
                    <td style="text-align:right;">{{ number_format($t->egyseg_netto_ar, 0, ',', ' ') }} Ft</td>
                    <td style="text-align:center;">
                        <span style="font-size:11px;font-weight:700;color:#a07848;">{{ $t->afa_kulcs }}%</span>
                    </td>
                    <td style="text-align:right;">{{ number_format($t->netto_osszeg, 0, ',', ' ') }} Ft</td>
                    <td style="text-align:right;color:#94a3b8;">{{ number_format($t->afa_osszeg, 0, ',', ' ') }} Ft</td>
                    <td style="text-align:right;font-weight:700;color:#1e293b;">{{ number_format($t->brutto_osszeg, 0, ',', ' ') }} Ft</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="background:rgba(201,169,122,0.04);">
                <tr>
                    <td colspan="7" style="text-align:right;color:#94a3b8;font-size:11px;">ÖSSZESEN:</td>
                    <td style="text-align:right;">{{ number_format($szamla->netto_osszeg, 0, ',', ' ') }} Ft</td>
                    <td style="text-align:right;color:#94a3b8;">{{ number_format($szamla->afa_osszeg, 0, ',', ' ') }} Ft</td>
                    <td style="text-align:right;font-size:15px;font-weight:800;color:#a07848;">{{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft</td>
                </tr>
            </tfoot>
        </table>
        @endif
    </div>

    {{-- Audit log --}}
    @if($szamla->auditLog->isNotEmpty())
    <div class="sz-card sz-card-full">
        <div class="sz-card-header">
            <div class="sz-card-icon"><i class="fas fa-history"></i></div>
            <div class="sz-card-title">Audit napló ({{ $szamla->auditLog->count() }})</div>
        </div>
        @foreach($szamla->auditLog as $log)
        @php
            $evtLabel = match($log->esemeny) {
                'szamla_kiallitva'    => ['fa-file-invoice', '#a07848',  'Számla kiállítva'],
                'dijbekero_kiallitva' => ['fa-file-alt',     '#2563eb',  'Díjbekérő kiállítva'],
                'statusz_valtozas'    => ['fa-exchange-alt', '#64748b',  'Státusz változás'],
                'fizetes_rogzitve'    => ['fa-coins',        '#d97706',  'Fizetés rögzítve'],
                'fizetes_teljesult'   => ['fa-check-circle', '#16a34a',  'Fizetés teljesült'],
                'fizetes_sikertelen'  => ['fa-times-circle', '#dc2626',  'Fizetés sikertelen'],
                'storno_kiallitva'    => ['fa-ban',          '#64748b',  'Stornó kiállítva'],
                'billingo_szinkron'   => ['fa-cloud-upload-alt','#a07848','Billingo szinkron'],
                'emlekeztetokuldve'   => ['fa-paper-plane',  '#6366f1',  'Emlékeztető elküldve'],
                'manualis_fizetes'    => ['fa-hand-holding-usd','#16a34a','Manuális fizetve jelölés'],
                default               => ['fa-dot-circle',   '#94a3b8',  $log->esemeny],
            };
        @endphp
        <div class="sz-audit-item">
            <div style="width:28px;height:28px;border-radius:7px;background:rgba({{ $evtLabel[2] === 'Stornó kiállítva' ? '100,116,139' : '201,169,122' }},0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                <i class="fas {{ $evtLabel[0] }}" style="font-size:11px;color:{{ $evtLabel[1] }};"></i>
            </div>
            <div style="flex:1;">
                <div class="sz-audit-evt">{{ $evtLabel[2] }}</div>
                <div class="sz-audit-meta">
                    {{ $log->created_at->format('Y. m. d. H:i') }}
                    @if($log->user) · {{ $log->user->nev ?? $log->user->email }} @else · Rendszer @endif
                    @if($log->megjegyzes) · {{ $log->megjegyzes }} @endif
                </div>
                @if($log->uj_ertek)
                    <div style="font-size:10px;color:#94a3b8;margin-top:2px;font-family:monospace;">
                        {{ json_encode($log->uj_ertek, JSON_UNESCAPED_UNICODE) }}
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

@endsection
