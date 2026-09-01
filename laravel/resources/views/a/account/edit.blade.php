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
<div class="row mb-2 card p-0">
<form role="form" name="form-user" id="form-user" class="form-horizontal" data-update="{{route('accountupdate.update')}}">
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
                                    <img src="{{asset('storage/images/users/'.$q->photo)}}" id="img_show" class="avatar rounded-circle img-thumbnail mb-2" alt="avatar" style="height:200px;width:200px;object-fit: cover;">
                                    <?php
                                    }
                                    ?>
                                    <h6 id="file_name">Upload Foto</h6>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*">
                                        <label class="custom-file-label text-md-left text-left" for="customFile">Browse File</label>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-8 col-sm-12 form-account-custom p-3">
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                                <label>Nama </label>
                                                <input type="text" class="form-control" name="name" id="name"  placeholder="Nama" value="{{$q->nama}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email <span class="red"></span></label>
                                                <input type="email" class="form-control" name="email" id="email" value="{{$q->email}}"  placeholder="email@email.com">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Alamat <span class="red"></span></label>
                                        <textarea type="text" class="form-control" name="address" id="address" placeholder="Alamat" required>{{$q->alamat}}</textarea>
                                    
                                </div>
                            <div class="form-group">
                                <label>No. HP</label>
                                    <input type="text" class="form-control" name="contact" id="contact" placeholder="No. HP" required value="{{$q->kontak}}">
                                
                            </div>
                            <h5 class="card-title ml-md-3 mt-4">Username dan Password</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label>Username</label>
                                            <input type="text" class="form-control" name="username_a" id="username_a" Placeholder="Username" value="{{$q->username}}" required readonly>
                                        
                                    </div>
                                    <div class="form-group">
                                          <label>New Username</label>
                                            <input type="text" class="form-control" name="username" id="username_a2" Placeholder="New Username">
                                        
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label>Password</label>
                                            <input type="text" class="form-control" name="password1" id="password1" Placeholder="Password" 
                                            data-parsley-minlength="8"
                                            data-parsley-uppercase="1"
                                            data-parsley-lowercase="1"
                                            data-parsley-number="1"
                                            data-parsley-special="1"
                                            >
                                    </div>
                                    <div class="form-group">
                                          <label>Confirm Password</label>
                                            <input type="text" class="form-control" name="password" id="password" Placeholder="Password"  data-parsley-equalto="#password1">
                                    </div>

                                </div>

                            </div>
                        </div>
                </div>
</form>
</div>

<div class="row mb-5 card p-3 account-page">
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md" name="save" id="save">
                    <i class="fa fa-save"></i> Update Akun
                </button>
            </div>
</div>

<script src="{{asset('assets/js/page/user/account.js')}}"></script>
@endsection

