@extends('ujlayout')

@section('content')

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom:16px;"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-warning" style="margin-bottom:16px;">
            @foreach ($errors->all() as $error)
                <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Tab navigation --}}
    <div class="adat-tabs">
        <button class="adat-tab active" id="tab-btn-szemelyes" onclick="switchAdatTab('szemelyes')">
            <i class="fas fa-user"></i> Személyes adatok
        </button>
        <button class="adat-tab" id="tab-btn-kapcsolat" onclick="switchAdatTab('kapcsolat')">
            <i class="fas fa-address-book"></i> Kapcsolattartók
        </button>
    </div>

    {{-- =========================================================
         SZEMÉLYES ADATOK PANEL
    ========================================================== --}}
    <div class="adat-panel" id="panel-szemelyes">
        <div class="adat-netfone-card">
            <div class="adat-netfone-header">
                <div class="adat-netfone-title">Személyes adatok</div>
                <div class="adat-netfone-desc">
                    Itt láthatod a Triton Security rendszerében nyilvántartott személyes adataidat.
                    Bizonyos adatokat módosíthatsz a „Módosítom" gomb megnyomásával.
                </div>
            </div>

            <div class="adat-netfone-divider"></div>

            {{-- VIEW MODE --}}
            <div id="szemelyes-view">
                <div class="adat-netfone-row">
                    <i class="fas fa-hashtag adat-netfone-icon"></i>
                    <span>Ügyfélszám: <strong>{{ $ugyfel->Ugyfel_ID }}</strong></span>
                </div>
                <div class="adat-netfone-row">
                    <i class="fas fa-id-card adat-netfone-icon"></i>
                    <span>Teljes név: <strong>{{ $ugyfel->Nev }}</strong></span>
                </div>
                <div class="adat-netfone-row">
                    <i class="fas fa-map-marker-alt adat-netfone-icon"></i>
                    <span>Város: <strong>{{ $ugyfel->varos->Nev ?? '—' }}</strong></span>
                </div>
                <div class="adat-netfone-divider"></div>
                <div class="adat-netfone-row">
                    <i class="fas fa-file-invoice adat-netfone-icon"></i>
                    <span>Számlázási név: <strong>{{ $ugyfel->Szamlazasi_Nev ?? '—' }}</strong></span>
                </div>
                <div class="adat-netfone-row">
                    <i class="fas fa-home adat-netfone-icon"></i>
                    <span>Számlázási cím: <strong>{{ $ugyfel->Szamlazasi_Cim ?? '—' }}</strong></span>
                </div>
                @if($ugyfel->Adoszam)
                <div class="adat-netfone-row">
                    <i class="fas fa-receipt adat-netfone-icon"></i>
                    <span>Adószám: <strong>{{ $ugyfel->Adoszam }}</strong></span>
                </div>
                @endif

                <div class="adat-netfone-actions">
                    <button class="btn-modositom" onclick="showAdatEdit('szemelyes')">
                        <i class="fas fa-edit"></i> Módosítom
                    </button>
                </div>
            </div>

            {{-- EDIT MODE --}}
            <div id="szemelyes-edit" style="display:none">
                <form method="POST" action="{{ route('ugyfel.adataim.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="section" value="szemelyes">

                    <div class="adat-edit-group">
                        <label class="adat-edit-label">Teljes név</label>
                        <input type="text" name="Nev" class="adat-edit-input"
                               value="{{ old('Nev', $ugyfel->Nev) }}" required>
                    </div>
                    <div class="adat-edit-group">
                        <label class="adat-edit-label">Város</label>
                        <select name="Varos_ID" class="adat-edit-input">
                            @foreach($varosok as $v)
                                <option value="{{ $v->Varos_ID }}"
                                    {{ old('Varos_ID', $ugyfel->Varos_ID) == $v->Varos_ID ? 'selected' : '' }}>
                                    {{ $v->Irny_szam }} {{ $v->Nev }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="adat-edit-group">
                        <label class="adat-edit-label">Számlázási név</label>
                        <input type="text" name="Szamlazasi_Nev" class="adat-edit-input"
                               value="{{ old('Szamlazasi_Nev', $ugyfel->Szamlazasi_Nev) }}" required>
                    </div>
                    <div class="adat-edit-group">
                        <label class="adat-edit-label">Számlázási cím</label>
                        <input type="text" name="Szamlazasi_Cim" class="adat-edit-input"
                               value="{{ old('Szamlazasi_Cim', $ugyfel->Szamlazasi_Cim) }}" required>
                    </div>
                    <div class="adat-edit-group">
                        <label class="adat-edit-label">
                            Adószám
                            <span style="font-weight:400;color:#8b949e;font-size:12px;">(opcionális)</span>
                        </label>
                        <input type="text" name="Adoszam" class="adat-edit-input"
                               value="{{ old('Adoszam', $ugyfel->Adoszam) }}"
                               placeholder="pl. 12345678-1-01">
                    </div>

                    <div class="adat-netfone-actions" style="gap:10px; flex-wrap:wrap;">
                        <button type="submit" class="btn-modositom">
                            <i class="fas fa-save"></i> Mentés
                        </button>
                        <button type="button" class="btn-megse" onclick="hideAdatEdit('szemelyes')">
                            <i class="fas fa-times"></i> Mégse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =========================================================
         KAPCSOLATTARTÓK PANEL
    ========================================================== --}}
    <div class="adat-panel" id="panel-kapcsolat" style="display:none">
        <div class="adat-netfone-card">
            <div class="adat-netfone-header">
                <div class="adat-netfone-title">Kapcsolattartók</div>
                <div class="adat-netfone-desc">
                    Az elérhetőségi adataid, melyeken fel tudunk veled venni a kapcsolatot.
                </div>
            </div>

            <div class="adat-netfone-divider"></div>

            {{-- VIEW MODE --}}
            <div id="kapcsolat-view">
                <div class="adat-netfone-row">
                    <i class="fas fa-envelope adat-netfone-icon"></i>
                    <span>Email cím: <strong>{{ $ugyfel->Email }}</strong></span>
                </div>
                <div class="adat-netfone-row">
                    <i class="fas fa-phone adat-netfone-icon"></i>
                    <span>Telefonszám: <strong>{{ $ugyfel->Telefonszam ?? '—' }}</strong></span>
                </div>

                <div class="adat-netfone-actions">
                    <button class="btn-modositom" onclick="showAdatEdit('kapcsolat')">
                        <i class="fas fa-edit"></i> Módosítom
                    </button>
                </div>
            </div>

            {{-- EDIT MODE --}}
            <div id="kapcsolat-edit" style="display:none">
                <form method="POST" action="{{ route('ugyfel.adataim.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="section" value="kapcsolat">

                    <div class="adat-edit-group">
                        <label class="adat-edit-label">Email cím</label>
                        <input type="email" class="adat-edit-input adat-edit-disabled"
                               value="{{ $ugyfel->Email }}" disabled>
                        <span class="adat-edit-hint">
                            <i class="fas fa-info-circle"></i>
                            Az email cím módosításához keresd fel ügyfélszolgálatunkat.
                        </span>
                    </div>
                    <div class="adat-edit-group">
                        <label class="adat-edit-label">Telefonszám</label>
                        <input type="text" name="Telefonszam" class="adat-edit-input"
                               value="{{ old('Telefonszam', $ugyfel->Telefonszam) }}"
                               placeholder="+36301234567" required>
                    </div>

                    <div class="adat-netfone-actions" style="gap:10px; flex-wrap:wrap;">
                        <button type="submit" class="btn-modositom">
                            <i class="fas fa-save"></i> Mentés
                        </button>
                        <button type="button" class="btn-megse" onclick="hideAdatEdit('kapcsolat')">
                            <i class="fas fa-times"></i> Mégse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
function switchAdatTab(tab) {
    document.querySelectorAll('.adat-tab').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.adat-panel').forEach(p => p.style.display = 'none');
    document.getElementById('tab-btn-' + tab).classList.add('active');
    document.getElementById('panel-' + tab).style.display = 'block';
}
function showAdatEdit(section) {
    document.getElementById(section + '-view').style.display = 'none';
    document.getElementById(section + '-edit').style.display = 'block';
}
function hideAdatEdit(section) {
    document.getElementById(section + '-view').style.display = 'block';
    document.getElementById(section + '-edit').style.display = 'none';
}
@if($errors->any() && old('section'))
document.addEventListener('DOMContentLoaded', function() {
    switchAdatTab('{{ old('section') }}');
    showAdatEdit('{{ old('section') }}');
});
@endif
</script>

@endsection
