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
                <a href="{{route('tabulasi.kelurahan.main')}}?kecamatan={{Formatter::encrypt0($kelurahan->kec_id)}}"   class="btn-shadow btn btn-success">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-arrow-left fa-w-20"></i>
                    </span>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
<form id="form-filter" class="hide" data-bar-chart='{{route('tabulasi.kelurahan.bar_chart')}}'>
    {{ csrf_field() }}
    <input type="hidden" name="kec_id" id="kec_id" value="{{$kelurahan->id}}">
</form>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
        <div class="card p-3">
            <div class="col-md-12 text-center mt-1 mb-3">
                <h3 class="nama-kelurahan">{{$kelurahan->kelurahan}}</h3>
            </div>
            <div class="col-md-12">

                @foreach ($tps as $dt )
                <button class="mb-2 mr-2 btn btn-lg btn-info">{{sprintf("%03d", $dt->tps);}}<span class="badge badge-{{$dt->tercapai>0?'light':'secondary'}}">{{$dt->tercapai}}</span></button>
                @endforeach
                {{-- <div class="table-action">
                    <div class="text-right">
                    </div>
                  </div>
                  <div class="table-responsive">
                      <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_daerah">
                          <thead class="thead-light">
                              <tr>
                              <th scope="col">TPS</th>
                              <th scope="col" class="text-center">Tercapai</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach ($tps as $dt )
                                <tr class="data-tps" data-persen="">
                                    <td>
                                        {{$dt->tps}}
                                    </td>
                                    <td class="text-center">{!!$dt->tercapai_show!!}</td>
                                </tr>
                            @endforeach
                          </tbody>
                      </table>
                  </div> --}}
            
            </div>
        </div>

    </div>
</div>


{{-- <script src="{{asset('assets/js/page/tabulasi/pendukung/kelurahan.js')}}"></script> --}}

<!-- Menampilkan Data -->

@endsection

