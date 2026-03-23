@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="page-header">
    <h1><i class="fas fa-edit"></i> Anyag szerkesztése</h1>
    <a href="{{ route('anyagok.show', $anyag->id) }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Vissza
    </a>
</div>

<div style="display:flex;align-items:center;gap:10px;padding:10px 16px;background:rgba(201,169,122,0.06);border:1px solid rgba(201,169,122,0.2);border-radius:10px;font-size:12px;color:#64748b;margin-bottom:20px;">
    <i class="fas fa-hashtag" style="color:#c9a97a;"></i>
    Anyag azonosítója: <strong style="color:#a07848;">#{{ $anyag->id }}</strong>
    &nbsp;|&nbsp;
    <i class="fas fa-box" style="color:#c9a97a;"></i>
    <strong style="color:#a07848;">{{ $anyag->nev }}</strong>
</div>

<form action="{{ route('anyagok.update', $anyag->id) }}" method="POST">
@csrf
@method('PUT')

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
                    <input type="text" name="nev" class="f-input" value="{{ old('nev', $anyag->nev) }}" required>
                </div>
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-ruler"></i> Mértékegység <span class="req">*</span></div>
                    <input type="text" name="mertekegyseg" class="f-input" value="{{ old('mertekegyseg', $anyag->mertekegyseg) }}" required>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fc-submit">
    <button type="submit" class="btn-save" style="margin-top:0;">
        <i class="fas fa-save"></i> Mentés
    </button>
    <a href="{{ route('anyagok.show', $anyag->id) }}" class="btn-back">
        <i class="fas fa-times"></i> Mégsem
    </a>
</div>

</form>
@endsection
