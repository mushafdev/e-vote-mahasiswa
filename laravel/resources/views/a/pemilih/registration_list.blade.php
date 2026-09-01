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
                        <label for="status_verifikasi">Status Verfikasi</label>
                        <select class="form-control filter-form" name="status_verifikasi" id="status_verifikasi">
                            <option value="">Semua</option>
                            <option value="NP">Belum Verifikasi Email</option>
                            <option value="NA">Belum Verifikasi Admin</option>
                            <option value="Y">Diterima</option>
                            <option value="N">Ditolak</option>
                        </select>
                      </div>
                    </div>
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
                                <div class="widget-heading">Total Pendaftar</div>
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
            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_pemilih"  data-list="{{route('pemilih.get_registration_list')}}" data-delete="{{route('pemilih.destroy')}}"  data-token="{{csrf_token()}}">
               <thead class="thead-light">
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Waktu Registrasi</th>
                        <th scope="col">Nama</th>
                        <th scope="col">NIM/No. Identitas</th>
                        <th scope="col">No.WA</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status Verifikasi</th>
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

<script src="{{asset('assets/js/page/pemilih/registration_list.js')}}?v=3"></script>
@endsection

