@extends('layout')

@section('content')

@include('breadcrumbs')
<h1>Új Szerelő Hozzáadása</h1>

<form action="{{ route('szerelok.store') }}" method="POST">
    @csrf
    <fieldset>
        <label for="Nev">Név:</label>
        <input type="text" name="Nev" id="Nev" required>
    </fieldset>
    <fieldset>
        <label for="Telefonszam">Telefonszám:</label>
        <input type="text" name="Telefonszam" id="Telefonszam" required>
    </fieldset>
    @include('signaturePad')

    <button type="submit" class="btn btn-primary" data-action="save-png">Mentés</button>
</form>
@endsection
