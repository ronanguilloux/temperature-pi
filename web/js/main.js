if ( ! window.console ) console = { log: function(){} };
google.load("visualization", "1", {packages:["annotatedtimeline"]});
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
            if('undefined' != data) {
                for(row in data){
                    for(col in data[row]) {
                        data[row][col][0] = new Date(eval(data[row][col][0]));
                    }
                }
                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('datetime', 'Date');
                dataTable.addColumn('number', 'Celsius');
                for(key in data.table) {
                    dataTable.addRow([new Date(data.table[key][0]), parseFloat(data.table[key][1])]);
                }
                var options = {
                    title: $('title').text() + ' | Last temp: ' + lastTemp
                    , displayAnnotations: true
                };
                var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_div'));
                chart.draw(dataTable, options);
            }
            //setTimeout("drawChart();", 5000); // recursion (milliseconds) for dev purpose only
        }
    });
}

