@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-tools"></i> {{ $szerelo->Nev }} — Szerkesztése</h1>
        <a href="{{ route('szerelok.show', $szerelo->Szerelo_ID) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
    </div>

    <form action="{{ route('szerelok.update', $szerelo->Szerelo_ID) }}" method="POST">
        @csrf
        @method('PUT')
        <fieldset>
            <label for="Nev">Név</label>
            <input type="text" name="Nev" id="Nev" value="{{ $szerelo->Nev }}" required>
        </fieldset>
        <fieldset>
            <label for="Telefonszam">Telefonszám</label>
            <input type="text" name="Telefonszam" id="Telefonszam" value="{{ $szerelo->Telefonszam }}" required>
        </fieldset>

        <div style="width:100%;">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Frissítés
            </button>
        </div>
    </form>
@endsection
