@extends('layout')

@section('content')

@include('breadcrumbs')
<h1>Anyag Részletei</h1>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $anyag->Nev }}</h5>
        <p class="card-text">Mértékegység: {{ $anyag->Mertekegyseg }}</p>
        <a href="{{ route('anyagok.index') }}" class="btn btn-primary">Vissza a listához</a>
    </div>
</div>
@endsection
