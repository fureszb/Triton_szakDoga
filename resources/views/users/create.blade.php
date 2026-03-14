@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="page-header">
    <h1><i class="fas fa-user-plus"></i> Új felhasználó</h1>
    <a href="{{ route('users.index') }}" class="btn-back">
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

<form action="{{ route('users.store') }}" method="POST">
@csrf

<div class="fc-grid">

    <div class="fc-card">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-user-cog"></i></div>
            <div class="fc-htitle">Bejelentkezési adatok</div>
        </div>
        <div class="fc-body">
            <div class="f-group" id="nevWrap">
                <div class="f-label"><i class="fas fa-user"></i> Név <span class="req">*</span></div>
                <input type="text" name="nev" id="nev" class="f-input" value="{{ old('nev') }}" required>
            </div>
            <div class="f-group" id="emailWrap">
                <div class="f-label"><i class="fas fa-envelope"></i> Email <span class="req">*</span></div>
                <input type="email" name="Email" id="email" class="f-input" value="{{ old('Email') }}" required>
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-lock"></i> Jelszó <span class="req">*</span></div>
                <input type="password" name="Password" class="f-input" required>
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
                <div class="f-label"><i class="fas fa-shield-alt"></i> Szerepkör <span class="req">*</span></div>
                <select name="Role" id="role" class="f-select" required>
                    <option value="">— Válassz szerepkört —</option>
                    <option value="Admin" {{ old('Role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Ugyfel" {{ old('Role') == 'Ugyfel' ? 'selected' : '' }}>Ügyfél</option>
                    <option value="Uzletkoto" {{ old('Role') == 'Uzletkoto' ? 'selected' : '' }}>Üzletkötő</option>
                </select>
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-user-tie"></i> Hozzárendelt ügyfél</div>
                <select name="Ugyfel_ID" id="Ugyfel_ID" class="f-select">
                    <option value="">— Nincs hozzárendelve —</option>
                    @foreach ($ugyfelek as $id => $nev)
                        <option value="{{ $id }}" {{ old('Ugyfel_ID') == $id ? 'selected' : '' }}>
                            {{ $id }} – {{ $nev }}
                        </option>
                    @endforeach
                </select>
                <div class="f-hint">Csak Ügyfél szerepkörnél kötelező.</div>
            </div>
        </div>
    </div>

</div>

<div class="fc-submit">
    <button type="submit" class="btn-save" style="margin-top:0;">
        <i class="fas fa-save"></i> Mentés
    </button>
    <a href="{{ route('users.index') }}" class="btn-back">
        <i class="fas fa-times"></i> Mégsem
    </a>
</div>

</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ugyfelSelect = document.getElementById('Ugyfel_ID');
    const nevWrap   = document.getElementById('nevWrap');
    const emailWrap = document.getElementById('emailWrap');
    const nevInput  = document.getElementById('nev');
    const emailInput = document.getElementById('email');

    ugyfelSelect.addEventListener('change', function () {
        const selected = this.value;
        if (selected) {
            nevWrap.style.display   = 'none';
            emailWrap.style.display = 'none';
            nevInput.removeAttribute('required');
            emailInput.removeAttribute('required');
        } else {
            nevWrap.style.display   = 'flex';
            emailWrap.style.display = 'flex';
            nevInput.setAttribute('required', '');
            emailInput.setAttribute('required', '');
        }
    });
});
</script>
@endsection
