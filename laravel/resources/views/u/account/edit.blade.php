@extends('u.main')

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
<div class="row mb-2 card p-0">
<form role="form" name="form-user" id="form-user" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" id="id" name="id" value="{{Formatter::encrypt0($q->id)}}"  readonly />
                <div class="row">
                        <div class="col-md-4 col-sm-12 bg-grey">
                            <div class="text-center p-xs-3 pt-4">
                                    <?php
                                    if(empty($q->photo)){
                                    ?>
                                    <img src="{{asset('assets/images/user.png')}}" id="img_show" class="avatar rounded-circle img-thumbnail mb-2" alt="avatar" style="height:200px;width:200px;object-fit: cover;">
                                    <?php
                                    }else{
                                    ?>
                                    <img src="{{asset('storage/images/teams/'.$q->photo)}}" id="img_show" class="avatar rounded-circle img-thumbnail mb-2" alt="avatar" style="height:200px;width:200px;object-fit: cover;">
                                    <?php
                                    }
                                    ?>
                                    <h6 id="file_name">Upload Foto</h6>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="userfile" name="userfile" accept="image/*">
                                        <label class="custom-file-label text-md-left text-left" for="customFile">Browse File</label>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-8 col-sm-12 pt-3 pb-3">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Nama </label>
                                        <input type="text" class="form-control" name="name" id="name"  placeholder="Nama" value="{{$q->nama}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                    <label>Email <span class="red"></span></label>
                                        <input type="email" class="form-control" name="email" id="email" value="{{$q->email}}"  placeholder="email@email.com">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                    <label>Alamat <span class="red"></span></label>
                                        <textarea type="text" class="form-control" name="address" id="address" placeholder="Alamat" required>{{$q->alamat}}</textarea>
                                    </div>
                                </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                <label>Kontak</label>
                                    <input type="text" class="form-control" name="contact" id="contact" placeholder="Kontak" required value="{{$q->telephone}}">
                                </div>
                            </div>
                            <h5 class="card-title ml-md-3 mt-4">Username dan Password</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                          <label>Username</label>
                                            <input type="text" class="form-control" name="username_a" id="username_a" Placeholder="Username" value="{{$q->username}}" required readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                          <label>New Username</label>
                                            <input type="text" class="form-control" name="username" id="username_a2" Placeholder="New Username">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                          <label>Password</label>
                                            <input type="text" class="form-control" name="password1" id="password1" Placeholder="Password" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                          <label>Confirm Password</label>
                                            <input type="text" class="form-control" name="password" id="password" Placeholder="Password"  data-parsley-equalto="#password1">
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                </div>
</form>
</div>

<div class="row mb-5 card p-3">
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md" name="save" id="save">
                    <i class="fa fa-save"></i> Update Akun
                </button>
            </div>
</div>


<script>
    $('document').ready(function(){
        $("#save").click(function(e) {
            e.preventDefault();
            $('#username_a2').parsley().removeError('uniqueerror', {updateClass: true});
            $('#password').parsley().removeError('uniqueerror', {updateClass: true});
            if($('#form-user').parsley().validate()){
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
                url     : "{{route('account.update')}}",
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
                    if( reject.status === 422 ) {
                        var data=reject.responseText;
                        var jsonResponse = JSON.parse(data);
                        var usernameError=jsonResponse.errors.username;
                        var passwordError=jsonResponse.errors.password;
                        if(usernameError !== undefined){
                            $('#username_a2').parsley().addError('uniqueerror', {message: usernameError, updateClass: true});
                        }
                        if(passwordError !== undefined){
                            $('#password').parsley().addError('uniqueerror', {message: passwordError, updateClass: true});
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

    $("#userfile").change(function() {
        readURL(this);
    });
</script>
@endsection

