@extends('layout')

@section('content')
    @include('breadcrumbs')
    <div class="container">
        <h1>{{ $user->Name }} Felhasználó részletei</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Általános információk</h3>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Név:</strong> {{ $user->nev }}</li>
                    <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                    <li class="list-group-item"><strong>Szerepkör:</strong> {{ $user->role }}</li>
                    <li class="list-group-item"><strong>Ügyfél:</strong>
                        {{ $user->ugyfel->Nev ?? 'Nincs hozzárendelve ügyfél' }}</li>
                </ul>
            </div>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-primary mt-3">Vissza a listához</a>
    </div>
@endsection
