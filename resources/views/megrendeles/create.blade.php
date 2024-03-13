@extends('layout')

@section('content')

@include('breadcrumbs')

<h1>Új Megrendelés</h1>
@if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-warning">{{ $error }}</div>
    @endforeach
</div>
@endif

    <form id="createForm" action="{{ route('megrendeles.store') }}" method="POST">
        @csrf
        <fieldset>
            <label for="Megrendeles_Nev">Megrendelő neve</label>
            <input type="text" name="Megrendeles_Nev" id="Megrendeles_Nev" value="{{ old('Megrendeles_Nev') }}">
        </fieldset>

        <fieldset>
            <label for="Varos_ID">Város</label>
            <select name="Varos_ID" id="Varos_ID">
                <option value="">Válassz várost</option>
                @foreach ($varosok as $varos)
                    <option value="{{ $varos->Varos_ID }}">{{ $varos->Irny_szam }} {{ $varos->Nev }}</option>
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
                                szereloSelect.innerHTML +=
                                    `<option value="${szerelo.Szerelo_ID}">${szerelo.Nev}</option>`;
                            });
                        })

                        .catch(error => console.error('Hiba történt a szerelők lekérdezésekor', error));
                });
            });
        </script>
        <fieldset>
            <label for="Szerelo_ID">Szerelő</label>
            <select name="Szerelo_ID" id="Szerelo_ID">
                <option value="">Válassz szerelőt</option>
            </select>
        </fieldset>


        <fieldset>
            <label for="Leiras">Munka Leírása</label>
            <textarea name="Leiras" id="Leiras">{{ old('Leiras') }}</textarea>
        </fieldset>

        <fieldset>
            <label for="Munkakezdes_Idopontja">Munkakezdés időpontja</label>
            <input type="datetime-local" name="Munkakezdes_Idopontja" id="Munkakezdes_Idopontja"
                value="{{ old('Munkakezdes_Idopontja') }}">
        </fieldset>

        <fieldset>
            <label for="Munkabefejezes_Idopontja">Munkabefejezés időpontja</label>
            <input type="datetime-local" name="Munkabefejezes_Idopontja" id="Munkabefejezes_Idopontja"
                value="{{ old('Munkabefejezes_Idopontja') }}">
        </fieldset>

        <div id="anyagokContainer">
            <div class="anyagMennyisegPár">
                <select name="Anyag_ID[]" class="anyagSelect">
                    <option value="">Válassz anyagot</option>
                    @foreach ($anyagok as $anyag)
                        <option value="{{ $anyag->Anyag_ID }}">{{ $anyag->Nev }}({{ $anyag->Mertekegyseg }})</option>
                    @endforeach
                </select>
                <input type="number" name="Mennyiseg[]" placeholder="Mennyiség" min="1">
                <button type="button" class="removeAnyag">Eltávolítás</button>
            </div>
        </div>
        <button type="button" id="addAnyag">Új anyag hozzáadása</button>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('addAnyag').addEventListener('click', function() {
                    var container = document.getElementById('anyagokContainer');
                    var newPair = container.firstElementChild.cloneNode(true);
                    newPair.querySelector('.anyagSelect').selectedIndex = 0;
                    newPair.querySelector('input[type=number]').value = '';
                    container.appendChild(newPair);
                });


                document.getElementById('anyagokContainer').addEventListener('click', function(e) {
                    if (e.target.classList.contains('removeAnyag')) {
                        if (document.querySelectorAll('.anyagMennyisegPár').length > 1) {
                            e.target.parentElement.remove();
                        } else {
                            alert('Legalább egy anyagot meg kell adni.');
                        }
                    }
                });
            });
        </script>

        @include('signaturePad')

        <div class="grid">
            <button id="saveButton" type="submit" data-action="save-png" class="button save">Mentés új megrendelésként</button>
        <a href="{{ route('ugyfel.create') }}" title="Új ügyfél hozzáadása">

                <div class="hozzaad">+</div>
            </a>
        </div>
        </form>
@endsection
