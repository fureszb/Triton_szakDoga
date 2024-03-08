@extends('layout')

@section('content')
<h1>Anyagok Listája</h1>
<a href="{{ route('anyagok.create') }}" class="btn btn-primary">Új Anyag Hozzáadása</a>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Név</th>
            <th>Mértékegység</th>
            <th>Műveletek</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($anyagok as $anyag)
        <tr>
            <td>{{ $anyag->Nev }}</td>
            <td>{{ $anyag->Mertekegyseg }}</td>
            <td>
                <a href="{{ route('anyagok.show', $anyag->Anyag_ID) }}" class="btn btn-info">Megtekint</a>
                <a href="{{ route('anyagok.edit', $anyag->Anyag_ID) }}" class="btn btn-warning">Szerkeszt</a>
                <form action="{{ route('anyagok.destroy', $anyag->Anyag_ID) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Biztosan törölni szeretné?')">Töröl</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
