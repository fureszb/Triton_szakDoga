@extends('ujlayout')

@section('content')
@include('breadcrumbs')

@if ($anyag)

<div class="sc-page-header">
    <div class="sc-page-header-left">
        <h1><i class="fas fa-boxes"></i> {{ $anyag->Nev }}</h1>
        <span class="sc-id-badge">#{{ $anyag->Anyag_ID }}</span>
    </div>
    <div class="sc-page-header-actions">
        <a href="{{ route('anyagok.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
        <a href="{{ route('anyagok.edit', $anyag->Anyag_ID) }}" class="btn-edit">
            <i class="fas fa-edit"></i> Szerkesztés
        </a>
    </div>
</div>

<div class="sc-grid">
    <div class="sc-card" style="max-width:480px;">
        <div class="sc-card-header">
            <div class="sc-hicon"><i class="fas fa-box"></i></div>
            <div class="sc-htitle">Anyag adatai</div>
        </div>
        <div class="sc-rows">
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-tag"></i> Megnevezés</div>
                <div class="sc-val">{{ $anyag->Nev }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-ruler"></i> Mértékegység</div>
                <div class="sc-val">{{ $anyag->Mertekegyseg }}</div>
            </div>
        </div>
    </div>
</div>

@else
    <div class="empty-state">
        <i class="fas fa-boxes"></i>
        <p>Az anyag nem található.</p>
    </div>
@endif
@endsection
