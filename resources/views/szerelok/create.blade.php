<meta name="csrf-token" content="{{ csrf_token() }}">
@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="page-header">
    <h1><i class="fas fa-hard-hat"></i> Új szerelő hozzáadása</h1>
    <a href="{{ route('szerelok.index') }}" class="btn-back">
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

<form action="{{ route('szerelok.store') }}" method="POST">
@csrf

<div class="fc-grid">
    <div class="fc-card fc-full">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-hard-hat"></i></div>
            <div class="fc-htitle">Szerelő adatai</div>
        </div>
        <div class="fc-body">
            <div class="fc-row">
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-user"></i> Teljes név <span class="req">*</span></div>
                    <input type="text" name="Nev" class="f-input" value="{{ old('Nev') }}" placeholder="Kovács János" required>
                </div>
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-phone"></i> Telefonszám <span class="req">*</span></div>
                    <input type="text" name="Telefonszam" class="f-input" value="{{ old('Telefonszam') }}" placeholder="+36301234567" required>
                </div>
            </div>
        </div>
    </div>

    <div class="fc-card fc-full">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-pen-nib"></i></div>
            <div class="fc-htitle">Aláírás</div>
        </div>
        <div class="fc-body">
            @include('signaturePad')
        </div>
    </div>
</div>

<div class="fc-submit">
    <button id="saveSignatureButton" data-action="save-png2" type="submit" class="btn-save" style="margin-top:0;">
        <i class="fas fa-save"></i> Mentés
    </button>
    <a href="{{ route('szerelok.index') }}" class="btn-back">
        <i class="fas fa-times"></i> Mégsem
    </a>
</div>

</form>
@endsection
