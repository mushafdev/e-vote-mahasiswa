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
                    {{ csrf_field() }}
                    <div class="form-row">
                      <div class="form-group col-md-2">
                        <label for="kabupaten_id">Kabupaten</label>
                        <select class="form-control filter-form select2 select2-kab" name="kab_id" id="kab_id" style="width: 100%;">
                            <option value="">Semua</option>
                        </select>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="kecamatan_id">Kecamatan</label>
                        <select class="form-control filter-form select2-kec" name="kec_id" id="kec_id" data-parsley-errors-container="#kec-errors" style="width: 100%;">
                            <option value="">Semua</option>
                        </select>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="Kelurahan">Kelurahan</label>
                        <select class="form-control filter-form select2-kel" name="kel_id" id="kel_id" data-parsley-errors-container="#kel-errors" style="width: 100%;">
                            <option value="">Semua</option>
                        </select>
                      </div>
                      <div class="form-group col-md-2">
                        <label for="tps">TPS</label>
                        <input type="number" class="form-control filter-form" name="tps" id="tps">
                      </div>
                      <div class="form-group col-md-2">
                        <label for="kk">No. KK</label>
                        <input type="number" class="form-control filter-form" name="nkk" id="nkk">
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
                                <div class="widget-heading">Total Pemilih</div>
                                <div class="widget-numbers text-success" id="total_pemilih">0</div>
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
            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_pemilih" data-list="{{route('pemilih.get_pemilih')}}" data-delete="{{route('pemilih.destroy')}}" data-kirim-new-auth="{{route('pemilih.send_new_auth')}}" data-token="{{csrf_token()}}">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">NIM/No. Identitas</th>
                        <th scope="col">No.WA</th>
                        <th scope="col">Email</th>
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

<script src="{{asset('assets/js/page/pemilih/pemilih_list.js')}}?v=3"></script>
@endsection

