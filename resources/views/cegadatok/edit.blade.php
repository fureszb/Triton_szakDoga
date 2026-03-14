@extends('ujlayout')

@section('content')
@include('breadcrumbs')

<div class="page-header">
    <h1><i class="fas fa-building"></i> Cégadatok szerkesztése</h1>
</div>

@if(session('success'))
    <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:10px;margin-bottom:20px;font-size:13px;color:#065f46;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div style="margin-bottom:16px;">
        @foreach ($errors->all() as $error)
            <div class="alert alert-warning" style="margin-bottom:6px;">
                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
            </div>
        @endforeach
    </div>
@endif

<form action="{{ route('cegadatok.update') }}" method="POST">
@csrf
@method('PUT')

<div class="fc-grid">

    {{-- Alapadatok --}}
    <div class="fc-card">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-building"></i></div>
            <div class="fc-htitle">Alapadatok</div>
        </div>
        <div class="fc-body">
            <div class="f-group">
                <div class="f-label"><i class="fas fa-signature"></i> Cégnév <span class="req">*</span></div>
                <input type="text" name="nev" class="f-input"
                       value="{{ old('nev', $cegadat->nev) }}" required
                       placeholder="pl. Triton Security Kft.">
                @error('nev')<div class="f-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-map-marker-alt"></i> Székhely és cím <span class="req">*</span></div>
                <input type="text" name="szekhelycim" class="f-input"
                       value="{{ old('szekhelycim', $cegadat->szekhelycim) }}" required
                       placeholder="pl. 1234 Budapest, Minta utca 1.">
                @error('szekhelycim')<div class="f-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
            </div>
            <div class="fc-row">
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-receipt"></i> Adószám <span class="req">*</span></div>
                    <input type="text" name="adoszam" class="f-input"
                           value="{{ old('adoszam', $cegadat->adoszam) }}" required
                           placeholder="12345678-2-42">
                    @error('adoszam')<div class="f-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
                </div>
                <div class="f-group">
                    <div class="f-label"><i class="fas fa-file-alt"></i> Cégjegyzékszám <span class="req">*</span></div>
                    <input type="text" name="cegjegyzekszam" class="f-input"
                           value="{{ old('cegjegyzekszam', $cegadat->cegjegyzekszam) }}" required
                           placeholder="01-09-123456">
                    @error('cegjegyzekszam')<div class="f-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Elérhetőségek --}}
    <div class="fc-card">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-address-book"></i></div>
            <div class="fc-htitle">Elérhetőségek</div>
        </div>
        <div class="fc-body">
            <div class="f-group">
                <div class="f-label"><i class="fas fa-phone"></i> Telefonszám <span class="req">*</span></div>
                <input type="text" name="telefon" class="f-input"
                       value="{{ old('telefon', $cegadat->telefon) }}" required
                       placeholder="+36 1 234 5678">
                @error('telefon')<div class="f-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-envelope"></i> E-mail cím <span class="req">*</span></div>
                <input type="email" name="email" class="f-input"
                       value="{{ old('email', $cegadat->email) }}" required
                       placeholder="info@tritonsecurity.hu">
                @error('email')<div class="f-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
            </div>
            <div class="f-group">
                <div class="f-label"><i class="fas fa-globe"></i> Weboldal <span style="font-weight:400;color:#94a3b8;">(opcionális)</span></div>
                <input type="text" name="web" class="f-input"
                       value="{{ old('web', $cegadat->web) }}"
                       placeholder="www.tritonsecurity.hu">
                @error('web')<div class="f-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Pénzügyi adatok --}}
    <div class="fc-card fc-full">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-university"></i></div>
            <div class="fc-htitle">Pénzügyi adatok</div>
        </div>
        <div class="fc-body">
            <div class="f-group" style="max-width:480px;">
                <div class="f-label"><i class="fas fa-credit-card"></i> Bankszámlaszám <span style="font-weight:400;color:#94a3b8;">(opcionális)</span></div>
                <input type="text" name="bankszamlaszam" class="f-input"
                       value="{{ old('bankszamlaszam', $cegadat->bankszamlaszam) }}"
                       placeholder="12345678-12345678-12345678">
                @error('bankszamlaszam')<div class="f-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

</div>

<div class="fc-submit">
    <button type="submit" class="btn-save" style="margin-top:0;">
        <i class="fas fa-save"></i> Mentés
    </button>
</div>

</form>
@endsection
