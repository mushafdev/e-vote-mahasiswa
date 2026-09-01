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
<form role="form" name="form-setting" id="form-setting" class="form-horizontal" data-update="{{route('setting.update')}}">
                {{ csrf_field() }}
                <input type="hidden" id="id" name="id" value=""  readonly />
                <div class="row">
                        <div class="col-md-12 col-sm-12 pt-3 pb-3">
                            <h5 class="card-title ml-md-3 mt-4">Update pengaturan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title ml-md-3 mt-4">Nomor Rekening Kategori Barang</h5>
                                    <table class="table m-3">
                                        <thead>

                                             {{-- // New Update --}}
                                            <tr>
                                                <th rowspan="2" class="text-center align-middle">Kategori Barang</th>
                                                <th class="text-center">No. Rekening</th>
                                            </tr>
                                            <tr>
                                                <th>Debet</th>
                                                <th>Kredit</th>
                                            </tr>
                                            {{-- // New Update --}}
                                        </thead>
                                        <tbody>
                                                @foreach ($kategories as $dt )
                                                <tr>
                                                    <td>{{$dt->kategori_barang}}</td>
                                                    <td><input type="text"  class="form-control" id="kategori_norek_{{$dt->id}}" name="kategori_norek[{{$dt->id}}]" placeholder="No. Rekening" value="{{$dt->norek}}"></td>

                                                    {{-- // New Update --}}
                                                    <td><input type="text"  class="form-control" id="kategori_norek_debet_{{$dt->id}}" name="kategori_norek_debet[{{$dt->id}}]" placeholder="No. Rekening" value="{{$dt->norek_debet}}"></td>
                                                    {{-- // New Update --}}
                                                </tr>
                                                @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div class="col-md-6">
                                    <h5 class="card-title ml-md-3 mt-4">Nomor Rekening Sub Kategori Barang</h5>
                                    <table class="table m-3">
                                        <thead>
                                            {{-- // New Update --}}
                                            <tr>
                                                <th rowspan="2" class="text-center align-middle">Sub Kategori Barang</th>
                                                <th class="text-center">No. Rekening</th>
                                            </tr>
                                            <tr>
                                                <th>Debet</th>
                                                <th>Kredit</th>
                                            </tr>
                                            {{-- // New Update --}}
                                        </thead>
                                        <tbody>
                                                @foreach ($subkategories as $dt )
                                                <tr>
                                                    <td>{{$dt->sub_kategori_barang}}</td>
                                                    <td><input type="text"  class="form-control" id="sub_kategori_norek_{{$dt->id}}" name="sub_kategori_norek[{{$dt->id}}]" placeholder="No. Rekening" value="{{$dt->norek}}"></td>
                                                     {{-- // New Update --}}
                                                     <td><input type="text"  class="form-control" id="sub_kategori_norek_debet_{{$dt->id}}" name="sub_kategori_norek_debet[{{$dt->id}}]" placeholder="No. Rekening" value="{{$dt->norek_debet}}"></td>
                                                     {{-- // New Update --}}
                                                </tr>
                                                @endforeach
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                </div>
</form>
</div>

<div class="row mb-5 card p-3">
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-md" name="save" id="save">
                    <i class="fa fa-save"></i> Update
                </button>
            </div>
</div>

<script src="{{asset('assets/js/page/setting/setting.js')}}"></script>
@endsection

