@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<style>
.sc-form-card {
    background: #fff;
    border: 1px solid #e8edf2;
    border-radius: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    overflow: hidden;
}
.sc-form-card-header {
    padding: 14px 20px;
    background: linear-gradient(90deg, rgba(201,169,122,0.08) 0%, rgba(201,169,122,0.02) 100%);
    border-bottom: 1px solid #e8edf2;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sc-form-card-header i { color: #c9a97a; }
.sc-form-body { padding: 20px; }
.sc-field-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px;
}
.sc-field label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #94a3b8;
    margin-bottom: 6px;
}
.sc-field input,
.sc-field select,
.sc-field textarea {
    width: 100%;
    padding: 9px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #334155;
    background: #fff;
    outline: none;
    transition: border-color 0.15s;
    box-sizing: border-box;
}
.sc-field input:focus,
.sc-field select:focus,
.sc-field textarea:focus { border-color: #c9a97a; }
.sc-field input[readonly],
.sc-field select[disabled] {
    background: #f8fafc;
    color: #94a3b8;
    cursor: not-allowed;
}
.sc-field textarea { resize: vertical; min-height: 80px; }
.sc-field .field-hint { font-size: 11px; color: #94a3b8; margin-top: 4px; }
.sc-field .invalid-feedback { color: #dc2626; font-size: 11px; margin-top: 4px; }

/* Státusz badge */
.sc-status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; border-radius: 6px; padding: 5px 12px;
}
.sc-status-badge.fuggoben   { background: rgba(59,130,246,0.1);   color: #2563eb; border: 1px solid rgba(59,130,246,0.2); }
.sc-status-badge.fizetve    { background: rgba(34,197,94,0.1);    color: #16a34a; border: 1px solid rgba(34,197,94,0.2); }
.sc-status-badge.kesedelmes { background: rgba(239,68,68,0.1);    color: #dc2626; border: 1px solid rgba(239,68,68,0.2); }
.sc-status-badge.stornozva  { background: rgba(148,163,184,0.15); color: #64748b; border: 1px solid rgba(148,163,184,0.3); }

.sc-info-readonly {
    padding: 9px 12px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #475569;
    font-weight: 500;
}
</style>

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <h1><i class="fas fa-file-invoice-dollar"></i>
        Számla szerkesztése – #{{ str_pad($szamla->szamla_id, 5, '0', STR_PAD_LEFT) }}
    </h1>
    <a href="{{ route('szamlak.show', $szamla->szamla_id) }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Vissza
    </a>
</div>

{{-- Stornó figyelmeztetés --}}
@if($szamla->statusz === 'stornozva')
<div style="background:rgba(239,68,68,0.07);border:1.5px solid rgba(239,68,68,0.2);border-radius:10px;padding:12px 18px;margin-bottom:18px;display:flex;align-items:center;gap:10px;font-size:13px;color:#dc2626;">
    <i class="fas fa-ban"></i>
    <strong>Ez a számla stornózva van.</strong> Stornózott számla adatai csak korlátozottan szerkeszthetők.
</div>
@endif

@if ($errors->any())
<div style="background:rgba(239,68,68,0.07);border:1.5px solid rgba(239,68,68,0.2);border-radius:10px;padding:12px 18px;margin-bottom:18px;">
    <ul style="margin:0;padding-left:18px;color:#dc2626;font-size:13px;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('szamlak.update', $szamla->szamla_id) }}">
    @csrf
    @method('PUT')

    {{-- Alapadatok (csak olvasható) --}}
    <div class="sc-form-card">
        <div class="sc-form-card-header">
            <i class="fas fa-info-circle"></i> Alapadatok (csak olvasható)
        </div>
        <div class="sc-form-body">
            <div class="sc-field-grid">
                <div class="sc-field">
                    <label>Számla ID</label>
                    <div class="sc-info-readonly">#{{ str_pad($szamla->szamla_id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="sc-field">
                    <label>Típus</label>
                    <div class="sc-info-readonly">
                        @switch($szamla->szamla_tipus)
                            @case('szamla') <i class="fas fa-file-invoice"></i> Számla @break
                            @case('dijbekero') <i class="fas fa-file-alt"></i> Díjbekérő @break
                            @case('storno') <i class="fas fa-ban"></i> Stornó @break
                        @endswitch
                    </div>
                </div>
                <div class="sc-field">
                    <label>Megrendelés</label>
                    <div class="sc-info-readonly">
                        <a href="{{ route('megrendeles.show', $szamla->megrendeles_id) }}" style="color:#c9a97a;text-decoration:none;">
                            #{{ str_pad($szamla->megrendeles_id, 5, '0', STR_PAD_LEFT) }}
                            {{ $szamla->megrendeles->Megrendeles_Nev ?? '' }}
                        </a>
                    </div>
                </div>
                <div class="sc-field">
                    <label>Ügyfél</label>
                    <div class="sc-info-readonly">{{ $szamla->megrendeles->ugyfel->Nev ?? '—' }}</div>
                </div>
                <div class="sc-field">
                    <label>Kiállítás dátuma</label>
                    <div class="sc-info-readonly">{{ $szamla->kiallitas_datum?->format('Y. m. d.') ?? '—' }}</div>
                </div>
                <div class="sc-field">
                    <label>Státusz</label>
                    <div>
                        <span class="sc-status-badge {{ $szamla->statusz }}">
                            @switch($szamla->statusz)
                                @case('fuggoben')   <i class="fas fa-hourglass-half"></i> Függőben @break
                                @case('fizetve')    <i class="fas fa-check-circle"></i> Fizetve @break
                                @case('kesedelmes') <i class="fas fa-exclamation-circle"></i> Késedelmes @break
                                @case('stornozva')  <i class="fas fa-ban"></i> Stornózva @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Szerkeszthető mezők --}}
    <div class="sc-form-card">
        <div class="sc-form-card-header">
            <i class="fas fa-edit"></i> Szerkeszthető adatok
        </div>
        <div class="sc-form-body">
            <div class="sc-field-grid">

                <div class="sc-field">
                    <label>Teljesítés dátuma</label>
                    <input type="date" name="teljesites_datum"
                           value="{{ old('teljesites_datum', $szamla->teljesites_datum?->format('Y-m-d')) }}"
                           {{ $szamla->statusz === 'stornozva' ? 'readonly' : '' }}>
                    @error('teljesites_datum')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="sc-field">
                    <label>Fizetési határidő</label>
                    <input type="date" name="fizetesi_hatarido"
                           value="{{ old('fizetesi_hatarido', $szamla->fizetesi_hatarido?->format('Y-m-d')) }}"
                           {{ $szamla->statusz === 'stornozva' ? 'readonly' : '' }}>
                    @error('fizetesi_hatarido')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="sc-field">
                    <label>Fizetési mód</label>
                    <select name="fizetesi_mod" {{ $szamla->statusz === 'stornozva' ? 'disabled' : '' }}>
                        <option value="">— Nincs megadva —</option>
                        <option value="banki_atutalas" {{ old('fizetesi_mod', $szamla->fizetesi_mod) === 'banki_atutalas' ? 'selected' : '' }}>
                            Banki átutalás
                        </option>
                        <option value="stripe" {{ old('fizetesi_mod', $szamla->fizetesi_mod) === 'stripe' ? 'selected' : '' }}>
                            Bankkártyás fizetés (Stripe)
                        </option>
                    </select>
                    @if($szamla->statusz === 'stornozva')
                        <input type="hidden" name="fizetesi_mod" value="{{ $szamla->fizetesi_mod }}">
                    @endif
                    @error('fizetesi_mod')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div style="margin-top:16px;">
                <div class="sc-field">
                    <label>Megjegyzés</label>
                    <textarea name="megjegyzes"
                              rows="3"
                              {{ $szamla->statusz === 'stornozva' ? 'readonly' : '' }}
                              placeholder="Belső megjegyzés (nem jelenik meg az ügyfélen)...">{{ old('megjegyzes', $szamla->megjegyzes) }}</textarea>
                    @error('megjegyzes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Billingo adatok (csak olvasható, info jellegű) --}}
    @if($szamla->billingo_id)
    <div class="sc-form-card">
        <div class="sc-form-card-header">
            <i class="fas fa-cloud"></i> Billingo adatok
        </div>
        <div class="sc-form-body">
            <div class="sc-field-grid">
                <div class="sc-field">
                    <label>Billingo ID</label>
                    <div class="sc-info-readonly">{{ $szamla->billingo_id }}</div>
                </div>
                <div class="sc-field">
                    <label>Számlaszám</label>
                    <div class="sc-info-readonly">{{ $szamla->billingo_szam ?? '—' }}</div>
                </div>
                <div class="sc-field">
                    <label>PDF link</label>
                    <div class="sc-info-readonly">
                        @if($szamla->billingo_pdf_url)
                            <a href="{{ route('szamlak.download', $szamla->szamla_id) }}" style="color:#c9a97a;">
                                <i class="fas fa-download"></i> Letöltés
                            </a>
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Összegek (csak olvasható) --}}
    <div class="sc-form-card">
        <div class="sc-form-card-header">
            <i class="fas fa-calculator"></i> Összegek
        </div>
        <div class="sc-form-body">
            <div class="sc-field-grid">
                <div class="sc-field">
                    <label>Nettó összeg</label>
                    <div class="sc-info-readonly">{{ number_format($szamla->netto_osszeg, 0, ',', ' ') }} Ft</div>
                </div>
                <div class="sc-field">
                    <label>ÁFA összeg</label>
                    <div class="sc-info-readonly">{{ number_format($szamla->afa_osszeg, 0, ',', ' ') }} Ft</div>
                </div>
                <div class="sc-field">
                    <label>Bruttó összeg</label>
                    <div class="sc-info-readonly" style="font-weight:700;font-size:15px;color:#1e293b;">
                        {{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft
                    </div>
                </div>
            </div>
            <p class="field-hint" style="margin-top:10px;">
                <i class="fas fa-info-circle"></i>
                Az összegek a tételsorok alapján kerülnek kiszámításra és automatikusan frissülnek.
                Módosításhoz a Részletek oldalon szerkeszd a tételeket.
            </p>
        </div>
    </div>

    {{-- Gombok --}}
    @if($szamla->statusz !== 'stornozva')
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> Mentés
        </button>
        <a href="{{ route('szamlak.show', $szamla->szamla_id) }}" class="btn-back">
            <i class="fas fa-times"></i> Mégsem
        </a>
    </div>
    @else
    <div style="display:flex;gap:12px;align-items:center;">
        <a href="{{ route('szamlak.show', $szamla->szamla_id) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza a részletekhez
        </a>
        <span style="font-size:12px;color:#94a3b8;">Stornózott számla nem szerkeszthető.</span>
    </div>
    @endif
</form>

@endsection
