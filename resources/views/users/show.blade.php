@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="sc-page-header">
    <div class="sc-page-header-left">
        <h1><i class="fas fa-user-cog"></i> {{ $user->nev }}</h1>
        <span class="sc-id-badge">#{{ $user->User_ID }}</span>
    </div>
    <div class="sc-page-header-actions">
        <a href="{{ route('users.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
        <a href="{{ route('users.edit', $user->User_ID) }}" class="btn-edit">
            <i class="fas fa-edit"></i> Szerkesztés
        </a>
    </div>
</div>

<div class="sc-grid">

    <div class="sc-card">
        <div class="sc-card-header">
            <div class="sc-hicon"><i class="fas fa-user"></i></div>
            <div class="sc-htitle">Felhasználói adatok</div>
        </div>
        <div class="sc-rows">
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-user"></i> Név</div>
                <div class="sc-val">{{ $user->nev }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-envelope"></i> Email</div>
                <div class="sc-val">{{ $user->email }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-shield-alt"></i> Szerepkör</div>
                <div class="sc-val">
                    @if($user->role === 'Admin')
                        <span class="role-admin"><i class="fas fa-crown"></i> Admin</span>
                    @elseif($user->role === 'Uzletkoto')
                        <span class="role-uzlet"><i class="fas fa-briefcase"></i> Üzletkötő</span>
                    @else
                        <span class="role-ugyfel"><i class="fas fa-user"></i> Ügyfél</span>
                    @endif
                </div>
            </div>
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-link"></i> Ügyfél</div>
                <div class="sc-val">{{ $user->ugyfel->Nev ?? 'Nincs hozzárendelve' }}</div>
            </div>
        </div>
    </div>

</div>
@endsection
