$('document').ready(function(){

    var form=$("#form-setting");
    var urlUpdate=form.data('update');

    $("#save").click(function(e) {
        e.preventDefault();
        if($('#form-setting').parsley().validate()){
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
            process_done1(param,'<i class="fa fa-save"></i> Update Akun');
                Swal.fire({
                    title: "Success!",
                    text: 'Pengaturan sukses diupdate',
                    type: "success",
                    timer: 2000
                }).then((result) => {
                    location.reload();

                });
            },
            error: function (reject) {
                process_done1(param,'<i class="fa fa-save"></i> Update');
                Swal.fire("Error", "There is an error", "error");
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

$("#userfile").change(function() {
    readURL(this);
});
