@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-edit"></i> {{ $anyag->Nev }} — Szerkesztése</h1>
        <a href="{{ route('anyagok.show', $anyag->Anyag_ID) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
    </div>

    <form action="{{ route('anyagok.update', $anyag->Anyag_ID) }}" method="POST">
        @csrf
        @method('PUT')

        <fieldset>
            <label for="Nev">Anyag neve</label>
            <input type="text" name="Nev" id="Nev" value="{{ old('Nev', $anyag->Nev) }}" required>
        </fieldset>
        <fieldset>
            <label for="Mertekegyseg">Mértékegység</label>
            <input type="text" name="Mertekegyseg" id="Mertekegyseg"
                value="{{ old('Mertekegyseg', $anyag->Mertekegyseg) }}" required>
        </fieldset>

        <div style="width:100%;">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Frissítés
            </button>
        </div>
    </form>
@endsection
