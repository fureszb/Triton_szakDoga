@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> Felhasználó szerkesztése</h1>
        <a href="{{ route('users.show', $user->User_ID) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
    </div>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-warning">{{ $error }}</div>
        @endforeach
    @endif

    <form action="{{ route('users.update', $user->User_ID) }}" method="POST">
        @csrf
        @method('PUT')

        <fieldset>
            <label for="nev">Név</label>
            <input type="text" name="nev" id="nev" value="{{ old('nev', $user->nev) }}" required>
        </fieldset>
        <fieldset>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
        </fieldset>
        <fieldset>
            <label for="role">Szerepkör</label>
            <select name="role" id="role">
                <option value="">Válassz szerepkört</option>
                <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="Ugyfel" {{ old('role', $user->role) == 'Ugyfel' ? 'selected' : '' }}>Ügyfél</option>
                <option value="Uzletkoto" {{ old('role', $user->role) == 'Uzletkoto' ? 'selected' : '' }}>Üzletkötő</option>
            </select>
        </fieldset>
        <fieldset>
            <label for="Ugyfel_ID">Ügyfél (opcionális)</label>
            <select name="Ugyfel_ID" id="Ugyfel_ID">
                <option value="">Válassz ügyfelet (opcionális)</option>
                @foreach ($ugyfelek as $ugyfel)
                    <option value="{{ $ugyfel->Ugyfel_ID }}"
                        {{ old('Ugyfel_ID', optional($user->ugyfel)->Ugyfel_ID) == $ugyfel->Ugyfel_ID ? 'selected' : '' }}>
                        {{ $ugyfel->Ugyfel_ID }} - {{ $ugyfel->Nev }}
                    </option>
                @endforeach
            </select>
        </fieldset>

        <div style="width:100%;">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Frissítés
            </button>
        </div>
    </form>
@endsection
