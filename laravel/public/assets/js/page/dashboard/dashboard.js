var tglEks=$('.tgl_eks').text();
$('document').ready(function(){


    var urlLineChart=$('#form-filter').data('line-chart');
    var urlPieChart=$('#form-filter').data('pie-chart');

    get_line_chart();
    get_pie_chart();

    $(".form-filter").change(function() {
        get_line_chart();
    });

    function get_line_chart(){
        var param = $("#filter");
        var form = $('#form-filter')[0];
        var form_data = new FormData(form);
        $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlLineChart,
            dataType: 'json',
            beforeSend: function() {
                $('.load').show();
            },
            success : function(data) {
                line_chart(data);
                $('.load').hide();
            }
        });
    }
    
    function get_pie_chart(){
        var param = $("#filter");
        var form = $('#form-filter')[0];
        var form_data = new FormData(form);
        $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlPieChart,
            dataType: 'json',
            beforeSend: function() {
                $('.load').show();
            },
            success : function(data) {
                pie_chart(data);
                $('.load').hide();
            }
        });
    }

});

function line_chart(resp){
    am4core.useTheme(am4themes_animated);

    // Create chart instance
    var chart = am4core.create("lineChart", am4charts.XYChart);

    // Add data
    chart.data =resp.x;

    // Create axes
    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    dateAxis.renderer.grid.template.location = 0;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    function createSeries(field, name) {
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = field;
        series.dataFields.dateX = "date";
        series.name = name;
        series.tooltipText = "{dateX}: [b]{valueY}[/] Pendukung";
        series.strokeWidth = 2;

        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.stroke = am4core.color("#fff");
        bullet.circle.strokeWidth = 2;

        return series;
    }

    var series1 = createSeries("pendukung", "Pendukung");
    var series5 = createSeries("void", "Toggle All");

    series1.fill = am4core.color("#8fd3f4");
    series5.fill = am4core.color("#4a4a4a");

    series1.stroke = am4core.color("#8fd3f4");
    series5.stroke = am4core.color("#4a4a4a");


    series1.smoothing = "monotoneX";
    series5.smoothing = "monotoneX";

    series5.events.on("hidden", function() {
    series1.hide();
    });

    series5.events.on("shown", function() {
    series1.show();
    series2.show();
    });

    chart.legend = new am4charts.Legend();
    chart.cursor = new am4charts.XYCursor();

}

function pie_chart(resp){
    var chart = am4core.create("pieChart", am4charts.PieChart);

    // Add data
    chart.data = resp.x;

    
    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "jumlah";
    pieSeries.dataFields.category = "kategori";
    pieSeries.labels.template.disabled = true;
    pieSeries.ticks.template.disabled = true;
    // pieSeries.alignLabels = false;
    // pieSeries.labels.template.text = "{value.percent.formatNumber('#.0')}% ({value}) ";
    // pieSeries.labels.template.radius = am4core.percent(-40);
    // pieSeries.labels.template.fill = am4core.color("white");

    // Add legend
    chart.legend = new am4charts.Legend();
    chart.legend.valueLabels.template.text = "{value.percent}% ({value})";
}

// Set the date we're counting down to
var countDownDate = new Date(tglEks).getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  $('.cd_d').html(days);
  $('.cd_h').html(hours);
  $('.cd_m').html(minutes);
  $('.cd_s').html(seconds);

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);