@extends('a.main')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{ $icon }} text-white">
                </i>
            </div>
            <div>{!! $title !!} <span class="text-danger font-weight-bold">{{Str::upper(Request::segment(2))}}</span>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
                <a class="btn btn-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-filter fa-w-20"></i>
                    </span>
                    Filter
                  </a>
                <a href="{{route('invite_tim.create')}}"   class="btn-shadow btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-plus-circle fa-w-20"></i>
                    </span>
                    Invite TIM
                </a>
            </div>
        </div>
    </div>
</div>

  
<div class="row">
    <div class="col-md-12 p-0">
        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <h5>Form Filter</h5>
                <form>
                    <div class="form-row">
                    @if (Session::get('role')=='a')
                        <div class="form-group col-md-3">
                            <label for="kelompok_tim_id">Kelompok TIM</label>
                            <select class="form-control filter-form select2"  name="kelompok_tim_id" id="kelompok_tim_id" style="width: 100%;">
                                <option value=""></option>
                                @foreach ($kelompok_tims as $dt )
                                    <option value="{{$dt->id}}">{{$dt->kelompok_tim}}</option>
                                @endforeach
                            
                            </select>
                        </div>
                    @endif
                      
                      <div class="form-group col-md-2">
                        <label for="kabupaten_id">Kabupaten</label>
                        <select class="form-control filter-form select2 select2-kab" name="kab_id" id="kab_id" style="width: 100%;">
                            <option value=""></option>
                        </select>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="kecamatan_id">Kecamatan</label>
                        <select class="form-control filter-form select2-kec" name="kec_id" id="kec_id" data-parsley-errors-container="#kec-errors" style="width: 100%;">
                            <option value=""></option>
                        </select>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="Kelurahan">Kelurahan</label>
                        <select class="form-control filter-form select2-kel" name="kel_id" id="kel_id" data-parsley-errors-container="#kel-errors" style="width: 100%;">
                            <option value=""></option>
                        </select>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="Kelurahan">Level</label>
                        <select class="form-control filter-form" name="level" id="level">
                            <option value="">Semua</option>
                            <option value="K">Kordinator</option>
                            <option value="R">Relawan</option>
                        </select>
                      </div>
                    </div>
                    {{-- <button type="button" id="filter" class="btn btn-secondary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-filter fa-w-20"></i>
                    </span>
                    Filter</button> --}}
                  </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 p-4 mb-3 card">
        <div class="table-action">
          <div class="text-right">
          </div>
        </div>
        <input type="hidden" id="kategori" value="{{Request::segment(2)}}">
        {{-- <div>
            Toggle column: <a class="toggle-vis" data-column="0">Name</a> - <a class="toggle-vis" data-column="1">Position</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>
        </div> --}}
        <div class="table-place-full">
            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_tim" data-list="{{route('tim.get_tim')}}" data-delete="{{route('tim.destroy')}}" data-token="{{csrf_token()}}">
                <thead class="thead-light">
                    <tr>
                    <th scope="col" class="text-center">No</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Kelompok TIM</th>
                    <th scope="col">Nama</th>
                    <th scope="col">NIK</th>
                    <th scope="col">No. Telp</th>
                    <th scope="col">Level</th>
                    <th scope="col">Area</th>
                    <th scope="col">Pendukung</th>
                    <th scope="col" class="text-center span2 datatable-nosort">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Menampilkan Data -->

<script src="{{asset('assets/js/page/tim/tim_list.js')}}"></script>
@endsection

