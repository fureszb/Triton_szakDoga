@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="page-header">
    <h1><i class="fas fa-tools"></i> Szerelő szerkesztése</h1>
    <a href="{{ route('szerelok.show', $szerelo->id) }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Vissza
    </a>
</div>

{{-- ID sáv --}}
<div style="display:flex;align-items:center;gap:10px;padding:10px 16px;background:rgba(201,169,122,0.06);border:1px solid rgba(201,169,122,0.2);border-radius:10px;font-size:12px;color:#64748b;margin-bottom:20px;">
    <i class="fas fa-hashtag" style="color:#c9a97a;"></i>
    Szerelő azonosítója: <strong style="color:#a07848;">#{{ $szerelo->id }}</strong>
    &nbsp;|&nbsp;
    <i class="fas fa-hard-hat" style="color:#c9a97a;"></i>
    <strong style="color:#a07848;">{{ $szerelo->nev }}</strong>
</div>

<form action="{{ route('szerelok.update', $szerelo->id) }}" method="POST">
@csrf
@method('PUT')

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
                    <input type="text" name="nev" class="f-input" value="{{ old('nev', $szerelo->nev) }}" required>
                </div>
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-phone"></i> Telefonszám <span class="req">*</span></div>
                    <input type="text" name="telefonszam" class="f-input" value="{{ old('telefonszam', $szerelo->telefonszam) }}" required>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fc-submit">
    <button type="submit" class="btn-save" style="margin-top:0;">
        <i class="fas fa-save"></i> Mentés
    </button>
    <a href="{{ route('szerelok.show', $szerelo->id) }}" class="btn-back">
        <i class="fas fa-times"></i> Mégsem
    </a>
</div>

</form>
@endsection
