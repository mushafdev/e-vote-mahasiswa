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
<form role="form" name="form-kandidat" id="form-kandidat" data-save="{{route('kandidat.store')}}" data-main="{{route('kandidat.main')}}" class="form-horizontal">
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
                                        <input type="text" class="form-control" name="nama" id="nama"  placeholder="Nama" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>NIM/No. Identitas <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="no_anggota" id="no_anggota"  placeholder="NIM/No. Identitas" required>
    
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>No. urut <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="no_urut" id="no_urut"  placeholder="No. urut" required>
    
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Calon Kandidat Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="calon_kandidat_status" name="calon_kandidat_status" required>
                                            @foreach ($calon_kandidat_status as $k => $v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kandidat Status<span class="text-danger">*</span></label>
                                        <select class="form-control" id="kandidat_status" name="kandidat_status" required>
                                            @foreach ($kandidat_status as $k => $v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
    
                                    </div>
                                </div>
                            </div>
                                
                                <div class="form-group">
                                    <label>Deskripsi <span class="text-danger">*</span></label>
                                    <div id="toolbar-container"></div>
                                    <div class="" id="editor"></div>

                                </div>


                        </div>
                </div>
</form>
</div>

<div class="row mb-5 card p-3">
            <div class="modal-footer">
                <a href="{{route('kandidat.main')}}" class="btn btn-success btn-md" name="cancel" id="cancel">
                <i class="fa fa-arrow-left"></i> Kembali Ke Daftar</a>
                <button type="button" class="btn btn-primary btn-md" name="save" id="save">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
</div>

{{-- <script src="{{asset('assets/vendor/ckeditor5Document/ckeditor.js')}}"></script> --}}
<script src="{{asset('assets/vendor/ckeditor4/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/page/kandidat/kandidat.js')}}"></script>
@endsection

