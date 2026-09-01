$('document').ready(function(){

    var urlList=$('#t_pendukung').data('list');
    var Token=$('#t_pendukung').data('token');
    var urlDelete=$('#t_pendukung').data('delete');

    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    })

    $('.modal-load').hide();
    var ax_save;
    var ax_save_account;
    var table;
    var printCounter = 0;

    function  createTitleFilter(){

        let title_export = "";
        var kelompok_tim =$('#kelompok_tim_id').find(':selected').text();
        var tim =$('#tim_id').find(':selected').text();
        var nkk =$('#nkk').val();
        var kabupaten = $('#kab_id').find(':selected').text();
        var kecamatan = $('#kec_id').find(':selected').text();
        var kelurahan = $('#kel_id').find(':selected').text();                
        var tps = $('#tps').val();                
        var kategori_pendukung = $('#kategori_pendukung :selected').text();
    
        if(kelompok_tim!==''){
            title_export+=kelompok_tim;
        }
        if(tim!==''){
            title_export+='/'+tim;
        }
        if(nkk!==''){
            title_export+='/KK.'+nkk;
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
        if(tps!==''){
            title_export+='/TPS.'+tps;
        }
       
        if(kategori_pendukung!=='Semua'){
            title_export+='/'+'Kategori : '+kategori_pendukung;
        }
        if(title_export.startsWith('/')){
            title_export=title_export.slice(1);
        }
        if(title_export !==''){
            title_export='Filter : '+title_export;
        }

        return title_export;
    }

    table = $('#t_pendukung').DataTable({
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
                        title:'Data Pendukung',
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
                        title:'Data Pendukung',
                        messageTop:function () {
                            return createTitleFilter();
                        },
                        messageBottom: null,
                    },
                    {
                        title:'Data Pendukung',
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
                console.log($('#kelompok_tim_id').find(':selected').text());
            },
            "data": function(d) {
                d._token = Token;
                d.kelompok_tim = $('#kelompok_tim_id').find(':selected').val();
                d.tim_id = $('#tim_id').find(':selected').val();
                d.kabupaten = $('#kab_id').find(':selected').val();
                d.kecamatan = $('#kec_id').find(':selected').val();
                d.kelurahan = $('#kel_id').find(':selected').val();                
                d.tps = $('#tps').val();                
                d.nkk = $('#nkk').val();                
                d.kategori_pendukung = $('#kategori_pendukung').val();
            },
            "dataSrc": function (json) {
                $("#total_pendukung").html(json.total_pendukung);
                $("#total_strong").html(json.total_strong);
                $("#total_weak").html(json.total_weak);
                return json.data;
            }
        },
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'nama', name: 'nama'},
        {data: 'nik', name: 'nik'},
        {data: 'nkk', name: 'nkk'},
        {data: 'kabupaten', name: 'kabupaten'},
        {data: 'kecamatan', name: 'kecamatan'},
        {data: 'kelurahan', name: 'kelurahan'},
        {data: 'tps', name: 'tps'},
        {data: 'kategori_pendukung', name: 'kategori_pendukung'},
        {data: 'telp', name: 'telp'},
        {data: 'referensi', name: 'referensi'},
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
