$('document').ready(function(){

    

    var urlList=$('#t_pemilih').data('list');
    var Token=$('#t_pemilih').data('token');

    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    })

    $('.modal-load').hide();

    function  createTitleFilter(){

        let title_export = "";            
        return title_export;
    }
    var ax_save;
    var ax_save_account;
    var table;
    table = $('#t_pemilih').DataTable({
        layout: {
            top1Start: {
                buttons: [
                    'colvis',
                    {
                        extend: 'createState',
                        config: {
                            creationModal: true,
                            toggle: {
                                columns: {
                                    search: true,
                                    visible: true
                                }
                            }
                        }
                    },
                    'savedStates',
                    'copy',
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible',
                            modifier: {
                                page: 'all',
                                search: 'none',
                            }
                        },
                        // orientation: 'landscape',
                        customize: function(doc) {
                            doc.styles.tableHeader.fontSize = 8;
                            doc.defaultStyle.fontSize = 8;
                        } ,
                        header: true,
                        title:'Data Calon Kandidat 2',
                        messageTop:function () {
                            return createTitleFilter();
                        },
                        messageBottom: null,
                    },
                    {
                        title:'Data Calon Kandidat 2',
                        exportOptions: {
                            columns: ':visible',
                        },
                        extend: 'print',
                        header: true,
                        messageTop:function () {
                            return createTitleFilter();
                        },
                        messageBottom: null,
                    },
                    {
                        text: 'Refresh',
                        action: function ( e, dt, node, config ) {
                            dt.ajax.reload();
                        }
                    }
                ]
            }
        },
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        "scrollX": true,
        "lengthMenu": [[100,10, 25, 50, 500], [100,10, 25, 50, 500]],
        "language": {
            "info": "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Search"
        },
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        ajax: {
            "type": "POST",
            "url": urlList,
            "beforeSend" :function(){
                process1($("#filter"));
            },
            "complete" : function(){
                process_done1($("#filter"), '<i class="fa fa-filter"></i> Filter');
            },
            "data": function(d) {
                d._token = Token;
            },
            "dataSrc": function (json) {
                $("#total_pemilih").html(json.total_pemilih);
                $("#total_belum_memilih").html(json.total_belum_memilih);
                $("#total_sah").html(json.total_sah);
                $("#total_tidak_sah").html(json.total_tidak_sah);
                return json.data;
            }
        },
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'nama', name: 'nama'},
        {data: 'no_anggota', name: 'no_anggota'},
        {data: 'total_suara', name: 'total_suara'},
        ],
        
        "columnDefs": [
            {
                "targets": "datatable-nosort",
                "orderable": false,
            },
        ],
        

    });

    table.on('stateLoaded', function (e, s, data) {
        data.columns.forEach(function (col, i) {
            let input = table.column(i).footer().querySelector('input');
     
            if (input && col.search) {
                input.value = col.search.search;
            }
        });
    });

    $(".filter-form").change(function() {
        table.ajax.reload(null, true);
    });

    $(".dataTables_filter input")
    .unbind()
    .bind("input keypress", function(e) { 
    //    if(this.value.length >= 3 || e.keyCode == 13) {
        if(e.keyCode == 13) {
            table.search(this.value).draw();
        }
        if(this.value == "") {
            table.search("").draw();
        }
        return;
    });

    $("#filter").click(function(e) {
        table.ajax.reload( null, true );
    });



});


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
        title.text = "Grafik Perolehan Suara Kandidat";
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
