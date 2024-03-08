@extends('layout')

@section('content')
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
    <button type="submit" class="btn btn-primary">Mentés</button>
</form>
@endsection
