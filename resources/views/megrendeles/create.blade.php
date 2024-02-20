@extends('layout')

@section('content')
<h1>Új Megrendelés</h1>
@error('Megrendeles_Nev')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Objektum_ID')
<div class="alert alert-warning">{{ $message }}</div>
@enderror

@error('Utca_Hazszam')
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
        <label for="Megrendeles_Nev">Megrendelő neve</label>
        <input type="text" name="Megrendeles_Nev" id="Megrendeles_Nev" value="{{ old('Megrendeles_Nev') }}">
    </fieldset>

    <fieldset>
        <label for="Objektum_ID">Város</label>
        <select name="Objektum_ID" id="Objektum_ID">
            <option value="">Válassz várost</option>
            @foreach ($objektumok as $objektum)
            <option value="{{ $objektum->Objektum_ID }}">{{ $objektum->Varos }}</option>
            @endforeach
        </select>
    </fieldset>

    <fieldset>
        <label for="Utca_Hazszam">Utca, házszám</label>
        <input type="text" name="Utca_Hazszam" id="Utca_Hazszam" value="{{ old('Utca_Hazszam') }}">
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

            @foreach ($szolgaltatasok as $szolgaltatas)
            <option value="{{ $szolgaltatas->Szolgaltatas_ID }}">{{ $szolgaltatas->Tipus }}</option>
            @endforeach
        </select>
    </fieldset>


    <fieldset>
        <label for="Szerelo_ID">Szerelő</label>
        <select name="Szerelo_ID" id="Szerelo_ID">
            <option value="">Válassz szerelőt</option>
        </select>
    </fieldset>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const szolgaltatasSelect = document.getElementById('Szolgaltatas_ID');
            const szereloSelect = document.getElementById('Szerelo_ID');
            console.log(szereloSelect)
            szolgaltatasSelect.addEventListener('change', function() {
                const szolgaltatasId = this.value;
                fetch(`/szolgaltatas-szerelok/${szolgaltatasId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        szereloSelect.innerHTML = '<option value="">Válassz szerelőt</option>';
                        data.forEach(szerelo => {
                            szereloSelect.innerHTML += `<option value="${szerelo.Szerelo_ID}">${szerelo.Nev}</option>`;
                        });
                    })

                    .catch(error => console.error('Hiba történt a szerelők lekérdezésekor', error));
            });
        });
    </script>



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