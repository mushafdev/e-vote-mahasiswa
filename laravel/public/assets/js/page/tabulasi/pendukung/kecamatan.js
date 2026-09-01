
var urlBarChart=$('#form-filter').data('bar-chart');
var namaKabupaten=$('.nama-kabupaten').text();

get_bar_chart();

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

function bar_chart(resp){
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end
        
        var chart = am4core.create("gTarget", am4charts.XYChart);
        chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
        
        chart.data =resp.x;

        
        let title = chart.titles.create();
        title.text = "Grafik Pencapaian Pendukung";
        title.fontSize = 15;
       
        let topContainer = chart.chartContainer.createChild(am4core.Container);
        topContainer.layout = "absolute";
        topContainer.toBack();
        topContainer.paddingBottom = 15;
        topContainer.width = am4core.percent(100);

        let axisTitle = topContainer.createChild(am4core.Label);
        axisTitle.text = namaKabupaten;
        axisTitle.fontWeight = 600;
        axisTitle.fontSize = 20;
        axisTitle.align = "center";
        axisTitle.marginBottom = 30;
        
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.dataFields.category = "kecamatan";
        categoryAxis.renderer.minGridDistance = 40;
        categoryAxis.fontSize = 11;
        
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.categoryX = "kecamatan";
        series.dataFields.valueY = "jumlah";
        series.columns.template.tooltipText = "{valueY.value} Pendukung";
        series.columns.template.tooltipY = 0;
        series.columns.template.strokeOpacity = 0;
        
        // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
        series.columns.template.adapter.add("fill", function(fill, target) {
          return chart.colors.getIndex(target.dataItem.index);
        });
        
    }); 
}