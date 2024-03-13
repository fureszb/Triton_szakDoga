@extends('layout')

@section('content')

@include('breadcrumbs')
<h1>Szerelő Részletei</h1>

<div>
    <p><strong>Név:</strong> {{ $szerelo->Nev }}</p>
    <p><strong>Telefonszám:</strong> {{ $szerelo->Telefonszam }}</p>
    <p><strong>Szolgáltatások:</strong></p>
    <ul>
        @foreach($szerelo->szolgaltatasok as $szolgaltatas)
            <li>{{ $szolgaltatas->Tipus }}</li>
        @endforeach
    </ul>
    <a href="{{ route('szerelok.index') }}" class="btn btn-primary">Vissza a listához</a>
</div>
@endsection
