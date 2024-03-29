@extends('ujlayout')

@section('content')

@include('breadcrumbs')
<h1>Anyag Szerkesztése</h1>
<hr class="showHr"></hr>
<form action="{{ route('anyagok.update', $anyag->Anyag_ID) }}" method="POST">
    @csrf
    @method('PUT')

    <fieldset>
        <label for="Nev">Anyag neve</label>
        <input type="text" name="Nev" id="Nev" value="{{ old('Nev', $anyag->Nev) }}" required>
    </fieldset>
    <fieldset>
        <label for="Mertekegyseg">Anyag mértékegység</label>
        <input type="text" name="Mertekegyseg" id="Mertekegyseg" value="{{ old('Mertekegyseg', $anyag->Mertekegyseg) }}" required>
    </fieldset>

    <button type="submit" class="btn btn-primary">Frissítés</button>
</form>
@endsection
