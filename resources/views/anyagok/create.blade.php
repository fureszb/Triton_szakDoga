@extends('layout')

@section('content')
<h1>Új Anyag Hozzáadása</h1>

@error('Nev')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Mertekegyseg')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

<form id="newMaterialForm" action="{{ route('anyagok.store') }}" method="POST">
    @csrf
    <fieldset>
        <label for="Nev">Anyag Leírása</label>
        <input type="text" name="Nev" id="Nev" value="{{ old('Nev') }}" required>
    </fieldset>
    <fieldset>
        <label for="Mertekegyseg">Anyag mértékegyseg</label>
        <input type="text" name="Mertekegyseg" id="Mertekegyseg" value="{{ old('Mertekegyseg') }}" required>
    </fieldset>

    <button id="saveMaterialButton" type="submit" class="btn btn-primary">Mentés</button>
</form>
@endsection
