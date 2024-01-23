@extends('layout')

@section('content')
    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
    <h1 class="title">Ügyfelek
        <a href="{{ route('ugyfel.index', ['sort_by' => 'UgyfelID', 'sort_dir' => 'asc']) }}" title="Növekvő sorrend">
            <div class="sort">
                <img  style="transform: rotate(180deg);" src="{{ asset('sort.png') }}" alt="">
            </div>
        </a>
        <a href="{{ route('ugyfel.index', ['sort_by' => 'UgyfelID', 'sort_dir' => 'desc']) }}" title="Csökkenő sorrend">
            <div class="sort">
                <img src="{{ asset('sort.png') }}" alt="">
            </div>
        </a>
        <form class="kereses" action="{{ route('ugyfel.index') }}" method="GET">
            <input type="text" name="search" placeholder="Keresés név vagy azonosító alapján">
            <button type="submit">Keresés</button>
        </form>
    </h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul>
        @foreach ($ugyfel as $u)
            <li>{{ $u->UgyfelID }} - {{ $u->Nev }}</li>
            <a href="{{ route('ugyfel.show', $u->UgyfelID) }}" class="button">Megjelenítés</a>
            <a href="{{ route('ugyfel.edit', $u->UgyfelID) }}" class="button">Szerkesztés</a>
            <form action="{{ route('ugyfel.destroy', $u->UgyfelID) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="torol" type="submit" onclick="return confirm('Biztos törölni kívánja az ügyfélt?')">Törlés</button>
            </form>
        @endforeach
    </ul>

    <div id="paginator">
        {{ $ugyfel->appends(['sort_by' => request('sort_by'), 'sort_dir' => request('sort_dir')])->links('custom_pagination') }}
    </div>


@endsection
