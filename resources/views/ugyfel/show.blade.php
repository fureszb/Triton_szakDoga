@extends('layout')

@section('content')

<h1>{{ $ugyfel->Nev }} Ügyfél részletek</h1>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3>Általános információk</h3>
            <ul class="list-group">
                <li class="list-group-item"><strong>UgyfelID:</strong> {{ $ugyfel->UgyfelID }}</li>
                <li class="list-group-item"><strong>Név:</strong> {{ $ugyfel->Nev }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $ugyfel->Email }}</li>
                <li class="list-group-item"><strong>Objektum címe:</strong> {{ $ugyfel->ObjCim }}</li>
                <li class="list-group-item"><strong>Telefonszám:</strong> {{ $ugyfel->Telefon }}</li>
                <li class="list-group-item"><strong>Számlázási név:</strong> {{ $ugyfel->SzamNev }}</li>
                <li class="list-group-item"><strong>Számlázási cím:</strong> {{ $ugyfel->SzamCim }}</li>
                <li class="list-group-item"><strong>Kezdés dátuma:</strong> {{ $ugyfel->KezdDatum }}</li>
                <li class="list-group-item"><strong>Befejezés dátuma:</strong> {{ $ugyfel->BefDatum }}</li>
                <li class="list-group-item"><strong>Adószám:</strong> {{ $ugyfel->AdoSzam }}</li>
            </ul>
        </div>
        <div class="col-md-6">
            <br>
            <h3>Munka és szerelő</h3>
            <ul class="list-group">
                <li class="list-group-item"><strong>Szerelő:</strong> {{ $ugyfel->szerelo->Nev }}</li>
                <li class="list-group-item"><strong>Szolgáltatás:</strong> {{ $ugyfel->szolgaltatas->jelleg }}</li>
                <li class="list-group-item"><strong>Munka:</strong> {{ $ugyfel->munka->Jelleg }}</li>
                <li class="list-group-item"><strong>Munka leírás:</strong> {{ $ugyfel->munka->Leiras }}</li>
                <li class="list-group-item"><strong>Felhasznált Anyagok:</strong> {{ $ugyfel->FelhasznaltAnyagok}}</li>
            </ul>
        </div>
    </div>
</div>

@endsection
