@extends('ujLayout')
@error('error')
    <div class="alert alert-warning">{{ $message }}</div>
@enderror
@section('content')
    @include('breadcrumbs')
    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
    <h1>Megrendelések</h1>
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
        <a href="{{ route('megrendeles.create') }}" title="Új megrendelés hozzáadása">

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
        @foreach ($megrendelesek as $megrendeles)
            <li>
                {{ $megrendeles->Megrendeles_ID }} - {{ $megrendeles->Megrendeles_Nev }}
            </li>
            <a href="{{ route('megrendeles.show', ['id' => $megrendeles->Megrendeles_ID]) }}"
                class="button">Megjelenítés</a>
            <a href="{{ route('megrendeles.edit', ['megrendeles' => $megrendeles->Megrendeles_ID]) }}"
                class="button">Szerkesztés</a>
            <form action="{{ route('megrendeles.destroy', ['megrendeles' => $megrendeles->Megrendeles_ID]) }}"
                method="POST">
                @csrf
                @method('DELETE')
                <button class="torol" type="submit"
                    onclick="return confirm('Biztos törölni kívánja a megrendelést?')">Törlés</button>
            </form>
        @endforeach
    </ul>
@endsection
