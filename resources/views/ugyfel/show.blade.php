@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="detail-header">
        <h1><i class="fas fa-user-friends"></i> {{ $ugyfel->Nev }}</h1>
        <div class="detail-header-actions">
            <a href="{{ route('ugyfel.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Vissza
            </a>
            <a href="{{ route('ugyfel.edit', ['ugyfel' => $ugyfel->Ugyfel_ID]) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Szerkesztés
            </a>
        </div>
    </div>

    <div class="detail-section">
        <div class="detail-section-label">Általános információk</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Ügyfél ID</div>
                <div class="info-value">{{ $ugyfel->Ugyfel_ID }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Név</div>
                <div class="info-value">{{ $ugyfel->Nev }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $ugyfel->Email ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Telefonszám</div>
                <div class="info-value">{{ $ugyfel->Telefonszam ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <div class="detail-section-label">Számlázási adatok</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Számlázási név</div>
                <div class="info-value">{{ $ugyfel->Szamlazasi_Nev ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Számlázási cím</div>
                <div class="info-value">
                    {{ $varos->Irny_szam }} {{ $varos->Nev }}, {{ $ugyfel->Szamlazasi_Cim }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Adószám</div>
                <div class="info-value">{{ $ugyfel->Adoszam ?? '-' }}</div>
            </div>
        </div>
    </div>
@endsection
