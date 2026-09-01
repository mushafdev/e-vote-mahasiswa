$('document').ready(function(){
    var form=$("#form-pemilih");
    var urlSave=form.data('save');
    var urlUpdate=form.data('update');
    var urlUpdateVerifikasi=form.data('update-verifikasi');
    var urlMain=form.data('main');
    var urlMainRegistrasi=form.data('main-registrasi');
    var urlRegistrationList=form.data('registration-list');

    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    })

    $("#save").click(function(e) {
        e.preventDefault();
        if($("#form-pemilih").parsley().validate()){
        var param = this;
        process1(param);
        var form = $('form')[0];
        var form_data = new FormData(form);
        ax_save = $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlSave,
            success : function(data) {
            process_done1(param,'<i class="fa fa-save"></i> Simpan');
            Swal.fire({
                    title: "Success!",
                    text: 'Data sukses disimpan',
                    type: "success",
                    timer: 2000
                }).then((result) => {
                    location.replace(urlMain);

                });
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Simpan');
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    var nikError=jsonResponse.errors.nik;
                    if(nikError !== undefined){
                        $('#nik').parsley().addError('uniqueerror', {message: nikError, updateClass: true});
                    }
                }else{
                    Swal.fire("Error", "There is an error", "error");
                }
            }
        });
        }else {
        return false();
        }

    });

    $("#update").click(function(e) {
        e.preventDefault();
        if($("#form-pemilih").parsley().validate()){
        var param = this;
        process1(param);
        var form = $('form')[0];
        var form_data = new FormData(form);
        ax_save = $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlUpdate,
            success : function(data) {
            process_done1(param,'<i class="fa fa-save"></i> Simpan');
            location.reload();
                Swal.fire("Success!",'Data sukses diupdate',"success");
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Simpan');
                $('#nik').parsley().removeError('uniqueerror', {updateClass: true});
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    var nikError=jsonResponse.errors.nik;
                    if(nikError !== undefined){
                        $('#nik').parsley().addError('uniqueerror', {message: nikError, updateClass: true});
                    }
                }else{
                    Swal.fire("Error", "There is an error", "error");
                }
            }
        });
        }else {
        return false();
        }

    });
    
    $("#update-verifikasi").click(function(e) {
        e.preventDefault();
        if($("#form-pemilih").parsley().validate()){
        var param = this;
        process1(param);
        var form = $('form')[0];
        var form_data = new FormData(form);
        ax_save = $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlUpdateVerifikasi,
            success : function(data) {
            process_done1(param,'<i class="fa fa-save"></i> Update');
            Swal.fire({
                title: "Success!",
                text: 'Data sukses diupdate',
                type: "success",
                timer: 2000
            }).then((result) => {
                location.replace(urlRegistrationList);
                // location.reload();

            });
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Update');
                $('#form-pemilih').parsley().reset();
                    if( reject.status === 422 ) {
                        var data=reject.responseText;
                        var jsonResponse = JSON.parse(data);
                        $.each( jsonResponse.errors, function( key, value) {
                            $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                        });
                    }else{
                        Swal.fire("Error", "There is an error", "error");
                    }
            }
        });
        }else {
        return false();
        }

    });


});


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.fileName = input.files[0].name;
        reader.onload = function(e) {
            $('#img_show').attr('src', e.target.result);
            $('#file_name').text(e.target.fileName);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#photo").change(function() {
    readURL(this);
});
