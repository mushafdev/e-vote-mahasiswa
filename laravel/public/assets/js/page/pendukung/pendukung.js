$('document').ready(function(){
    var form=$("#form-pendukung");
    var urlSearchPendukung=$("#form-search").data('search-pendukung');
    var urlGetPendukung=$("#form-search").data('detail-pendukung');
    var urlInvitePendukung=$("#form-invite").data('invite-pendukung');
    var urlSave=form.data('save');
    var urlDetail=form.data('detail');
    var urlUpdate=form.data('update');
    var urlMain=form.data('main');

    $('.form-control').on('keyup change', function() {
        $(this).parsley().validate();
    })


    var input = document.getElementById("keyword");
    input.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        document.getElementById("search-button").click();
    }
    });

    $("#search-button").click(function(e) {

        e.preventDefault();
        if($("#form-search").parsley().validate()){
            $.ajax({
                type    : 'GET',
                data    : {keyword:$("#keyword").val()},
                url     : urlSearchPendukung,
                dataType:'json',
                beforeSend: function() {
                    $('.loading-data').show();
                },
                success : function(data) {
                    $('.loading-data').hide();
                    $('#count_show').html(data.total_show);
                    $('#table-result').html(data.table);
                },
                error: function (reject) {
                    
                    Swal.fire("Error", "There is an error", "error");
                }
            });
        }else {
            return false();
        }
    });

    $(document).on( 'click', '#t_pendukung tbody tr', function () {
        $('.edited').html('-');
        $('#telp').val('');
        $('#kategori_pendukung').val('');
        $('#tim_id').val("x").trigger("change");
        var id = $(this).data("id");
        $('#modal-update').modal('show');
        $.ajax({
            type    : 'GET',
            data    : {id:id},
            url     : urlGetPendukung,
            dataType: 'json',
            beforeSend: function() {
                $('.modal-load').show();
            },
            success : function(data) {

                $('#id').val(data.id);
                $('#nik_i').html(data.q.nik);
                $('#nama_i').html(data.q.nama);
                $('#jenis_kelamin_i').html(data.q.jenis_kelamin);
                $('.kab_id').val(data.q.kab_id);
                $('.kec_id').val(data.q.kec_id);
                $('.kel_id').val(data.q.kel_id);
                $('#kabupaten_i').html(data.q.kabupaten);
                $('#kecamatan_i').html(data.q.kecamatan);
                $('#kelurahan_i').html(data.q.kelurahan);
                $('#tps').val(data.q.tps);
                $('#rw_rt_i').html(data.q.rw+'/'+data.q.rt);
                $('#alamat_i').html(data.q.alamat);
                $('.modal-load').hide();
            }
            });
    });
        
    $("#invite").click(function(e) {
        e.preventDefault();
        if($("#form-invite").parsley().validate()){
        var param = this;
        process1(param);
        var form = $('#form-invite')[0];
        var form_data = new FormData(form);
        ax_save = $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlInvitePendukung,
            success : function(data) {
            process_done1(param,'<i class="fa fa-save"></i> Invite');
            if(data.status==500){
                Swal.fire("Maaf", data.message, "error");
            }else{
                Swal.fire({
                    title: "Success!",
                    text: 'Pendukung berhasil diinvite',
                    type: "success",
                    timer: 2000
                }).then((result) => {
                    location.reload();

                });
            }
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Invite');
                $('#photo').parsley().removeError('uniqueerror', {updateClass: true});
                if( reject.status === 422 ) {
                    var data=reject.responseText;
                    var jsonResponse = JSON.parse(data);
                    var kategoriPendukungError=jsonResponse.errors.kategori_pendukung;
                    var telpError=jsonResponse.errors.telp;
                    var timIdError=jsonResponse.errors.tim_id;
                    var photoError=jsonResponse.errors.photo;
                    if(kategoriPendukungError !== undefined){
                        $('#kategori_pendukung').parsley().addError('uniqueerror', {message: kategoriPendukungError, updateClass: true});
                    }
                    if(telpError !== undefined){
                        $('#telp').parsley().addError('uniqueerror', {message: telpError, updateClass: true});
                    }
                    if(timIdError !== undefined){
                        $('#tim_id').parsley().addError('uniqueerror', {message: timIdError, updateClass: true});
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

    $("#save").click(function(e) {
        e.preventDefault();
        if($("#form-pendukung").parsley().validate()){
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
        if($("#form-pendukung").parsley().validate()){
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
