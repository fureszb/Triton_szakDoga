@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-user-friends"></i> Ügyfelek</h1>
    </div>

    <div class="page-toolbar">
        <a href="{{ route('ugyfel.index', ['sort_by' => 'Nev', 'sort_dir' => 'asc']) }}" class="sort-btn" title="Növekvő sorrend">
            <i class="fas fa-sort-alpha-up"></i>
        </a>
        <a href="{{ route('ugyfel.index', ['sort_by' => 'Nev', 'sort_dir' => 'desc']) }}" class="sort-btn" title="Csökkenő sorrend">
            <i class="fas fa-sort-alpha-down-alt"></i>
        </a>
        <a href="{{ route('ugyfel.create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Új ügyfél
        </a>
        <form class="search-form-inline" action="{{ route('ugyfel.index') }}" method="GET">
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
                <th>Név</th>
                <th>Email</th>
                <th>Telefonszám</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ugyfel as $u)
                <tr>
                    <td><span class="table-id">#{{ $u->Ugyfel_ID }}</span></td>
                    <td><strong>{{ $u->Nev }}</strong></td>
                    <td>{{ $u->Email ?? '-' }}</td>
                    <td>{{ $u->Telefonszam ?? '-' }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('ugyfel.show', ['id' => $u->Ugyfel_ID]) }}" class="btn-view">
                                <i class="fas fa-eye"></i> Megtekint
                            </a>
                            <a href="{{ route('ugyfel.edit', ['ugyfel' => $u->Ugyfel_ID]) }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Szerkeszt
                            </a>
                            <form class="form-delete" action="{{ route('ugyfel.destroy', ['ugyfel' => $u->Ugyfel_ID]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" type="submit"
                                    onclick="return confirm('Biztos törölni kívánja az ügyfélt?')">
                                    <i class="fas fa-trash-alt"></i> Töröl
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-user-friends"></i>
                            <p>Nincsenek rögzített ügyfelek.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @include('custom_pagination', ['paginator' => $ugyfel])
@endsection
