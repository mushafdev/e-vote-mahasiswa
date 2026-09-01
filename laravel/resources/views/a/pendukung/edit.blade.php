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
<form role="form" name="form-user" id="form-user" data-update="{{route('invite_tim.update')}}" data-main="{{route('invite_tim.main')}}" class="form-horizontal">
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
                                    <img src="{{asset('storage/images/tims/'.$q->photo)}}" id="img_show" class="avatar rounded-circle img-thumbnail mb-2" alt="avatar" style="height:200px;width:200px;object-fit: cover;">
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
                        <div class="col-md-8 col-sm-12 pt-3 pb-3">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama<span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" name="nama" id="nama"  placeholder="Nama" value="{{$q->nama}}" required>
        
                                    </div>  
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>NIK <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nik" id="nik"  placeholder="NIK" value="{{$q->nik}}" required>
    
                                    </div>
                                </div>
                            </div>
                                
                                <div class="form-group">
                                    <label>Alamat <span class="text-danger"></span></label>
                                        <textarea type="text" class="form-control" name="address" id="address" placeholder="Alamat" required>{{$q->alamat}}</textarea>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                            <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" required>
                                                <option value="">Pilih</option>
                                                @foreach ( $jenis_kelamin as $k => $v )
                                                    <option value="{{$k}}" {{($k==$q->jenis_kelamin)?'selected':''}}>{{$v}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>No. Telp <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="telp" id="telp" placeholder="No. Telp" value="{{$q->telp}}" required>
        
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="card-title">Pengaturan TIM</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kelompok TIM <span class="text-danger">*</span></label>
                                            <select class="form-control select2" name="kelompok_tim_id" id="kelompok_tim_id" required data-parsley-errors-container="#kelompok-tim-errors">
                                                <option value="">Pilih</option>
                                                @foreach ($group_tims as $k => $v )
                                                    <optgroup label="{{$v}}">
                                                        @foreach ($kelompok_tims->where('kategori',$k) as $dt )
                                                            <option value="{{$dt->id}}" {{($dt->id==$q->kelompok_tim_id)?'selected':''}}>{{$dt->kelompok_tim}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                               
                                            </select>
                                            <span id="kelompok-tim-errors"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Level <span class="text-danger">*</span></label>
                                            <select class="form-control" name="level" id="level" required>
                                                <option value="">Pilih</option>
                                                @foreach ( $level as $k => $v )
                                                    <option value="{{$k}}" {{($k==$q->level)?'selected':''}}>{{$v}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title">Area Cover</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kabupaten</label>
                                            <select class="form-control select2 select2-kab" name="kab_id" id="kab_id" data-parsley-errors-container="#kabupaten-errors">
                                                <option value="{{$q->kab_id}}">{{$q->kabupaten}}</option>
                                            </select>

                                            <span id="kabupaten-errors"></span>
                                            <span class="text-info"><i>*Kosongkan jika tim dapat melihat semua kabupaten</i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kecamatan</label>
                                            <select class="form-control select2-kec" name="kec_id" id="kec_id" data-parsley-errors-container="#kec-errors">
                                                <option value="{{$q->kec_id}}">{{$q->kecamatan}}</option>
                                            </select>

                                            <span id="kec-errors"></span>
                                            <span class="text-info"><i>*Kosongkan jika tim dapat melihat semua kecamtan</i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kelurahan/Desa</label>
                                            <select class="form-control select2-kel" name="kel_id" id="kel_id" data-parsley-errors-container="#kel-errors">
                                                <option value="{{$q->kel_id}}">{{$q->kelurahan}}</option>
                                            </select>

                                            <span id="kel-errors"></span>
                                            <span class="text-info"><i>*Kosongkan jika tim dapat melihat semua kelurahan/desa</i></span>
                                        </div>
                                    </div>
                                </div>                              



                        </div>
                </div>
</form>
</div>

<div class="row mb-5 card p-3">
            <div class="modal-footer">
                <a href="{{route('invite_tim.main')}}" class="btn btn-success btn-md" name="cancel" id="cancel">
                <i class="fa fa-arrow-left"></i> Kembali Ke Daftar</a>
                <button type="button" class="btn btn-primary btn-md" name="update" id="update">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
</div>


<script src="{{asset('assets/js/page/user/user.js')}}"></script>

@endsection

