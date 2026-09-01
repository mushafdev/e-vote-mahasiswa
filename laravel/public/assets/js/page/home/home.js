
//$('.tool_tip').tooltip({trigger:'manual'}).tooltip('show');
var urlBarChart=document.getElementById("gTarget").dataset.barchart;

get_bar_chart();


function get_bar_chart(){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            bar_chart(this.responseText);
        }
    };
    
    xhr.onloadstart = function () {
        // document.getElementById("button").innerHTML = "Loading...";
    }

    xhr.onerror = function () {
        // alert("Gagal mengambil data");
    };
    xhr.onloadend = function () {
        if (this.responseText !== "") {
            var data = JSON.parse(this.responseText);
            bar_chart(data);

            // setTimeout(function () {
            //     bar_chart(data);
            // }, 3000);
        }
    };

    xhr.open("GET", urlBarChart, true);
    
    xhr.send();
}

function bar_chart(resp){
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end
        
        var chart = am4core.create("gTarget", am4charts.XYChart);
        chart.padding(40, 40, 40, 40);

        let title = chart.titles.create();
        title.text = "Grafik Perolehan Suara Bakal Calon Tahap 2";
        title.fontSize = 15;
       
        let topContainer = chart.chartContainer.createChild(am4core.Container);
        topContainer.layout = "absolute";
        topContainer.toBack();
        topContainer.paddingBottom = 15;
        topContainer.width = am4core.percent(100);

        
        var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.dataFields.category = "kandidat";
        categoryAxis.renderer.minGridDistance = 1;
        categoryAxis.renderer.inversed = true;
        categoryAxis.renderer.grid.template.disabled = true;
        
        var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
        valueAxis.min = 0;
        
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.categoryY = "kandidat";
        series.dataFields.valueX = "jumlah";
        series.tooltipText = "{valueX.value}"
        series.columns.template.strokeOpacity = 0;
        series.columns.template.column.cornerRadiusBottomRight = 5;
        series.columns.template.column.cornerRadiusTopRight = 5;
        
        var labelBullet = series.bullets.push(new am4charts.LabelBullet())
        labelBullet.label.horizontalCenter = "left";
        labelBullet.label.dx = 10;
        labelBullet.label.text = "{values.valueX.workingValue.formatNumber('0')}";
        labelBullet.locationX = 1;
        
        // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
        series.columns.template.adapter.add("fill", function(fill, target){
          return chart.colors.getIndex(target.dataItem.index);
        });
        
        categoryAxis.sortBySeries = series;
        chart.data =resp.x;
        
        
        
        });
}