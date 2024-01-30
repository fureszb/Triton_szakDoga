@extends('layout')

@section('content')

@error('nev')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('email')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('telefon')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('szamnev')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('szamcim')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('adoszam')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Ugyfel_ID')
<div class="alert alert-warning">{{ $message }}</div>
@enderror



<form action="{{ route('ugyfel.update', $ugyfel->Ugyfel_ID) }}" method="POST">
    @csrf
    @method('PUT')

    <fieldset>
        <label for="Ugyfel_ID">Ugyfel_ID</label>
         <input type="text" name="Ugyfel_ID" id="Ugyfel_ID" value="{{ old('id', $ugyfel->Ugyfel_ID) }}">
     </fieldset>


    <fieldset>
        <label for="nev">Név</label>
        <input type="text" name="nev" id="nev" value="{{ old('nev', $ugyfel->Nev) }}">
    </fieldset>

    <fieldset>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $ugyfel->Email) }}">
    </fieldset>

    <fieldset>
        <label for="telefon">Telefonszám</label>
        <input type="text" name="telefon" id="telefon" value="{{ old('telefon') ?? $ugyfel->Telefonszam }}">
    </fieldset>

    <fieldset>
        <label for="szamnev">Számlázási név</label>
        <input type="text" name="szamnev" id="szamnev" value="{{ old('szamnev') ?? $ugyfel->Szamlazasi_Nev }}">
    </fieldset>

    <fieldset>
        <label for="szamcim">Számlázási cím</label>
        <input type="text" name="szamcim" id="szamcim" value="{{ old('szamcim') ?? $ugyfel->Szamlazasi_Cim }}">
    </fieldset>

    <fieldset>
        <label for="adoszam">Adószám</label>
        <input type="text" name="adoszam" id="adoszam" value="{{ old('adoszam') ?? $ugyfel->Adoszam }}">
    </fieldset>

    <button type="submit">Mentés</button>
    </form>

@endsection
