@extends('ujlayout')

@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-user-cog"></i> Felhasználók</h1>
    </div>

    <div class="page-toolbar">
        <a href="{{ route('users.create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Új felhasználó
        </a>
        <form class="search-form-inline" action="{{ route('users.index') }}" method="GET">
            <input type="text" name="search" placeholder="Keresés név alapján"
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
                <th>Szerepkör</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td><span class="table-id">#{{ $user->id }}</span></td>
                    <td><strong>{{ $user->nev }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'Admin')
                            <span class="badge" style="background:#fee2e2;color:#991b1b;">Admin</span>
                        @elseif($user->role === 'Uzletkoto')
                            <span class="badge" style="background:#fef3c7;color:#92400e;">Üzletkötő</span>
                        @else
                            <span class="badge badge-done">Ügyfél</span>
                        @endif
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('users.show', ['user' => $user->id]) }}" class="btn-view">
                                <i class="fas fa-eye"></i> Megtekint
                            </a>
                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Szerkeszt
                            </a>
                            <form class="form-delete" action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" type="submit"
                                    onclick="return confirm('Biztos törölni kívánja a felhasználót?')">
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
                            <i class="fas fa-user-cog"></i>
                            <p>Nincsenek rögzített felhasználók.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
@endsection
