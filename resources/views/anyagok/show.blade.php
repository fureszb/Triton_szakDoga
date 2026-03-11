@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    @if ($anyag)
        <div class="detail-header">
            <h1><i class="fas fa-boxes"></i> {{ $anyag->Nev }}</h1>
            <div class="detail-header-actions">
                <a href="{{ route('anyagok.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Vissza
                </a>
                <a href="{{ route('anyagok.edit', $anyag->Anyag_ID) }}" class="btn-edit">
                    <i class="fas fa-edit"></i> Szerkesztés
                </a>
            </div>
        </div>

        <div class="detail-section">
            <div class="detail-section-label">Anyag adatai</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Anyag ID</div>
                    <div class="info-value">{{ $anyag->Anyag_ID }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Anyag neve</div>
                    <div class="info-value">{{ $anyag->Nev }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Mértékegység</div>
                    <div class="info-value">{{ $anyag->Mertekegyseg }}</div>
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
