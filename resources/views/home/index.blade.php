@extends('ujlayout')

@section('content')
    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <style>
        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            flex-wrap: wrap;
            gap: 20px;
        }
        .chart {
            flex: 1;
            max-width: 450px;
            height: 500px;
        }
        h4.section-label {
            font-size: 11px;
            letter-spacing: 3px;
            color: #9e9e9e;
            text-transform: uppercase;
            margin-top: 28px;
            margin-bottom: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        h4.section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f3f4f6;
        }
        @media screen and (max-width: 1100px) {
            .chart-container { flex-wrap: wrap; }
            .chart { max-width: 400px; }
        }
    </style>

    <div class="page-header">
        <h1><i class="fas fa-tachometer-alt"></i> Kezdőlap</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h4 class="section-label">Útmutató lépései</h4>
    @include('home.guide')

    @include('diagrammok.varosStatisztika')
    @include('diagrammok.szolgaltatasStatisztika')

    <h4 class="section-label">Statisztika</h4>
    <div class="chart-container">
        <div id="bar_chart" class="chart" style="width:100%;"></div>
        <div id="pie_chart" class="chart" style="width:100%;"></div>
    </div>
@endsection
