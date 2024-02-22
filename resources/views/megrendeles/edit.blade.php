@extends('layout')

@section('content')
    <h1>Új Megrendelés Szerkesztése</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editForm" action="{{ route('megrendeles.update', $megrendeles->Megrendeles_ID) }}" method="POST">
        @csrf
        @method('PUT')

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
                        {{ $varos->Varos_ID == $megrendeles->Varos_ID ? 'selected' : '' }}>{{ $varos->Nev }}
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

        <!-- A szolgáltatások és szerelők dinamikus kezelése kihívást jelenthet, ezért a példában egyszerűsített logikát alkalmazunk -->
        <fieldset>
            <label for="Szolgaltatas_ID">Szolgáltatás</label>
            <select name="Szolgaltatas_ID" id="Szolgaltatas_ID">
                <option value="">Válassz szolgáltatást</option>
                @foreach ($szolgaltatasok as $szolgaltatas)
                    <option value="{{ $szolgaltatas->Szolgaltatas_ID }}"
                        {{ $szolgaltatas->Szolgaltatas_ID == $megrendeles->Szolgaltatas_ID ? 'selected' : '' }}>
                        {{ $szolgaltatas->Nev }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset>
            <label for="Szerelo_ID">Szerelő</label>
            <select name="Szerelo_ID" id="Szerelo_ID">
                <option value="">Válassz szerelőt</option>
                @foreach ($szerelok as $szerelo)
                    <option value="{{ $szerelo->Szerelo_ID }}"
                        {{ $szerelo->Szerelo_ID == $megrendeles->Szerelo_ID ? 'selected' : '' }}>{{ $szerelo->Nev }}
                    </option>
                @endforeach
            </select>
        </fieldset>

        <fieldset>
            <label for="Leiras">Leírás</label>
            <textarea name="Leiras" id="Leiras">{{ old('Leiras', $megrendeles->Leiras) }}</textarea>
        </fieldset>

        <fieldset>
            <label for="Munkakezdes">Munkakezdés időpontja</label>
            <input type="datetime-local" name="Munkakezdes" id="Munkakezdes"
                value="{{ old('Munkakezdes', date('Y-m-d\TH:i', strtotime($megrendeles->Munkakezdes))) }}">
        </fieldset>

        <fieldset>
            <label for="Munkabefejezes">Munkabefejezés időpontja</label>
            <input type="datetime-local" name="Munkabefejezes" id="Munkabefejezes"
                value="{{ old('Munkabefejezes', date('Y-m-d\TH:i', strtotime($megrendeles->Munkabefejezes))) }}">
        </fieldset>

        <!-- Anyagok kezelésére egy külön szekció lehet szükséges, amely dinamikusan kezeli az anyagok listáját -->

        <button type="submit" class="btn btn-primary">Mentés</button>
    </form>
@endsection
