
var urlBarChart=$('#form-filter').data('bar-chart');
var urlLineChart=$('#form-filter').data('line-chart');
var namaKategori=$('.nama-kategori').text();

get_bar_chart();
get_line_chart();

$(".form-filter").change(function() {
    get_line_chart();
});

function get_bar_chart(){
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
        url     : urlBarChart,
        dataType: 'json',
        beforeSend: function() {
            // $('.load').show();
        },
        success : function(data) {
            bar_chart(data);
            // $('.load').hide();
        }
    });
}

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

function bar_chart(resp){
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end
        
        var chart = am4core.create("gTarget", am4charts.XYChart);
        chart.padding(40, 40, 40, 40);

        let title = chart.titles.create();
        title.text = "Grafik Pencapaian Pendukung";
        title.fontSize = 15;
       
        let topContainer = chart.chartContainer.createChild(am4core.Container);
        topContainer.layout = "absolute";
        topContainer.toBack();
        topContainer.paddingBottom = 15;
        topContainer.width = am4core.percent(100);

        let axisTitle = topContainer.createChild(am4core.Label);
        axisTitle.text = namaKategori;
        axisTitle.fontWeight = 600;
        axisTitle.fontSize = 20;
        axisTitle.align = "center";
        axisTitle.marginBottom = 30;
        
        var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.dataFields.category = "kelompok_tim";
        categoryAxis.renderer.minGridDistance = 1;
        categoryAxis.renderer.inversed = true;
        categoryAxis.renderer.grid.template.disabled = true;
        
        var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
        valueAxis.min = 0;
        
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.categoryY = "kelompok_tim";
        series.dataFields.valueX = "jumlah";
        series.tooltipText = "{valueX.value}"
        series.columns.template.strokeOpacity = 0;
        series.columns.template.column.cornerRadiusBottomRight = 5;
        series.columns.template.column.cornerRadiusTopRight = 5;
        
        var labelBullet = series.bullets.push(new am4charts.LabelBullet())
        labelBullet.label.horizontalCenter = "left";
        labelBullet.label.dx = 10;
        labelBullet.label.text = "{values.valueX.workingValue.formatNumber('#.0as')}";
        labelBullet.locationX = 1;
        
        // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
        series.columns.template.adapter.add("fill", function(fill, target){
          return chart.colors.getIndex(target.dataItem.index);
        });
        
        categoryAxis.sortBySeries = series;
        chart.data =resp.x;
        
        
        
        });
}

function line_chart(resp){
    am4core.useTheme(am4themes_animated);

    // Create chart instance
    var chart = am4core.create("gLineChart", am4charts.XYChart);
    let title = chart.titles.create();
    title.text = "Grafik Peningkatan Pendukung";
    title.fontSize = 15;
   
    let topContainer = chart.chartContainer.createChild(am4core.Container);
    topContainer.layout = "absolute";
    topContainer.toBack();
    topContainer.paddingBottom = 15;
    topContainer.width = am4core.percent(100);

    let axisTitle = topContainer.createChild(am4core.Label);
    axisTitle.text = namaKategori;
    axisTitle.fontWeight = 600;
    axisTitle.fontSize = 20;
    axisTitle.align = "center";
    axisTitle.marginBottom = 30;
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
        series.tooltipText = "{name}: [b]{valueY}[/] Pendukung";
        series.strokeWidth = 2;

        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.stroke = am4core.color("#fff");
        bullet.circle.strokeWidth = 2;

        return series;
    }

    resp.kelompok_tims.forEach((item, index) => {
        item.id=createSeries(item.id, item.kelompok_tim);
    });

    var svoid = createSeries("void", "Toggle All");

    resp.kelompok_tims.forEach((item, index) => {
        item.id.smoothing = "monotoneX";
    });

    svoid.smoothing = "monotoneX";

    svoid.events.on("hidden", function() {
        resp.kelompok_tims.forEach((item, index) => {
            item.id.hide();
        });
    });

    svoid.events.on("shown", function() {
        resp.kelompok_tims.forEach((item, index) => {
            item.id.show();
        });
    });

    chart.legend = new am4charts.Legend();
    chart.cursor = new am4charts.XYCursor();

}