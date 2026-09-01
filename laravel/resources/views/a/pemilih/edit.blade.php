@extends('a.main')

@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{$icon}} text-white">
                </i>
            </div>
            <div>{!!$title!!}
            </div>
        </div>
        <div class="page-title-actions">
        <div class="text-right">
        </div>
        </div>
    </div>
</div>
<form role="form" name="form-pemilih" id="form-pemilih" data-update="{{route('pemilih.update')}}" data-main="{{route('pemilih.main')}}" class="form-horizontal">
{{ csrf_field() }}
<input type="hidden" id="id" name="id" value="{{Formatter::encrypt0($q->id)}}"  readonly />
<div class="row card">
        <div class="col-md-12 col-sm-12 p-4">

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nama<span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" name="nama" id="nama" value="{{$q->nama}}"  placeholder="Nama" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>NIK <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="nik" id="nik" value="{{$q->nik}}"  placeholder="NIK" required>

                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" required>
                            <option value="">Pilih</option>
                            @foreach ($jenis_kelamin as $k => $v )
                                <option value="{{$k}}" {{$k==$q->jenis_kelamin?'selected':''}}>{{$v}}</option>
                            @endforeach

                        </select>

                    </div>
                </div>
                
            </div>
                
                <div class="form-group">
                    <label>Alamat <span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" required>{{$q->alamat}}</textarea>

                </div>
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>RW</label>
                            <input type="text" class="form-control" name="rw" id="rw" placeholder="RW" value="{{$q->rw}}" >
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>RT</label>
                            <input type="text" class="form-control" name="rt" id="rt" placeholder="RT" value="{{$q->rt}}" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir" value="{{$q->tempat_lahir}}" >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tgl. Lahir</label>
                            <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" placeholder="" value="{{$q->tgl_lahir}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>No. Telp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="telp" id="telp" placeholder="No. Telp" value="{{$q->telp}}" required>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Email</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="{{$q->email}}">

                        </div>
                    </div>
                </div>
                <hr>
            
                <h5 class="card-title">Daerah</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kabupaten</label>
                            <select class="form-control select2 select2-kab" name="kab_id" id="kab_id" data-parsley-errors-container="#kabupaten-errors" style="width:100%" required>
                                <option value="{{$q->kab_id}}">{{$q->kabupaten}}</option>
                            </select>

                            <span id="kabupaten-errors"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <select class="form-control select2-kec" name="kec_id" id="kec_id" data-parsley-errors-container="#kec-errors" style="width:100%" required>
                                <option value="{{$q->kec_id}}">{{$q->kecamatan}}</option>
                            </select>

                            <span id="kec-errors"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kelurahan/Desa</label>
                            <select class="form-control select2-kel" name="kel_id" id="kel_id" data-parsley-errors-container="#kel-errors" style="width:100%" required>
                                <option value="{{$q->kel_id}}">{{$q->kelurahan}}</option>
                            </select>

                            <span id="kel-errors"></span>
                        </div>
                    </div>
                </div>

        </div>
</div>
</form>

<div class="row mb-5 card p-3">
            <div class="modal-footer">
                <a href="{{route('pemilih.main')}}" class="btn btn-success btn-md" name="cancel" id="cancel">
                <i class="fa fa-arrow-left"></i> Kembali Ke Daftar</a>
                <button type="button" class="btn btn-primary btn-md" name="update" id="update">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
</div>

<script src="{{asset('assets/js/page/pemilih/pemilih.js')}}?v=2212"></script>
@endsection

