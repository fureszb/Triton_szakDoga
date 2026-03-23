@extends('ujlayout')

@section('content')

<style>
/* ── Welcome banner ───────────────────────────────────────── */
.home-welcome {
    background: linear-gradient(135deg, #c9a97a 0%, #8c6a3a 55%, #3a2510 100%);
    border-radius: 14px;
    padding: 28px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.home-welcome::before {
    content: 'TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON TRITON';
    position: absolute;
    inset: -10px;
    font-size: 18px;
    font-weight: 900;
    color: rgba(255,255,255,0.05);
    letter-spacing: 22px;
    line-height: 2.4;
    word-break: break-all;
    pointer-events: none;
    transform: rotate(-12deg);
    white-space: normal;
}
.home-welcome-text h2 {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 4px;
}
.home-welcome-text p {
    font-size: 13px;
    color: rgba(255,255,255,0.75);
    margin: 0;
}
.home-welcome-badge {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 10px;
    padding: 12px 20px;
    text-align: center;
    flex-shrink: 0;
    backdrop-filter: blur(4px);
}
.home-welcome-badge .date-label {
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: rgba(255,255,255,0.6);
}
.home-welcome-badge .date-val {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    margin-top: 2px;
}

/* ── KPI cards ────────────────────────────────────────────── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}
.kpi-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px 20px 16px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    gap: 10px;
    transition: box-shadow 0.15s, transform 0.15s;
}
.kpi-card:hover {
    box-shadow: 0 4px 16px rgba(201,169,122,0.18);
    transform: translateY(-2px);
}
.kpi-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}
.kpi-icon.beige  { background: rgba(201,169,122,0.15); color: #a07848; }
.kpi-icon.blue   { background: rgba(59,130,246,0.1);   color: #2563eb; }
.kpi-icon.green  { background: rgba(34,197,94,0.1);    color: #16a34a; }
.kpi-icon.purple { background: rgba(139,92,246,0.1);   color: #7c3aed; }
.kpi-icon.orange { background: rgba(249,115,22,0.1);   color: #ea580c; }
.kpi-val {
    font-size: 30px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}
.kpi-lbl {
    font-size: 12px;
    font-weight: 500;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ── Quick actions ────────────────────────────────────────── */
.section-title {
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
.section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e8edf2;
}
.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 28px;
}
.quick-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s;
    border: 1.5px solid transparent;
}
.quick-btn-primary {
    background: #c9a97a;
    color: #fff;
    border-color: #c9a97a;
}
.quick-btn-primary:hover {
    background: #a07848;
    border-color: #a07848;
    color: #fff;
}
.quick-btn-outline {
    background: #fff;
    color: #475569;
    border-color: #d1d5db;
}
.quick-btn-outline:hover {
    background: #f8fafc;
    border-color: #c9a97a;
    color: #a07848;
}

/* ── Charts ───────────────────────────────────────────────── */
.charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 28px;
}
.chart-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    padding: 20px;
}
.chart-card-title {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
}
.chart-card-sub {
    font-size: 11px;
    color: #94a3b8;
    margin-bottom: 16px;
}
.chart-inner {
    width: 100%;
    height: 280px;
}
@media (max-width: 900px) {
    .charts-grid { grid-template-columns: 1fr; }
    .home-welcome { flex-direction: column; align-items: flex-start; }
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 500px) {
    .kpi-grid { grid-template-columns: 1fr 1fr; }
}
</style>

