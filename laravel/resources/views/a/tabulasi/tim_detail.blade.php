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
                <a href="{{route('tabulasi.tim.main',$kelompok_tims->kategori)}}"   class="btn-shadow btn btn-success">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-arrow-left fa-w-20"></i>
                    </span>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
<form id="form-filter" class="hide" data-bar-chart='{{route('tabulasi.tim.detail_bar_chart')}}'>
    {{ csrf_field() }}
    <input type="hidden" name="kelompok_tim_id" id="kelompok_tim_id" value="{{$kelompok_tims->id}}">
</form>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
        <div class="card p-3">
            <div class="col-md-12">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="tab-tim-utama" role="tabpanel">
                            <div class="text-center mt-1 mb-3">
                                <h3 class="nama-kelompok-tim"><span class="text-danger font-weight-bold">{{$kelompok_tims->kelompok_tim}}</span></h3>
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
                                        <th scope="col">Nama</th>
                                        <th scope="col">NIK</th>
                                        <th scope="col">Telp</th>
                                        <th scope="col" class="text-center">Tercapai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($tims->sortBy('tercapai')->reverse() as $dt )
                                        <tr class="data-kelompok-tim{{$dt->id}}">
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td>{{$dt->nama}}</td>
                                            <td>{{$dt->nik}} </td>
                                            <td>{{$dt->telp}} </td>
                                            <td class="text-center">{!!$dt->tercapai_show!!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        
                        
                            <div id="gTarget" style="width: 100%;height: 500px;"></div>

                        </div>
                    </div>
            </div>
        </div>

    </div>
</div>


<script src="{{asset('assets/js/page/tabulasi/tim/detail.js')}}"></script>

<!-- Menampilkan Data -->

@endsection

