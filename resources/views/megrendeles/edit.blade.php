@extends('ujlayout')

@section('content')

<style>
/* ── Form sections ──────────────────────────────────────── */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
.form-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    overflow: hidden;
}
.form-card-full { grid-column: 1 / -1; }
.form-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(90deg, rgba(201,169,122,0.07) 0%, rgba(201,169,122,0.01) 100%);
}
.form-card-header-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(201,169,122,0.15);
    color: #a07848;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}
.form-card-title { font-size: 13px; font-weight: 700; color: #1e293b; }
.form-card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* ── Status badge header ─────────────────────────────────── */
.edit-status-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    background: rgba(201,169,122,0.06);
    border-radius: 10px;
    border: 1px solid rgba(201,169,122,0.2);
    font-size: 12px;
    color: #64748b;
    margin-bottom: 4px;
}
.edit-status-bar strong { color: #a07848; }

/* ── Field groups ───────────────────────────────────────── */
.f-group { display: flex; flex-direction: column; gap: 5px; }
.f-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.f-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 5px;
}
.f-label i { color: #c9a97a; font-size: 10px; }
.f-label .req { color: #ef4444; }
.f-input, .f-select, .f-textarea {
    width: 100%;
    padding: 9px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #334155;
    background: #fff;
    transition: border-color 0.15s, box-shadow 0.15s;
    font-family: inherit;
    outline: none;
    box-sizing: border-box;
}
.f-input:focus, .f-select:focus, .f-textarea:focus {
    border-color: #c9a97a;
    box-shadow: 0 0 0 3px rgba(201,169,122,0.15);
}
.f-textarea { resize: vertical; min-height: 80px; }
.f-hint { font-size: 11px; color: #94a3b8; margin-top: 1px; }

/* ── Status select ──────────────────────────────────────── */
.status-select-wrap { position: relative; }
.status-badge-folyamatban {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; border-radius: 5px;
    padding: 3px 9px; background: rgba(59,130,246,0.1); color: #2563eb;
}
.status-badge-befejezve {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; border-radius: 5px;
    padding: 3px 9px; background: rgba(34,197,94,0.1); color: #16a34a;
}

/* ── Anyag sor ──────────────────────────────────────────── */
.anyag-par {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 8px;
    align-items: center;
}
.btn-remove-anyag {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 7px;
    border: 1.5px solid #fecaca;
    background: #fff5f5;
    color: #dc2626;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.15s;
    flex-shrink: 0;
}
.btn-remove-anyag:hover { background: #fee2e2; }
.btn-add-anyag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 8px;
    border: 1.5px dashed #c9a97a;
    background: rgba(201,169,122,0.05);
    color: #a07848;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    margin-top: 4px;
}
.btn-add-anyag:hover { background: rgba(201,169,122,0.12); }

/* ── Submit area ─────────────────────────────────────────── */
.form-submit-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8edf2;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-card-full { grid-column: 1; }
    .f-row { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .form-card-body { padding: 14px; }
    .form-submit-bar { flex-direction: column; align-items: stretch; }
}
</style>

<div class="page-header">
    <h1><i class="fas fa-clipboard-check"></i> Megrendelés szerkesztése</h1>
    <a href="{{ route('megrendeles.show', ['id' => $megrendeles->Megrendeles_ID]) }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Vissza
    </a>
</div>

@if ($errors->any())
    <div style="margin-bottom:16px;">
        @foreach ($errors->all() as $error)
            <div class="alert alert-warning" style="margin-bottom:6px;">
                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
            </div>
        @endforeach
    </div>
@endif

<form id="editForm" action="{{ route('megrendeles.update', $megrendeles->Megrendeles_ID) }}" method="POST">
@csrf
@method('PUT')
<input type="hidden" name="Munka_ID" value="{{ $munka->Munka_ID }}">

{{-- Azonosító sáv --}}
<div class="edit-status-bar" style="margin-bottom:20px;">
    <i class="fas fa-hashtag" style="color:#c9a97a;"></i>
    Megrendelés azonosítója: <strong>#{{ str_pad($megrendeles->Megrendeles_ID, 5, '0', STR_PAD_LEFT) }}</strong>
    &nbsp;|&nbsp;
    <i class="fas fa-user" style="color:#c9a97a;"></i>
    Ügyfél: <strong>{{ $megrendeles->ugyfel->Nev ?? '—' }}</strong>
</div>

<div class="form-grid">

    {{-- Ügyfél + megrendelő --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-header-icon"><i class="fas fa-user"></i></div>
            <div class="form-card-title">Ügyfél adatai</div>
        </div>
        <div class="form-card-body">
            <div class="f-group">
                <div class="f-label"><i class="fas fa-user"></i> Ügyfél <span class="req">*</span></div>
                <select name="Ugyfel_ID" id="Ugyfel_ID" class="f-select">
                    <option value="">— Válassz ügyfelet —</option>
                    @foreach ($ugyfelek as $ugyfel)
                        <option value="{{ $ugyfel->Ugyfel_ID }}"
                                {{ $ugyfel->Ugyfel_ID == $megrendeles->Ugyfel_ID ? 'selected' : '' }}>
                            {{ $ugyfel->Ugyfel_ID }} – {{ $ugyfel->Nev }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-file-signature"></i> Megrendelő neve <span class="req">*</span></div>
                <input type="text" name="Megrendeles_Nev" id="Megrendeles_Nev"
                       class="f-input"
                       value="{{ old('Megrendeles_Nev', $megrendeles->Megrendeles_Nev) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-toggle-on"></i> Státusz <span class="req">*</span></div>
                <select name="Statusz" id="Statusz" class="f-select">
                    <option value="1" {{ old('Statusz', $megrendeles->Statusz) == 1 ? 'selected' : '' }}>
                        Folyamatban
                    </option>
                    <option value="0" {{ old('Statusz', $megrendeles->Statusz) == 0 ? 'selected' : '' }}>
                        Befejezve
                    </option>
                </select>
                <div class="f-hint">„Befejezve" = aláírt szerződés</div>
            </div>

            {{-- Végösszeg és határidő a Számla rendszerben kezelendő --}}
            <div class="f-hint" style="margin-top:6px;padding:10px 14px;background:rgba(201,169,122,0.08);border-radius:8px;border:1px solid rgba(201,169,122,0.25);">
                <i class="fas fa-info-circle" style="color:#a07848;"></i>
                A fizetési összeg és határidő a <a href="{{ route('szamlak.index') }}" style="color:#a07848;font-weight:600;">Számlák</a> menüpontban kezelhető.
            </div>
        </div>
    </div>

    {{-- Helyszín --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-header-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="form-card-title">Helyszín adatai</div>
        </div>
        <div class="form-card-body">
            <div class="f-group">
                <div class="f-label"><i class="fas fa-city"></i> Város <span class="req">*</span></div>
                <select name="Varos_ID" id="Varos_ID" class="f-select">
                    <option value="">— Válassz várost —</option>
                    @foreach ($varosok as $varos)
                        <option value="{{ $varos->Varos_ID }}"
                                {{ $varos->Varos_ID == $megrendeles->Varos_ID ? 'selected' : '' }}>
                            {{ $varos->Irny_szam }} {{ $varos->Nev }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-road"></i> Utca, házszám <span class="req">*</span></div>
                <input type="text" name="Utca_Hazszam" id="Utca_Hazszam"
                       class="f-input"
                       value="{{ old('Utca_Hazszam', $megrendeles->Utca_Hazszam) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-file-pdf"></i> PDF elérési út</div>
                <input type="text" name="Pdf_EleresiUt" id="Pdf_EleresiUt"
                       class="f-input"
                       value="{{ old('Pdf_EleresiUt', $megrendeles->Pdf_EleresiUt) }}"
                       placeholder="pl. storage/app/public/doc.pdf">
                <div class="f-hint">Opcionális — ha létezik már feltöltött PDF.</div>
            </div>
        </div>
    </div>

    {{-- Munka adatai --}}
    <div class="form-card form-card-full">
        <div class="form-card-header">
            <div class="form-card-header-icon"><i class="fas fa-tools"></i></div>
            <div class="form-card-title">Munka adatai</div>
        </div>
        <div class="form-card-body">
            <div class="f-row">
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-cogs"></i> Szolgáltatás <span class="req">*</span></div>
                    <select name="Szolgaltatas_ID" id="Szolgaltatas_ID" class="f-select">
                        <option value="">— Válassz szolgáltatást —</option>
                        @foreach ($szolgaltatasok as $szo)
                            <option value="{{ $szo->Szolgaltatas_ID }}"
                                    {{ old('Szolgaltatas_ID', $munka->Szolgaltatas_ID) == $szo->Szolgaltatas_ID ? 'selected' : '' }}>
                                {{ $szo->Tipus }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-hard-hat"></i> Szerelő <span class="req">*</span></div>
                    <select name="Szerelo_ID" id="Szerelo_ID" class="f-select">
                        <option value="">— Válassz szerelőt —</option>
                        @foreach ($szerelok as $szerelo)
                            <option value="{{ $szerelo->Szerelo_ID }}"
                                    {{ old('Szerelo_ID', $munka->Szerelo_ID) == $szerelo->Szerelo_ID ? 'selected' : '' }}>
                                {{ $szerelo->Nev }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="f-row">
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-calendar-alt"></i> Munkakezdés <span class="req">*</span></div>
                    <input type="datetime-local" name="Munkakezdes_Idopontja" id="Munkakezdes_Idopontja"
                           class="f-input"
                           value="{{ old('Munkakezdes_Idopontja', isset($munka) ? date('Y-m-d\TH:i', strtotime($munka->Munkakezdes_Idopontja)) : '') }}">
                </div>
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-calendar-check"></i> Befejezés <span class="req">*</span></div>
                    <input type="datetime-local" name="Munkabefejezes_Idopontja" id="Munkabefejezes_Idopontja"
                           class="f-input"
                           value="{{ old('Munkabefejezes_Idopontja', isset($munka) ? date('Y-m-d\TH:i', strtotime($munka->Munkabefejezes_Idopontja)) : '') }}">
                </div>
            </div>

            <div class="f-group">
                <div class="f-label"><i class="fas fa-align-left"></i> Munka leírása</div>
                <textarea name="Leiras" id="Leiras" class="f-textarea"
                          placeholder="Opcionális megjegyzés...">{{ old('Leiras', $munka->Leiras ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Anyagok --}}
    <div class="form-card form-card-full">
        <div class="form-card-header">
            <div class="form-card-header-icon"><i class="fas fa-boxes"></i></div>
            <div class="form-card-title">Felhasznált anyagok</div>
        </div>
        <div class="form-card-body">
            <div id="anyagokContainer" style="display:flex;flex-direction:column;gap:8px;">
                @foreach ($megrendeles->felhasznaltAnyagok as $fa)
                    <div class="anyag-par">
                        <select name="Anyag_ID[]" class="anyagSelect f-select">
                            <option value="">— Válassz anyagot —</option>
                            @foreach ($anyagok as $anyag)
                                <option value="{{ $anyag->Anyag_ID }}"
                                        {{ $anyag->Anyag_ID == $fa->Anyag_ID ? 'selected' : '' }}>
                                    {{ $anyag->Nev }} ({{ $anyag->Mertekegyseg }})
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="Mennyiseg[]" placeholder="db" min="1"
                               class="f-input" style="max-width:90px;"
                               value="{{ $fa->Mennyiseg }}">
                        <button type="button" class="removeAnyag btn-remove-anyag" title="Eltávolítás">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Üres sor template (rejtett) - klónozáshoz --}}
            <template id="anyagTemplate">
                <div class="anyag-par">
                    <select name="Anyag_ID[]" class="anyagSelect f-select">
                        <option value="">— Válassz anyagot —</option>
                        @foreach ($anyagok as $anyag)
                            <option value="{{ $anyag->Anyag_ID }}">
                                {{ $anyag->Nev }} ({{ $anyag->Mertekegyseg }})
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="Mennyiseg[]" placeholder="db" min="1"
                           class="f-input" style="max-width:90px;">
                    <button type="button" class="removeAnyag btn-remove-anyag" title="Eltávolítás">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </template>

            <button type="button" id="addAnyag" class="btn-add-anyag">
                <i class="fas fa-plus"></i> Anyag hozzáadása
            </button>
        </div>
    </div>

</div>

{{-- Submit --}}
<div class="form-submit-bar">
    <button type="submit" class="btn-save">
        <i class="fas fa-save"></i> Mentés
    </button>
    <a href="{{ route('megrendeles.show', ['id' => $megrendeles->Megrendeles_ID]) }}" class="btn-back">
        <i class="fas fa-times"></i> Mégsem
    </a>
</div>

</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('anyagokContainer');
    const template  = document.getElementById('anyagTemplate');

    document.getElementById('addAnyag').addEventListener('click', function () {
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    });

    container.addEventListener('click', function (e) {
        const btn = e.target.closest('.removeAnyag');
        if (!btn) return;
        const rows = container.querySelectorAll('.anyag-par');
        if (rows.length > 1) {
            btn.closest('.anyag-par').remove();
        } else {
            btn.closest('.anyag-par').querySelector('.anyagSelect').selectedIndex = 0;
            btn.closest('.anyag-par').querySelector('input[type=number]').value = '';
        }
    });
});
</script>

@endsection
