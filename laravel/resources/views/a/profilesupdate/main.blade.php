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
<form role="form" name="form-profiles" id="form-profiles" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" id="id" name="id" value="{{Formatter::encrypt0($q->id)}}"  readonly />
                <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="text-center p-xs-3 pt-4">
                                    <?php
                                    if(empty($q->image)){
                                    ?>
                                    <img src="{{asset('assets/images/picture.png')}}" id="img_show" class="avatar img-thumbnail mb-2" alt="avatar" style="height:200px;width:200px;object-fit: cover;">
                                    <?php
                                    }else{
                                    ?>
                                    <img src="{{asset('images/profiles/'.$q->image)}}" id="img_show" class="avatar img-thumbnail mb-2" alt="avatar" style="height:200px;width:200px;object-fit: cover;">
                                    <?php
                                    }
                                    ?>
                                    <h6 id="file_name">Upload Image</h6>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="userfile" name="userfile" accept="image/*">
                                        <label class="custom-file-label text-md-left text-left" for="customFile">Browse File</label>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="form-group">
                                <div class="col-sm-12">
                                <label>Content <span class="red"></span></label>
                                    <textarea type="text" class="form-control" name="content" id="content" placeholder="Content" required>{{$q->content}}</textarea>
                                </div>
                            </div>
                        </div>
                </div>
</form>
</div>

<div class="row mb-5 card p-3">
            <div class="modal-footer">
                <a href="{{route('users.main')}}" class="btn btn-success btn-md" name="cancel" id="cancel">
                <i class="fa fa-arrow-left"></i> Back to list</a>
                <button type="button" class="btn btn-primary btn-md" name="save" id="save">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
</div>

<script src="{{asset('assets/vendor/tinymce/tinymce.min.js')}}"></script>
<script type="text/javascript">
    tinymce.init({selector:'#content',menubar:'',theme:'modern'});
    $(document).ready(function() {
        $("#save").click(function(e) {
            e.preventDefault();
            if($('#form-profiles').parsley().validate()){
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
                url     : "{{route('profilesupdate.update')}}",
                success : function(data) {
                process_done1(param,'<i class="fa fa-save"></i> Simpan');
                    Swal.fire("Success!",'',"success");
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

