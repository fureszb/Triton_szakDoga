@extends('ujlayout')

@section('content')
    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
    <div class="megrendeles">
        <h1>Megrendeléseim</h1>
        <hr class="showHr">
    </div>
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @elseif (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($megrendelesek && count($megrendelesek) > 0)
        @foreach ($megrendelesek as $megrendeles)
            <ul>
                <li>
                    {{ $megrendeles->Megrendeles_ID }} - {{ $megrendeles->Megrendeles_Nev }} -
                    @foreach ($megrendeles->munkak as $munka)
                        @if ($loop->first)
                            {{ $munka->szolgaltatas->Tipus }}
                        @endif
                    @endforeach
                </li>
                <a href="{{ route('megrendeles.show', ['id' => $megrendeles->Megrendeles_ID]) }}"
                    class="button">Megjelenítés</a>
            </ul>
        @endforeach
    @else
        <p>Nincsenek megrendeléseid.</p>
    @endif


@endsection
