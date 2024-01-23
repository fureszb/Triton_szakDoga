@extends('layout')

@section('content')

@error('nev')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('email')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('objcim')
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

@error('kezd_datum')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('bef_datum')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('adoszam')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('szerelo')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('szolgaltatas')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('munka')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('felhasznalt_anyagok')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

<form action="{{ route('ugyfel.update', $ugyfel->UgyfelID) }}" method="POST">
    @csrf
    @method('PUT')

    <fieldset>
        <label for="id">ID</label>
         <input type="text" name="id" id="id" value="{{ old('id', $ugyfel->UgyfelID) }}">
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
        <label for="objcim">Objektum címe</label>
        <input type="text" name="objcim" id="objcim" value="{{ old('objcim') ?? $ugyfel->ObjCim }}">
    </fieldset>

    <fieldset>
        <label for="telefon">Telefonszám</label>
        <input type="text" name="telefon" id="telefon" value="{{ old('telefon') ?? $ugyfel->Telefon }}">
    </fieldset>

    <fieldset>
        <label for="szamnev">Számlázási név</label>
        <input type="text" name="szamnev" id="szamnev" value="{{ old('szamnev') ?? $ugyfel->SzamNev }}">
    </fieldset>

    <fieldset>
        <label for="szamcim">Számlázási cím</label>
        <input type="text" name="szamcim" id="szamcim" value="{{ old('szamcim') ?? $ugyfel->SzamCim }}">
    </fieldset>

    <fieldset>
        <label for="kezd_datum">Kezdő Dátum</label>
        <input type="datetime-local" name="kezd_datum" id="kezd_datum" value="{{ old('kezd_datum') ?? $ugyfel->KezdDatum }}">
    </fieldset>

    <fieldset>
        <label for="bef_datum">Befejező Dátum</label>
        <input type="datetime-local" name="bef_datum" id="bef_datum" value="{{ old('bef_datum') ?? $ugyfel->BefDatum }}">
    </fieldset>

    <fieldset>
        <label for="adoszam">Adószám</label>
        <input type="text" name="adoszam" id="adoszam" value="{{ old('adoszam') ?? $ugyfel->AdoSzam }}">
    </fieldset>

    <fieldset>
        <label for="szerelo">Szerelő</label>
        <select name="szerelo" id="szerelo">
            @foreach ($szerelok as $szereloOption)
                <option value="{{ $szereloOption->id }}" {{ (old('szerelo') ?? $ugyfel->szerelo) == $szereloOption->id ? 'selected' : '' }}>{{ $szereloOption->Nev }}</option>
            @endforeach
        </select>
    </fieldset>




    <fieldset>
        <label for="szolgaltatas">Szolgáltatás</label>
        <select name="szolgaltatas" id="szolgaltatas">
            @foreach ($szolgaltatasok as $szolgaltatasOption)
                <option value="{{ $szolgaltatasOption->id }}" {{ (old('szolgaltatas') ?? $ugyfel->szolgaltatas) == $szolgaltatasOption->id ? 'selected' : '' }}>{{ $szolgaltatasOption->jelleg }}</option>
            @endforeach
        </select>
    </fieldset>




    <fieldset>
        <label for="munka">Munka</label>
        <select name="munka" id="munka">
            @foreach ($munkak as $munkaOption)
                <option value="{{ $munkaOption->id }}" {{ (old('munka') ?? $ugyfel->munka) == $munkaOption->id ? 'selected' : '' }}>{{ $munkaOption->Jelleg }}</option>
            @endforeach
        </select>
    </fieldset>


    <fieldset>
        <label for="felhasznalt_anyagok">Felhasznált Anyagok</label>
        <input type="text" name="felhasznalt_anyagok" id="felhasznalt_anyagok" value="{{ old('felhasznalt_anyagok') ?? $ugyfel->FelhasznaltAnyagok }}">
    </fieldset>

    <button type="submit">Mentés</button>
    </form>

@endsection
