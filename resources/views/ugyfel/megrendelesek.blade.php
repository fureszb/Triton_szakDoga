@extends('ujlayout')

@section('content')
    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-clipboard-check"></i> Megrendeléseim</h1>
    </div>

    @if (session('info'))
        <div class="alert alert-warning">{{ session('info') }}</div>
    @elseif (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($megrendelesek && count($megrendelesek) > 0)
        <div class="order-cards">
            @foreach ($megrendelesek as $megrendeles)
                <div class="order-card">
                    <div class="order-card-meta">
                        <div class="order-card-id">#{{ $megrendeles->Megrendeles_ID }}</div>
                        <div class="order-card-name">{{ $megrendeles->Megrendeles_Nev }}</div>
                        <div class="order-card-service">
                            <i class="fas fa-wifi"></i>
                            @foreach ($megrendeles->munkak as $munka)
                                @if ($loop->first)
                                    {{ $munka->szolgaltatas->Tipus }}
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div>
                        @if($megrendeles->Alairt_e)
                            <span class="badge badge-active" style="margin-bottom:8px;display:block;text-align:center;">Folyamatban</span>
                        @else
                            <span class="badge badge-done" style="margin-bottom:8px;display:block;text-align:center;">Befejezve</span>
                        @endif
                        <a href="{{ route('megrendeles.show', ['id' => $megrendeles->Megrendeles_ID]) }}"
                           class="btn-view">
                            <i class="fas fa-eye"></i> Részletek
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state" style="margin-top:40px;">
            <i class="fas fa-clipboard-check"></i>
            <p>Nincsenek megrendeléseid.</p>
        </div>
    @endif
@endsection
