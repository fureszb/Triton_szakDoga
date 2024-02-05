@extends('layout')

@section('content')
<h1>Új Megrendelés</h1>
@error('Megrendeles_Nev')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Objektum_Cim')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Ugyfel_ID')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Szolgaltatas_ID')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Szerelo_ID')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Leiras')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Munkakezdes_Idopontja')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Munkabefejezes_Idopontja')
<div class="alert alert-warning">{{ $message }}</div>
@enderror
@error('Anyag_ID')
<div class="alert alert-warning">{{ $message }}</div>
@enderror
@error('Mennyiseg')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

<form id="createForm" action="{{ route('megrendeles.store') }}" method="POST">
    @csrf
    <fieldset>
        <label for="Megrendeles_Nev">Megrendelés Nev</label>
        <input type="text" name="Megrendeles_Nev" id="Megrendeles_Nev" value="{{ old('Megrendeles_Nev') }}">
    </fieldset>
    <fieldset>
        <label for="Objektum_Cim">Objektum Cím</label>
        <input type="text" name="Objektum_Cim" id="Objektum_Cim" value="{{ old('Objektum_Cim') }}">
    </fieldset>


    <fieldset>
        <label for="Ugyfel_ID">Ügyfél</label>
        <select name="Ugyfel_ID" id="Ugyfel_ID">
            <option value="">Válassz Ügyfelet</option>
            @foreach ($ugyfelek as $ugyfel)
            <option value="{{ $ugyfel->Ugyfel_ID }}">{{ $ugyfel->Ugyfel_ID }} - {{ $ugyfel->Nev }}</option>
            @endforeach
        </select>
    </fieldset>

    <fieldset>
        <label for="Szolgaltatas_ID">Szolgáltatás</label>
        <select name="Szolgaltatas_ID" id="Szolgaltatas_ID">
            <option value="">Válassz szolgáltatást</option>
            {{-- Itt kellene bejárni az előre felvitt szolgáltatásokat --}}
            @foreach ($szolgaltatasok as $szolgaltatas)
            <option value="{{ $szolgaltatas->Szolgaltatas_ID }}">{{ $szolgaltatas->Tipus }}</option>
            @endforeach
        </select>
    </fieldset>

    <fieldset>
        <label for="Szerelo_ID">Szerelő</label>
        <select name="Szerelo_ID" id="Szerelo_ID">
            <option value="">Válassz szerelőt</option>
            {{-- Itt kellene bejárni az előre felvitt szerelőket --}}
            @foreach ($szerelok as $szerelo)
            <option value="{{ $szerelo->Szerelo_ID }}">{{ $szerelo->Nev }}</option>
            @endforeach
        </select>
    </fieldset>

    <fieldset>
        <label for="Leiras">Munka Leírása</label>
        <textarea name="Leiras" id="Leiras">{{ old('Leiras') }}</textarea>
    </fieldset>

    <fieldset>
        <label for="Munkakezdes_Idopontja">Munkakezdés időpontja</label>
        <input type="datetime-local" name="Munkakezdes_Idopontja" id="Munkakezdes_Idopontja" value="{{ old('Munkakezdes_Idopontja') }}">
    </fieldset>

    <fieldset>
        <label for="Munkabefejezes_Idopontja">Munkabefejezés időpontja</label>
        <input type="datetime-local" name="Munkabefejezes_Idopontja" id="Munkabefejezes_Idopontja" value="{{ old('Munkabefejezes_Idopontja') }}">
    </fieldset>



   
    @include('signaturePad')

    <div class="grid">
    <button id="saveButton" type="submit">Mentés új megrendelésként</button>
    <a href="{{ route('ugyfel.create') }}" title="Új ügyfél hozzáadása">

        <div class="hozzaad">+</div>
    </a>
    </div>
</form>
@endsection
