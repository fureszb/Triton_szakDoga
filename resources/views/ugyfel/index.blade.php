@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
    <div class="showDiv">
        <h1>Ügyfelek</h1>
        <hr class="showHr">
    </div>
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
        <a href="{{ route('ugyfel.create') }}" title="Új ügyfél hozzáadása">

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


    @foreach ($ugyfel as $u)
        <ul>
            <li>
                {{ $u->Ugyfel_ID }} - {{ $u->Nev }}
            </li>
            <a href="{{ route('ugyfel.show', ['id' => $u->Ugyfel_ID]) }}" class="button">Megjelenítés</a>
            <a href="{{ route('ugyfel.edit', ['ugyfel' => $u->Ugyfel_ID]) }}" class="button">Szerkesztés</a>
            <form action="{{ route('ugyfel.destroy', ['ugyfel' => $u->Ugyfel_ID]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="torol" type="submit"
                    onclick="return confirm('Biztos törölni kívánja az ügyfélt?')">Törlés</button>
            </form>
        </ul>
    @endforeach
@endsection
