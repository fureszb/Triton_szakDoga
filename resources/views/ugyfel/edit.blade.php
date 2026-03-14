@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="page-header">
    <h1><i class="fas fa-user-edit"></i> Ügyfél szerkesztése</h1>
    <a href="{{ route('ugyfel.show', ['id' => $ugyfel->Ugyfel_ID]) }}" class="btn-back">
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
    Ügyfél azonosítója: <strong style="color:#a07848;">{{ $ugyfel->Ugyfel_ID }}</strong>
    &nbsp;|&nbsp;
    <i class="fas fa-user" style="color:#c9a97a;"></i>
    <strong style="color:#a07848;">{{ $ugyfel->Nev }}</strong>
</div>

<form action="{{ route('ugyfel.update', $ugyfel->Ugyfel_ID) }}" method="POST">
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
                <input type="text" name="Ugyfel_ID" class="f-input" value="{{ old('Ugyfel_ID', $ugyfel->Ugyfel_ID) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-user"></i> Teljes név <span class="req">*</span></div>
                <input type="text" name="nev" class="f-input" value="{{ old('nev', $ugyfel->Nev) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-envelope"></i> Email cím <span class="req">*</span></div>
                <input type="email" name="email" class="f-input" value="{{ old('email', $ugyfel->Email) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-phone"></i> Telefonszám <span class="req">*</span></div>
                <input type="text" name="telefon" class="f-input" value="{{ old('telefon', $ugyfel->Telefonszam) }}">
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
                <input type="text" name="szamnev" class="f-input" value="{{ old('szamnev', $ugyfel->Szamlazasi_Nev) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-city"></i> Város <span class="req">*</span></div>
                <select name="Varos_ID" class="f-select">
                    <option value="">— Válassz várost —</option>
                    @foreach ($varosok as $varos)
                        <option value="{{ $varos->Varos_ID }}"
                                {{ $varos->Varos_ID == $ugyfel->Varos_ID ? 'selected' : '' }}>
                            {{ $varos->Irny_szam }} {{ $varos->Nev }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-road"></i> Számlázási cím <span class="req">*</span></div>
                <input type="text" name="szamcim" class="f-input" value="{{ old('szamcim', $ugyfel->Szamlazasi_Cim) }}">
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-receipt"></i> Adószám</div>
                <input type="text" name="adoszam" class="f-input" value="{{ old('adoszam', $ugyfel->Adoszam) }}">
            </div>
        </div>
    </div>

</div>

<div class="fc-submit">
    <button type="submit" class="btn-save" style="margin-top:0;">
        <i class="fas fa-save"></i> Mentés
    </button>
    <a href="{{ route('ugyfel.show', ['id' => $ugyfel->Ugyfel_ID]) }}" class="btn-back">
        <i class="fas fa-times"></i> Mégsem
    </a>
</div>

</form>
@endsection
