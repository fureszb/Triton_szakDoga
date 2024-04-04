@extends('ujLayout')
@error('error')
    <div class="alert alert-warning">{{ $message }}</div>
@enderror
@section('content')
    @include('breadcrumbs')
    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
    <h1>Megrendelések</h1>
    <hr class="showHr">

    <div class="title">
        <a href="{{ route('megrendeles.index', ['sort_by' => 'Megrendeles_Nev', 'sort_dir' => 'asc']) }}"
            title="Növekvő sorrend">
            <div class="sort">
                <i class="fas fa-sort-alpha-up" src="{{ asset('sort.png') }}"></i>
            </div>
        </a>
        <a href="{{ route('megrendeles.index', ['sort_by' => 'Megrendeles_Nev', 'sort_dir' => 'desc']) }}"
            title="Csökkenő sorrend">
            <div class="sort">
                <i class="fas fa-sort-alpha-down-alt" src="{{ asset('sort.png') }}"></i>
            </div>
        </a>
        <a href="{{ route('megrendeles.create') }}" title="Új megrendelés hozzáadása">

            <i class="fas fa-plus-square"></i>

        </a>
        <form class="kereses" action="{{ route('megrendeles.index') }}" method="GET">
            <input type="text" name="search" placeholder="Keresés név vagy azonosító alapján">
            <button type="submit">Keresés</button>
        </form>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul>
        @foreach ($megrendelesek as $megrendeles)
            <li>
                {{ $megrendeles->Megrendeles_ID }} - {{ $megrendeles->Megrendeles_Nev }}
            </li>
            <a href="{{ route('megrendeles.show', ['id' => $megrendeles->Megrendeles_ID]) }}"
                class="button">Megjelenítés</a>
            <a href="{{ route('megrendeles.edit', ['megrendeles' => $megrendeles->Megrendeles_ID]) }}"
                class="button">Szerkesztés</a>
            <form action="{{ route('megrendeles.destroy', ['megrendeles' => $megrendeles->Megrendeles_ID]) }}"
                method="POST">
                @csrf
                @method('DELETE')
                <button class="torol" type="submit"
                    onclick="return confirm('Biztos törölni kívánja a megrendelést?')">Törlés</button>
            </form>
        @endforeach
    </ul>

    @include('custom_pagination', ['paginator' => $megrendelesek])

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .table-container {
            width: 90%;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .button {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 5px;
        }

        .edit {
            background-color: #f0ad4e;
        }

        .delete {
            background-color: #d9534f;
        }

        .view {
            background-color: #5bc0de;
        }
    </style>
@endsection
