@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-building"></i> Cégadatok szerkesztése</h1>
    </div>

    @if(session('success'))
        <div class="alert-success" style="
            background:#d1fae5; color:#065f46; border:1px solid #6ee7b7;
            border-radius:8px; padding:12px 16px; margin-bottom:16px;
            font-size:13px; display:flex; align-items:center; gap:8px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('cegadatok.update') }}" method="POST">
        @csrf
        @method('PUT')

        <fieldset>
            <legend>Alapadatok</legend>

            <label for="nev">Cégnév</label>
            <input type="text" name="nev" id="nev"
                   value="{{ old('nev', $cegadat->nev) }}" required>
            @error('nev')<span class="field-error">{{ $message }}</span>@enderror

            <label for="szekhelycim">Székhely és cím</label>
            <input type="text" name="szekhelycim" id="szekhelycim"
                   value="{{ old('szekhelycim', $cegadat->szekhelycim) }}" required>
            @error('szekhelycim')<span class="field-error">{{ $message }}</span>@enderror

            <label for="adoszam">Adószám</label>
            <input type="text" name="adoszam" id="adoszam"
                   value="{{ old('adoszam', $cegadat->adoszam) }}" required
                   placeholder="pl. 12345678-2-42">
            @error('adoszam')<span class="field-error">{{ $message }}</span>@enderror

            <label for="cegjegyzekszam">Cégjegyzékszám</label>
            <input type="text" name="cegjegyzekszam" id="cegjegyzekszam"
                   value="{{ old('cegjegyzekszam', $cegadat->cegjegyzekszam) }}" required
                   placeholder="pl. 01-09-123456">
            @error('cegjegyzekszam')<span class="field-error">{{ $message }}</span>@enderror
        </fieldset>

        <fieldset>
            <legend>Elérhetőségek</legend>

            <label for="telefon">Telefonszám</label>
            <input type="text" name="telefon" id="telefon"
                   value="{{ old('telefon', $cegadat->telefon) }}" required
                   placeholder="pl. +36 1 234 5678">
            @error('telefon')<span class="field-error">{{ $message }}</span>@enderror

            <label for="email">E-mail cím</label>
            <input type="email" name="email" id="email"
                   value="{{ old('email', $cegadat->email) }}" required>
            @error('email')<span class="field-error">{{ $message }}</span>@enderror

            <label for="web">Weboldal <small style="color:#9ca3af;">(opcionális)</small></label>
            <input type="text" name="web" id="web"
                   value="{{ old('web', $cegadat->web) }}"
                   placeholder="pl. www.tritonsecurity.hu">
            @error('web')<span class="field-error">{{ $message }}</span>@enderror
        </fieldset>

        <fieldset>
            <legend>Pénzügyi adatok</legend>

            <label for="bankszamlaszam">Bankszámlaszám <small style="color:#9ca3af;">(opcionális)</small></label>
            <input type="text" name="bankszamlaszam" id="bankszamlaszam"
                   value="{{ old('bankszamlaszam', $cegadat->bankszamlaszam) }}"
                   placeholder="pl. 12345678-12345678-12345678">
            @error('bankszamlaszam')<span class="field-error">{{ $message }}</span>@enderror
        </fieldset>

        <div style="width:100%;">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Mentés
            </button>
        </div>
    </form>
@endsection
