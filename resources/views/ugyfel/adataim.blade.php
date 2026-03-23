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
                    <span>Ügyfélszám: <strong>{{ $ugyfel->id }}</strong></span>
                </div>
                <div class="adat-netfone-row">
                    <i class="fas fa-id-card adat-netfone-icon"></i>
                    <span>Teljes név: <strong>{{ $ugyfel->nev }}</strong></span>
                </div>
                <div class="adat-netfone-row">
                    <i class="fas fa-map-marker-alt adat-netfone-icon"></i>
                    <span>Város: <strong>{{ $ugyfel->varos->nev ?? '—' }}</strong></span>
                </div>
                <div class="adat-netfone-divider"></div>
                <div class="adat-netfone-row">
                    <i class="fas fa-file-invoice adat-netfone-icon"></i>
                    <span>Számlázási név: <strong>{{ $ugyfel->szamlazasi_nev ?? '—' }}</strong></span>
                </div>
                <div class="adat-netfone-row">
                    <i class="fas fa-home adat-netfone-icon"></i>
                    <span>Számlázási cím: <strong>{{ $ugyfel->szamlazasi_cim ?? '—' }}</strong></span>
                </div>
                @if($ugyfel->adoszam)
                <div class="adat-netfone-row">
                    <i class="fas fa-receipt adat-netfone-icon"></i>
                    <span>Adószám: <strong>{{ $ugyfel->adoszam }}</strong></span>
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
                        <input type="text" name="nev" class="adat-edit-input"
                               value="{{ old('nev', $ugyfel->nev) }}" required>
                    </div>
                    <div class="adat-edit-group">
                        <label class="adat-edit-label">Város</label>
                        <select name="varos_id" id="varos-select-adataim" class="adat-edit-input varos-select">
                            <option value="">— Keressen irányítószámra vagy városra —</option>
                            @foreach($varosok as $v)
                                <option value="{{ $v->id }}"
                                    {{ old('varos_id', $ugyfel->varos_id) == $v->id ? 'selected' : '' }}>
                                    {{ $v->Irny_szam }} {{ $v->nev }}
                                </option>
                            @endforeach
                        </select>
                        <div class="varos-ujvaros-panel" data-for="varos-select-adataim" style="display:none;">
                            <div class="varos-ujvaros-row">
                                <input type="text" class="varos-uj-irsz adat-edit-input" placeholder="Irányítószám" maxlength="4" style="width:120px;">
                                <input type="text" class="varos-uj-nev adat-edit-input" placeholder="Város neve" style="flex:1;">
                                <button type="button" class="varos-uj-mentes btn btn-sm btn-primary">Mentés</button>
                                <button type="button" class="varos-uj-megsem btn btn-sm btn-secondary">Mégse</button>
                            </div>
                            <div class="varos-uj-hiba" style="display:none; color:#c0392b; font-size:.85em; margin-top:4px;"></div>
                        </div>
                    </div>
                    <div class="adat-edit-group">
                        <label class="adat-edit-label">Számlázási név</label>
                        <input type="text" name="szamlazasi_nev" class="adat-edit-input"
                               value="{{ old('szamlazasi_nev', $ugyfel->szamlazasi_nev) }}" required>
                    </div>
                    <div class="adat-edit-group">
                        <label class="adat-edit-label">Számlázási cím</label>
                        <input type="text" name="szamlazasi_cim" class="adat-edit-input"
                               value="{{ old('szamlazasi_cim', $ugyfel->szamlazasi_cim) }}" required>
                    </div>
                    <div class="adat-edit-group">
                        <label class="adat-edit-label">
                            Adószám
                            <span style="font-weight:400;color:#8b949e;font-size:12px;">(opcionális)</span>
                        </label>
                        <input type="text" name="adoszam" class="adat-edit-input"
                               value="{{ old('adoszam', $ugyfel->adoszam) }}"
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
                    <span>Email cím: <strong>{{ $ugyfel->email }}</strong></span>
                </div>
                <div class="adat-netfone-row">
                    <i class="fas fa-phone adat-netfone-icon"></i>
                    <span>Telefonszám: <strong>{{ $ugyfel->telefonszam ?? '—' }}</strong></span>
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
                               value="{{ $ugyfel->email }}" disabled>
                        <span class="adat-edit-hint">
                            <i class="fas fa-info-circle"></i>
                            Az email cím módosításához keresd fel ügyfélszolgálatunkat.
                        </span>
                    </div>
                    <div class="adat-edit-group">
                        <label class="adat-edit-label">Telefonszám</label>
                        <input type="text" name="telefonszam" class="adat-edit-input"
                               value="{{ old('telefonszam', $ugyfel->telefonszam) }}"
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
