@extends('layout')

@section('content')

@include('breadcrumbs')
  <h1>Új Megrendelés Szerkesztése</h1>
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-warning">{{ $error }}</div>
@endforeach
</div>
@endif

    <form id="editForm" action="{{ route('megrendeles.update', $megrendeles->Megrendeles_ID) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="Munka_ID" value="{{ $munka->Munka_ID }}">

        <fieldset>
            <label for="Ugyfel_ID">Ügyfél neve</label>
            <select name="Ugyfel_ID" id="Ugyfel_ID">
                <option value="">Válassz Ügyfelet</option>
                @foreach ($ugyfelek as $ugyfel)
                    <option value="{{ $ugyfel->Ugyfel_ID }}"
                        {{ $ugyfel->Ugyfel_ID == $megrendeles->Ugyfel_ID ? 'selected' : '' }}>{{ $ugyfel->Nev }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset>
            <label for="Megrendeles_Nev">Megrendelő neve</label>
            <input type="text" name="Megrendeles_Nev" id="Megrendeles_Nev"
                value="{{ old('Megrendeles_Nev', $megrendeles->Megrendeles_Nev) }}">
        </fieldset>

        <fieldset>
            <label for="Varos_ID">Város</label>
            <select name="Varos_ID" id="Varos_ID">
                <option value="">Válassz várost</option>
                @foreach ($varosok as $varos)
                    <option value="{{ $varos->Varos_ID }}"
                        {{ $varos->Varos_ID == $megrendeles->Varos_ID ? 'selected' : '' }}>{{ $varos->Irny_szam }}
                        {{ $varos->Nev }}
                    </option>
                @endforeach
            </select>
        </fieldset>

        <fieldset>
            <label for="Utca_Hazszam">Utca, házszám</label>
            <input type="text" name="Utca_Hazszam" id="Utca_Hazszam"
                value="{{ old('Utca_Hazszam', $megrendeles->Utca_Hazszam) }}">
        </fieldset>

        <fieldset>
            <label for="Pdf_EleresiUt">PDF Elérési Út</label>
            <input type="text" name="Pdf_EleresiUt" id="Pdf_EleresiUt"
                value="{{ old('Pdf_EleresiUt', $megrendeles->Pdf_EleresiUt) }}">
        </fieldset>

        <fieldset>
            <label for="Szolgaltatas_ID">Szolgáltatás</label>
            <select name="Szolgaltatas_ID" id="Szolgaltatas_ID">
                <option value="">Válassz szolgáltatást</option>
                @foreach ($szolgaltatasok as $szolgaltatas)
                    <option value="{{ $szolgaltatas->Szolgaltatas_ID }}"
                        {{ old('Szolgaltatas_ID', $munka->Szolgaltatas_ID) == $szolgaltatas->Szolgaltatas_ID ? 'selected' : '' }}>
                        {{ $szolgaltatas->Tipus }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset>
            <label for="Szerelo_ID">Szerelő</label>
            <select name="Szerelo_ID" id="Szerelo_ID">
                <option value="">Válassz szerelőt</option>
                @foreach ($szerelok as $szerelo)
                    <option value="{{ $szerelo->Szerelo_ID }}"
                        {{ old('Szerelo_ID', $munka->Szerelo_ID) == $szerelo->Szerelo_ID ? 'selected' : '' }}>
                        {{ $szerelo->Nev }}
                    </option>
                @endforeach
            </select>
        </fieldset>
        <fieldset>
            <label for="Leiras">Leírás</label>
            <textarea name="Leiras" id="Leiras">{{ old('Leiras', $munka->Leiras ?? '') }}</textarea>
        </fieldset>
        <fieldset>
            <label for="Alairt_e">Státusz:</label>
            <select name="Alairt_e" id="Alairt_e" class="form-control">
                <option value="1" {{ old('Alairt_e', $megrendeles->Alairt_e) == 1 ? 'selected' : '' }}>Folyamatban
                </option>
                <option value="0" {{ old('Alairt_e', $megrendeles->Alairt_e) == 0 ? 'selected' : '' }}>Befejezve
                </option>
            </select>
        </fieldset>
        <fieldset>
            <label for="Munkakezdes_Idopontja">Munkakezdés időpontja</label>
            <input type="datetime-local" name="Munkakezdes_Idopontja" id="Munkakezdes_Idopontja"
                value="{{ old('Munkakezdes_Idopontja', isset($munka) ? date('Y-m-d\TH:i', strtotime($munka->Munkakezdes_Idopontja)) : '') }}">
        </fieldset>

        <fieldset>
            <label for="Munkabefejezes_Idopontja">Munkabefejezés időpontja</label>
            <input type="datetime-local" name="Munkabefejezes_Idopontja" id="Munkabefejezes_Idopontja"
                value="{{ old('Munkabefejezes_Idopontja', isset($munka) ? date('Y-m-d\TH:i', strtotime($munka->Munkabefejezes_Idopontja)) : '') }}">
        </fieldset>

        <div id="anyagokContainer">
            @foreach ($megrendeles->felhasznaltAnyagok as $index => $felhasznaltAnyag)
                <div class="anyagMennyisegPár">
                    <select name="Anyag_ID[]" class="anyagSelect">
                        <option value="">Válassz anyagot</option>
                        @foreach ($anyagok as $anyag)
                            <option value="{{ $anyag->Anyag_ID }}"
                                {{ $anyag->Anyag_ID == $felhasznaltAnyag->Anyag_ID ? 'selected' : '' }}>
                                {{ $anyag->Nev }} ({{ $anyag->Mertekegyseg }})
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="Mennyiseg[]" placeholder="Mennyiség"
                        value="{{ $felhasznaltAnyag->Mennyiseg }}" min="1">
                    <button type="button" class="removeAnyag">Eltávolítás</button>
                </div>
            @endforeach
        </div>
        <button type="button" id="addAnyag">Új anyag hozzáadása</button>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Új anyag hozzáadása gomb eseménykezelője
                document.getElementById('addAnyag').addEventListener('click', function() {
                    var container = document.getElementById('anyagokContainer');
                    var newPair = container.firstElementChild.cloneNode(true);
                    newPair.querySelector('.anyagSelect').selectedIndex = 0;
                    newPair.querySelector('input[type=number]').value = '';
                    newPair.querySelector('.removeAnyag').addEventListener('click',
                    removeAnyagFunction); // Hozzáadunk egy új eseménykezelőt az eltávolítás gombhoz
                    container.appendChild(newPair);
                });

                // Eseménykezelő hozzáadása az eltávolítás gombokhoz
                var removeButtons = document.querySelectorAll('.removeAnyag');
                removeButtons.forEach(function(button) {
                    button.addEventListener('click', removeAnyagFunction);
                });

                // Az eltávolítás funkciója
                function removeAnyagFunction(e) {
                    var container = document.getElementById('anyagokContainer');
                    if (container.querySelectorAll('.anyagMennyisegPár').length > 1) {
                        e.target.parentElement.remove();
                    } else {
                        alert('Legalább egy anyagot meg kell adni.');
                    }
                }
            });
        </script>
        <br><br>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </form>
@endsection
