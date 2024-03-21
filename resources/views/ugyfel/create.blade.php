@extends('layout')



@section('content')
    @include('breadcrumbs')

    <h1>Új ügyfél</h1>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-warning">{{ $error }}</div>
        @endforeach
        </div>
    @endif

    <form id="createForm" action="{{ route('ugyfel.store') }}" method="POST">
        @csrf
        <fieldset>
            <label for="Ugyfel_ID">ID</label>
            <input type="text" name="Ugyfel_ID" id="Ugyfel_ID" value="{{ old('Ugyfel_ID') }}">
        </fieldset>
        <fieldset>
            <label for="nev">Név</label>
            <input type="text" name="nev" id="nev" value="{{ old('nev') }}">
        </fieldset>
        <fieldset>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
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
            <label for="Varos_ID">Város</label>
            <select name="Varos_ID" id="Varos_ID">
                <option value="">Válassz várost</option>
                @foreach ($varosok as $varos)
                    <option value="{{ $varos->Varos_ID }}">{{ $varos->Irny_szam }} {{ $varos->Nev }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset>
            <label for="szamcim">Utca, házszám</label>
            <input type="text" name="szamcim" id="szamcim" value="{{ old('szamcim') }}">
        </fieldset>
        <fieldset>
            <label for="adoszam">Adószám</label>
            <input type="text" name="adoszam" id="adoszam" value="{{ old('adoszam') }}">
        </fieldset>

        <div class="grid">
            <button id="saveButton" type="submit">Mentés új ügyfélként</button>

            <a href="{{ route('megrendeles.create') }}" title="Új megrendeléshez rendelés">
                <!--<div id="mentTovabit" class="hozzaad">+</div>-->
            </a>
        </div>


    </form>
@endsection
