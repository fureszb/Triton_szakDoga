@extends('ujlayout')

@section('content')
@include('breadcrumbs')

@if ($szerelo)

<div class="sc-page-header">
    <div class="sc-page-header-left">
        <h1><i class="fas fa-hard-hat"></i> {{ $szerelo->Nev }}</h1>
        <span class="sc-id-badge">#{{ $szerelo->Szerelo_ID }}</span>
    </div>
    <div class="sc-page-header-actions">
        <a href="{{ route('szerelok.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Vissza
        </a>
        <a href="{{ route('szerelok.edit', $szerelo->Szerelo_ID) }}" class="btn-edit">
            <i class="fas fa-edit"></i> Szerkesztés
        </a>
    </div>
</div>

<div class="sc-grid">

    <div class="sc-card">
        <div class="sc-card-header">
            <div class="sc-hicon"><i class="fas fa-user"></i></div>
            <div class="sc-htitle">Alapadatok</div>
        </div>
        <div class="sc-rows">
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-user"></i> Név</div>
                <div class="sc-val">{{ $szerelo->Nev }}</div>
            </div>
            <div class="sc-row">
                <div class="sc-lbl"><i class="fas fa-phone"></i> Telefon</div>
                <div class="sc-val">{{ $szerelo->Telefonszam }}</div>
            </div>
        </div>
    </div>

    <div class="sc-card">
        <div class="sc-card-header">
            <div class="sc-hicon"><i class="fas fa-cogs"></i></div>
            <div class="sc-htitle">Szakterületek</div>
        </div>
        @if($szerelo->szolgaltatasok && count($szerelo->szolgaltatasok) > 0)
            <div class="sc-tag-list">
                @foreach ($szerelo->szolgaltatasok as $szo)
                    <div class="sc-tag">
                        <i class="fas fa-wifi"></i>
                        {{ $szo->Tipus }}
                    </div>
                @endforeach
            </div>
        @else
            <div style="padding:20px 18px;color:#94a3b8;font-size:13px;">
                <i class="fas fa-info-circle" style="margin-right:6px;"></i>
                Nincs hozzárendelt szakterület.
            </div>
        @endif
    </div>

</div>

@else
    <div class="empty-state">
        <i class="fas fa-tools"></i>
        <p>A szerelő nem található.</p>
    </div>
@endif
@endsection
