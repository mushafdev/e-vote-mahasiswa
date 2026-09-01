@extends('a.main')

@section('content')

<script src="{{asset('assets/vendor/amcharts4/core.js')}}"></script>
<script src="{{asset('assets/vendor/amcharts4/charts.js')}}"></script>
<script src="{{asset('assets/vendor/amcharts4/animated.js')}}"></script>
<style>
    .dataTables_scrollHeadInner{
        width: 100% !important;
    }
</style>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{ $icon }} text-white">
                </i>
            </div>
            <div>{!! $title !!}
                <div class="page-title-subheading">
                    {!!$sub_title!!}
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
            </div>
        </div>
    </div>
</div>
<form id="form-filter" class="" data-bar-chart='{{route('tabulasi.tim.bar_chart')}}' data-line-chart='{{route('tabulasi.tim.line_chart')}}'>
    {{ csrf_field() }}
    <input type="hidden" name="kategori" id="kategori" value="{{Request::segment(3)}}">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
            <div class="card p-3">
                <div class="col-md-12">
                        <ul class="tabs-animated-shadow tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link show {{Request::segment(3)=='utama'?'active':''}}" href="{{route('tabulasi.tim.main','utama')}}">
                                    <span class="nav-text">Tim Utama</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link show {{Request::segment(3)=='sayap'?'active':''}}" href="{{route('tabulasi.tim.main','sayap')}}">
                                    <span class="nav-text">Tim Sayap</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="tab-tim-utama" role="tabpanel">
                                <div class="text-center mt-1 mb-3">
                                    <h3 class="nama-kategori">Tim <span class="text-danger font-weight-bold">{{Str::upper(Request::segment(3))}}</span></h3>
                                </div>
                                <div class="table-action">
                                    <div class="text-right">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_daerah">
                                        <thead class="thead-light">
                                            <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Kelompok Tim</th>
                                            {{-- <th scope="col" class="text-center">Target</th> --}}
                                            <th scope="col" class="text-center">Tercapai</th>
                                            {{-- <th scope="col" class="text-center">Persentase</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($kelompok_tims->sortBy('tercapai')->reverse() as $dt )
                                            <tr class="data-kelompok-tim{{$dt->id}}" data-persen="{{$dt->persentase}}">
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td>
                                                    <a href="{{route('tabulasi.tim.detail').'?kelompok_tim='.Formatter::encrypt0($dt->id)}}">{{$dt->kelompok_tim}} </a>
                                                </td>
                                                {{-- <td class="text-center">{{$dt->target}}</td> --}}
                                                <td class="text-center">{!!$dt->tercapai_show!!}</td>
                                                {{-- <td class="text-center">{!!$dt->persentase_show!!}</td> --}}
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            
                                <div id="gTarget" style="width: 100%;height: 500px;"></div>
                                <hr>
                                <div class="form-body">
                                    <div class="form-group row">
                                        <label for="year" class="col-md-2 col-form-label">Dari Tanggal</label>
                                            <div class="col-md-3">
                                            <input type="date" id="start_date" name="start_date" class="form-control form-filter" value="{{date('Y-m-01')}}">
                                            </div>
                                        <label for="month" class="col-md-2 col-form-label">Sampai Tanggal</label>
                                            <div class="col-md-3">
                                            <input type="date" id="end_date" name="end_date" class="form-control form-filter" value="{{date('Y-m-d')}}">
                                            </div>
                                    </div>
                                </div>
                                <div id="gLineChart" style="width: 100%;height: 500px;"></div>

                            </div>
                        </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script src="{{asset('assets/js/page/tabulasi/tim/tim.js')}}"></script>

<!-- Menampilkan Data -->

@endsection

