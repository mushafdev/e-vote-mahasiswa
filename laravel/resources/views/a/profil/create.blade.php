@extends('a.main')

@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{$icon}} text-white">
                </i>
            </div>
            <div>{{$title}}
            </div>
        </div>
        <div class="page-title-actions">
        <div class="text-right">
        </div>
        </div>
    </div>
</div>
<div class="row mb-2 card p-3">
<form role="form" name="form-profilupdate" id="form-profilupdate" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" id="id" name="id"  readonly />
                <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="text-center p-xs-3 pt-4">
                                    <img src="{{asset('assets/images/picture.png')}}" id="img_show" class="avatar img-thumbnail mb-2" alt="avatar" style="height:200px;width:200px;object-fit: cover;">
                                    <h6 id="file_name">Upload Gambar</h6>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="userfile" name="userfile" accept="image/*">
                                        <label class="custom-file-label text-md-left text-left" for="customFile">Browse File</label>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Judul </label>
                                        <input type="text" class="form-control" name="name" id="name"  placeholder="Judul" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Urut </label>
                                        <input type="number" class="form-control" name="urut" id="urut"  placeholder="Urut" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label>Konten <span class="red"></span></label>
                                            <textarea type="text" class="form-control" name="content" id="content" placeholder="Konten"></textarea>
                                        </div>
                                    </div>
                                <div class="col-sm-12">
                                    <label>Status </label>
                                    <select class="form-control" name="active" id="active">
                                        <option value="Y">Aktif</option>
                                        <option value="N">Tidak Aktif</option>
                                    </select>
                                    </div>
                                </div>
                        </div>
                </div>
</form>
</div>

<div class="row mb-5 card p-3">
            <div class="modal-footer">
                <a href="{{route('profilupdate.main')}}" class="btn btn-success btn-md" name="cancel" id="cancel">
                <i class="fa fa-arrow-left"></i> Kembali Ke Daftar</a>
                <button type="button" class="btn btn-primary btn-md" name="save" id="save">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
</div>

<script src="{{asset('assets/vendor/tinymce/tinymce.min.js')}}"></script>
<script>
    tinymce.init({selector:'#content',menubar:'',theme:'modern'});
    $('document').ready(function(){
        $("#save").click(function(e) {
            e.preventDefault();
            if($('#form-profilupdate').parsley().validate()){
            var param = this;
            process1(param);
            tinymce.triggerSave(true,true);
            var form = $('form')[0];
            var form_data = new FormData(form);
            ax_save = $.ajax({
                type    : 'POST',
                data    : form_data,
                contentType : false,
                processData : false,
                cache: false,
                async:true,
                url     : "{{route('profilupdate.store')}}",
                success : function(data) {
                process_done1(param,'<i class="fa fa-save"></i> Simpan');
                Swal.fire({
                        title: "Success!",
                        text: data,
                        type: "success",
                        timer: 2000
                    }).then((result) => {
                        location.replace("{{route('profilupdate.main')}}");

                    });
                },
                error: function (reject) {
                    process_done1(param,'<i class="fa fa-save"></i> Simpan');
                    $('#username').parsley().removeError('uniqueerror', {updateClass: true});
                    if( reject.status === 422 ) {
                        var data=reject.responseText;
                        var jsonResponse = JSON.parse(data);
                        var usernameError=jsonResponse.errors.username;
                        $('#username').parsley().addError('uniqueerror', {message: usernameError, updateClass: true});
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

    $("#userfile").change(function() {
        readURL(this);
    });
</script>
@endsection

