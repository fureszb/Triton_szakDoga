@extends('ujlayout')

@section('content')
    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>
    <style>
        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;

        }

        .chart {
            flex: 1;
            max-width: 450px;
            height: 500px;
        }

        h4 {
            font-size: 0.9rem !important;
            letter-spacing: 3px;
            color: #9e9e9e !important;
            text-transform: uppercase;
            margin-top: 15px !important;
            margin-bottom: 15px !important;
        }

        .statisztika {
            margin: 40px;
            font-size: 0.9rem !important;
        }
        .dashboard a{
            text-decoration: none;
        }

        @media screen and (max-width: 1100px) {
            .chart-container {
                flex-wrap: wrap;
                justify-content: flex-end;
            }

            h4 {
                padding-top: 10px;
            }

            .chart {
                max-width: 400px;
            }



        }

        @media screen and (max-width: 700px) {
            .statisztika {
                margin: 10px;
            }
        }
    </style>

    <h1>Kezdőlap</h1>
    <hr class="showHr">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h4>Útmutató lépései</h4>
    @include('home.guide')
    @include('diagrammok.varosStatisztika')
    @include('diagrammok.szolgaltatasStatisztika')

    <h4>Statisztika</h4>
    <div class="chart-container">
        <div id="bar_chart" class="chart" style="width: 100%;"></div>
        <div id="pie_chart" class="chart" style="width: 100%;"></div>
    </div>
@endsection
