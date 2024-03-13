@extends('layout')

@section('content')

@include('breadcrumbs')
<h1>Szerelők Listája</h1>
<a href="{{ route('szerelok.create') }}" class="btn btn-primary">Új Szerelő Hozzáadása</a>

<table class="table">
    <thead>
        <tr>
            <th>Név</th>
            <th>Telefonszám</th>
            <th>Műveletek</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($szerelok as $szerelo)
        <tr>
            <td>{{ $szerelo->Nev }}</td>
            <td>{{ $szerelo->Telefonszam }}</td>
            <td>
                <a href="{{ route('szerelok.show', $szerelo->Szerelo_ID) }}" class="btn btn-info">Megtekint</a>
                <a href="{{ route('szerelok.edit', $szerelo->Szerelo_ID) }}" class="btn btn-warning">Szerkeszt</a>
                <form action="{{ route('szerelok.destroy', $szerelo->Szerelo_ID) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Biztosan törölni szeretnéd?')">Töröl</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
