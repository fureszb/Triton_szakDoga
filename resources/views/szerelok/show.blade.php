@extends('ujlayout')

@section('content')

@include('breadcrumbs')

<style>
    .list-group li {
    width: 100% !important;
}
</style>
@if ($szerelo)
    <h1>{{$szerelo->Nev}} - Részletei</h1>
    <hr class="showHr">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Általános információk</h3>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Név:</strong> {{ $szerelo->Nev }}</li>
                    <li class="list-group-item"><strong>Telefonszám:</strong> {{ $szerelo->Telefonszam }}</li>
                    <li class="list-group-item"><strong>Szolgáltatások:</strong>
                        <ul>
                            @foreach($szerelo->szolgaltatasok as $szolgaltatas)
                                <li>{{ $szolgaltatas->Tipus }}</li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('szerelok.index') }}" class="btn btn-primary">Vissza a listához</a>
            </div>
        </div>
    </div>
@else
    <p>A szerelő nem található.</p>
@endif

@endsection
