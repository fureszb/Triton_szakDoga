@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="page-header">
    <h1><i class="fas fa-user-edit"></i> Ügyfél szerkesztése</h1>
    <a href="{{ route('ugyfel.show', ['id' => $ugyfel->id]) }}" class="btn-back">
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

{{-- ID sáv --}}
<div style="display:flex;align-items:center;gap:10px;padding:10px 16px;background:rgba(201,169,122,0.06);border:1px solid rgba(201,169,122,0.2);border-radius:10px;font-size:12px;color:#64748b;margin-bottom:20px;">
    <i class="fas fa-hashtag" style="color:#c9a97a;"></i>
    Ügyfél azonosítója: <strong style="color:#a07848;">{{ $ugyfel->id }}</strong>
    &nbsp;|&nbsp;
    <i class="fas fa-user" style="color:#c9a97a;"></i>
    <strong style="color:#a07848;">{{ $ugyfel->nev }}</strong>
</div>

<form action="{{ route('ugyfel.update', $ugyfel->id) }}" method="POST">
@csrf
@method('PUT')

<div class="fc-grid">

    {{-- Személyes adatok --}}
    <div class="fc-card">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-user"></i></div>
            <div class="fc-htitle">Személyes adatok</div>
        </div>
        <div class="fc-body">
            <div class="f-group">
                <div class="f-label"><i class="fas fa-hashtag"></i> Ügyfél ID <span class="req">*</span></div>
                <input type="text" name="ugyfel_id" class="f-input" value="{{ old('ugyfel_id', $ugyfel->id) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-user"></i> Teljes név <span class="req">*</span></div>
                <input type="text" name="nev" class="f-input" value="{{ old('nev', $ugyfel->nev) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-envelope"></i> Email cím <span class="req">*</span></div>
                <input type="email" name="email" class="f-input" value="{{ old('email', $ugyfel->email) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-phone"></i> Telefonszám <span class="req">*</span></div>
                <input type="text" name="telefon" class="f-input" value="{{ old('telefon', $ugyfel->telefonszam) }}">
            </div>
        </div>
    </div>

    {{-- Számlázási adatok --}}
    <div class="fc-card">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-file-invoice"></i></div>
            <div class="fc-htitle">Számlázási adatok</div>
        </div>
        <div class="fc-body">
            <div class="f-group">
                <div class="f-label"><i class="fas fa-building"></i> Számlázási név <span class="req">*</span></div>
                <input type="text" name="szamnev" class="f-input" value="{{ old('szamnev', $ugyfel->szamlazasi_nev) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-city"></i> Város <span class="req">*</span></div>
                <select name="varos_id" id="varos-select-ugyfel-edit" class="f-select varos-select">
                    <option value="">— Keressen irányítószámra vagy városra —</option>
                    @foreach ($varosok as $varos)
                        <option value="{{ $varos->id }}"
                                {{ $varos->id == $ugyfel->varos_id ? 'selected' : '' }}>
                            {{ $varos->Irny_szam }} {{ $varos->nev }}
                        </option>
                    @endforeach
                </select>
                <div class="varos-ujvaros-panel" data-for="varos-select-ugyfel-edit" style="display:none;">
                    <div class="varos-ujvaros-row">
                        <input type="text" class="varos-uj-irsz f-input" placeholder="Irányítószám (pl. 6000)" maxlength="4" style="width:130px;">
                        <input type="text" class="varos-uj-nev f-input" placeholder="Város neve (pl. Kecskemét)" style="flex:1;">
                        <button type="button" class="varos-uj-mentes btn btn-sm btn-primary">Mentés</button>
                        <button type="button" class="varos-uj-megsem btn btn-sm btn-secondary">Mégse</button>
                    </div>
                    <div class="varos-uj-hiba" style="display:none; color:#c0392b; font-size:.85em; margin-top:4px;"></div>
                </div>
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-road"></i> Számlázási cím <span class="req">*</span></div>
                <input type="text" name="szamcim" class="f-input" value="{{ old('szamcim', $ugyfel->szamlazasi_cim) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-receipt"></i> Adószám</div>
                <input type="text" name="adoszam" class="f-input" value="{{ old('adoszam', $ugyfel->adoszam) }}">
            </div>
        </div>
    </div>

</div>

<div class="fc-submit">
    <button type="submit" class="btn-save" style="margin-top:0;">
        <i class="fas fa-save"></i> Mentés
    </button>
    <a href="{{ route('ugyfel.show', ['id' => $ugyfel->id]) }}" class="btn-back">
        <i class="fas fa-times"></i> Mégsem
    </a>
</div>

</form>
@endsection
