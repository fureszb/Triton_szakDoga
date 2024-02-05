@extends('layout')
@error('error')
<div class="alert alert-warning">{{ $message }}</div>
@enderror
@section('content')
<script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
<h1 class="title">Megrendelések
    <a href="{{ route('megrendeles.index', ['sort_by' => 'Megrendeles_ID', 'sort_dir' => 'asc']) }}" title="Növekvő sorrend">
        <div class="sort">
            <img style="transform: rotate(180deg);" src="{{ asset('sort.png') }}" alt="">
        </div>
    </a>
    <a href="{{ route('megrendeles.index', ['sort_by' => 'Megrendeles_ID', 'sort_dir' => 'desc']) }}" title="Csökkenő sorrend">
        <div class="sort">
            <img src="{{ asset('sort.png') }}" alt="">
        </div>
    </a>

    <a href="{{ route('megrendeles.create') }}" title="Új megrendelés hozzáadása">

        <div class="hozzaad">+</div>
    </a>

    <form class="kereses" action="{{ route('megrendeles.index') }}" method="GET">
        <input type="text" name="search" placeholder="Keresés név vagy azonosító alapján">
        <button type="submit">Keresés</button>
    </form>
</h1>
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<ul>
    @foreach ($megrendelesek as $megrendeles)
    <li>
        {{ $megrendeles->Megrendeles_ID }} - {{ $megrendeles->Megrendeles_Nev }}
    </li>
    <a href="{{ route('megrendeles.show', ['id' => $megrendeles->Megrendeles_ID]) }}" class="button">Megjelenítés</a>
    <a href="{{ route('megrendeles.edit', ['megrendeles' => $megrendeles->Megrendeles_ID]) }}" class="button">Szerkesztés</a>
    <form action="{{ route('megrendeles.destroy', ['megrendeles' => $megrendeles->Megrendeles_ID]) }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="torol" type="submit" onclick="return confirm('Biztos törölni kívánja a megrendelést?')">Törlés</button>
    </form>
    @endforeach
</ul>
@endsection
