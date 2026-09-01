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
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                          <label>Pajak(%)</label>
                                            <input type="number" class="form-control" name="pajak" id="pajak" Placeholder="" value="{{$setting->pajak}}" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                          <label>Persediaan Unit Kerja</label>
                                          <select class="form-control" name="persediaan_kp" id="persediaan_kp">
                                            
                                            @foreach ($persediaan_kp as $k => $v )
                                                <option value="{{$k}}" {{$k==$setting->persediaan_kp?'selected':''}}>{{$v}}</option>
                                            @endforeach
                                          </select>
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
                    <i class="fa fa-save"></i> Update
                </button>
            </div>
</div>

<script src="{{asset('assets/js/page/setting/setting.js')}}"></script>
@endsection

