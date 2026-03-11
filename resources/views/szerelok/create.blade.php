<meta name="csrf-token" content="{{ csrf_token() }}">
@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-hard-hat"></i> Új szerelő hozzáadása</h1>
        <a href="{{ route('szerelok.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
    </div>

    <form action="{{ route('szerelok.store') }}" method="POST">
        @csrf
        <fieldset>
            <label for="Nev">Név</label>
            <input type="text" name="Nev" id="Nev" required>
        </fieldset>
        <fieldset>
            <label for="Telefonszam">Telefonszám</label>
            <input type="text" name="Telefonszam" id="Telefonszam" required>
        </fieldset>

        @include('signaturePad')

        <div style="width:100%;">
            <button id="saveSignatureButton" data-action="save-png2" type="submit" class="btn-save">
                <i class="fas fa-save"></i> Mentés
            </button>
        </div>
    </form>
@endsection
