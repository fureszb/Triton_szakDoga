@extends('ujlayout')

@section('content')
<script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
<div class="megrendeles"><h1>Megrendeléseim</h1>
<hr class="showHr"></div>
@if (session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@elseif (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($megrendelesek && count($megrendelesek) > 0)
    <ul>
        @foreach ($megrendelesek as $m)
            <li>
                {{ $m->Megrendeles_ID }} - {{ $m->Megrendeles_Nev }}
            </li>
            <a href="{{ route('megrendeles.show', ['id' => $m->Megrendeles_ID]) }}" class="button">Megjelenítés</a>
            <div></div> <div></div>
        @endforeach
    </ul>
@else
    <p>Nincsenek megrendeléseid.</p>
@endif

@endsection
