$('document').ready(function(){

    var urlList=$('#t_permissions').data('list');
    var Token=$('#t_permissions').data('token');
    var urlDelete=$('#t_permissions').data('delete');
    var urlSave=$('#t_permissions').data('save');
    var urlGetData=$('#t_permissions').data('get-data');

    var myeditor;
    DecoupledEditor
    .create( document.querySelector( '#editor' ),{
        toolbar:  [
                        'heading', '|',
                        'fontfamily', 'fontsize', '|',
                        'alignment', '|',
                        'fontColor', 'fontBackgroundColor', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', '|',
                        'link', '|',
                        'outdent', 'indent', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'code', 'codeBlock', '|',
                        'insertTable', '|',
                        'undo', 'redo','|','blockQuote', '-'
                        // 'ckfinder','uploadImage'
                    ]
    } )
    .then( editor => {
        const toolbarContainer = document.querySelector( '#toolbar-container' );

        toolbarContainer.appendChild( editor.ui.view.toolbar.element );
        myeditor = editor;
    } )
    .catch( error => {
        console.error( error );
    } );
    $('.modal-load').hide();

    $('#add').click(function(e){
        e.preventDefault();
        $('#form').parsley().reset();
        $("#param").val('add');
        $('#form-title').text('Tambah Permission');
        process_done1('#simpan','<i class="fa fa-save"></i> Simpan');
        $('#form')[0].reset();
        $('#id').val('');
        $('.modal-load').hide();
        $("#modal").modal('show');
    });

    var ax_save;
    var ax_save_account;
    var table;
    table = $('#t_permissions').DataTable({
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
                    d.x = $("#f_kategori").val();
                }
            },
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'name', name: 'name'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        "columnDefs": [
        {
            "targets": "datatable-nosort",
            "orderable": false,
        },
        ],

    });


    $("#save").click(function(e) {
            e.preventDefault();
            if ($('#form').parsley().validate()) {
                var param = this;
                process1(param);
                const editorData = myeditor.getData();
                var form = $('form')[0];
                var form_data = new FormData(form);
                form_data.append("template_text", editorData);
                ax_save = $.ajax({
                    type: 'POST',
                    data: form_data,
                    contentType : false,
                    processData : false,
                    cache: false,
                    async:true,
                    url: urlSave,
                    DataType: 'json',
                    success: function(data) {
                        process_done1(param, '<i class="fa fa-save"></i> Simpan');
                        Swal.fire('Success!', data.text, 'success');
                        table.ajax.reload(null, false);
                        $('#modal').modal('toggle');
                    },
                    error: function(reject) {
                        process_done1(param, '<i class="fa fa-save"></i> Simpan');
                        $('#name').parsley().removeError('uniqueerror', {
                            updateClass: true
                        });
                        if (reject.status === 422) {
                            var data = reject.responseText;
                            var jsonResponse = JSON.parse(data);
                            var nameError = jsonResponse.errors.name;
                            $('#name').parsley().addError('uniqueerror', {
                                message: nameError,
                                updateClass: true
                            });
                        } else {
                            Swal.fire("Error", "Maaf, terjadi kesalahan", "error");
                        }
                    }
                });
            } else {
                return false();
            }

        });

    $(document).on( 'click', '.edit', function () {
            $('.modal-load').show();
            $('#form-title').text('Edit Permission');
            $('#form').parsley().reset();
            var id = $(this).data("id");
            $.ajax({
            type    : 'GET',
            data    : {id:id},
            url     : urlGetData,
            dataType: 'json',
            success : function(data) {
                $('#id').val(data.id);
                $('#param').val('edit');
                $("#name").val(data.name);

                if(data.template_text!=undefined || data.template_text!=null){


                    $("#editor").html(myeditor.setData(data.template_text));
                }
                $('.modal-load').hide();
            }
            });

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
