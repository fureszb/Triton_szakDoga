@extends('alkalmazas')

@section('main')

    <div class="container mt-5">

        <h1 class="mb-4">Új ügyfél</h1>

        @if ($errors->any())
            <div class="alert alert-warning">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form id="createForm" action="{{ route('ugyfel.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="row mb-3">
                <div class="col">
                    <label for="Ugyfel_ID" class="form-label">ID</label>
                    <input type="text" class="form-control" name="Ugyfel_ID" id="Ugyfel_ID"
                        value="{{ old('Ugyfel_ID') }}" required>
                </div>
                <div class="col">
                    <label for="nev" class="form-label">Név</label>
                    <input type="text" class="form-control" name="nev" id="nev" value="{{ old('nev') }}"
                        required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}"
                        required>
                </div>
                <div class="col">
                    <label for="telefon" class="form-label">Telefonszám</label>
                    <input type="text" class="form-control" name="telefon" id="telefon" value="{{ old('telefon') }}"
                        required>
                </div>
            </div>

            <!-- Repeat this row for other pairs of fields -->

            <div class="mb-3">
                <label for="Varos_ID" class="form-label">Város</label>
                <select class="form-select" name="Varos_ID" id="Varos_ID" required>
                    <option value="">Válassz várost</option>
                    @foreach ($varosok as $varos)
                        <option value="{{ $varos->Varos_ID }}" {{ old('Varos_ID') == $varos->Varos_ID ? 'selected' : '' }}>
                            {{ $varos->Irny_szam }} {{ $varos->Nev }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="szamnev" class="form-label">Számlázási név</label>
                    <input type="text" class="form-control" name="szamnev" id="szamnev" value="{{ old('szamnev') }}"
                        required>
                </div>
                <div class="col">
                    <label for="szamcim" class="form-label">Utca, házszám</label>
                    <input type="text" class="form-control" name="szamcim" id="szamcim" value="{{ old('szamcim') }}"
                        required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="adoszam" class="form-label">Adószám</label>
                    <input type="text" class="form-control" name="adoszam" id="adoszam" value="{{ old('adoszam') }}"
                        required>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <button id="saveButton" class="btn btn-primary" type="submit">Mentés új megrendelésként</button>
                </div>
            </div>
        </form>
    </div>

@endsection
