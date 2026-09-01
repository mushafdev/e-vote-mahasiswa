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
                <a href="{{route('kandidat.create')}}" class="btn-shadow btn btn-primary" id="add">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-plus-circle fa-w-20"></i>
                    </span>
                    Tambah
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
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
                                <div class="widget-heading">Total Kandidat</div>
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
            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_pemilih" data-list="{{route('kandidat.get_kandidat')}}" data-delete="{{route('kandidat.destroy')}}" data-token="{{csrf_token()}}">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Foto</th>
                        <th scope="col">No. Urut</th>
                        <th scope="col">Nama</th>
                        <th scope="col">NIM/No. Identitas</th>
                        <th scope="col">Calon Kandidat</th>
                        <th scope="col">Kandidat</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Menampilkan Data -->

<script src="{{asset('assets/js/page/kandidat/kandidat_list.js')}}"></script>
@endsection

