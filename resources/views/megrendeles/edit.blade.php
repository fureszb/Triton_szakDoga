@extends('layout')

@section('content')
@error('Megrendeles_Nev')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Objektum_Cim')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Alairt_e')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Pdf_EleresiUt')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

<form action="{{ route('megrendeles.update', $megrendeles->Megrendeles_ID) }}" method="POST">
    @csrf
    @method('PUT')

    <fieldset>
        <label for="Megrendeles_Nev">Megrendeles Nev</label>
        <input type="text" name="Megrendeles_Nev" id="Megrendeles_Nev" value="{{ old('Megrendeles_Nev', $megrendeles->Megrendeles_Nev) }}">
    </fieldset>

    <fieldset>
        <label for="Objektum_Cim">Objektum Cim</label>
        <input type="text" name="Objektum_Cim" id="Objektum_Cim" value="{{ old('Objektum_Cim', $megrendeles->Objektum_Cim) }}">
    </fieldset>

    <fieldset>
        <label for="Alairt_e">Alairt-e</label>
        <select name="Alairt_e" id="Alairt_e">
            <option value="0" {{ old('Alairt_e', $megrendeles->Alairt_e) === 0 ? 'selected' : '' }}>Nem</option>
            <option value="1" {{ old('Alairt_e', $megrendeles->Alairt_e) === 1 ? 'selected' : '' }}>Igen</option>
        </select>
    </fieldset>

    <fieldset>
        <label for="Pdf_EleresiUt">Pdf Elérési Út</label>
        <input type="text" name="Pdf_EleresiUt" id="Pdf_EleresiUt" value="{{ old('Pdf_EleresiUt', $megrendeles->Pdf_EleresiUt) }}">
    </fieldset>

    <button type="submit">Módosít</button>
</form>
@endsection
