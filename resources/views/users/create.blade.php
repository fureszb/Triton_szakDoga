@extends('layout')

@section('content')
@include('breadcrumbs')

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ugyfelSelect = document.getElementById('Ugyfel_ID');
            const nevInput = document.getElementById('nev');
            const emailInput = document.getElementById('email');

            function toggleRequired(isRequired) {
                if (isRequired) {
                    nevInput.setAttribute('required');
                    emailInput.setAttribute('required');
                } else {
                    nevInput.removeAttribute('required');
                    emailInput.removeAttribute('required');
                }
            }

            ugyfelSelect.addEventListener('change', function() {
                const selected = this.value;

                if (selected) {
                    nevInput.parentElement.style.display = 'none';
                    emailInput.parentElement.style.display = 'none';
                    toggleRequired(false);
                } else {
                    nevInput.parentElement.style.display = 'block';
                    emailInput.parentElement.style.display = 'block';
                    toggleRequired(true);
                }
            });
        });
    </script>


@endsection
