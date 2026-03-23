@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="sc-page-header">
    <div class="sc-page-header-left">
        <h1><i class="fas fa-user-friends"></i> {{ $ugyfel->nev }}</h1>
        <span class="sc-id-badge">#{{ $ugyfel->id }}</span>
    </div>
    <div class="sc-page-header-actions">
        <a href="{{ route('ugyfel.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
        <a href="{{ route('ugyfel.edit', ['ugyfel' => $ugyfel->id]) }}" class="btn-edit">
            <i class="fas fa-edit"></i> Szerkesztés
        </a>
    </div>
</div>

<div class="sc-grid">

    {{-- Személyes adatok --}}
    <div class="sc-card">
        <div class="sc-card-header">
            <div class="sc-hicon"><i class="fas fa-user"></i></div>
            <div class="sc-htitle">Személyes adatok</div>
        </div>
        <div class="sc-rows">
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-user"></i> Név</div>
                <div class="sc-val">{{ $ugyfel->nev }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-envelope"></i> Email</div>
                <div class="sc-val">{{ $ugyfel->email ?? '—' }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-phone"></i> Telefon</div>
                <div class="sc-val">{{ $ugyfel->telefonszam ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- Számlázási adatok --}}
    <div class="sc-card">
        <div class="sc-card-header">
            <div class="sc-hicon"><i class="fas fa-file-invoice"></i></div>
            <div class="sc-htitle">Számlázási adatok</div>
        </div>
        <div class="sc-rows">
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-building"></i> Száml. név</div>
                <div class="sc-val">{{ $ugyfel->szamlazasi_nev ?? '—' }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-map-marker-alt"></i> Cím</div>
                <div class="sc-val">
                    {{ $varos->Irny_szam ?? '' }} {{ $varos->nev ?? '' }},
                    {{ $ugyfel->szamlazasi_cim }}
                </div>
            </div>
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-receipt"></i> Adószám</div>
                <div class="sc-val">{{ $ugyfel->adoszam ?? '—' }}</div>
            </div>
        </div>
    </div>

</div>
@endsection
