@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-user-plus"></i> Új ügyfél</h1>
        <a href="{{ route('ugyfel.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
    </div>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-warning">{{ $error }}</div>
        @endforeach
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

        <div style="width:100%;">
            <button id="saveButton" type="submit" class="btn-save">
                <i class="fas fa-save"></i> Mentés új ügyfélként
            </button>
        </div>
    </form>
@endsection
