@extends('ujlayout')
@section('content')
@include('breadcrumbs')

<style>
.sc-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px; }
.sc-card { background: #fff; border-radius: 12px; border: 1px solid #e8edf2; box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow: hidden; }
.sc-card-full { grid-column: 1 / -1; }
.sc-card-header {
    display: flex; align-items: center; gap: 10px; padding: 12px 18px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(90deg, rgba(201,169,122,0.07) 0%, rgba(201,169,122,0.01) 100%);
}
.sc-card-icon { width: 30px; height: 30px; border-radius: 7px; background: rgba(201,169,122,0.15); color: #a07848; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }
.sc-card-title { font-size: 12px; font-weight: 700; color: #1e293b; }
.sc-card-body { padding: 18px; }
/* Form */
.sc-field { margin-bottom: 14px; }
.sc-field:last-child { margin-bottom: 0; }
.sc-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 5px; }
.sc-input, .sc-select, .sc-textarea {
    width: 100%; padding: 9px 12px; border-radius: 8px;
    border: 1.5px solid #e2e8f0; background: #fff;
    font-size: 13px; color: #1e293b;
    transition: border-color 0.15s, box-shadow 0.15s;
    box-sizing: border-box;
}
.sc-input:focus, .sc-select:focus, .sc-textarea:focus {
    outline: none; border-color: #c9a97a;
    box-shadow: 0 0 0 3px rgba(201,169,122,0.12);
}
.sc-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.sc-3col { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }
/* Tételek */
.sc-tetel-row {
    display: grid;
    grid-template-columns: 2fr 1fr 70px 90px 120px 60px 36px;
    gap: 8px;
    align-items: end;
    padding: 10px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #f1f5f9;
    margin-bottom: 8px;
}
.sc-tetel-row:nth-child(odd) { background: #fafbfc; }
.sc-tetel-add-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 14px; border-radius: 8px; cursor: pointer;
    font-size: 12px; font-weight: 600;
    background: rgba(201,169,122,0.1); border: 1.5px dashed rgba(201,169,122,0.4); color: #a07848;
    width: 100%; justify-content: center; margin-top: 6px;
    transition: all 0.15s;
}
.sc-tetel-add-btn:hover { background: rgba(201,169,122,0.2); }
.sc-del-btn {
    width: 32px; height: 32px; border-radius: 7px; border: none; cursor: pointer;
    background: rgba(239,68,68,0.08); color: #dc2626; font-size: 12px;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.15s;
}
.sc-del-btn:hover { background: rgba(239,68,68,0.15); }
/* Összesítő */
.sc-ossz-table { width: 100%; border-collapse: collapse; }
.sc-ossz-table td { padding: 8px 14px; font-size: 13px; }
.sc-ossz-table tr:last-child td { font-size: 16px; font-weight: 800; color: #a07848; border-top: 2px solid #e8edf2; padding-top: 12px; }
/* Errors */
.sc-error { color: #dc2626; font-size: 11px; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
@media (max-width: 768px) { .sc-form-grid { grid-template-columns: 1fr; } .sc-card-full { grid-column: 1; } .sc-2col, .sc-3col { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <h1><i class="fas fa-file-invoice"></i> Új számla létrehozása</h1>
</div>

<form method="POST" action="{{ route('szamlak.store') }}" id="szamlaForm">
    @csrf

    <div class="sc-form-grid">

        {{-- Megrendelés kiválasztása --}}
        <div class="sc-card sc-card-full">
            <div class="sc-card-header">
                <div class="sc-card-icon"><i class="fas fa-clipboard-list"></i></div>
                <div class="sc-card-title">Megrendelés és típus</div>
            </div>
            <div class="sc-card-body">
                <div class="sc-3col">
                    <div class="sc-field">
                        <label class="sc-label">Megrendelés *</label>
                        <select name="megrendeles_id" class="sc-select" required id="megrendelesSelect">
                            <option value="">— Válassz megrendelést —</option>
                            @foreach($megrendelesek as $mr)
                                <option value="{{ $mr->id }}"
                                    {{ (old('megrendeles_id', $megrendeles?->id) == $mr->id) ? 'selected' : '' }}>
                                    #{{ $mr->id }} – {{ $mr->megrendeles_nev }}
                                    @if($mr->ugyfel) ({{ $mr->ugyfel->nev }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('megrendeles_id')
                            <div class="sc-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="sc-field">
                        <label class="sc-label">Számla típusa *</label>
                        <select name="szamla_tipus" class="sc-select" required>
                            <option value="szamla"    {{ old('szamla_tipus', 'szamla')    === 'szamla'    ? 'selected' : '' }}>Számla</option>
                            <option value="dijbekero" {{ old('szamla_tipus', 'szamla')    === 'dijbekero' ? 'selected' : '' }}>Díjbekérő</option>
                        </select>
                    </div>
                    <div class="sc-field">
                        <label class="sc-label">Fizetési mód *</label>
                        <select name="fizetesi_mod" class="sc-select" required>
                            <option value="stripe"         {{ old('fizetesi_mod') === 'stripe'         ? 'selected' : '' }}>Bankkártya (Stripe)</option>
                            <option value="banki_atutalas" {{ old('fizetesi_mod') === 'banki_atutalas' ? 'selected' : '' }}>Banki átutalás</option>
                            <option value="keszpenz"       {{ old('fizetesi_mod') === 'keszpenz'       ? 'selected' : '' }}>Készpénz</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dátumok --}}
        <div class="sc-card">
            <div class="sc-card-header">
                <div class="sc-card-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="sc-card-title">Dátumok</div>
            </div>
            <div class="sc-card-body">
                <div class="sc-field">
                    <label class="sc-label">Kiállítás dátuma *</label>
                    <input type="date" name="kiallitas_datum" class="sc-input" required
                           value="{{ old('kiallitas_datum', now()->toDateString()) }}">
                    @error('kiallitas_datum') <div class="sc-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                </div>
                <div class="sc-field">
                    <label class="sc-label">Teljesítés dátuma *</label>
                    <input type="date" name="teljesites_datum" class="sc-input" required
                           value="{{ old('teljesites_datum', now()->toDateString()) }}">
                    @error('teljesites_datum') <div class="sc-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                </div>
                <div class="sc-field">
                    <label class="sc-label">Fizetési határidő *</label>
                    <input type="date" name="fizetesi_hatarido" class="sc-input" required
                           value="{{ old('fizetesi_hatarido', now()->addDays(8)->toDateString()) }}">
                    @error('fizetesi_hatarido') <div class="sc-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Megjegyzés --}}
        <div class="sc-card">
            <div class="sc-card-header">
                <div class="sc-card-icon"><i class="fas fa-comment"></i></div>
                <div class="sc-card-title">Megjegyzés</div>
            </div>
            <div class="sc-card-body">
                <div class="sc-field">
                    <label class="sc-label">Belső megjegyzés (opcionális)</label>
                    <textarea name="megjegyzes" class="sc-textarea" rows="5"
                              placeholder="Pl. akciós ár, különleges feltételek...">{{ old('megjegyzes') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Tételek --}}
        <div class="sc-card sc-card-full">
            <div class="sc-card-header">
                <div class="sc-card-icon"><i class="fas fa-list-ul"></i></div>
                <div class="sc-card-title">Tételek</div>
                <div style="margin-left:auto;font-size:11px;color:#94a3b8;">Nettó egységárat adj meg, az ÁFA automatikusan számítódik</div>
            </div>
            <div class="sc-card-body">

                {{-- Fejléc --}}
                <div style="display:grid;grid-template-columns:2fr 1fr 70px 90px 120px 60px 36px;gap:8px;padding:0 10px 6px;margin-bottom:4px;">
                    <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">Megnevezés</span>
                    <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">Típus</span>
                    <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">Menny.</span>
                    <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">Me.</span>
                    <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">Egységár (nettó)</span>
                    <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;">ÁFA %</span>
                    <span></span>
                </div>

                <div id="tetelek-container">
                    {{-- Tételek JS-ből kerülnek ide --}}
                </div>

                <button type="button" class="sc-tetel-add-btn" id="addTetelBtn">
                    <i class="fas fa-plus"></i> Tétel hozzáadása
                </button>

                @error('tetelek') <div class="sc-error" style="margin-top:8px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror

                {{-- Összesítő --}}
                <div style="margin-top:18px;display:flex;justify-content:flex-end;">
                    <div style="min-width:280px;background:#f8fafc;border-radius:10px;border:1px solid #e8edf2;overflow:hidden;">
                        <table class="sc-ossz-table">
                            <tr>
                                <td style="color:#64748b;">Nettó összesen:</td>
                                <td style="text-align:right;font-weight:600;" id="ossz-netto">0 Ft</td>
                            </tr>
                            <tr>
                                <td style="color:#64748b;">ÁFA összesen:</td>
                                <td style="text-align:right;font-weight:600;" id="ossz-afa">0 Ft</td>
                            </tr>
                            <tr>
                                <td style="color:#a07848;font-weight:700;">Bruttó összesen:</td>
                                <td style="text-align:right;color:#a07848;" id="ossz-brutto">0 Ft</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Gombok --}}
    <div style="display:flex;gap:10px;margin-top:4px;">
        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> Számla létrehozása
        </button>
        <a href="{{ route('szamlak.index') }}" class="btn-back">
            <i class="fas fa-times"></i> Mégsem
        </a>
    </div>

</form>

<script>
let tetelIndex = 0;

function formatFt(n) {
    return new Intl.NumberFormat('hu-HU').format(Math.round(n)) + ' Ft';
}

function addTetel(data = {}) {
    const i = tetelIndex++;
    const defaults = {
        tetel_tipus: 'egyeb',
        nev: '',
        mennyiseg: 1,
        mertekegyseg: 'db',
        egyseg_netto_ar: 0,
        afa_kulcs: 27,
        ...data
    };

    const row = document.createElement('div');
    row.className = 'sc-tetel-row';
    row.dataset.index = i;
    row.innerHTML = `
        <div>
            <input type="text" name="tetelek[${i}][nev]" class="sc-input" placeholder="Tétel neve *"
                   value="${defaults.nev}" required oninput="szamolOssz()">
        </div>
        <div>
            <select name="tetelek[${i}][tetel_tipus]" class="sc-select">
                <option value="egyeb"    ${defaults.tetel_tipus === 'egyeb'    ? 'selected' : ''}>Egyéb</option>
                <option value="anyag"    ${defaults.tetel_tipus === 'anyag'    ? 'selected' : ''}>Anyag</option>
                <option value="munkaora" ${defaults.tetel_tipus === 'munkaora' ? 'selected' : ''}>Munkaóra</option>
            </select>
        </div>
        <div>
            <input type="number" name="tetelek[${i}][mennyiseg]" class="sc-input" step="0.001" min="0.001"
                   value="${defaults.mennyiseg}" required oninput="szamolOssz()" style="text-align:right;">
        </div>
        <div>
            <input type="text" name="tetelek[${i}][mertekegyseg]" class="sc-input" placeholder="db"
                   value="${defaults.mertekegyseg}" required>
        </div>
        <div>
            <input type="number" name="tetelek[${i}][egyseg_netto_ar]" class="sc-input" step="1" min="0"
                   value="${defaults.egyseg_netto_ar}" required oninput="szamolOssz()" style="text-align:right;"
                   placeholder="0">
        </div>
        <div>
            <select name="tetelek[${i}][afa_kulcs]" class="sc-select" onchange="szamolOssz()">
                <option value="27" ${defaults.afa_kulcs == 27 ? 'selected' : ''}>27%</option>
                <option value="5"  ${defaults.afa_kulcs == 5  ? 'selected' : ''}>5%</option>
                <option value="0"  ${defaults.afa_kulcs == 0  ? 'selected' : ''}>0%</option>
            </select>
        </div>
        <div style="display:flex;align-items:center;">
            <button type="button" class="sc-del-btn" onclick="removeTetel(this)" title="Törlés">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    `;

    document.getElementById('tetelek-container').appendChild(row);
    szamolOssz();
}

function removeTetel(btn) {
    const row = btn.closest('.sc-tetel-row');
    row.remove();
    szamolOssz();
}

function szamolOssz() {
    let nettoOssz = 0, afaOssz = 0;
    document.querySelectorAll('.sc-tetel-row').forEach(row => {
        const i = row.dataset.index;
        const menny = parseFloat(row.querySelector(`input[name="tetelek[${i}][mennyiseg]"]`)?.value) || 0;
        const ar    = parseFloat(row.querySelector(`input[name="tetelek[${i}][egyseg_netto_ar]"]`)?.value) || 0;
        const afa   = parseFloat(row.querySelector(`select[name="tetelek[${i}][afa_kulcs]"]`)?.value) || 0;
        const netto = menny * ar;
        nettoOssz += netto;
        afaOssz   += netto * (afa / 100);
    });
    document.getElementById('ossz-netto').textContent   = formatFt(nettoOssz);
    document.getElementById('ossz-afa').textContent     = formatFt(afaOssz);
    document.getElementById('ossz-brutto').textContent  = formatFt(nettoOssz + afaOssz);
}

document.getElementById('addTetelBtn').addEventListener('click', () => addTetel());

// Old tételek visszatöltése (validation hiba esetén)
@if(old('tetelek'))
    @foreach(old('tetelek') as $i => $t)
        addTetel({
            tetel_tipus: "{{ $t['tetel_tipus'] ?? 'egyeb' }}",
            nev: "{{ addslashes($t['nev'] ?? '') }}",
            mennyiseg: {{ $t['mennyiseg'] ?? 1 }},
            mertekegyseg: "{{ $t['mertekegyseg'] ?? 'db' }}",
            egyseg_netto_ar: {{ $t['egyseg_netto_ar'] ?? 0 }},
            afa_kulcs: {{ $t['afa_kulcs'] ?? 27 }},
        });
    @endforeach
@elseif($megrendeles && $megrendeles->felhasznaltAnyagok->count() > 0)
    // Felhasznált anyagok automatikus betöltése
    @foreach($megrendeles->felhasznaltAnyagok as $fa)
        addTetel({
            tetel_tipus: 'anyag',
            nev: "{{ addslashes($fa->anyag->nev ?? '') }}",
            mennyiseg: {{ $fa->mennyiseg ?? 1 }},
            mertekegyseg: "{{ addslashes($fa->anyag->mertekegyseg ?? 'db') }}",
            egyseg_netto_ar: 0,
            afa_kulcs: 27,
        });
    @endforeach
@else
    // Alapértelmezett 1 üres tétel
    addTetel();
@endif
</script>

@endsection
