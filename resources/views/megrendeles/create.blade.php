@extends('layout')

@section('content')
    <h1>Új Megrendeles Létrehozása</h1>
    <form action="{{ route('megrendeles.store') }}" method="POST">
        @csrf
        <label for="Megrendeles_Nev">Megrendeles Nev:</label>
        <input type="text" name="Megrendeles_Nev" id="Megrendeles_Nev" required value="{{ old('Megrendeles_Nev') }}">

        <label for="Objektum_Cim">Objektum Cim:</label>
        <input type="text" name="Objektum_Cim" id="Objektum_Cim" required value="{{ old('Objektum_Cim') }}">


        <button type="submit">Mentés</button>
    </form>
@endsection
