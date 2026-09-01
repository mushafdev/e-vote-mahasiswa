$('document').ready(function(){
    var urlList=$('#t_barang_keluar').data('list');
    var urlDetailRekapRB=$('#t_barang_keluar').data('detail-rekap-rb');
    var Token=$('#t_barang_keluar').data('token');

    $('.modal-load').hide();
    var ax_save;
    var ax_save_account;
    var table;
    table = $('#t_barang_keluar').DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        "scrollX": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "info": "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Search"
        },
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        ajax: {
            "type": "GET",
            "url": urlList,
            "beforeSend" :function(){
                process1($("#print"));
            },
            "complete" : function(){
                process_done1($("#print"), '<i class="fa fa-print"></i> Print');
            },
            "data": function(d) {
                d._token = Token;
                d.start_date = $("#start_date").val();
                d.end_date = $("#end_date").val();
                d.unit_kerja_id = $("#unit_kerja_id").val();
                d.jenis_pengeluaran_id = $("#jenis_pengeluaran_id").val();
            },
            "dataSrc": function (json) {
                $("#total_grand_total").html(json.total_grand_total);
                return json.data;
            }
        },
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'waktu', name: 'waktu'},
        {data: 'no_transaksi', name: 'no_transaksi'},
        // New Update
        {data: 'unit_kerja', name: 'unit_kerja'},
        // New Update
        {data: 'jenis_pengeluaran', name: 'jenis_pengeluaran'},
        {data: 'persediaan', name: 'persediaan'},
        {data: 'no_referensi', name: 'no_referensi'},
        {data: 'penanggung_jawab', name: 'penanggung_jawab'},
        {data: 'grand_total', name: 'grand_total',className: "text-right"},
        ],
        "columnDefs": [
        {
            "targets": "datatable-nosort",
            "orderable": false,
        },
        ],

    });

    $(".filter-form").change(function() {
        table.ajax.reload(null, true);
    });

    $(document).on( 'click', '.detail-rekap-rb', function () {
        $("#barang_id").val($(this).data('id'));
        $("#uk").val($(this).data('uk'));
        $("#d_start_date").val($("#start_date").val());
        $("#d_end_date").val($("#end_date").val());
        getDetailRekapRB();
    });

    function getDetailRekapRB(){
        $('.modal-load').show();

        $('#kode').prop('readonly',true);
        $.ajax({
        type    : 'GET',
        data    : {id:$("#barang_id").val(),unit_kerja_id:$("#uk").val(),start_date:$('#start_date').val(),end_date:$('#end_date').val()},
        url     : urlDetailRekapRB,
        dataType: 'json',
        beforeSend : function(){
            process1($("#filter"));
        },
        complete : function(){
            process_done1($("#filter"), '<i class="fa fa-filter"></i> Filter');
        },
        success : function(data) {
            $('#t_kartu_stok tbody').html(data.table);
            $('#unit_kerja_show').html(data.unit_kerja);
            $('#kode_show').html(data.detail_barang.kode);
            $('#nama_barang_show').html(data.detail_barang.barang);
            $('#satuan_show').html(data.detail_barang.satuan);
            $('.modal-load').hide();
        }
        });
    }




});
