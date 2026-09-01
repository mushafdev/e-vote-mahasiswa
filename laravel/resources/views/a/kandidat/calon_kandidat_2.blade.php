@extends('a.main')

@section('content')

<script src="{{asset('assets/vendor/amcharts4/core.js')}}"></script>
<script src="{{asset('assets/vendor/amcharts4/charts.js')}}"></script>
<script src="{{asset('assets/vendor/amcharts4/animated.js')}}"></script>
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
    <div class="col-lg-12 col-md-12 col-sm-12 p-4 mb-3 card">
        <div class="main-card mb-3">
            <div class="no-gutters row">
                <div class="col-md-3">
                    <div class="widget-content">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-right ml-0 mr-3">
                                <div class="font-icon-wrapper font-icon-lg"><i class="pe-7s-users icon-gradient bg-primary"> </i></div>
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">Total Kandidat</div>
                                <div class="widget-numbers text-primary" id="total_kandidat">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget-content">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-right ml-0 mr-3">
                                <div class="font-icon-wrapper font-icon-lg"><i class="pe-7s-users icon-gradient bg-secondary"> </i></div>
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">Total Pemilih</div>
                                <div class="widget-numbers text-secondary" id="total_pemilih">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget-content">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-right ml-0 mr-3">
                                <div class="font-icon-wrapper font-icon-lg"><i class="pe-7s-users icon-gradient bg-success"> </i></div>
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">Telah Memilih</div>
                                <div class="widget-numbers text-success" id="total_telah_memilih">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget-content">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-right ml-0 mr-3">
                                <div class="font-icon-wrapper font-icon-lg"><i class="pe-7s-users icon-gradient bg-warning"> </i></div>
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">Belum Memilih</div>
                                <div class="widget-numbers text-warning" id="total_belum_memilih">0</div>
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
            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_pemilih" data-list="{{route('kandidat.get_calon_kandidat_2')}}" data-delete="{{route('kandidat.destroy_calon_kandidat_2')}}" data-token="{{csrf_token()}}">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">NIM/No. Identitas</th>
                        <th scope="col">Total Suara</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 p-4 mb-3 card">
        <div id="gTarget" data-barchart='{{route('chart.bar_chart_calon_kandidat_2')}}' style="width: 100%;height: 500px;"></div>
         
    </div>
</div>


<!-- Menampilkan Data -->

<script src="{{asset('assets/js/page/kandidat/calon_kandidat_2_list.js')}}?v=5"></script>
@endsection

