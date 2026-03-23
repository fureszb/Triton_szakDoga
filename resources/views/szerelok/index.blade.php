@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-tools"></i> Szerelők</h1>
    </div>

    <div class="page-toolbar">
        <a href="{{ route('szerelok.index', ['sort_by' => 'Szerelo_ID', 'sort_dir' => 'asc']) }}" class="sort-btn" title="Növekvő sorrend">
            <i class="fas fa-sort-alpha-up"></i>
        </a>
        <a href="{{ route('szerelok.index', ['sort_by' => 'Szerelo_ID', 'sort_dir' => 'desc']) }}" class="sort-btn" title="Csökkenő sorrend">
            <i class="fas fa-sort-alpha-down-alt"></i>
        </a>
        <a href="{{ route('szerelok.create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Új szerelő
        </a>
        <form class="search-form-inline" action="{{ route('szerelok.index') }}" method="GET">
            <input type="text" name="search" placeholder="Keresés név vagy telefonszám alapján"
                   value="{{ request('search') }}">
            <button type="submit"><i class="fas fa-search"></i> Keresés</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Telefonszám</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($szerelok as $szerelo)
                <tr>
                    <td><span class="table-id">#{{ $szerelo->id }}</span></td>
                    <td><strong>{{ $szerelo->nev }}</strong></td>
                    <td>{{ $szerelo->telefonszam }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('szerelok.show', $szerelo->id) }}" class="btn-view">
                                <i class="fas fa-eye"></i> Megtekint
                            </a>
                            <a href="{{ route('szerelok.edit', $szerelo->id) }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Szerkeszt
                            </a>
                            <form class="form-delete" action="{{ route('szerelok.destroy', $szerelo->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" type="submit"
                                    onclick="return confirm('Biztosan törölni szeretnéd?')">
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
                            <i class="fas fa-tools"></i>
                            <p>Nincsenek rögzített szerelők.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @include('custom_pagination', ['paginator' => $szerelok])
@endsection
