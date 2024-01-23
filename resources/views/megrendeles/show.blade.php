@extends('layouts.app')

@section('content')
    <h1>Megrendeles Részletei</h1>
    <p>Megrendeles Nev: {{ $megrendeles->Megrendeles_Nev }}</p>
    <p>Objektum Cim: {{ $megrendeles->Objektum_Cim }}</p>
    <!-- További részletek... -->
@endsection
