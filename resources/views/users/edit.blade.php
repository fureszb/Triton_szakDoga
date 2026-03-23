@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="page-header">
    <h1><i class="fas fa-user-edit"></i> Felhasználó szerkesztése</h1>
    <a href="{{ route('users.show', $user->id) }}" class="btn-back">
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

<div style="display:flex;align-items:center;gap:10px;padding:10px 16px;background:rgba(201,169,122,0.06);border:1px solid rgba(201,169,122,0.2);border-radius:10px;font-size:12px;color:#64748b;margin-bottom:20px;">
    <i class="fas fa-hashtag" style="color:#c9a97a;"></i>
    Felhasználó ID: <strong style="color:#a07848;">#{{ $user->id }}</strong>
    &nbsp;|&nbsp;
    <i class="fas fa-user" style="color:#c9a97a;"></i>
    <strong style="color:#a07848;">{{ $user->nev }}</strong>
</div>

<form action="{{ route('users.update', $user->id) }}" method="POST">
@csrf
@method('PUT')

<div class="fc-grid">

    <div class="fc-card">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-user-cog"></i></div>
            <div class="fc-htitle">Bejelentkezési adatok</div>
        </div>
        <div class="fc-body">
            <div class="f-group">
                <div class="f-label"><i class="fas fa-user"></i> Név <span class="req">*</span></div>
                <input type="text" name="nev" class="f-input" value="{{ old('nev', $user->nev) }}" required>
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-envelope"></i> Email <span class="req">*</span></div>
                <input type="email" name="email" class="f-input" value="{{ old('email', $user->email) }}" required>
            </div>
        </div>
    </div>

    <div class="fc-card">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-shield-alt"></i></div>
            <div class="fc-htitle">Szerepkör és ügyfél</div>
        </div>
        <div class="fc-body">
            <div class="f-group">
                <div class="f-label"><i class="fas fa-shield-alt"></i> Szerepkör</div>
                <select name="role" class="f-select">
                    <option value="">— Válassz —</option>
                    <option value="Admin"     {{ old('role', $user->role) == 'Admin'     ? 'selected' : '' }}>Admin</option>
                    <option value="Ugyfel"    {{ old('role', $user->role) == 'Ugyfel'    ? 'selected' : '' }}>Ügyfél</option>
                    <option value="Uzletkoto" {{ old('role', $user->role) == 'Uzletkoto' ? 'selected' : '' }}>Üzletkötő</option>
                </select>
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-user-tie"></i> Hozzárendelt ügyfél</div>
                <select name="ugyfel_id" class="f-select">
                    <option value="">— Nincs hozzárendelve —</option>
                    @foreach ($ugyfelek as $ugyfel)
                        <option value="{{ $ugyfel->id }}"
                            {{ old('ugyfel_id', optional($user->ugyfel)->id) == $ugyfel->id ? 'selected' : '' }}>
                            {{ $ugyfel->id }} – {{ $ugyfel->nev }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

</div>

<div class="fc-submit">
    <button type="submit" class="btn-save" style="margin-top:0;">
        <i class="fas fa-save"></i> Mentés
    </button>
    <a href="{{ route('users.show', $user->id) }}" class="btn-back">
        <i class="fas fa-times"></i> Mégsem
    </a>
</div>

</form>
@endsection
