$('document').ready(function(){
    var form=$("#form-user");
    var urlSave=form.data('save');
    var urlDetail=form.data('detail');
    var urlUpdate=form.data('update');
    var urlMain=form.data('main');

    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    })
    
    $('#level').on('change', function() {
        var level=$(this).val();
        if(level=='a'){
            $('#kelompok_tim_id').val('').trigger('change');
            $('#kelompok_tim_id').prop('required',false);
            $('.form-kelompok-tim').hide();
        }else{
            $('#kelompok_tim_id').prop('required',true);
            $('.form-kelompok-tim').show();
        }
    })

    $("#save").click(function(e) {
        e.preventDefault();
        if($("#form-user").parsley().validate()){
        var param = this;
        process1(param);
        var form = $('form')[0];
        var form_data = new FormData(form);
        form_data.set('password', dForm($("#password").val()));
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
                $('#username').parsley().removeError('uniqueerror', {updateClass: true});
                $('#photo').parsley().removeError('uniqueerror', {updateClass: true});
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    var usernameError=jsonResponse.errors.username;
                    var photoError=jsonResponse.errors.photo;
                    if(usernameError !== undefined){
                        $('#username').parsley().addError('uniqueerror', {message: usernameError, updateClass: true});
                    }
                    if(photoError !== undefined){
                        $('#photo').parsley().addError('uniqueerror', {message: photoError, updateClass: true});
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
        if($("#form-user").parsley().validate()){
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
                Swal.fire("Success!",'Data sukses diupdate',"success");
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Simpan');
                $('#username,#photo').parsley().removeError('uniqueerror', {updateClass: true});
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    var usernameError=jsonResponse.errors.username;
                    var photoError=jsonResponse.errors.photo;
                    if(usernameError !== undefined){
                        $('#username').parsley().addError('uniqueerror', {message: usernameError, updateClass: true});
                    }
                    if(photoError !== undefined){
                        $('#photo').parsley().addError('uniqueerror', {message: photoError, updateClass: true});
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
