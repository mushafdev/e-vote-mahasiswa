

$('document').ready(function(){
    var form=$("#form-verifikasi");
    var urlSave=form.data('save');
    var urlRequestOtp=form.data('request-otp');
   $('.form-video').hide();

    $("#verifikasi").click(function(e) {
        e.preventDefault();
        if($("#form-verifikasi").parsley().validate()){
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
            DataType: 'json',
            success : function(data) {
            process_done1(param,'Verifikasi');
            Swal.fire({
                    title: "Success!",
                    text: data.message,
                    type: "success",
                    timer: 2000
                }).then((result) => {
                    location.replace(data.url);

                });
            },
            error: function (reject) {
                process_done1(param,'Verifikasi');

                $('#form-verifikasi').parsley().reset();
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    $.each( jsonResponse.errors, function( key, value) {
                        $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                    });
                    // var nikError=jsonResponse.errors.nik;
                    // if(nikError !== undefined){
                    //     $('#nik').parsley().addError('uniqueerror', {message: nikError, updateClass: true});
                    // }
                }else if( reject.status === 403 ) {
                    Swal.fire({
                        title: "Captcha expire!",
                        text: 'Halaman akan direfresh. Silahkan ulangi',
                        type: "error",
                        timer: 2000
                    }).then((result) => {
                        location.reload();
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
    $('#kode_otp').on('keypress', function(e) {
        if(e.which == 13) {
            e.preventDefault();
            $("#verifikasi").click();
        }
    });

    $("#request-otp").click(function(e) {
        e.preventDefault();
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
            url     : urlRequestOtp,
            DataType: 'json',
            success : function(data) {
            process_done1(param,'<small>Kirim ulang Kode OTP</small>');
            Swal.fire({
                    title: "Success!",
                    text: 'Kode OTP berhasil dikirim',
                    type: "info",
                    timer: 2000
                }).then((result) => {

                });
            },
            error: function (reject) {
                process_done1(param,'<small>Kirim ulang Kode OTP</small>');

                $('#form-verifikasi').parsley().reset();
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    $.each( jsonResponse.errors, function( key, value) {
                        $('#'+key).parsley().addError('uniqueerror', {message: value[0], updateClass: true});
                    });
                    // var nikError=jsonResponse.errors.nik;
                    // if(nikError !== undefined){
                    //     $('#nik').parsley().addError('uniqueerror', {message: nikError, updateClass: true});
                    // }
                }else if( reject.status === 403 || reject.status === 400) {
                    Swal.fire({
                        title: "Captcha expire!",
                        text: 'Halaman akan direfresh. Silahkan ulangi',
                        type: "error",
                        timer: 2000
                    }).then((result) => {
                        location.reload();
                    });
                }else{
                    Swal.fire("Error", "There is an error", "error");
                }
            }
        });

    });
});




