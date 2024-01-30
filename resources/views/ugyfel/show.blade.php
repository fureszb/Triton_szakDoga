@extends('layout')

@section('content')

<h1>{{ $ugyfel->Nev }} Ügyfél részletek</h1>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3>Általános információk</h3>
            <ul class="list-group">
                <li class="list-group-item"><strong>UgyfelID:</strong> {{ $ugyfel->Ugyfel_ID }}</li>
                <li class="list-group-item"><strong>Név:</strong> {{ $ugyfel->Nev }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $ugyfel->Email }}</li>
                <li class="list-group-item"><strong>Telefonszám:</strong> {{ $ugyfel->Telefonszam }}</li>
                <li class="list-group-item"><strong>Számlázási név:</strong> {{ $ugyfel->Szamlazasi_Nev }}</li>
                <li class="list-group-item"><strong>Számlázási cím:</strong> {{ $ugyfel->Szamlazasi_Cim }}</li>
                @if (is_null($ugyfel->Adoszam))
                <li class="list-group-item"><strong>Adószám:</strong> -</li>
                @else
                <li class="list-group-item"><strong>Adószám:</strong> {{ $ugyfel->Adoszam }}</li>
                @endif

            </ul>
        </div>
       
       
    </div>
</div>

@endsection