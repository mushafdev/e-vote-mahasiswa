@extends('a.main')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{ $icon }} text-white">
                </i>
            </div>
            <div>{!! $title !!}
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
                  @if (Session::get('role')=='a')
                  <a href="{{route('pendukung.invite_pendukung')}}"   class="btn-shadow btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-plus-circle fa-w-20"></i>
                    </span>
                    Invite Pendukung
                </a>  
                  @endif
                
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 p-0">
        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <h5>Form Filter</h5>
                <form action="#">
                    <div class="form-row">
                        @if (Session::get('role')=='a')
                        <div class="form-group col-md-3">
                            <label for="kelompok_tim_id">Kelompok TIM</label>
                            <select class="form-control filter-form select2-tim"  name="kelompok_tim_id" id="kelompok_tim_id" style="width: 100%;">
                                <option value=""></option>
                                
                            </select>
                        </div>
                        @endif
                      
                        <div class="form-group col-md-4">
                            <label for="kabupaten_id">Referensi</label>
                            <select class="form-control filter-form select2-ref-all" name="tim_id" id="tim_id" style="width: 100%;">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="kk">No. KK</label>
                            <input type="number" class="form-control filter-form" name="nkk" id="nkk">
                          </div>
                      <div class="form-group col-md-3">
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
                        <label for="tps">TPS</label>
                        <input type="number" class="form-control filter-form" name="tps" id="tps">
                      </div>
                      <div class="form-group col-md-2">
                        <label for="Kelurahan">Kategori Pendukung</label>
                        <select class="form-control filter-form" name="kategori_pendukung" id="kategori_pendukung">
                            <option value="">Semua</option>
                            <option value="S">Strong</option>
                            <option value="W">Weak</option>
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
        <div class="main-card mb-3">
            <div class="no-gutters row">
                <div class="col-md-4">
                    <div class="widget-content">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-right ml-0 mr-3">
                                <div class="font-icon-wrapper font-icon-lg"><i class="pe-7s-users icon-gradient bg-grow-early"> </i></div>
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">Total Pendukung</div>
                                <div class="widget-numbers text-success" id="total_pendukung">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="widget-content">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-right ml-0 mr-3">
                                <div class="font-icon-wrapper font-icon-lg"><i class="pe-7s-user icon-gradient bg-love-kiss"> </i></div>
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">Pendukung Kuat(Strong)</div>
                                <div class="widget-numbers text-info" id="total_strong">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="widget-content">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-right ml-0 mr-3">
                                <div class="font-icon-wrapper font-icon-lg"><i class="pe-7s-user icon-gradient bg-warning"> </i></div>
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">Pendukung Lemah(Weak)</div>
                                <div class="widget-numbers text-warning" id="total_weak">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-action">
          <div class="text-right">
          </div>
        </div>
        <div class="table-place-full">
            <table class="table table-bordered responsive  nowrap table-striped table-hover" style="width:100%" id="t_pendukung" data-list="{{route('pendukung.get_pendukung')}}" data-delete="{{route('pendukung.destroy')}}" data-token="{{csrf_token()}}">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">NIK</th>
                        <th scope="col">No.KK</th>
                        <th scope="col">Kabupaten</th>
                        <th scope="col">Kecamatan</th>
                        <th scope="col">Kel/Desa</th>
                        <th scope="col">TPS</th>
                        <th scope="col">Kategori Pendukung</th>
                        <th scope="col">No. Telp</th>
                        <th scope="col">Direferensikan oleh</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Menampilkan Data -->

<script src="{{asset('assets/js/page/pendukung/pendukung_list.js')}}"></script>
@endsection

