@extends('ujlayout')

@section('content')

@include('breadcrumbs')
<h1>{{ $szerelo->Nev }} - Szerkesztése</h1>
<hr class="showHr">

<form action="{{ route('szerelok.update', $szerelo->Szerelo_ID) }}" method="POST">
    @csrf
    @method('PUT')
    <fieldset>
        <label for="Nev">Név:</label>
        <input type="text" name="Nev" id="Nev" value="{{ $szerelo->Nev }}" required>
    </fieldset>
    <fieldset>
        <label for="Telefonszam">Telefonszám:</label>
        <input type="text" name="Telefonszam" id="Telefonszam" value="{{ $szerelo->Telefonszam }}" required>
    </fieldset>
    <button type="submit" class="btn btn-primary">Frissítés</button>
</form>
@endsection