<div class="card-body">

    {{-- Welcome banner --}}
    <div class="home-welcome">
        <div class="home-welcome-text">
            <h2>Üdvözöljük, {{ Auth::user()->nev }}!</h2>
            <p>{{ Auth::user()->role === 'Admin' ? 'Rendszergazda' : 'Üzletkötő' }} &mdash; TRITON SECURITY kezelőfelület</p>
        </div>
        <div class="home-welcome-badge">
            <div class="date-label">Mai dátum</div>
            <div class="date-val">{{ now()->format('Y. m. d.') }}</div>
        </div>
    </div>

    {{-- KPI cards --}}
    <div class="section-title">Áttekintés</div>
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon beige"><i class="fas fa-users"></i></div>
            <div>
                <div class="kpi-val">{{ $ugyfelekSzama }}</div>
                <div class="kpi-lbl">Ügyfelek</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon blue"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <div class="kpi-val">{{ $aktivMegrendelesek }}</div>
                <div class="kpi-lbl">Aktív megrendelés</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="kpi-val">{{ $alairtvaMegrendelesek }}</div>
                <div class="kpi-lbl">Aláírt megrendelés</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon purple"><i class="fas fa-tools"></i></div>
            <div>
                <div class="kpi-val">{{ $szerelokSzama }}</div>
                <div class="kpi-lbl">Szerelők</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon orange"><i class="fas fa-boxes"></i></div>
            <div>
                <div class="kpi-val">{{ $anyagokSzama }}</div>
                <div class="kpi-lbl">Anyagok</div>
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="section-title">Gyors műveletek</div>
    <div class="quick-actions">
        <a href="{{ route('ugyfel.create') }}" class="quick-btn quick-btn-primary">
            <i class="fas fa-user-plus"></i> Új ügyfél
        </a>
        <a href="{{ route('megrendeles.create') }}" class="quick-btn quick-btn-primary">
            <i class="fas fa-plus-circle"></i> Új megrendelés
        </a>
        <a href="{{ route('ugyfel.index') }}" class="quick-btn quick-btn-outline">
            <i class="fas fa-users"></i> Ügyfelek
        </a>
        <a href="{{ route('megrendeles.index') }}" class="quick-btn quick-btn-outline">
            <i class="fas fa-clipboard-list"></i> Megrendelések
        </a>
        @can('access-admin')
        <a href="{{ route('szerelok.create') }}" class="quick-btn quick-btn-outline">
            <i class="fas fa-user-cog"></i> Új szerelő
        </a>
        <a href="{{ route('anyagok.create') }}" class="quick-btn quick-btn-outline">
            <i class="fas fa-box"></i> Új anyag
        </a>
        <a href="{{ route('users.index') }}" class="quick-btn quick-btn-outline">
            <i class="fas fa-shield-alt"></i> Felhasználók
        </a>
        @endcan
    </div>

    {{-- ── Számlázási folyamat ─────────────────────────────────────────────── --}}
    <div class="section-title" style="margin-top:8px;">Számlázási &amp; fizetési folyamat</div>

    <style>
    /* ── Workflow wrapper ────────────────────────────────────── */
    .wf-wrap {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        padding: 28px 28px 22px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .wf-wrap::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, #7c3aed, #2563eb, #c9a97a, #d97706, #0891b2, #16a34a, #dc2626);
        border-radius: 16px 16px 0 0;
    }

    /* ── Sor ─────────────────────────────────────────────────── */
    .wf-row {
        display: flex;
        align-items: stretch;
        gap: 0;
        position: relative;
    }
    .wf-row + .wf-row { margin-top: 0; }

    /* ── Egy lépés kártya ────────────────────────────────────── */
    .wf-step {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 16px 16px 14px;
        border-radius: 12px;
        position: relative;
        transition: box-shadow 0.15s, transform 0.15s;
        min-width: 0;
    }
    .wf-step:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        z-index: 1;
    }

    /* ── Lépés sorszám ───────────────────────────────────────── */
    .wf-num {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 10px;
        opacity: 0.55;
        font-family: 'JetBrains Mono', monospace;
    }

    /* ── Ikon kör ────────────────────────────────────────────── */
    .wf-icon {
        width: 42px; height: 42px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 17px;
        margin-bottom: 12px;
        flex-shrink: 0;
    }

    /* ── Szöveg ──────────────────────────────────────────────── */
    .wf-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 5px;
        line-height: 1.3;
    }
    .wf-desc {
        font-size: 11px;
        color: #64748b;
        line-height: 1.55;
        flex: 1;
        margin-bottom: 12px;
    }

    /* ── Gyors link gomb ─────────────────────────────────────── */
    .wf-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 6px;
        transition: all 0.15s;
        border: 1.5px solid currentColor;
        opacity: 0.85;
    }
    .wf-link:hover { opacity: 1; transform: translateX(2px); }

    /* ── Nyíl összekötők (vízszintes) ───────────────────────── */
    .wf-arrow-h {
        display: flex;
        align-items: center;
        padding: 0 2px;
        color: #cbd5e1;
        font-size: 18px;
        flex-shrink: 0;
        align-self: center;
        margin-bottom: 26px; /* igazítás az ikon centrumához */
    }

    /* ── Fordulat (jobb oldali ív) ───────────────────────────── */
    .wf-turn {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        flex-shrink: 0;
        width: 52px;
        color: #cbd5e1;
        font-size: 20px;
        padding-bottom: 4px;
        align-self: stretch;
    }
    /* Bal oldali fordulat (alsó sor elejénél) */
    .wf-turn-left {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        flex-shrink: 0;
        width: 52px;
        color: #cbd5e1;
        font-size: 20px;
        padding-top: 4px;
        align-self: stretch;
    }

    /* ── Alsó sor jobbról balra ───────────────────────────────── */
    .wf-row-rev {
        display: flex;
        flex-direction: row-reverse;
        align-items: stretch;
        gap: 0;
        position: relative;
        margin-top: 6px;
    }

    /* ── Kiosztott színek per lépés ──────────────────────────── */
    .wf-s1 { background: rgba(124,58,237,0.05); }
    .wf-s1 .wf-icon  { background: rgba(124,58,237,0.12); color: #7c3aed; }
    .wf-s1 .wf-num   { color: #7c3aed; }
    .wf-s1 .wf-link  { color: #7c3aed; }

    .wf-s2 { background: rgba(37,99,235,0.05); }
    .wf-s2 .wf-icon  { background: rgba(37,99,235,0.12); color: #2563eb; }
    .wf-s2 .wf-num   { color: #2563eb; }
    .wf-s2 .wf-link  { color: #2563eb; }

    .wf-s3 { background: rgba(201,169,122,0.08); }
    .wf-s3 .wf-icon  { background: rgba(201,169,122,0.18); color: #a07848; }
    .wf-s3 .wf-num   { color: #a07848; }
    .wf-s3 .wf-link  { color: #a07848; }

    .wf-s4 { background: rgba(217,119,6,0.05); }
    .wf-s4 .wf-icon  { background: rgba(217,119,6,0.12); color: #d97706; }
    .wf-s4 .wf-num   { color: #d97706; }
    .wf-s4 .wf-link  { color: #d97706; }

    .wf-s5 { background: rgba(8,145,178,0.05); }
    .wf-s5 .wf-icon  { background: rgba(8,145,178,0.12); color: #0891b2; }
    .wf-s5 .wf-num   { color: #0891b2; }
    .wf-s5 .wf-link  { color: #0891b2; }

    .wf-s6 { background: rgba(22,163,74,0.05); }
    .wf-s6 .wf-icon  { background: rgba(22,163,74,0.12); color: #16a34a; }
    .wf-s6 .wf-num   { color: #16a34a; }
    .wf-s6 .wf-link  { color: #16a34a; }

    .wf-s7 { background: rgba(220,38,38,0.05); border: 1px dashed rgba(220,38,38,0.18); }
    .wf-s7 .wf-icon  { background: rgba(220,38,38,0.10); color: #dc2626; }
    .wf-s7 .wf-num   { color: #dc2626; }
    .wf-s7 .wf-link  { color: #dc2626; }

    @media (max-width: 860px) {
        .wf-row, .wf-row-rev { flex-direction: column; gap: 8px; }
        .wf-arrow-h, .wf-turn, .wf-turn-left { display: none; }
        .wf-step { flex: none; }
    }
    </style>

    <div class="wf-wrap">

        {{-- ── Felső sor: 1 → 2 → 3 → 4 ──────────────────────────────────── --}}
        <div class="wf-row">

            {{-- Lépés 1: Díjbekérő --}}
            <div class="wf-step wf-s1">
                <div class="wf-num">01 &mdash; Díjbekérő</div>
                <div class="wf-icon"><i class="fas fa-file-alt"></i></div>
                <div class="wf-title">Díjbekérő kiállítása</div>
                <div class="wf-desc">Az admin létrehoz egy díjbekérőt az ügyfélnek. Nem jogilag kötelező érvényű, de jelzi a várható költségeket.</div>
                <a href="{{ route('szamlak.create') }}" class="wf-link">
                    <i class="fas fa-plus"></i> Új díjbekérő
                </a>
            </div>

            <div class="wf-arrow-h"><i class="fas fa-chevron-right"></i></div>

            {{-- Lépés 2: Végleges számla --}}
            <div class="wf-step wf-s2">
                <div class="wf-num">02 &mdash; Számla</div>
                <div class="wf-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="wf-title">Végleges számla generálása</div>
                <div class="wf-desc">A díjbekérőből végleges számla generálható, amely jogilag kötelező érvényű fizetési felszólítás.</div>
                <a href="{{ route('szamlak.index') }}" class="wf-link">
                    <i class="fas fa-list"></i> Számlák listája
                </a>
            </div>

            <div class="wf-arrow-h"><i class="fas fa-chevron-right"></i></div>

            {{-- Lépés 3: Kiállítás --}}
            <div class="wf-step wf-s3">
                <div class="wf-num">03 &mdash; Kiállítás</div>
                <div class="wf-icon"><i class="fas fa-stamp"></i></div>
                <div class="wf-title">Számla kiállítása</div>
                <div class="wf-desc">Kiállítás <strong>saját sablonnal</strong> (dompdf, Triton design) <em>vagy</em> <strong>Billingo API-n</strong> keresztül automatikus áfabevallással.</div>
                <a href="{{ route('szamlak.index') }}" class="wf-link">
                    <i class="fas fa-file-invoice"></i> Kiállítás
                </a>
            </div>

            <div class="wf-arrow-h"><i class="fas fa-chevron-right"></i></div>

            {{-- Lépés 4: Küldés --}}
            <div class="wf-step wf-s4">
                <div class="wf-num">04 &mdash; Küldés</div>
                <div class="wf-icon"><i class="fas fa-paper-plane"></i></div>
                <div class="wf-title">Emlékeztető küldése</div>
                <div class="wf-desc">Billingo esetén az email automatikusan megy. Saját sablonnál manuális emlékeztetők küldhetők a GUI-ból.</div>
                <a href="{{ route('emlekeztetok.index') }}" class="wf-link">
                    <i class="fas fa-bell"></i> Emlékeztetők
                </a>
            </div>

            {{-- Ív lefelé (jobb oldal) --}}
            <div class="wf-turn">
                <svg width="52" height="100%" viewBox="0 0 52 120" fill="none" xmlns="http://www.w3.org/2000/svg" style="height:120px;width:52px;">
                    <path d="M 8 20 Q 44 20 44 60 Q 44 100 8 100" stroke="#cbd5e1" stroke-width="2" fill="none" stroke-dasharray="5 3"/>
                    <polygon points="4,94 14,100 4,106" fill="#cbd5e1"/>
                </svg>
            </div>
        </div>

        {{-- ── Alsó sor: 7 ← 6 ← 5 (fordítva jelenik meg row-reverse miatt) --}}
        <div class="wf-row-rev">

            {{-- Lépés 7: Stornó (megjelenik bal oldalt a row-reverse miatt) --}}
            <div class="wf-step wf-s7">
                <div class="wf-num">07 &mdash; Stornó</div>
                <div class="wf-icon"><i class="fas fa-ban"></i></div>
                <div class="wf-title">Stornó (opcionális)</div>
                <div class="wf-desc">Ha a számla visszavonandó, stornó számla kerül kiállításra negatív tételekkel. Az eredeti számla <em>stornozva</em> státuszba kerül.</div>
                <a href="{{ route('szamlak.index') }}" class="wf-link">
                    <i class="fas fa-undo"></i> Számlák
                </a>
            </div>

            <div class="wf-arrow-h" style="transform:rotate(180deg);"><i class="fas fa-chevron-right"></i></div>

            {{-- Lépés 6: Státusz --}}
            <div class="wf-step wf-s6">
                <div class="wf-num">06 &mdash; Státusz</div>
                <div class="wf-icon"><i class="fas fa-check-double"></i></div>
                <div class="wf-title">Státusz frissítés</div>
                <div class="wf-desc">A számla státusza <strong>függőben → fizetve</strong> állapotba vált. Audit log bejegyzés + automatikus fizetési visszaigazoló email.</div>
                <a href="{{ route('fizetes.index') }}" class="wf-link">
                    <i class="fas fa-coins"></i> Fizetések
                </a>
            </div>

            <div class="wf-arrow-h" style="transform:rotate(180deg);"><i class="fas fa-chevron-right"></i></div>

            {{-- Lépés 5: Fizetés --}}
            <div class="wf-step wf-s5">
                <div class="wf-num">05 &mdash; Fizetés</div>
                <div class="wf-icon"><i class="fas fa-credit-card"></i></div>
                <div class="wf-title">Fizetés teljesítése</div>
                <div class="wf-desc">Az ügyfél fizet <strong>Stripe</strong>-on keresztül online, vagy <strong>banki átutalással</strong> — utóbbit az admin rögzíti manuálisan.</div>
                <a href="{{ route('fizetes.index') }}" class="wf-link">
                    <i class="fas fa-wallet"></i> Fizetések
                </a>
            </div>

            {{-- Ív felfelé (bal oldal) --}}
            <div class="wf-turn-left">
                <svg width="52" height="100%" viewBox="0 0 52 120" fill="none" xmlns="http://www.w3.org/2000/svg" style="height:120px;width:52px;">
                    <path d="M 44 100 Q 8 100 8 60 Q 8 20 44 20" stroke="#cbd5e1" stroke-width="2" fill="none" stroke-dasharray="5 3"/>
                    <polygon points="48,14 38,20 48,26" fill="#cbd5e1"/>
                </svg>
            </div>

        </div>

        {{-- Legenda --}}
        <div style="margin-top:18px;padding-top:14px;border-top:1px solid #f1f5f9;display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:6px;">
                <span style="width:12px;height:2px;background:#cbd5e1;border-radius:2px;display:inline-block;"></span>
                A folyamat balról jobbra, majd jobbról balra halad
            </span>
            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:6px;">
                <i class="fas fa-ban" style="color:#dc2626;font-size:10px;"></i>
                A stornó lépés opcionális — csak visszavonáskor szükséges
            </span>
            <span style="font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:6px;">
                <i class="fas fa-mouse-pointer" style="color:#c9a97a;font-size:10px;"></i>
                Kattints bármelyik lépésen a gyors hozzáféréshez
            </span>
        </div>
    </div>

    {{-- Charts --}}
    <div class="section-title">Statisztika</div>
    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-card-title">Megrendelések városonként</div>
            <div class="chart-card-sub">Aktív megrendelések eloszlása</div>
            <div id="bar_chart" class="chart-inner"></div>
        </div>
        <div class="chart-card">
            <div class="chart-card-title">Szolgáltatások megoszlása</div>
            <div class="chart-card-sub">Megrendelt szolgáltatástípusok aránya</div>
            <div id="pie_chart" class="chart-inner"></div>
        </div>
    </div>

</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', { packages: ['corechart'] });
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {
    // Bar chart – orders per city
    var barData = [
        ['Város', 'Megrendelések száma'],
        @foreach ($statistics as $stat)
            ['{{ $stat->nev }}', {{ $stat->MegrendelesekSzama }}],
        @endforeach
    ];
    if (barData.length > 1) {
        var barChart = new google.visualization.ColumnChart(document.getElementById('bar_chart'));
        barChart.draw(google.visualization.arrayToDataTable(barData), {
            legend: { position: 'none' },
            chartArea: { left: 40, top: 16, right: 16, bottom: 48, width: '100%', height: '80%' },
            colors: ['#c9a97a'],
            bar: { groupWidth: '55%' },
            hAxis: { textStyle: { color: '#64748b', fontSize: 11 } },
            vAxis: { textStyle: { color: '#64748b', fontSize: 11 }, minValue: 0, format: '#' },
            backgroundColor: 'transparent',
        });
    } else {
        document.getElementById('bar_chart').innerHTML = '<div style="color:#94a3b8;text-align:center;padding-top:60px;font-size:13px;">Nincs elég adat</div>';
    }

    // Pie chart – services
    var pieData = [
        ['Típus', 'Mennyiség'],
        @foreach ($szolgaltatasokKereslete as $sz)
            ['{{ $sz->tipus }}', {{ $sz->Kereslet }}],
        @endforeach
    ];
    if (pieData.length > 1) {
        var pieChart = new google.visualization.PieChart(document.getElementById('pie_chart'));
        pieChart.draw(google.visualization.arrayToDataTable(pieData), {
            legend: { position: 'bottom', textStyle: { color: '#64748b', fontSize: 11 } },
            chartArea: { left: 16, top: 16, right: 16, bottom: 48, width: '100%', height: '75%' },
            colors: ['#c9a97a','#a07848','#e0c49a','#7a5830','#d4a96a','#8c6a3a'],
            pieSliceBorderColor: '#fff',
            backgroundColor: 'transparent',
            pieHole: 0.35,
        });
    } else {
        document.getElementById('pie_chart').innerHTML = '<div style="color:#94a3b8;text-align:center;padding-top:60px;font-size:13px;">Nincs elég adat</div>';
    }
}
</script>

@endsection
