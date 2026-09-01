@extends('a.main')

@section('content')
<?php
$m=ltrim(date('m'), '0');
$month=date('m');
$year=date('Y');
?>
<script src="{{asset('assets/vendor/amcharts4/core.js')}}"></script>
<script src="{{asset('assets/vendor/amcharts4/charts.js')}}"></script>
<script src="{{asset('assets/vendor/amcharts4/animated.js')}}"></script>
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
<form class="form ml-1 mr-1" id="form-filter" class="form-vertical" data-line-chart="{{route('dashboard.line_chart')}}" data-pie-chart="{{route('dashboard.pie_chart')}}" data-bar-chart='{{route('tabulasi.kecamatan.bar_chart')}}' style="border-bottom: 1px solid #bfbfbf;">
{{ csrf_field() }}
<input type="hidden" name="kab_id" id="kab_id" value="{{Formatter::identity()['regency_id']}}">
<div class="row mb-3">
    <div class="col-lg-4 col-xl-4 p-0">
        <div class="card widget-content text-center pb-1">
            <div class="widget-content-wrapper">
                <div class="row">
                    <div class="col-12">
                        <div class="widget-content-left">
                            <div class="widget-subheading">Hitung Mundur Pemilihan</div>
                            <span class="tgl_eks hide">{{Formatter::identity()['tgl_eks']}}</span>
                            <div class="widget-numbers text-warning"><span>{{Formatter::tgl_indo(Formatter::getDateDatetime(Formatter::identity()['tgl_eks']))}}</span></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-3 p-1">
                                <div class=" mb-0 widget-chart widget-chart2 text-left ">
                                    <div class="widget-content text-center">
                                        <div class="widget-content-outer">
                                            <div class="widget-numbers mt-0 fsize-3 text-info cd_d">239</div>
                                            <div class="widget-content-left fsize-1">
                                                <div class="text-muted opacity-6">Hari</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 p-1">
                                <div class=" mb-0 widget-chart widget-chart2 text-left ">
                                    <div class="widget-content text-center">
                                        <div class="widget-content-outer">
                                            
                                            <div class="widget-numbers mt-0 fsize-3 text-info cd_h">20</div>
                                            <div class="widget-content-left fsize-1">
                                                <div class="text-muted opacity-6">Jam</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 p-1">
                                <div class=" mb-0 widget-chart widget-chart2 text-left ">
                                    <div class="widget-content text-center">
                                        <div class="widget-content-outer">
                                            
                                            <div class="widget-numbers mt-0 fsize-3 text-info cd_m">23</div>
                                            <div class="widget-content-left fsize-1">
                                                <div class="text-muted opacity-6">Menit</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 p-1">
                                <div class=" mb-0 widget-chart widget-chart2 text-left ">
                                    <div class="widget-content text-center">
                                        <div class="widget-content-outer">
                                            
                                            <div class="widget-numbers mt-0 fsize-3 text-info cd_s">13</div>
                                            <div class="widget-content-left fsize-1">
                                                <div class="text-muted opacity-6">Detik</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xl-4 p-0">
        <div class="main-card mb-0 card">
            <div class="no-gutters row p-2">
                <div class="col-md-6">
                    <div class="pt-0 pb-0 card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Data DPT</div>
                                                <div class="widget-numbers2 text-success">{{Formatter::pointSeparator($tot_dpt)}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Tot. TPS</div>
                                                <div class="widget-numbers2 text-success">{{Formatter::pointSeparator($tot_tps)}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="pt-0 pb-0 card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Tot. Kecamatan</div>
                                                <div class="widget-numbers2 text-success">{{Formatter::pointSeparator($tot_kec)}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Tot. Kel/Desa</div>
                                                <div class="widget-numbers2 text-success">{{Formatter::pointSeparator($tot_kel)}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="col-lg-4 col-xl-4 p-0">
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-0 card">
                    <div class="no-gutters row p-2">
                        <div class="col-md-12">
                            <div class="p-0 card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="widget-content p-0">
                                            <div class="widget-content-outer">
                                                <div class="text-center">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Tot. Pemilih</div>
                                                        <div class="widget-numbers text-warning">{{Formatter::pointSeparator($tot_pendukung)}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="widget-content p-0">
                                            <div class="widget-content-outer">
                                                <div class="row">
                                                    <div class="col-6 pr-0">
                                                        <div class="widget-content-left text-center">
                                                            <i class="fa fa-custom fa-user text-primary" aria-hidden="true"></i>
                                                            <div class="widget-subheading">Laki-Laki</div>
                                                            <div class="widget-numbers2 text-primary">{{Formatter::pointSeparator($tot_pendukung_l)}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 pl-0">
                                                        <div class="widget-content-left text-center">

                                                            <i class="fa fa-custom fa-user text-danger" aria-hidden="true"></i>
                                                            <div class="widget-subheading">Perempuan</div>
                                                            <div class="widget-numbers2 text-danger">{{Formatter::pointSeparator($tot_pendukung_p)}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
</div>


</form>


 <script src="{{asset('assets/js/page/dashboard/dashboard.js')}}"></script>
 <script src="{{asset('assets/js/page/tabulasi/pendukung/kecamatan.js')}}"></script>


<!-- Chart code -->
@endsection

