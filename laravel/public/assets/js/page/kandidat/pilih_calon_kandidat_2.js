$('document').ready(function(){
    var form=$("#form-pilih");
    var urlGetKandidat=$("#form-pilih").data('detail-kandidat');
    var urlPilihKandidat=$("#form-pilih").data('pilih-kandidat');



    $(document).on( 'click', '.detail-kandidat', function () {
        $("#pilih").prop('disabled',true);
        $('.edited').html('-');
        $('#telp').val('');
        $('#kategori_kandidat').val('');
        $('#tim_id').val("x").trigger("change");
        var id = $(this).data("id");
        $('#modal-update').modal('show');
        $.ajax({
            type    : 'GET',
            data    : {id:id},
            url     : urlGetKandidat,
            dataType: 'json',
            beforeSend: function() {
                $('.modal-load').show();
            },
            success : function(data) {

                $('#id').val(data.id);
                $('#no_anggota_i').html(data.q.no_anggota);
                $('#nama_i').html(data.q.nama);
                $('#no_urut_i').html(data.q.no_urut);
                $('#img_i').attr('src', data.photo);
                $("#pilih").prop('disabled',false);
                $('.modal-load').hide();
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Pilih Kandidat Ini');
                Swal.fire("Error", "There is an error", "error");
                location.reload();
            }
            });
    });
        
    $("#pilih").click(function(e) {
        e.preventDefault();
        if($("#form-pilih").parsley().validate()){
        var param = this;
        process1(param);
        var form = $('#form-pilih')[0];
        var form_data = new FormData(form);
        ax_save = $.ajax({
            type    : 'POST',
            data    : form_data,
            contentType : false,
            processData : false,
            cache: false,
            async:true,
            url     : urlPilihKandidat,
            success : function(data) {
            process_done1(param,'<i class="fa fa-save"></i> Pilih Kandidat Ini');
            if(data.status==500){
                Swal.fire("Maaf", data.message, "error");
            }else{
                Swal.fire({
                    title: "Success!",
                    text: 'Kandidat berhasil dipilih',
                    type: "success",
                    timer: 2000
                }).then((result) => {
                    location.reload();

                });
            }
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Pilih Kandidat Ini');
                $('#photo').parsley().removeError('uniqueerror', {updateClass: true});
                if( reject.status === 422 ) {
                   
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
