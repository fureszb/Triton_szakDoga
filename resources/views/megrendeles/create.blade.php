@extends('layout')

@section('content')
<h1>Új Megrendelés</h1>
@error('Megrendeles_Nev')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Objektum_Cim')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Ugyfel_ID')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

<form id="createForm" action="{{ route('megrendeles.store') }}" method="POST">
    @csrf
    <fieldset>
        <label for="Megrendeles_Nev">Megrendelés Nev</label>
        <input type="text" name="Megrendeles_Nev" id="Megrendeles_Nev" value="{{ old('Megrendeles_Nev') }}">
    </fieldset>
    <fieldset>
        <label for="Objektum_Cim">Objektum Cím</label>
        <input type="text" name="Objektum_Cim" id="Objektum_Cim" value="{{ old('Objektum_Cim') }}">
    </fieldset>

    
    <fieldset>
        <label for="Ugyfel_ID">Ügyfél</label>
        <select name="Ugyfel_ID" id="Ugyfel_ID">
            <option value="">Válassz Ügyfelet</option>
            @foreach ($ugyfelek as $ugyfel)
            <option value="{{ $ugyfel->Ugyfel_ID }}">{{ $ugyfel->Ugyfel_ID }} - {{ $ugyfel->Nev }}</option>
            @endforeach
        </select>
    </fieldset>
    

    <div class="grid">
    <button id="saveButton" type="submit">Mentés új megrendelésként</button>
    <a href="{{ route('ugyfel.create') }}" title="Új ügyfél hozzáadása">
        
        <div class="hozzaad">+</div>
    </a>
    </div>
</form>
@endsection