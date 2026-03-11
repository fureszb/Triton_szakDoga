@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="detail-header">
        <h1><i class="fas fa-user-cog"></i> {{ $user->nev }}</h1>
        <div class="detail-header-actions">
            <a href="{{ route('users.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Vissza
            </a>
            <a href="{{ route('users.edit', $user->User_ID) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Szerkesztés
            </a>
        </div>
    </div>

    <div class="detail-section">
        <div class="detail-section-label">Felhasználói adatok</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Felhasználó ID</div>
                <div class="info-value">{{ $user->User_ID }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Név</div>
                <div class="info-value">{{ $user->nev }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $user->email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Szerepkör</div>
                <div class="info-value">
                    @if($user->role === 'Admin')
                        <span class="badge" style="background:#fee2e2;color:#991b1b;">Admin</span>
                    @elseif($user->role === 'Uzletkoto')
                        <span class="badge" style="background:#fef3c7;color:#92400e;">Üzletkötő</span>
                    @else
                        <span class="badge badge-done">Ügyfél</span>
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Hozzárendelt ügyfél</div>
                <div class="info-value">{{ $user->ugyfel->Nev ?? 'Nincs hozzárendelve' }}</div>
            </div>
        </div>
    </div>
@endsection
