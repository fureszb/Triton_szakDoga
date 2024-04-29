@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
    <h1>Felhasználók</h1>
    <hr class="showHr">
    <div class="title">

        <a href="{{ route('ugyfel.index', ['sort_by' => 'UgyfelID', 'sort_dir' => 'asc']) }}" title="Növekvő sorrend">
            <div class="sort">
                <i class="fas fa-sort-alpha-up" src="{{ asset('sort.png') }}"></i>
            </div>
        </a>
        <a href="{{ route('ugyfel.index', ['sort_by' => 'UgyfelID', 'sort_dir' => 'desc']) }}" title="Csökkenő sorrend">
            <div class="sort">
                <i class="fas fa-sort-alpha-down-alt" src="{{ asset('sort.png') }}"></i>
            </div>
        </a>
        <a href="{{ route('users.create') }}" title="Új felhasználó hozzáadása">

            <i class="fas fa-plus-square"></i>

        </a>
        <form class="kereses" action="{{ route('ugyfel.index') }}" method="GET">
            <input type="text" name="search" placeholder="Keresés név vagy azonosító alapján">
            <button type="submit">Keresés</button>
        </form>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul>
        @foreach ($users as $user)
            <li>
                {{ $user->nev }} - {{ $user->role}}

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
