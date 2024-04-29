@extends('ujlayout')

@section('content')
    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
    <div><h1 class="home">Kezdőlap</h1></div>
    <hr class="showHr">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @include('szolgaltatasok.index')
@endsection
