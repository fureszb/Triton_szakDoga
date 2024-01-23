@extends('layout')

@section('content')
    <h1>Új ügyfél</h1>
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

    <form id="createForm" action="{{ route('ugyfel.store') }}" method="POST">
        @csrf
        <fieldset>
            <label for="id">ID</label>
            <input type="text" name="id" id="id" value="{{ old('id') }}">
        </fieldset>
        <fieldset>
            <label for="nev">Név</label>
            <input type="text" name="nev" id="nev" value="{{ old('nev', $ugyfel->nev) }}">
        </fieldset>
        <fieldset>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
        </fieldset>
        <fieldset>
            <label for="objcim">Objektum címe</label>
            <input type="text" name="objcim" id="objcim" value="{{ old('objcim') }}">
        </fieldset>
        <fieldset>
            <label for="telefon">Telefonszám</label>
            <input type="text" name="telefon" id="telefon" value="{{ old('telefon') }}">
        </fieldset>
        <fieldset>
            <label for="szamnev">Számlázási név</label>
            <input type="text" name="szamnev" id="szamnev" value="{{ old('szamnev') }}">
        </fieldset>
        <fieldset>
            <label for="szamcim">Számlázási cím</label>
            <input type="text" name="szamcim" id="szamcim" value="{{ old('szamcim') }}">
        </fieldset>
        <fieldset>
            <label for="kezd_datum">Kezdő Dátum</label>
            <input type="datetime-local" name="kezd_datum" id="kezd_datum" value="{{ old('kezd_datum') }}">
        </fieldset>
        <fieldset>
            <label for="bef_datum">Befejező Dátum</label>
            <input type="datetime-local" name="bef_datum" id="bef_datum" value="{{ old('bef_datum') }}">
        </fieldset>
        <fieldset>
            <label for="adoszam">Adószám</label>
            <input type="text" name="adoszam" id="adoszam" value="{{ old('adoszam') }}">
        </fieldset>
        <fieldset>
            <label for="szerelo">Szerelő</label>
            <select name="szerelo" id="szerelo">
                @foreach ($szerelok as $szerelo)
                    <option value="{{ $szerelo->SzereloID }}">{{ $szerelo->Nev }}</option>
                @endforeach
            </select>
        </fieldset>
        <fieldset>
            <label for="szolgaltatas">Szolgáltatás</label>
            <select name="szolgaltatas">
                @foreach ($szolgaltatasok as $szolgaltatas)
                    <option value="{{ $szolgaltatas->SzolgID }}">{{ $szolgaltatas->jelleg }}</option>
                @endforeach
            </select>
        </fieldset>
        <fieldset>
            <label for="munka">Munka</label>
            <select name="munka" id="munka">
                @foreach ($munkak as $munka)
                    <option value="{{ $munka->MunkaID }}">{{ $munka->Jelleg }}</option>
                @endforeach
            </select>
        </fieldset>
        <fieldset>
            <label for="felhasznalt_anyagok">Felhasznált anyagok</label>
            <textarea name="felhasznalt_anyagok" id="felhasznalt_anyagok">{{ old('felhasznalt_anyagok') }}</textarea>
        </fieldset>
        <button id="saveButton">Mentés új ügyfélként</button>

    </form>
@endsection
