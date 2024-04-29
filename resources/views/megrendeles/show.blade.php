@extends('ujlayout')

@section('content')

    @php
        $user = Auth::user();
    @endphp

    @if ($user->role != 'Ugyfel')
        @include('breadcrumbs')
    @endif




    @if ($megrendeles)
        <h1>{{ $megrendeles->Megrendeles_ID }} - {{ $megrendeles->Megrendeles_Nev }} - Megrendelés részletei</h1>
        <hr class="showHr">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3>Általános információk</h3>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Ügyfél neve:</strong> {{ $megrendeles->ugyfel->Nev ?? '-' }}</li>
                        <li class="list-group-item"><strong>Megrendelő neve:</strong> {{ $megrendeles->Megrendeles_Nev }}
                        </li>
                        <li class="list-group-item"><strong>Címe:</strong> {{ $megrendeles->varos->Irny_szam }}
                            {{ $megrendeles->varos->Nev }}, {{ $megrendeles->Utca_Hazszam }}
                        </li>
                        </li>
                        <li class="list-group-item"><strong>PDF Elérési Út:</strong>
                            {{ $megrendeles->Pdf_EleresiUt ?? '-' }}</li>
                        @foreach ($munkak as $munka)
                            <li class="list-group-item"><strong>Szolgáltatás:</strong>
                                {{ $munka->szolgaltatas->Tipus ?? '-' }}</li>
                            <li class="list-group-item"><strong>Szerelő:</strong> {{ $munka->szerelo->Nev ?? '-' }}</li>
                            <li class="list-group-item"><strong>Leírás:</strong> {{ $munka->Leiras }}</li>
                            <li class="list-group-item"><strong>Munkakezdés időpontja:</strong>
                                {{ $munka->Munkakezdes_Idopontja }}</li>
                            <li class="list-group-item"><strong>Munkabefejezés időpontja:</strong>
                                {{ $munka->Munkabefejezes_Idopontja }}</li>
                        @endforeach
                        <li class="list-group-item"><strong>Státusz:</strong>
                            {{ $megrendeles->Alairt_e ? 'Folyamatban' : 'Befejezve' }}
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3>Felhasznált anyagok</h3>
                    @if ($felhasznaltAnyagok && count($felhasznaltAnyagok) > 0)
                        <ul class="list-group">
                            @foreach ($felhasznaltAnyagok as $anyag)
                                <li class="list-group-item">{{ $anyag->anyag->Nev }}({{ $anyag->anyag->Mertekegyseg }}):
                                    {{ $anyag->Mennyiseg }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Nincsenek felhasznált anyagok rögzítve.</p>
                    @endif
                </div>
                <div class="pdf">
                    <a href="{{ url('/download-pdf/' . $megrendeles->ugyfel->Ugyfel_ID . '_' . rawurlencode($megrendeles->ugyfel->Nev) . '_' . $munka->szolgaltatas->Szolgaltatas_ID) . '_' . $megrendeles->Megrendeles_ID }}"
                        target="_blank">PDF Letöltése</a>
                </div>
            </div>
            @if ($user->role != 'Ugyfel')
                <a href="{{ route('megrendeles.edit', ['megrendeles' => $megrendeles->Megrendeles_ID]) }}"><button>
                        Szerkesztés</button></a>
            @endif
        </div>
    @else
        <p>A megrendelés nem található.</p>
    @endif
@endsection
