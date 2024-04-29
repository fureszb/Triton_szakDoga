<meta name="csrf-token" content="{{ csrf_token() }}">
@extends('ujlayout')


@section('content')
    @include('breadcrumbs')
    <h1>Új Szerelő Hozzáadása</h1>
    <hr class="showHr"></hr>
    <form action="{{ route('szerelok.store') }}" method="POST">
        @csrf
        <fieldset>
            <label for="Nev">Név:</label>
            <input type="text" name="Nev" id="Nev" required>
        </fieldset>
        <fieldset>
            <label for="Telefonszam">Telefonszám:</label>
            <input type="text" name="Telefonszam" id="Telefonszam" required>
        </fieldset>
        @include('signaturePad')

        <button id="saveSignatureButton customAlertNoHeading" data-action="save-png2" type="submit" class="btn btn-primary">Mentés</button>
    </form>
@endsection
