if ( ! window.console ) console = { log: function(){} };
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
    $.ajax({
        url: 'js/data.js',
        cache: false,
        dataType: 'json',
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
        },
        success: function(data) {
            $('#chart_div').empty();
            var lastTemp = data.table[data.table.length -1][1] + ' celsius';
            console.log('last temp: ' + lastTemp);
            if('undefined' != data) {
                var dataTable = google.visualization.arrayToDataTable(data.table);
                var options = {
                    title: $('title').text() + ' | Last temp: ' + lastTemp
                };
                var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                chart.draw(dataTable, options);
            }
            setTimeout("drawChart();", 5000); // recursion (milliseconds)
        }
    });
}

