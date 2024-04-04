@extends('ujlayout')

@section('content')

@include('breadcrumbs')

@if ($anyag)
    <h1>{{ $anyag->Nev }} - Részletei</h1>
    <hr class="showHr">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Anyag információk</h3>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Név:</strong> {{ $anyag->Nev }}</li>
                    <li class="list-group-item"><strong>Mértékegység:</strong> {{ $anyag->Mertekegyseg }}</li>
                </ul>
            </div>
        </div>
        <a href="{{ route('anyagok.edit', $anyag->Anyag_ID) }}"><button>
            Szerkesztés</button></a>
    </div>
@else
    <p>Az anyag nem található.</p>
@endif

@endsection
