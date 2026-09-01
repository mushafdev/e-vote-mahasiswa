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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
        <div class="card p-3">
            <div class="col-md-12">
                <div class="table-action">
                    <div class="text-right">
                    </div>
                  </div>
                  <div class="table-responsive">
                      <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_daerah">
                          <thead class="thead-light">
                              <tr>
                              <th scope="col">No</th>
                              <th scope="col">Kabupaten</th>
                              <th scope="col" class="text-center">Target</th>
                              <th scope="col" class="text-center">Tercapai</th>
                              <th scope="col" class="text-center">Persentase</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach ($kabupatens as $dt )
                                <tr class="data-kabupaten" data-persen="{{$dt->persentase}}">
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td>
                                        <a href="{{route('tabulasi.kecamatan.main').'?kabupaten='.Formatter::encrypt0($dt->id)}}">{{$dt->kabupaten}} </a>
                                    </td>
                                    <td class="text-center">{{$dt->target}}</td>
                                    <td class="text-center">{!!$dt->tercapai_show!!}</td>
                                    <td class="text-center">{!!$dt->persentase_show!!}</td>
                                </tr>
                            @endforeach
                          </tbody>
                      </table>
                  </div>
            
            </div>
            <div class="col-md-12 text-center">
                <div id="gTarget" style="width: 100%;height: 500px;"></div>
            </div>
        </div>

    </div>
</div>


<script src="{{asset('assets/js/page/tabulasi/pendukung/kabupaten.js')}}"></script>

<!-- Menampilkan Data -->

@endsection

