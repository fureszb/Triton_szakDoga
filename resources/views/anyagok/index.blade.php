@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-boxes"></i> Anyagok</h1>
    </div>

    <div class="page-toolbar">
        <a href="{{ route('anyagok.index', ['sort_by' => 'Anyag_ID', 'sort_dir' => 'asc']) }}" class="sort-btn" title="Növekvő sorrend">
            <i class="fas fa-sort-alpha-up"></i>
        </a>
        <a href="{{ route('anyagok.index', ['sort_by' => 'Anyag_ID', 'sort_dir' => 'desc']) }}" class="sort-btn" title="Csökkenő sorrend">
            <i class="fas fa-sort-alpha-down-alt"></i>
        </a>
        <a href="{{ route('anyagok.create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Új anyag
        </a>
        <form class="search-form-inline" action="{{ route('anyagok.index') }}" method="GET">
            <input type="text" name="search" placeholder="Keresés név vagy azonosító alapján"
                   value="{{ request('search') }}">
            <button type="submit"><i class="fas fa-search"></i> Keresés</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Anyag neve</th>
                <th>Mértékegység</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($anyagok as $anyag)
                <tr>
                    <td><span class="table-id">#{{ $anyag->Anyag_ID }}</span></td>
                    <td><strong>{{ $anyag->Nev }}</strong></td>
                    <td>{{ $anyag->Mertekegyseg }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('anyagok.show', $anyag->Anyag_ID) }}" class="btn-view">
                                <i class="fas fa-eye"></i> Megtekint
                            </a>
                            <a href="{{ route('anyagok.edit', $anyag->Anyag_ID) }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Szerkeszt
                            </a>
                            <form class="form-delete" action="{{ route('anyagok.destroy', $anyag->Anyag_ID) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" type="submit"
                                    onclick="return confirm('Biztosan törölni szeretné?')">
                                    <i class="fas fa-trash-alt"></i> Töröl
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="fas fa-boxes"></i>
                            <p>Nincsenek rögzített anyagok.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('custom_pagination', ['paginator' => $anyagok])
@endsection
