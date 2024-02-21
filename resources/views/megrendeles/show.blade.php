@extends('layout')

@section('content')
@if ($megrendeles)
<h1>{{ $megrendeles->Megrendeles_Nev }} - Megrendelés részletek</h1>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3>Általános információk</h3>
            <ul class="list-group">
                <li class="list-group-item"><strong>Megrendeles ID:</strong> {{ $megrendeles->Megrendeles_ID }}</li>
                <li class="list-group-item"><strong>Megrendeles Nev:</strong> {{ $megrendeles->Megrendeles_Nev }}</li>
                <li class="list-group-item"><strong>Utca, házszám:</strong> {{ $megrendeles->Utca_Hazszam }}</li>
                <li class="list-group-item"><strong>Alairt-e:</strong> {{ $megrendeles->Alairt_e ? 'Befejezve' : 'Folyamatban' }}</li>
                <li class="list-group-item"><strong>Pdf Elérési Út:</strong> {{ $megrendeles->Pdf_EleresiUt ?? '-' }}</li>
                {{-- Itt jönnek a további mezők --}}
                <li class="list-group-item"><strong>Ugyfel:</strong> {{ $megrendeles->ugyfel->Nev ?? '-' }}</li>
                <li class="list-group-item"><strong>Szolgaltatas:</strong> {{ $megrendeles->szolgaltatas->Tipus ?? '-' }}</li>
                <li class="list-group-item"><strong>Szerelo:</strong> {{ $megrendeles->szerelo->Nev ?? '-' }}</li>
                <li class="list-group-item"><strong>Leiras:</strong> {{ $megrendeles->Leiras ?? '-' }}</li>
                <li class="list-group-item"><strong>Munkakezdes Idopontja:</strong> {{ $megrendeles->Munkakezdes_Idopontja ?? '-' }}</li>
                <li class="list-group-item"><strong>Munkabefejezes Idopontja:</strong> {{ $megrendeles->Munkabefejezes_Idopontja ?? '-' }}</li>
                {{-- Felhasznált anyagok listája --}}
                @if($megrendeles->felhasznaltAnyagok && count($megrendeles->felhasznaltAnyagok) > 0)
                    <li class="list-group-item">
                        <strong>Felhasznált Anyagok:</strong>
                        <ul>
                            @foreach($megrendeles->felhasznaltAnyagok as $anyag)
                                <li>{{ $anyag->anyag->Leiras }}: {{ $anyag->Mennyiseg }}</li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@else
<p>A megrendelés nem található.</p>
@endif
@endsection
