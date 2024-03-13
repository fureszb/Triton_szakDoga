<!DOCTYPE html>
<html>

<head>
    <title>Szolgáltatások Statisztika</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart', 'bar', 'geo', 'table']
        });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Kördiagram rajzolása
            var pieData = google.visualization.arrayToDataTable([
                ['Típus', 'Mennyiség'],
                @foreach ($szolgaltatasokKereslete as $szolgaltatas)
                    ['{{ $szolgaltatas->Tipus }}', {{ $szolgaltatas->Kereslet }}],
                @endforeach
            ]);


            var pieOptions = {
                title: 'Szolgáltatások Megoszlása'
            };

            var pieChart = new google.visualization.PieChart(document.getElementById('pie_chart'));
            pieChart.draw(pieData, pieOptions);

            // További diagramok rajzolása...
        }
    </script>
</head>

<body>
    <div id="pie_chart" style="width: 900px; height: 500px;"></div>
    <!-- További diagramok konténerei ide kerülnek -->
</body>

</html>
