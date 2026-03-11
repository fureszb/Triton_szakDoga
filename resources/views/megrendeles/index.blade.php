@extends('ujLayout')
@error('error')
    <div class="alert alert-warning">{{ $message }}</div>
@enderror
@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-clipboard-list"></i> Megrendelések</h1>
    </div>

    <div class="page-toolbar">
        <a href="{{ route('megrendeles.index', ['sort_by' => 'Megrendeles_Nev', 'sort_dir' => 'asc']) }}"
           class="sort-btn" title="Növekvő sorrend">
            <i class="fas fa-sort-alpha-up"></i>
        </a>
        <a href="{{ route('megrendeles.index', ['sort_by' => 'Megrendeles_Nev', 'sort_dir' => 'desc']) }}"
           class="sort-btn" title="Csökkenő sorrend">
            <i class="fas fa-sort-alpha-down-alt"></i>
        </a>
        <a href="{{ route('megrendeles.create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Új megrendelés
        </a>
        <form class="search-form-inline" action="{{ route('megrendeles.index') }}" method="GET">
            <input type="text" name="search" placeholder="Keresés név vagy azonosító alapján"
                   value="{{ request('search') }}">
            <button type="submit"><i class="fas fa-search"></i> Keresés</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Megrendelő neve</th>
                <th>Ügyfél</th>
                <th>Helyszín</th>
                <th>Státusz</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($megrendelesek as $megrendeles)
                <tr>
                    <td><span class="table-id">#{{ $megrendeles->Megrendeles_ID }}</span></td>
                    <td><strong>{{ $megrendeles->Megrendeles_Nev }}</strong></td>
                    <td>{{ $megrendeles->ugyfel->Nev ?? '-' }}</td>
                    <td>
                        @if($megrendeles->varos)
                            {{ $megrendeles->varos->Irny_szam }} {{ $megrendeles->varos->Nev }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($megrendeles->Alairt_e)
                            <span class="badge badge-active">Folyamatban</span>
                        @else
                            <span class="badge badge-done">Befejezve</span>
                        @endif
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('megrendeles.show', ['id' => $megrendeles->Megrendeles_ID]) }}" class="btn-view">
                                <i class="fas fa-eye"></i> Megtekint
                            </a>
                            <a href="{{ route('megrendeles.edit', ['megrendeles' => $megrendeles->Megrendeles_ID]) }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Szerkeszt
                            </a>
                            <form class="form-delete" action="{{ route('megrendeles.destroy', ['megrendeles' => $megrendeles->Megrendeles_ID]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" type="submit"
                                    onclick="return confirm('Biztos törölni kívánja a megrendelést?')">
                                    <i class="fas fa-trash-alt"></i> Töröl
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Nincsenek rögzített megrendelések.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @include('custom_pagination', ['paginator' => $megrendelesek])
@endsection
