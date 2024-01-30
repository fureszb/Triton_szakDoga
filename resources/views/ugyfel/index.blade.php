@extends('layout')

@section('content')
<script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
<h1 class="title">Ügyfelek
    <a href="{{ route('ugyfel.index', ['sort_by' => 'UgyfelID', 'sort_dir' => 'asc']) }}" title="Növekvő sorrend">
        <div class="sort">
            <img style="transform: rotate(180deg);" src="{{ asset('sort.png') }}" alt="">
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
    @foreach ($ugyfelek as $u)
    <li>
        {{ $u->Ugyfel_ID }} - {{ $u->Nev }}
    </li>
    <a href="{{ route('ugyfel.show', ['id' => $u->Ugyfel_ID]) }}" class="button">Megjelenítés</a>
    <a href="{{ route('ugyfel.edit', ['ugyfel' => $u->Ugyfel_ID]) }}" class="button">Szerkesztés</a>
    <form action="{{ route('ugyfel.destroy', ['ugyfel' => $u->Ugyfel_ID]) }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="torol" type="submit" onclick="return confirm('Biztos törölni kívánja az ügyfélt?')">Törlés</button>
    </form>
    @endforeach
</ul>




@endsection