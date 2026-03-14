@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="page-header">
    <h1><i class="fas fa-box-open"></i> Új anyag hozzáadása</h1>
    <a href="{{ route('anyagok.index') }}" class="btn-back">
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

<form action="{{ route('anyagok.store') }}" method="POST">
@csrf

<div class="fc-grid">
    <div class="fc-card fc-full" style="max-width:640px;">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-box"></i></div>
            <div class="fc-htitle">Anyag adatai</div>
        </div>
        <div class="fc-body">
            <div class="fc-row">
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-tag"></i> Anyag neve <span class="req">*</span></div>
                    <input type="text" name="Nev" class="f-input" value="{{ old('Nev') }}" placeholder="pl. Kábel 5m" required>
                </div>
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-ruler"></i> Mértékegység <span class="req">*</span></div>
                    <input type="text" name="Mertekegyseg" class="f-input" value="{{ old('Mertekegyseg') }}" placeholder="pl. db, m, kg" required>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fc-submit">
    <button type="submit" class="btn-save" style="margin-top:0;">
        <i class="fas fa-save"></i> Mentés
    </button>
    <a href="{{ route('anyagok.index') }}" class="btn-back">
        <i class="fas fa-times"></i> Mégsem
    </a>
</div>

</form>
@endsection
