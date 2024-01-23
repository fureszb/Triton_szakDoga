@extends('layout')

@section('content')
    <h1>Megrendelések Listája</h1>
    <a href="{{ route('megrendeles.create') }}">Új Megrendeles Hozzáadása</a>
    <table>
        <thead>
            <tr>
                <th>Megrendeles Nev</th>
                <th>Objektum Cim</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($megrendelesek as $megrendeles)
                <tr>
                    <td>{{ $megrendeles->Megrendeles_Nev }}</td>
                    <td>{{ $megrendeles->Objektum_Cim }}</td>
                    <td>
                        <a href="{{ route('megrendeles.show', $megrendeles->Megrendeles_ID) }}">Megtekint</a>
                        <a href="{{ route('megrendeles.edit', $megrendeles->Megrendeles_ID) }}">Szerkeszt</a>
                        <form action="{{ route('megrendeles.destroy', $megrendeles->Megrendeles_ID) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Töröl</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
