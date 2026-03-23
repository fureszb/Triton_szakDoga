@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="page-header">
    <h1><i class="fas fa-user-plus"></i> Új ügyfél</h1>
    <a href="{{ route('ugyfel.index') }}" class="btn-back">
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

<form action="{{ route('ugyfel.store') }}" method="POST">
@csrf

<div class="fc-grid">

    {{-- Személyes adatok --}}
    <div class="fc-card">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-user"></i></div>
            <div class="fc-htitle">Személyes adatok</div>
        </div>
        <div class="fc-body">

            <div class="f-group">
                <div class="f-label"><i class="fas fa-user"></i> Teljes név <span class="req">*</span></div>
                <input type="text" name="nev" class="f-input" value="{{ old('nev') }}" placeholder="Kovács János">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-envelope"></i> Email cím <span class="req">*</span></div>
                <input type="email" name="email" class="f-input" value="{{ old('email') }}" placeholder="pelda@email.hu">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-phone"></i> Telefonszám <span class="req">*</span></div>
                <input type="text" name="telefon" class="f-input" value="{{ old('telefon') }}" placeholder="+36301234567">
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
                <input type="text" name="szamnev" class="f-input" value="{{ old('szamnev') }}" placeholder="Kovács János / Kovács Kft.">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-city"></i> Város <span class="req">*</span></div>
                <select name="varos_id" id="varos-select-ugyfel-create" class="f-select varos-select">
                    <option value="">— Keressen irányítószámra vagy városra —</option>
                    @foreach ($varosok as $varos)
                        <option value="{{ $varos->id }}" {{ old('varos_id') == $varos->id ? 'selected' : '' }}>
                            {{ $varos->Irny_szam }} {{ $varos->nev }}
                        </option>
                    @endforeach
                </select>
                <div class="varos-ujvaros-panel" data-for="varos-select-ugyfel-create" style="display:none;">
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
                <div class="f-label"><i class="fas fa-road"></i> Utca, házszám <span class="req">*</span></div>
                <input type="text" name="szamcim" class="f-input" value="{{ old('szamcim') }}" placeholder="Kossuth utca 12.">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-receipt"></i> Adószám</div>
                <input type="text" name="adoszam" class="f-input" value="{{ old('adoszam') }}" placeholder="12345678-1-42">
                <div class="f-hint">Opcionális — magánszemélyeknél elhagyható.</div>
            </div>
        </div>
    </div>

</div>

<div class="fc-submit">
    <button type="submit" class="btn-save" style="margin-top:0;">
        <i class="fas fa-save"></i> Mentés új ügyfélként
    </button>
    <a href="{{ route('ugyfel.index') }}" class="btn-back">
        <i class="fas fa-times"></i> Mégsem
    </a>
</div>

</form>
@endsection
