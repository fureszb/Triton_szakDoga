@extends('ujlayout')

@section('content')
    @php
        $user = Auth::user();
    @endphp

    @if ($user->role != 'Ugyfel')
        @include('breadcrumbs')
    @endif

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    @if ($megrendeles)
        <div class="detail-header">
            <h1>
                <i class="fas fa-clipboard-list"></i>
                {{ $megrendeles->Megrendeles_Nev }}
                <small style="font-size:12px;color:#9ca3af;font-weight:400;margin-left:8px;">
                    #{{ $megrendeles->Megrendeles_ID }}
                </small>
            </h1>
            <div class="detail-header-actions">
                @if ($user->role != 'Ugyfel')
                    <a href="{{ route('megrendeles.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Vissza
                    </a>
                    <a href="{{ route('megrendeles.edit', ['megrendeles' => $megrendeles->Megrendeles_ID]) }}" class="btn-edit">
                        <i class="fas fa-edit"></i> Szerkesztés
                    </a>
                @endif
                <div class="pdf" style="margin:0;">
                    <a href="{{ url('/download-pdf/' . $megrendeles->ugyfel->Ugyfel_ID . '_' . rawurlencode($megrendeles->ugyfel->Nev) . '_' . $munka->szolgaltatas->Szolgaltatas_ID) . '_' . $megrendeles->Megrendeles_ID }}"
                       target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF Letöltése
                    </a>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <div class="detail-section-label">Általános információk</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Ügyfél neve</div>
                    <div class="info-value">{{ $megrendeles->ugyfel->Nev ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Megrendelő neve</div>
                    <div class="info-value">{{ $megrendeles->Megrendeles_Nev }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Helyszín</div>
                    <div class="info-value">
                        {{ $megrendeles->varos->Irny_szam }} {{ $megrendeles->varos->Nev }},
                        {{ $megrendeles->Utca_Hazszam }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Státusz</div>
                    <div class="info-value">
                        @if($megrendeles->Alairt_e)
                            <span class="badge badge-active">Folyamatban</span>
                        @else
                            <span class="badge badge-done">Befejezve</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @foreach ($munkak as $munka)
            <div class="detail-section">
                <div class="detail-section-label">Munka &amp; Szerelő</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Szolgáltatás</div>
                        <div class="info-value">{{ $munka->szolgaltatas->Tipus ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Szerelő neve</div>
                        <div class="info-value">{{ $munka->szerelo->Nev ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Szerelő telefonszáma</div>
                        <div class="info-value">{{ $munka->szerelo->Telefonszam ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Munkakezdés</div>
                        <div class="info-value">{{ $munka->Munkakezdes_Idopontja }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Munkabefejezés</div>
                        <div class="info-value">{{ $munka->Munkabefejezes_Idopontja }}</div>
                    </div>
                    @if($munka->Leiras)
                        <div class="info-item full-width">
                            <div class="info-label">Munka leírása</div>
                            <div class="info-value">{{ $munka->Leiras }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="detail-section">
            <div class="detail-section-label">Felhasznált anyagok</div>
            @if ($felhasznaltAnyagok && count($felhasznaltAnyagok) > 0)
                <div class="info-grid">
                    @foreach ($felhasznaltAnyagok as $anyag)
                        <div class="info-item">
                            <div class="info-label">{{ $anyag->anyag->Nev }} ({{ $anyag->anyag->Mertekegyseg }})</div>
                            <div class="info-value">{{ $anyag->Mennyiseg }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#9ca3af;font-size:13px;">Nincsenek felhasznált anyagok rögzítve.</p>
            @endif
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-clipboard-list"></i>
            <p>A megrendelés nem található.</p>
        </div>
    @endif
@endsection
