@extends('layout')

@section('content')
@if ($megrendeles)
<h1>{{ $megrendeles->Megrendeles_Nev }} - Megrendelés részletek</h1>
@else
<p>A megrendelés nem található.</p>
@endif
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
            </ul>
        </div>
    </div>
</div>

@endsection
