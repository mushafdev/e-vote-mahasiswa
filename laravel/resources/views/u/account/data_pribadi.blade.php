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
<form role="form" name="form-team" id="form-team" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" id="id" name="id" value="{{Formatter::encrypt0($q->id)}}"  readonly />
                <div class="row">
                        <div class="col-md-4 col-sm-12 bg-grey  pb-3">
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
                                        <label class="custom-file-label text-md-left text-left" for="customFile">Cari File</label>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-8 col-sm-12 pt-0 pb-3">
                            <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                                <li class="nav-item">
                                    <a role="tab" class="nav-link show active" id="tab-0" data-toggle="tab" href="#data-1" aria-selected="true">
                                        <span>Data Pribadi</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#data-2" aria-selected="false">
                                        <span>Data Pekerjaan</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#data-3" aria-selected="false">
                                        <span>Dokumen Persyaratan</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane tabs-animation fade active show" id="data-1" role="tabpanel">
                                    <div class="row p-2">
                                        <div class="col-md-12 mb-2">
                                            <h5 class="card-title">Data Pribadi</h5>
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                    <label>Nomor Anggota </label>
                                                    <div class="row">
                                                        <div class="col-6 pr-1">
                                                            <input type="text" class="form-control" name="no_anggota1" id="no_anggota1" value="{{$q->no_anggota1}}"  placeholder="- - - - - -" readonly required>
                                                        </div>
                                                        <div class="col-6 pl-1">
                                                            <input type="text" class="form-control" name="no_anggota2" id="no_anggota2" value="{{$q->no_anggota2}}"  placeholder="- - -   - - - -" required>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                    <label>Nama </label>
                                                    <input type="text" class="form-control" name="nama" id="nama" value="{{$q->nama}}"  placeholder="Nama" required>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                    <label>Tempat dan Tanggal Lahir </label>
                                                    <div class="row">
                                                        <div class="col-6 pr-1">
                                                            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="{{$q->tempat_lahir}}"  placeholder="Tempat Lahir" >
                                                        </div>
                                                        <div class="col-6 pl-1">
                                                            <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" value="{{$q->tanggal_lahir}}"  placeholder="DD/MM/YYYY" >
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>No. KTP <span class="red"></span></label>
                                                <input type="text" class="form-control" name="no_ktp" id="no_ktp" value="{{$q->no_ktp}}"  placeholder="No. KTP">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email <span class="red"></span></label>
                                                <input type="email" class="form-control" name="email" id="email" value="{{$q->email}}"  placeholder="email@email.com">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Telphone (No. WA)<span class="red"></span></label>
                                                <input type="number" class="form-control" name="telephone" id="telephone" value="{{$q->telephone}}"  placeholder="Telephone">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Alamat <span class="red"></span></label>
                                                <textarea type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" >{{$q->alamat}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Kab/Kota <span class="red"></span></label>
                                                <select class="form-control select2" id="kab_kota" name="kab_kota" style="width: 100%">
                                                    <option value="">--Pilih Kab/Kota--</option>
                                                    @foreach ($regencies as $dt )

                                                        <option value="{{$dt->name}}">{{$dt->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Kode Pos <span class="red"></span></label>
                                                <input type="number" class="form-control" name="kode_pos" id="kode_pos" value="{{$q->kode_pos}}"  placeholder="Kode Pos">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Pekerjaan <span class="red"></span></label>
                                                <input type="text" class="form-control" name="pekerjaan" id="pekerjaan" value="{{$q->pekerjaan}}"  placeholder="Pekerjaan">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Fax <span class="red"></span></label>
                                                <input type="text" class="form-control" name="fax" id="fax" value="{{$q->fax}}"  placeholder="Fax">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-3">
                                        <div class="modal-footer w-100">

                                            <button type="button" class="btn btn-primary btn-md" name="save" id="save">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane tabs-animation fade" id="data-2" role="tabpanel">
                                    <div class="row p-2">
                                        <div class="col-md-12 mb-2 mt-2">
                                            <h5 class="card-title">Data Pekerjaan</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="">Jenis Kantor </label>
                                                <select class="form-control" id="pekerjaan_jenis_kantor" name="pekerjaan_jenis_kantor" >
                                                    <option value="">--Pilih--</option>
                                                    @php
                                                        $jenis_kantors=array('Biro Arsitek','Perorangan', 'Lain-lain');
                                                    @endphp
                                                    @foreach ($jenis_kantors as $dt =>$val )
                                                        <option value="{{$val}}" {{($val==$q->pekerjaan_jenis_kantor)?'selected':''}} >{{$val}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="">Status Perusahaan </label>
                                                <select class="form-control" id="pekerjaan_status_perusahaan" name="pekerjaan_status_perusahaan" >
                                                    <option value="">--Pilih--</option>
                                                    @php
                                                        $status_kantors=array('PT','CV', 'Lain');
                                                    @endphp
                                                    @foreach ($status_kantors as $dt =>$val )

                                                        <option value="{{$val}}" {{($val==$q->pekerjaan_status_perusahaan)?'selected':''}}>{{$val}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="">Bidang Usaha </label>
                                                <input type="text" class="form-control" name="pekerjaan_bidang_usaha" id="pekerjaan_bidang_usaha" value="{{$q->pekerjaan_bidang_usaha}}"  placeholder="Bidang Usaha" >
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="">Nama Atasan </label>
                                                <input type="text" class="form-control" name="pekerjaan_atasan" id="pekerjaan_atasan" value="{{$q->pekerjaan_atasan}}"  placeholder="Nama Atasan" >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="">Jabatan </label>
                                                <input type="text" class="form-control" name="pekerjaan_jabatan" id="pekerjaan_jabatan" value="{{$q->pekerjaan_jabatan}}"  placeholder="Jabatan" >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="">Tgl Mulai Kerja </label>
                                                <input type="date" class="form-control" name="pekerjaan_mulai_kerja" id="pekerjaan_mulai_kerja" value="{{$q->pekerjaan_mulai_kerja}}"  placeholder="" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                    <label>Alamat Pekerjaan </label>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="label-2">Alamat </label>
                                                            <textarea type="text" class="form-control" name="pekerjaan_alamat" id="pekerjaan_alamat" placeholder="Alamat" >{{$q->pekerjaan_alamat}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-6 pr-md-1">
                                                            <label class="label-2 w-100">Kab/Kota </label>
                                                            <select class="form-control select2 w-100" id="pekerjaan_kab_kota" name="pekerjaan_kab_kota" style="width: 100%" >
                                                                <option value="">--Pilih Kab/Kota--</option>
                                                                @foreach ($regencies as $dt )
                                                                    <option value="{{$dt->name}}">{{$dt->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 pl-md-1">
                                                            <label class="label-2">Kode Pos </label>
                                                            <input type="text" class="form-control" name="pekerjaan_kode_pos" id="pekerjaan_kode_pos" value="{{$q->pekerjaan_kode_pos}}"  placeholder="Kode Pos" >
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-4 pr-md-1">
                                                            <label class="label-2">Telphone </label>
                                                            <input type="text" class="form-control" name="pekerjaan_telephone" id="pekerjaan_telephone" value="{{$q->pekerjaan_telephone}}"  placeholder="Telphone" >
                                                        </div>
                                                        <div class="col-md-4 pl-md-1 pr-md-1">
                                                            <label class="label-2">Fax</label>
                                                            <input type="text" class="form-control" name="pekerjaan_fax" id="pekerjaan_fax" value="{{$q->pekerjaan_fax}}"  placeholder="Fax" >
                                                        </div>
                                                        <div class="col-md-4 pl-md-1">
                                                            <label class="label-2">Email </label>
                                                            <input type="text" class="form-control" name="pekerjaan_email" id="pekerjaan_email" value="{{$q->pekerjaan_email}}"  placeholder="Email" >
                                                        </div>
                                                        <div class="col-md-12 mt-1">
                                                            <label class="label-2">Website </label>
                                                            <input type="text" class="form-control" name="pekerjaan_website" id="pekerjaan_website" value="{{$q->pekerjaan_website}}"  placeholder="Website" >
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-3">
                                        <div class="modal-footer w-100">

                                            <button type="button" class="btn btn-primary btn-md" name="save_work" id="save_work">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane tabs-animation fade" id="data-3" role="tabpanel">
                                    <div class="row p-2">
                                        <div class="col-md-12 mb-2">
                                            <h5 class="card-title">Dokumen Persyaratan</h5>
                                        </div>
                                        <div class="col-md-12">

                                            <div class="d-grid">

                                                <a class="text-info mb-3" href="{{asset('storage/files/16421404294(1).pdf')}}" target="_blank">Klik untuk download <b>Formulir Anggota Baru</b></a>
                                                <div class="table-responsive">

                                                    <table class="table table-bordered table-striped table-hover w-100" id="t_syarat_dokumen">
                                                        <thead class="thead-light">
                                                        <tr>
                                                          <th class="text-center">No</th>
                                                          <th class="text-center">Syarat Dokumen</th>
                                                          <th class="text-center">File</th>
                                                          <th class="text-center">Aksi</th>
                                                          <th class="text-center">Status</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-3">
                                        <div class="progress w-100" style="display:none;">
                                            <div class="progressBar progress-bar bg-warning active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                                <span class="sr-only">0%</span>
                                            </div>
                                        </div>
                                        <div class="modal-footer w-100">
                                            <button type="button" class="btn btn-primary btn-md" name="upload_dokumen_persyaratan" id="upload_dokumen_persyaratan">
                                                <i class="fa fa-upload"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>


                </div>

</form>
</div>


<script>
    $('document').ready(function(){
        $("#kab_kota").val('{{$q->kab_kota}}');
        $("#pekerjaan_kab_kota").val('{{$q->pekerjaan_kab_kota}}');
        $("#pengurus").val('{{$q->pengurus}}');


        table_syarat_dokumen();

        $("#save,#save_work").click(function(e) {
            e.preventDefault();
            if($('#form-team').parsley().validate()){
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
                url     : "{{route('teams.update')}}",
                success : function(data) {
                process_done1(param,'<i class="fa fa-save"></i> Simpan');
                Swal.fire({
                        title: "Success!",
                        text: 'Data sukses diupdate',
                        type: "success",
                        timer: 2000
                    }).then((result) => {

                    });
                },
                error: function (reject) {
                    process_done1(param,'<i class="fa fa-save"></i> Simpan');
                    $('#no_anggota1').parsley().removeError('uniqueerror', {updateClass: true});
                    if( reject.status === 422 ) {
                        var data=reject.responseText;
                        var jsonResponse = JSON.parse(data);
                        var no_anggota1Error=jsonResponse.errors.no_anggota1;
                        $('#no_anggota1').parsley().addError('uniqueerror', {message: no_anggota1Error, updateClass: true});
                    }else{
                        Swal.fire("Error", "There is an error", "error");
                    }
                }
            });
            }else {
            return false();
            }

        });

        $("#upload_dokumen_persyaratan").click(function(e) {
            e.preventDefault();
                $('.progress').show();
                var param = this;
                process1(param);
                var form = $('form')[0];
                var form_data = new FormData(form);
			    form_data.append("id_anggota", '{{Formatter::encrypt0($q->id)}}');
              $.ajax({
                xhr : function() {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener('progress', function(e){
						if(e.lengthComputable){
							console.log('Bytes Loaded : ' + e.loaded);
							console.log('Total Size : ' + e.total);
							console.log('Persen : ' + (e.loaded / e.total));

							var percent = Math.round((e.loaded / e.total) * 100);

							$('.progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
						}
					});
					return xhr;
				},
                type    : 'POST',
                data    : form_data,
                contentType : false,
                processData : false,
                cache: false,
                async:true,
                dataType:'json',
                url     : "{{route('teams.store_syarat_dokumen')}}",
                success : function(data) {
                    table_syarat_dokumen();
                    Swal.fire({
                        title: "Success!",
                        text: 'Sukses upload',
                        type: "success",
                        timer: 2000
                    }).then((result) => {

                    });
				    $('.progress').hide();
                    process_done1(param,'<i class="fa fa-upload"></i> Upload');
                },
                error: function (reject) {
                    process_done1(param,'<i class="fa fa-upload"></i> Upload');
                        Swal.fire("Error", "There is an error", "error");
                }
              });

          });


        if('{{$q->pengurus}}'=='y'){
            $("#jabatan").prop('readonly',false);
            $("#jabatan").prop('required',true);
        }else{
            $("#jabatan").prop('readonly',true);
            $("#jabatan").prop('required',false);
            $("#jabatan").val('');
        }


        $("#pengurus").change(function() {
            if($(this).val()=='y'){
                $("#jabatan").prop('readonly',false);
                $("#jabatan").prop('required',true);
            }else{
                $("#jabatan").prop('readonly',true);
                $("#jabatan").prop('required',false);
                $("#jabatan").val('');
            }
        });

    });


    function table_syarat_dokumen(){
      $.ajax({
            type    : 'GET',
            data    : {id:'{{Formatter::encrypt0(Session::get('id'))}}'},
            url     : "{{route('teams.get_data_syarat_dokumen')}}",
            dataType: 'json',
            success : function(data) {
              $('#t_syarat_dokumen tbody').html(data.table);
            }
          });
    }

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

