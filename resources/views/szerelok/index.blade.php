@extends('ujlayout')

@section('content')

@include('breadcrumbs')
<h1>Szerelők Listája</h1>
<hr class="showHr">
<div class="title">
    <a href="{{ route('szerelok.index', ['sort_by' => 'Szerelo_ID', 'sort_dir' => 'asc']) }}" title="Növekvő sorrend">
        <div class="sort">
            <i class="fas fa-sort-alpha-up"></i>
        </div>
    </a>
    <a href="{{ route('szerelok.index', ['sort_by' => 'Szerelo_ID', 'sort_dir' => 'desc']) }}" title="Csökkenő sorrend">
        <div class="sort">
            <i class="fas fa-sort-alpha-down-alt"></i>
        </div>
    </a>
    <a href="{{ route('szerelok.create') }}" title="Új szerelő hozzáadása">
        <i class="fas fa-plus-square"></i>
    </a>
    <form class="kereses" action="{{ route('szerelok.index') }}" method="GET">
        <input type="text" name="search" placeholder="Keresés név vagy telefonszám alapján">
        <button type="submit">Keresés</button>
    </form>
</div>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@foreach ($szerelok as $szerelo)
    <ul>
        <li>
            {{ $szerelo->Szerelo_ID }} - {{ $szerelo->Nev }} - {{ $szerelo->Telefonszam }}
        </li>
        <a href="{{ route('szerelok.show', $szerelo->Szerelo_ID) }}" class="button">Megtekint</a>
        <a href="{{ route('szerelok.edit', $szerelo->Szerelo_ID) }}" class="button">Szerkeszt</a>
        <form action="{{ route('szerelok.destroy', $szerelo->Szerelo_ID) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="torol" type="submit" onclick="return confirm('Biztosan törölni szeretnéd?')">Töröl</button>
        </form>
    </ul>
@endforeach

@endsection
