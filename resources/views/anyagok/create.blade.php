@extends('layout')

@section('content')
<h1>Új Anyag Hozzáadása</h1>

@error('leiras')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

<form id="newMaterialForm" action="{{ route('anyagok.store') }}" method="POST">
    @csrf
    <fieldset>
        <label for="Leiras">Anyag Leírása</label>
        <input type="text" name="leiras" id="Leiras" value="{{ old('leiras') }}" required>
    </fieldset>

    <button id="saveMaterialButton" type="submit" class="btn btn-primary">Mentés</button>
</form>
@endsection
