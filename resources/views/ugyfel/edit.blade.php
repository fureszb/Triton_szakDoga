@extends('alkalmazas')

@section('main')

<h1>Ügyfél Szerkesztése</h1>
@if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-warning">{{ $error }}</div>
    @endforeach
</div>
@endif



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
        <label for="Varos_ID">Város</label>
        <select name="Varos_ID" id="Varos_ID">
            <option value="">Válassz várost</option>
            @foreach ($varosok as $varos)
                <option value="{{ $varos->Varos_ID }}"
                    {{ $varos->Varos_ID == $ugyfel->Varos_ID ? 'selected' : '' }}>{{ $varos->Irny_szam }} {{ $varos->Nev }}
                </option>
            @endforeach
        </select>
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
