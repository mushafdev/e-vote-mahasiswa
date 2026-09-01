$('document').ready(function(){

    var urlList=$('#t_invite_tim').data('list');
    var Token=$('#t_invite_tim').data('token');
    var urlDelete=$('#t_invite_tim').data('delete');
    var urlSaveAccount=$('#t_invite_tim').data('save-account');
    var urlGetAccount=$('#t_invite_tim').data('get-account');

    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    })

    $('.modal-load').hide();
    var ax_save;
    var ax_save_account;
    var table;
    table = $('#t_invite_tim').DataTable({
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
        ajax: urlList,
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'user_photo', name: 'photo'},
        {data: 'kelompok_tim', name: 'kelompok_tim'},
        {data: 'nama', name: 'nama'},
        {data: 'nik', name: 'nik'},
        {data: 'telp', name: 'telp'},
        {data: 'level', name: 'level'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        "columnDefs": [
        {
            "targets": "datatable-nosort",
            "orderable": false,
        },
        ],

    });

    $("#save_account").click(function(e) {
        e.preventDefault();
        if($('#form_account').parsley().validate()){
        var param = this;
        process1(param);

        var form = $('#form_account')[0];
        var form_data = new FormData(form);
        form_data.set('password2', dForm($("#password2").val()));
        form_data.delete("password1");

        ax_save_account = $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlSaveAccount,
            success : function(data) {
            process_done1(param,'<i class="fa fa-save"></i> Simpan');
            Swal.fire('Success!',data,'success');
            $('#modal-account').modal('toggle');
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Simpan');
                $('#username_a2').parsley().removeError('uniqueerror', {updateClass: true});
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    var usernameError=jsonResponse.errors.username;
                    $('#username_a2').parsley().addError('uniqueerror', {message: usernameError, updateClass: true});
                }else{
                    Swal.fire("Error", "There is an error", "error");
                }
            }
        });
        }else {
        return false();
        }

    });


    $(document).on( 'click', '.account', function () {
        $('.modal-load').show();
        $('#form_account').parsley().reset();
        var id = $(this).data("id");
        $.ajax({
        type    : 'GET',
        data    : {id:id},
        url     : urlGetAccount,
        dataType: 'json',
        success : function(data) {
            $('#id_user_a').val(data.id_user);
            $("#username_a").val(data.username);
            $("#username_a2").val('');
            $("#password1").val('');
            $("#password2").val('');
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
