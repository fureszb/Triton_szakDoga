@extends('ujlayout')

@section('content')
    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
    <h1 class="title">Kezdőlap
        <form class="kereses" action="{{ route('users.index') }}" method="GET">
            <input type="text" name="search" placeholder="Keresés név vagy email alapján">
            <button type="submit">Keresés</button>
        </form>
    </h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @include('szolgaltatasok.index')
@endsection
