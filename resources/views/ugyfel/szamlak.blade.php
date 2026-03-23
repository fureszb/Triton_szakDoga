@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<style>
/* ── Welcome sáv ─────────────────────────────────────────── */
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
    content: 'TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON';
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
.ugyfel-welcome h2 { font-size: 18px; font-weight: 700; color: #fff; margin: 0 0 4px; }
.ugyfel-welcome p  { font-size: 12px; color: rgba(255,255,255,0.7); margin: 0; }
.ugyfel-welcome-right { flex-shrink: 0; display: flex; gap: 10px; }
.ugyfel-welcome-pill {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 8px;
    padding: 8px 14px;
    text-align: center;
    backdrop-filter: blur(4px);
    min-width: 80px;
}
.ugyfel-welcome-pill .pill-val { font-size: 20px; font-weight: 800; color: #fff; line-height: 1; }
.ugyfel-welcome-pill .pill-lbl { font-size: 10px; color: rgba(255,255,255,0.65); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 3px; }

/* ── Számla kártya ────────────────────────────────────────── */
.sz-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    margin-bottom: 14px;
    overflow: hidden;
    transition: box-shadow 0.15s;
}
.sz-card:hover { box-shadow: 0 3px 14px rgba(201,169,122,0.18); }

.sz-card-top {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
}
.sz-num {
    font-size: 11px; font-weight: 700; color: #16a34a;
    background: rgba(34,197,94,0.08);
    border: 1px solid rgba(34,197,94,0.22);
    border-radius: 6px; padding: 4px 9px; flex-shrink: 0;
}
.sz-num.pending {
    color: #2563eb;
    background: rgba(59,130,246,0.08);
    border-color: rgba(59,130,246,0.22);
}
.sz-name { flex: 1; min-width: 0; }
.sz-name-title {
    font-size: 14px; font-weight: 600; color: #1e293b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.sz-name-sub {
    font-size: 11px; color: #94a3b8; margin-top: 2px;
    display: flex; gap: 10px; flex-wrap: wrap;
}
.sz-name-sub span { display: flex; align-items: center; gap: 3px; }
.sz-name-sub i { color: #c9a97a; font-size: 10px; }
.sz-amount {
    font-size: 17px; font-weight: 800; color: #1e293b;
    flex-shrink: 0; text-align: right;
}
.sz-amount small { font-size: 11px; font-weight: 400; color: #94a3b8; display: block; }

/* ── Részletek sáv ────────────────────────────────────────── */
.sz-details {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 12px 20px;
    background: #f8fafc;
    flex-wrap: wrap;
}
.sz-detail-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 110px;
}
.sz-detail-label {
    font-size: 10px; font-weight: 600; text-transform: uppercase;
    letter-spacing: 0.7px; color: #94a3b8;
    display: flex; align-items: center; gap: 3px;
}
.sz-detail-label i { color: #c9a97a; font-size: 10px; }
.sz-detail-val { font-size: 12px; font-weight: 500; color: #334155; }

.sz-actions {
    margin-left: auto;
    display: flex;
    gap: 8px;
    flex-shrink: 0;
    align-items: center;
}
.sz-dl-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 15px; border-radius: 8px;
    font-size: 12px; font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, #c9a97a 0%, #a07848 100%);
    text-decoration: none; transition: all 0.15s;
    border: none;
}
.sz-dl-btn:hover { background: linear-gradient(135deg, #a07848 0%, #7a5830 100%); color: #fff; transform: translateY(-1px); }
.sz-billingo-num {
    font-size: 11px; color: #64748b;
    background: #f1f5f9; padding: 4px 10px; border-radius: 6px;
    display: flex; align-items: center; gap: 5px;
}

/* ── Szekciócím ───────────────────────────────────────────── */
.u-section-title {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1.8px; color: #94a3b8; margin-bottom: 12px;
    display: flex; align-items: center; gap: 8px;
}
.u-section-title::after { content: ''; flex: 1; height: 1px; background: #e8edf2; }

@media (max-width: 640px) {
    .ugyfel-welcome { flex-direction: column; align-items: flex-start; }
    .sz-card-top { flex-wrap: wrap; }
    .sz-details { flex-direction: column; gap: 10px; }
    .sz-actions { margin-left: 0; }
}
</style>

@php
    $fizetve      = $szamlak->where('statusz', 'fizetve');
    $billingo     = $szamlak->whereNotNull('billingo_id');
    $dijbekero_db = $szamlak->where('szamla_tipus', 'dijbekero');
    $osszBev      = $fizetve->sum('brutto_osszeg');
@endphp

@if (session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Welcome sáv --}}
<div class="ugyfel-welcome">
    <div>
        <h2><i class="fas fa-file-invoice" style="margin-right:8px;opacity:0.8;"></i>Számlák és bizonylatok</h2>
        <p>TRITON SECURITY &mdash; kiállított számlák és bizonylatok</p>
    </div>
    <div class="ugyfel-welcome-right">
        <div class="ugyfel-welcome-pill">
            <div class="pill-val">{{ $fizetve->count() }}</div>
            <div class="pill-lbl">Fizetve</div>
        </div>
        <div class="ugyfel-welcome-pill">
            <div class="pill-val">{{ $billingo->count() }}</div>
            <div class="pill-lbl">Számlával</div>
        </div>
        <div class="ugyfel-welcome-pill">
            <div class="pill-val">{{ $dijbekero_db->count() }}</div>
            <div class="pill-lbl">Díjbekérő</div>
        </div>
    </div>
</div>

@if ($szamlak->isEmpty())
    <div class="empty-state" style="margin-top:40px;">
        <i class="fas fa-file-invoice"></i>
        <p>Még nincs kiállított számlád.</p>
    </div>
@else

    {{-- ── Letölthető Billingo számlák ── --}}
    @if($billingo->count())
        <div class="u-section-title"><i class="fas fa-file-invoice" style="color:#a07848;"></i> Letölthető számlák</div>
        @foreach ($billingo as $szamla)
        @php
            $mr     = $szamla->megrendeles;
            $sikerFiz = $szamla->fizetesek->where('statusz', 'fizetve')->first();
            $fizMod = match($szamla->fizetesi_mod) {
                'stripe'        => 'Bankkártyával',
                'banki_atutalas'=> 'Átutalással',
                default         => 'Kézzel',
            };
            $fizDat = $sikerFiz?->fizetes_idopontja?->format('Y. m. d.') ?? '—';
        @endphp
        <div class="sz-card">
            <div class="sz-card-top">
                <div class="sz-num"><i class="fas fa-check"></i> {{ $szamla->billingo_szam ?? '#'.str_pad($szamla->szamla_id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="sz-name">
                    <div class="sz-name-title">{{ $mr->megrendeles_nev ?? '—' }}</div>
                    <div class="sz-name-sub">
                        @if($mr?->varos)
                            <span><i class="fas fa-map-marker-alt"></i> {{ $mr->varos->nev ?? '' }}, {{ $mr->utca_hazszam }}</span>
                        @endif
                        @if($sikerFiz)
                            <span><i class="fas fa-calendar-check"></i> Fizetve: {{ $fizDat }}</span>
                        @endif
                    </div>
                </div>
                <div class="sz-amount">
                    {{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft
                    <small>{{ $fizMod }}</small>
                </div>
            </div>
            <div class="sz-details">
                @if($szamla->billingo_szam)
                <div class="sz-detail-item">
                    <div class="sz-detail-label"><i class="fas fa-hashtag"></i> Számlaszám</div>
                    <div class="sz-detail-val" style="font-weight:700;">{{ $szamla->billingo_szam }}</div>
                </div>
                @endif
                <div class="sz-detail-item">
                    <div class="sz-detail-label"><i class="fas fa-calendar-alt"></i> Kiállítás</div>
                    <div class="sz-detail-val">{{ $szamla->kiallitas_datum?->format('Y. m. d.') ?? '—' }}</div>
                </div>
                <div class="sz-detail-item">
                    <div class="sz-detail-label"><i class="fas fa-calendar-check"></i> Határidő</div>
                    <div class="sz-detail-val">{{ $szamla->fizetesi_hatarido?->format('Y. m. d.') ?? '—' }}</div>
                </div>
                <div class="sz-detail-item">
                    <div class="sz-detail-label"><i class="fas fa-credit-card"></i> Fizetés módja</div>
                    <div class="sz-detail-val">{{ $fizMod }}</div>
                </div>
                <div class="sz-actions">
                    @if($szamla->billingo_szam)
                        <span class="sz-billingo-num"><i class="fas fa-file-invoice"></i> {{ $szamla->billingo_szam }}</span>
                    @endif
                    <a href="{{ route('szamlak.download', $szamla->szamla_id) }}" class="sz-dl-btn">
                        <i class="fas fa-download"></i> Számla letöltése
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    @endif

    {{-- ── Díjbekérők szekció ── --}}
    @php $dijbekero_lista = $szamlak->where('szamla_tipus', 'dijbekero'); @endphp
    @if($dijbekero_lista->count())
        <div id="dijbekero-section" class="u-section-title" style="margin-top:{{ $billingo->count() ? '20px' : '0' }}; scroll-margin-top: 20px;">
            <i class="fas fa-file-alt" style="color:#7c3aed;"></i> Díjbekérők
        </div>
        @foreach ($dijbekero_lista as $szamla)
        @php
            $mr     = $szamla->megrendeles;
            $isPaid = $szamla->statusz === 'fizetve';
            $fizMod = match($szamla->fizetesi_mod) {
                'stripe'         => 'Bankkártyával',
                'banki_atutalas' => 'Átutalással',
                default          => 'Kézzel',
            };
            $sikerFiz      = $szamla->fizetesek->where('statusz', 'fizetve')->first();
            $fizDat        = $sikerFiz?->fizetes_idopontja?->format('Y. m. d.') ?? '—';
            $napok         = $szamla->fizetesi_hatarido ? now()->diffInDays($szamla->fizetesi_hatarido, false) : null;
            $fuggobenAtutalas = $szamla->fizetesek->where('statusz', 'fuggoben')->where('fizetes_mod', 'banki_atutalas')->isNotEmpty();
        @endphp
        <div class="sz-card" style="border-left: 4px solid #7c3aed;">
            <div class="sz-card-top">
                <div class="sz-num" style="color:#7c3aed;background:rgba(124,58,237,0.08);border-color:rgba(124,58,237,0.25);">
                    <i class="fas fa-file-alt"></i> Díjbekérő
                </div>
                <div class="sz-name">
                    <div class="sz-name-title">{{ $mr->megrendeles_nev ?? '—' }}</div>
                    <div class="sz-name-sub">
                        @if($mr?->varos)
                            <span><i class="fas fa-map-marker-alt"></i> {{ $mr->varos->nev ?? '' }}, {{ $mr->utca_hazszam }}</span>
                        @endif
                        @if($szamla->fizetesi_hatarido)
                            <span><i class="fas fa-clock"></i>
                                @if($napok !== null && $napok < 0)
                                    <span style="color:#dc2626;">Lejárt {{ abs((int)$napok) }} napja</span>
                                @elseif($napok !== null && $napok <= 3)
                                    <span style="color:#d97706;">{{ (int)$napok }} nap múlva lejár</span>
                                @else
                                    Határidő: {{ $szamla->fizetesi_hatarido->format('Y. m. d.') }}
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="sz-amount">
                    {{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft
                    <small>{{ $isPaid ? 'Fizetve' : 'Függőben' }}</small>
                </div>
            </div>
            <div class="sz-details">
                <div class="sz-detail-item">
                    <div class="sz-detail-label"><i class="fas fa-calendar-alt"></i> Kiállítás</div>
                    <div class="sz-detail-val">{{ $szamla->kiallitas_datum?->format('Y. m. d.') ?? '—' }}</div>
                </div>
                <div class="sz-detail-item">
                    <div class="sz-detail-label"><i class="fas fa-calendar-check"></i> Határidő</div>
                    <div class="sz-detail-val"
                         style="{{ $napok !== null && $napok < 0 ? 'color:#dc2626;font-weight:700;' : ($napok !== null && $napok <= 3 ? 'color:#d97706;font-weight:600;' : '') }}">
                        {{ $szamla->fizetesi_hatarido?->format('Y. m. d.') ?? '—' }}
                    </div>
                </div>
                <div class="sz-detail-item">
                    <div class="sz-detail-label"><i class="fas fa-info-circle"></i> Állapot</div>
                    <div class="sz-detail-val">
                        @if($isPaid)
                            <span style="color:#16a34a;font-weight:600;"><i class="fas fa-check-circle"></i> Fizetve</span>
                        @elseif($fuggobenAtutalas)
                            <span style="color:#d97706;font-weight:600;"><i class="fas fa-clock"></i> Jóváhagyásra vár</span>
                        @else
                            <span style="color:#7c3aed;font-weight:600;"><i class="fas fa-hourglass-half"></i> Fizetésre vár</span>
                        @endif
                    </div>
                </div>
                <div class="sz-actions">
                    {{-- Díjbekérő letöltése (saját PDF – menet közben generálva ha nincs mentett) --}}
                    <a href="{{ route('szamlak.sajat.letoltes', $szamla->szamla_id) }}"
                       class="sz-dl-btn"
                       style="background:linear-gradient(135deg,#7c3aed,#6d28d9);">
                        <i class="fas fa-download"></i> Díjbekérő letöltése
                    </a>
                    @if(!$isPaid && $fuggobenAtutalas)
                        <span style="font-size:11px;color:#d97706;background:#fffbeb;padding:5px 11px;border-radius:6px;display:flex;align-items:center;gap:5px;border:1px solid #fde68a;">
                            <i class="fas fa-clock"></i> Átutalás jóváhagyásra vár
                        </span>
                    @elseif(!$isPaid && $szamla->megrendeles_id)
                        <a href="{{ route('payment.checkout', $szamla->megrendeles_id) }}"
                           style="display:inline-flex;align-items:center;gap:6px;padding:7px 15px;border-radius:8px;font-size:12px;font-weight:700;color:#fff;background:linear-gradient(135deg,#16a34a,#15803d);text-decoration:none;">
                            <i class="fas fa-credit-card"></i> Fizetek
                        </a>
                    @elseif($isPaid)
                        <span style="font-size:11px;color:#16a34a;background:#f0fdf4;padding:5px 11px;border-radius:6px;display:flex;align-items:center;gap:5px;border:1px solid #bbf7d0;">
                            <i class="fas fa-check-circle"></i> Fizetve: {{ $fizDat }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif

    {{-- ── Billingo nélküli (függőben / fizetve) számlák ── --}}
    @php $egyeb = $szamlak->where('szamla_tipus', 'szamla')->whereNull('billingo_id'); @endphp
    @if($egyeb->count())
        <div class="u-section-title" style="margin-top:{{ $billingo->count() ? '20px' : '0' }};">
            <i class="fas fa-receipt" style="color:#2563eb;"></i> Bizonylatok
        </div>
        @foreach ($egyeb as $szamla)
        @php
            $mr       = $szamla->megrendeles;
            $sikerFiz = $szamla->fizetesek->where('statusz', 'fizetve')->first();
            $fizMod   = match($szamla->fizetesi_mod) {
                'stripe'         => 'Bankkártyával',
                'banki_atutalas' => 'Átutalással',
                default          => 'Kézzel',
            };
            $fizDat           = $sikerFiz?->fizetes_idopontja?->format('Y. m. d.') ?? '—';
            $isPaid           = $szamla->statusz === 'fizetve';
            $fuggobenAtutalas = $szamla->fizetesek->where('statusz', 'fuggoben')->where('fizetes_mod', 'banki_atutalas')->isNotEmpty();
        @endphp
        <div class="sz-card">
            <div class="sz-card-top">
                <div class="sz-num {{ !$isPaid ? 'pending' : '' }}">
                    <i class="fas {{ $isPaid ? 'fa-check' : 'fa-hourglass-half' }}"></i>
                    #{{ str_pad($szamla->szamla_id, 5, '0', STR_PAD_LEFT) }}
                </div>
                <div class="sz-name">
                    <div class="sz-name-title">{{ $mr->megrendeles_nev ?? '—' }}</div>
                    <div class="sz-name-sub">
                        @if($mr?->varos)
                            <span><i class="fas fa-map-marker-alt"></i> {{ $mr->varos->nev ?? '' }}, {{ $mr->utca_hazszam }}</span>
                        @endif
                        @if($isPaid && $sikerFiz)
                            <span><i class="fas fa-calendar-check"></i> Fizetve: {{ $fizDat }}</span>
                        @elseif(!$isPaid && $szamla->fizetesi_hatarido)
                            <span><i class="fas fa-clock"></i> Határidő: {{ $szamla->fizetesi_hatarido->format('Y. m. d.') }}</span>
                        @endif
                    </div>
                </div>
                <div class="sz-amount">
                    {{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft
                    <small>{{ $isPaid ? $fizMod : 'Függőben' }}</small>
                </div>
            </div>
            <div class="sz-details">
                <div class="sz-detail-item">
                    <div class="sz-detail-label"><i class="fas fa-calendar-alt"></i> Kiállítás</div>
                    <div class="sz-detail-val">{{ $szamla->kiallitas_datum?->format('Y. m. d.') ?? '—' }}</div>
                </div>
                @if($isPaid)
                <div class="sz-detail-item">
                    <div class="sz-detail-label"><i class="fas fa-credit-card"></i> Fizetés módja</div>
                    <div class="sz-detail-val">{{ $fizMod }}</div>
                </div>
                @endif
                <div class="sz-detail-item">
                    <div class="sz-detail-label"><i class="fas fa-info-circle"></i> Számla</div>
                    <div class="sz-detail-val" style="color:#94a3b8;font-size:11px;">
                        @if($isPaid && !$szamla->sajat_pdf_path)
                            <i class="fas fa-clock" style="color:#c9a97a;"></i> Feldolgozás alatt — a számla hamarosan elérhető lesz.
                        @elseif(!$isPaid)
                            <span style="color:#2563eb;font-weight:600;"><i class="fas fa-hourglass-half"></i> Fizetésre vár</span>
                        @endif
                    </div>
                </div>
                <div class="sz-actions">
                    @if($szamla->sajat_pdf_path)
                        {{-- Saját (belső) PDF letöltése --}}
                        <a href="{{ route('szamlak.sajat.letoltes', $szamla->szamla_id) }}" class="sz-dl-btn">
                            <i class="fas fa-download"></i> Számla letöltése
                        </a>
                    @elseif($isPaid)
                        <span style="font-size:11px;color:#64748b;background:#f1f5f9;padding:5px 11px;border-radius:6px;display:flex;align-items:center;gap:5px;">
                            <i class="fas fa-hourglass-half" style="color:#c9a97a;"></i> Számla készül
                        </span>
                    @elseif(!$isPaid && $fuggobenAtutalas)
                        <span style="font-size:11px;color:#d97706;background:#fffbeb;padding:5px 11px;border-radius:6px;display:flex;align-items:center;gap:5px;border:1px solid #fde68a;">
                            <i class="fas fa-clock"></i> Átutalás jóváhagyásra vár
                        </span>
                    @else
                        {{-- Online fizetési gomb --}}
                        @if($szamla->megrendeles_id)
                        <a href="{{ route('payment.checkout', $szamla->megrendeles_id) }}"
                           style="display:inline-flex;align-items:center;gap:6px;padding:7px 15px;border-radius:8px;font-size:12px;font-weight:700;color:#fff;background:linear-gradient(135deg,#2563eb,#1d4ed8);text-decoration:none;">
                            <i class="fas fa-credit-card"></i> Fizetés
                        </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif

@endif

@endsection
