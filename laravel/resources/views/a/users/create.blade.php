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
<form role="form" name="form-user" id="form-user" data-save="{{route('users.store')}}" data-main="{{route('users.main')}}" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" id="id" name="id"  readonly />
                <div class="row">
                        <div class="col-md-4 col-sm-12 bg-grey">
                            <div class="text-center p-xs-3 pt-4">
                                    <img src="{{asset('assets/images/user.png')}}" id="img_show" class="avatar rounded-circle img-thumbnail mb-2" alt="avatar" style="height:200px;width:200px;object-fit: cover;">
                                    <h6 id="file_name">Upload Foto</h6>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*">
                                        <label class="custom-file-label text-md-left text-left" for="customFile">Cari File</label>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-8 col-sm-12 p-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama<span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" name="name" id="name"  placeholder="Nama" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email"  placeholder="email@email.com" required>
    
                                    </div>
                                </div>
                            </div>
                                
                                <div class="form-group">
                                    <label>Alamat <span class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" name="address" id="address" placeholder="Alamat" required></textarea>

                                </div>
                            <div class="form-group">
                                    <label>No. HP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="contact" id="contact" placeholder="No. HP" required>

                                </div>
                                <div class="form-group">
                                    <label>Username<span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>

                                </div>
                                <div class="form-group">
                                    <label>Password <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" autocomplete="off" name="password" id="password" placeholder="Password" required
                                        data-parsley-minlength="8"
                                        data-parsley-uppercase="1"
                                        data-parsley-lowercase="1"
                                        data-parsley-number="1"
                                        data-parsley-special="1"
                                        >

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Level </label>
                                            <select class="form-control" id="level" name="level" required>
                                                <?php
                                                $d_level=array('a'=>'Admin', 'p' =>'Panlih','kp'=>'Ketua Panlih','su'=>'Super Admin','v'=>'Verifikator Suara');
                                                foreach($d_level as $akt => $value):
                                                echo '<option value="'.$akt.'">'.$value.'</option>';
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status </label>
                                            <select class="form-control" id="active" name="active">
                                                <?php
                                                $d_active=array('Aktif' =>'Y' ,'Tidak Aktif'=>'N');
                                                foreach($d_active as $akt => $value):
                                                echo '<option value="'.$value.'">'.$akt.'</option>';
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>


                        </div>
                </div>
</form>
</div>

<div class="row mb-5 card p-3">
            <div class="modal-footer">
                <a href="{{route('users.main')}}" class="btn btn-success btn-md" name="cancel" id="cancel">
                <i class="fa fa-arrow-left"></i> Kembali Ke Daftar</a>
                <button type="button" class="btn btn-primary btn-md" name="save" id="save">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
</div>

<script src="{{asset('assets/js/page/user/user.js')}}"></script>
@endsection

