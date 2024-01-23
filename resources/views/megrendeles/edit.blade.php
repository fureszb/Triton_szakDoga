@extends('layout')

@section('content')
    <h1>Megrendeles Szerkesztése</h1>
    <form action="{{ route('megrendeles.update', $megrendeles->Megrendeles_ID) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="Megrendeles_Nev">Megrendeles Nev:</label>
        <input type="text" name="Megrendeles_Nev" id="Megrendeles_Nev" value="{{ $megrendeles->Megrendeles_Nev }}" required>
        
        <label for="Objektum_Cim">Objektum Cim:</label>
        <input type="text" name="Objektum_Cim" id="Objektum_Cim" value="{{ $megrendeles->Objektum_Cim }}" required>

        <button type="submit">Módosít</button>
    </form>
@endsection
