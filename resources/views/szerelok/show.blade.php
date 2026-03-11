@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    @if ($szerelo)
        <div class="detail-header">
            <h1><i class="fas fa-tools"></i> {{ $szerelo->Nev }}</h1>
            <div class="detail-header-actions">
                <a href="{{ route('szerelok.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Vissza
                </a>
                <a href="{{ route('szerelok.edit', $szerelo->Szerelo_ID) }}" class="btn-edit">
                    <i class="fas fa-edit"></i> Szerkesztés
                </a>
            </div>
        </div>

        <div class="detail-section">
            <div class="detail-section-label">Általános információk</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Szerelő ID</div>
                    <div class="info-value">{{ $szerelo->Szerelo_ID }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Név</div>
                    <div class="info-value">{{ $szerelo->Nev }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Telefonszám</div>
                    <div class="info-value">{{ $szerelo->Telefonszam }}</div>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <div class="detail-section-label">Szakterületek</div>
            @if($szerelo->szolgaltatasok && count($szerelo->szolgaltatasok) > 0)
                <div class="info-grid">
                    @foreach ($szerelo->szolgaltatasok as $szolgaltatas)
                        <div class="info-item">
                            <div class="info-label">Szolgáltatás</div>
                            <div class="info-value">
                                <i class="fas fa-wifi" style="color:#ed1b24;margin-right:6px;"></i>
                                {{ $szolgaltatas->Tipus }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#9ca3af;font-size:13px;">Nincs hozzárendelt szakterület.</p>
            @endif
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-tools"></i>
            <p>A szerelő nem található.</p>
        </div>
    @endif
@endsection
