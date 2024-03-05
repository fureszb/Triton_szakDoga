@extends('layout')

@section('content')
<script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
<h1 class="title">Megrendeléseim
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
