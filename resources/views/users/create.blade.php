@extends('layout')

@section('content')

    <h1>Új Felhasználó</h1>
    @if ($errors->any())
        <div class="alert alert-warning">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <fieldset>
            <label for="nev">Név</label>
            <input type="text" name="nev" id="nev" value="{{ old('nev') }}" required>
        </fieldset>

        <fieldset>
            <label for="email">Email</label>
            <input type="email" name="Email" id="email" value="{{ old('Email') }}" required>
        </fieldset>

        <fieldset>
            <label for="password">Jelszó</label>
            <input type="password" name="Password" id="password" required>
        </fieldset>

        <fieldset>
            <label for="role">Szerepkör</label>
            <select name="Role" id="role" required>
                <option value="">Válassz szerepkört</option>
                <option value="Admin">Admin</option>
                <option value="Ugyfel">Ügyfél</option>
                <option value="Uzletkoto">Üzletkötő</option>
            </select>
        </fieldset>

        <fieldset>
            <label for="Ugyfel_ID">Ügyfél (opcionális)</label>
            <select name="Ugyfel_ID" id="Ugyfel_ID">
                <option value="">Válassz ügyfelet (opcionális)</option>
                @foreach ($ugyfelek as $id => $nev)
                    <option value="{{ $id }}">{{ $id }} - {{ $nev }}</option>
                @endforeach
            </select>
        </fieldset>

        <button type="submit" class="btn btn-primary">Mentés</button>
    </form>

@endsection
