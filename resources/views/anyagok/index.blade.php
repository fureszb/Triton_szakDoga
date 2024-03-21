@extends('ujlayout')

@section('content')

@include('breadcrumbs')
<h1>Anyagok Listája</h1>
<hr class="showHr">
<div class="title">
    <a href="{{ route('anyagok.index', ['sort_by' => 'Anyag_ID', 'sort_dir' => 'asc']) }}" title="Növekvő sorrend">
        <div class="sort">
            <i class="fas fa-sort-alpha-up"></i>
        </div>
    </a>
    <a href="{{ route('anyagok.index', ['sort_by' => 'Anyag_ID', 'sort_dir' => 'desc']) }}" title="Csökkenő sorrend">
        <div class="sort">
            <i class="fas fa-sort-alpha-down-alt"></i>
        </div>
    </a>
    <a href="{{ route('anyagok.create') }}" title="Új anyag hozzáadása">
        <i class="fas fa-plus-square"></i>
    </a>
    <form class="kereses" action="{{ route('anyagok.index') }}" method="GET">
        <input type="text" name="search" placeholder="Keresés név vagy azonosító alapján">
        <button type="submit">Keresés</button>
    </form>
</div>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@foreach ($anyagok as $anyag)
    <ul>
        <li>
            {{ $anyag->Anyag_ID }} - {{ $anyag->Nev }}
        </li>
        <a href="{{ route('anyagok.show', $anyag->Anyag_ID) }}" class="button">Megtekint</a>
        <a href="{{ route('anyagok.edit', $anyag->Anyag_ID) }}" class="button">Szerkeszt</a>
        <form action="{{ route('anyagok.destroy', $anyag->Anyag_ID) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="torol" type="submit" onclick="return confirm('Biztosan törölni szeretné?')">Töröl</button>
        </form>
    </ul>
@endforeach

@endsection
