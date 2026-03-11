@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-box-open"></i> Új anyag hozzáadása</h1>
        <a href="{{ route('anyagok.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
    </div>

    @error('Nev')
        <div class="alert alert-warning">{{ $message }}</div>
    @enderror
    @error('Mertekegyseg')
        <div class="alert alert-warning">{{ $message }}</div>
    @enderror

    <form id="newMaterialForm" action="{{ route('anyagok.store') }}" method="POST">
        @csrf
        <fieldset>
            <label for="Nev">Anyag neve</label>
            <input type="text" name="Nev" id="Nev" value="{{ old('Nev') }}" required>
        </fieldset>
        <fieldset>
            <label for="Mertekegyseg">Mértékegység</label>
            <input type="text" name="Mertekegyseg" id="Mertekegyseg" value="{{ old('Mertekegyseg') }}" required>
        </fieldset>

        <div style="width:100%;">
            <button id="saveMaterialButton" type="submit" class="btn-save">
                <i class="fas fa-save"></i> Mentés
            </button>
        </div>
    </form>
@endsection
