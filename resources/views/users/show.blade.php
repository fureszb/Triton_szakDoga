@extends('ujlayout')

@section('content')
    @include('breadcrumbs')
    <div class="container">
        <h1>{{ $user->nev }} - részletei</h1>
        <hr class="showHr">
        </hr>
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
        <a href="{{ route('users.edit', $user->User_ID) }}"><button>
            Szerkesztés</button></a>
    </div>
@endsection
