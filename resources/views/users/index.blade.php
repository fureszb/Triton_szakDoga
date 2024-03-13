@extends('layout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
    <h1 class="title">Felhasználók
        <a href="{{ route('users.index', ['sort_by' => 'User_ID', 'sort_dir' => 'asc']) }}" title="Növekvő sorrend">
            <div class="sort">
                <img style="transform: rotate(180deg);" src="{{ asset('sort.png') }}" alt="Növekvő">
            </div>
        </a>
        <a href="{{ route('users.index', ['sort_by' => 'User_ID', 'sort_dir' => 'desc']) }}" title="Csökkenő sorrend">
            <div class="sort">
                <img src="{{ asset('sort.png') }}" alt="Csökkenő">
            </div>
        </a>
        <a href="{{ route('users.create') }}" title="Új felhasználó hozzáadása">
            <div class="hozzaad">+</div>
        </a>
        <form class="kereses" action="{{ route('users.index') }}" method="GET">
            <input type="text" name="search" placeholder="Keresés név vagy email alapján">
            <button type="submit">Keresés</button>
        </form>
    </h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul>
        @foreach ($users as $user)
            <li>
                {{ $user->nev }} - {{ $user->ugyfel->Nev ?? 'Nincs' }}

            </li>
            <a href="{{ route('users.show', ['user' => $user->User_ID]) }}" class="button">Megtekintés</a>
            <a href="{{ route('users.edit', ['user' => $user->User_ID]) }}" class="button">Szerkesztés</a>
            <form action="{{ route('users.destroy', ['user' => $user->User_ID]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="torol" type="submit"
                    onclick="return confirm('Biztos törölni kívánja a felhasználót?')">Törlés</button>
            </form>
        @endforeach
    </ul>
@endsection
