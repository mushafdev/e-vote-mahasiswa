$('document').ready(function(){

    var urlList=$('#t_pemilih').data('list');
    var Token=$('#t_pemilih').data('token');
    var urlDelete=$('#t_pemilih').data('delete');

    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    })

    $('.modal-load').hide();

    function  createTitleFilter(){

        let title_export = "";
        var StatusVerifikasi = $('#status_verifikasi').find(':selected').text();    
    
       
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
                        title:'Data Pendaftar',
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
        "lengthMenu": [[10, 25, 50, 500], [10, 25, 50, 500]],
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
                d.status_verifikasi = $('#status_verifikasi').val();    
            },
            "dataSrc": function (json) {
                $("#total_pemilih").html(json.total_pemilih);
                $("#total_strong").html(json.total_strong);
                $("#total_weak").html(json.total_weak);
                return json.data;
            }
        },
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'created_at', name: 'created_at'},
        {data: 'nama', name: 'nama'},
        {data: 'no_anggota', name: 'no_anggota'},
        {data: 'telp', name: 'telp'},
        {data: 'email', name: 'email'},
        {data: 'status', name: 'status'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
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


    $(document).on( 'click', '.delete', function () {
        var id = $(this).data("id");
        Swal.fire({
        title: 'Anda Yakin?',
        text: "Periksa data kembali sebelum membatalkan data ini",
        type: 'warning',
        allowOutsideClick: false,
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak'
        }).then((result) => {
        if (result.value) {
            $.ajax({
                type    : 'POST',
                data    : {id:id,_token:Token},
                url     : urlDelete,
                success : function(data) {
                    Swal.fire('Success!','Data berhasil dibatalkan.','success');
                    table.ajax.reload( null, false );
                },
                error : function(){
                    Swal.fire("Error", "There is an error", "error");
                }
            });
        }
        })
    });



});
