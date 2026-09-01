$('document').ready(function(){

    var urlList=$('#t_tim').data('list');
    var Token=$('#t_tim').data('token');
    var urlDelete=$('#t_tim').data('delete');
    var urlSaveAccount=$('#t_tim').data('save-account');
    var urlGetAccount=$('#t_tim').data('get-account');

    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    })

    $('.modal-load').hide();
    function  createTitleFilter(){

        let title_export = "";
        var kelompok_tim =$('#kelompok_tim_id').find(':selected').text();
        var kabupaten = $('#kab_id').find(':selected').text();
        var kecamatan = $('#kec_id').find(':selected').text();
        var kelurahan = $('#kel_id').find(':selected').text();              
        var level = $('#level :selected').text();
    
        if(kelompok_tim!==''){
            title_export+=kelompok_tim;
        }
        if(kabupaten!==''){
            title_export+='/'+kabupaten;
        }
        if(kecamatan!==''){
            title_export+='/'+kecamatan;
        }
        if(kelurahan!==''){
            title_export+='/'+kelurahan;
        }
       
        if(level!=='Semua'){
            title_export+='/'+'Level : '+level;
        }
        if(title_export.startsWith('/')){
            title_export=title_export.slice(1);
        }
        if(title_export !==''){
            title_export='Filter : '+title_export;
        }

        return title_export;
    }
    

    var ax_save;
    var ax_save_account;
    var table;
    table = $('#t_tim').DataTable({
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
                        title:'Data TIM',
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible',
                        },
                        header: true,
                        messageTop:function () {
                            return createTitleFilter();
                        },
                        messageBottom: null,
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible',
                        },
                        // orientation: 'landscape',
                        customize: function(doc) {
                            doc.styles.tableHeader.fontSize = 8;
                            doc.defaultStyle.fontSize = 8;
                        } ,
                        header: true,
                        title:'Data TIM',
                        messageTop:function () {
                            return createTitleFilter();
                        },
                        messageBottom: null,
                    },
                    {
                        title:'Data TIM',
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
                process1($("#filter"));
            },
            "complete" : function(){
                process_done1($("#filter"), '<i class="fa fa-filter"></i> Filter');
            },
            "data": function(d) {
                d._token = Token;
                d.kategori = $('#kategori').val();
                d.kelompok_tim = $('#kelompok_tim_id').find(':selected').val();
                d.kabupaten = $('#kab_id').find(':selected').val();
                d.kecamatan = $('#kec_id').find(':selected').val();
                d.kelurahan = $('#kel_id').find(':selected').val();
                d.level = $('#level').val();
            }
        },
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'user_photo', name: 'photo'},
        {data: 'kelompok_tim', name: 'kelompok_tim',visible:false},
        {data: 'nama', name: 'nama'},
        {data: 'nik', name: 'nik'},
        {data: 'telp', name: 'telp'},
        {data: 'level', name: 'level'},
        {data: 'area', name: 'area'},
        {data: 'tot_pendukung', name: 'tot_pendukung'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        
        rowGroup: {
            dataSrc: 'kelompok_tim'
        },
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

    $("#filter").click(function(e) {
        table.ajax.reload( null, true );
    });


    $(document).on( 'click', '.delete', function () {
        var id = $(this).data("id");
        Swal.fire({
        title: 'Are you sure?',
        text: "This record and it`s details will be permanantly deleted!",
        type: 'warning',
        allowOutsideClick: false,
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'No'
        }).then((result) => {
        if (result.value) {
            $.ajax({
                type    : 'POST',
                data    : {id:id,_token:Token},
                url     : urlDelete,
                success : function(data) {
                    Swal.fire('Success!','Deleted successfully.','success');
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
