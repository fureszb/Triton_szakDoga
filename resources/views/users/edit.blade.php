@extends('layout')

@section('content')
    <div class="container">
        <h1>Felhasználó Szerkesztése</h1>
        @if ($errors->any())
            <div>
                @foreach ($errors->all() as $error)
                    <div class="alert alert-warning">{{ $error }}</div>
                @endforeach
            </div>
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
                <select name="role" id="role" class="form-control">
                    <option value="">Válassz szerepkört</option>
                    <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Ugyfel" {{ old('role', $user->role) == 'Ugyfel' ? 'selected' : '' }}>Ügyfél</option>
                    <option value="Uzletkoto" {{ old('role', $user->role) == 'Uzletkoto' ? 'selected' : '' }}>Üzletkötő
                    </option>
                </select>
            </fieldset>

            <fieldset>
                <label for="Ugyfel_ID">Ügyfél (opcionális)</label>
                <select name="Ugyfel_ID" id="Ugyfel_ID" class="form-control">
                    <option value="">Válassz ügyfelet (opcionális)</option>
                    @foreach ($ugyfelek as $ugyfel)
                        <option value="{{ $ugyfel->Ugyfel_ID }}" {{ (old('Ugyfel_ID', optional($user->ugyfel)->Ugyfel_ID) == $ugyfel->Ugyfel_ID) ? 'selected' : '' }}>
                            {{ $ugyfel->Ugyfel_ID }} - {{ $ugyfel->Nev }}
                        </option>
                    @endforeach
                </select>
            </fieldset>



            <button type="submit" class="btn btn-primary">Frissítés</button>
        </form>
    </div>
@endsection
