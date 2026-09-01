$('document').ready(function(){

    var form=$("#form-user");
    var urlUpdate=form.data('update');


    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    })

    $("#save").click(function(e) {
        e.preventDefault();
        $('#username_a2').parsley().removeError('uniqueerror', {updateClass: true});
        // $('#password').parsley().removeError('uniqueerror', {updateClass: true});
        if($('#form-user').parsley().validate()){
        var param = this;
        process1(param);
        var form = $('form')[0];
        var form_data = new FormData(form);
        form_data.set('password', dForm($("#password").val()));
        form_data.delete("password1");

        ax_save = $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlUpdate,
            success : function(data) {
            process_done1(param,'<i class="fa fa-save"></i> Update Akun');
                Swal.fire({
                    title: "Success!",
                    text: 'Akun sukses diupdate',
                    type: "success",
                    timer: 2000
                }).then((result) => {
                    location.reload();

                });
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Update Akun');
                $('#photo').parsley().removeError('uniqueerror', {updateClass: true});
                
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    var photoError=jsonResponse.errors.photo;
                    var usernameError=jsonResponse.errors.username;
                    var passwordError=jsonResponse.errors.password;
                    if(usernameError !== undefined){
                        $('#username_a2').parsley().addError('uniqueerror', {message: usernameError, updateClass: true});
                    }
                    if(passwordError !== undefined){
                        $('#password').parsley().addError('uniqueerror', {message: passwordError, updateClass: true});
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


