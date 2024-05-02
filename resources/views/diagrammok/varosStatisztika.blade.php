<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });

    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        var dataArray = [
            ['Város', 'Megrendelések száma'],
            @foreach ($statistics as $stat)
                ['{{ $stat->Nev }}', {{ $stat->MegrendelesekSzama }}],
            @endforeach
        ];


        if (dataArray.length <= 1) {
            document.getElementById('bar_chart').innerHTML = '<div class="statisztika" style="color: grey; text-align: center;">Nem áll rendelkezésre elég adat a város statisztika betöltéséhez</div>';
            return;
        }

        var columnData = google.visualization.arrayToDataTable(dataArray);

        var columnOptions = {
            title: 'Megrendelések száma városonként',
            hAxis: {
                title: 'Város',
                minValue: 0
            },
            vAxis: {
                title: 'Megrendelések száma'
            },
            bars: 'vertical',
        };

        var columnChart = new google.visualization.ColumnChart(document.getElementById('bar_chart'));
        columnChart.draw(columnData, columnOptions);
    }
</script>
