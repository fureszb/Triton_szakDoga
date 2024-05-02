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
            var pieDataArray = [
                ['Típus', 'Mennyiség'],
                @foreach ($szolgaltatasokKereslete as $szolgaltatas)
                    ['{{ $szolgaltatas->Tipus }}', {{ $szolgaltatas->Kereslet }}],
                @endforeach
            ];

            if (pieDataArray.length <= 1) {
                document.getElementById('pie_chart').innerHTML = '<div class="statisztika" style="color: grey; text-align: center;">Nem áll rendelkezésre elég adat a szolgáltatás statisztika betöltéséhez</div>';
                return;
            }

            var pieData = google.visualization.arrayToDataTable(pieDataArray);

            var pieOptions = {
                title: 'Megrendelt szolgáltatások megoszlása'
            };

            var pieChart = new google.visualization.PieChart(document.getElementById('pie_chart'));
            pieChart.draw(pieData, pieOptions);
        }
    </script>
</head>
</html>
