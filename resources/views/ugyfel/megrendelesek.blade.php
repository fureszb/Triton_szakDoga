@extends('layout')

@section('content')
<script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
<h1 class="title"> {{$ugyfel->Nev}} - Megrendelései
    <a href="{{ route('megrendeles.index', ['sort_by' => 'MegrendelesID', 'sort_dir' => 'asc']) }}" title="Növekvő sorrend">
        <div class="sort">
            <img style="transform: rotate(180deg);" src="{{ asset('sort.png') }}" alt="">
        </div>
    </a>
    <a href="{{ route('megrendeles.index', ['sort_by' => 'MegrendelesID', 'sort_dir' => 'desc']) }}" title="Csökkenő sorrend">
        <div class="sort">
            <img src="{{ asset('sort.png') }}" alt="">
        </div>
    </a>
</h1>
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<ul>
    @foreach ($megrendelesek as $m)
    <li>
        {{ $m->Megrendeles_ID }} - {{ $m->Megrendeles_Nev }}
    </li>
    <a href="{{ route('megrendeles.show', ['id' => $m->Megrendeles_ID]) }}" class="button">Megjelenítés</a>
    <div></div> <div></div>
    @endforeach
</ul>
@endsection
