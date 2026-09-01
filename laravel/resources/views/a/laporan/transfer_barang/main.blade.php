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
                <div class="d-inline-block dropdown">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 p-4 mb-3 card">
        <form target="_blank" name="form_rekap_rb" action="{{route('laporan.transfer_barang.print')}}">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <div class="form-group">
                            <label class="mt-2">Dari <span class="red"></span></label>
                            <input type="date" class="form-control filter-form" name="start_date" id="start_date" value="{{date('Y-m-01')}}" placeholder="dd/mm/yyyy" required="">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-group">
                            <label class="mt-2">Sampai <span class="red"></span></label>
                            <input type="date" class="form-control filter-form" name="end_date" id="end_date" value="{{date('Y-m-d')}}" placeholder="dd/mm/yyyy" required="">
                    </div>
                </div>
                @if (Session::get('role')=='c' || Session::get('role')=='sp')
                <input type="hidden" name="unit_kerja_id" id="unit_kerja_id" value="{{Formatter::encrypt0(Session::get('unit_kerja_id'))}}">
                @else
                <div class="col-md-3 col-sm-12">
                    <div class="form-group">
                            <label class="mt-2">Unit kerja <span class="red"></span></label>
                            <select class="form-control filter-form select2" name="unit_kerja_id" id="unit_kerja_id">
                                <option value="">Semua</option>
                                @foreach ($unit_kerja as $dt )
                                <option value="{{Formatter::encrypt0($dt->id)}}">{{$dt->unit_kerja}}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
                @endif
                <div class="col-md-3 col-sm-12">
                    <div class="form-group">
                            <label class="mt-2">Status Terima <span class="red"></span></label>
                            <select class="form-control filter-form" name="" id="status_terima">
                                <option value="">Semua</option>
                                <option value="N">Menunggu</option>
                                <option value="Y">Diterima</option>
                            </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    {{-- New Update --}}
                    <div class="form-group {{(Session::get('role')=='c')?'form-filter-btn':''}} ">
                        {{-- New Update --}}
                        <button type="submit" class="btn-shadow btn btn-md btn-warning col-md-6 print" id=""><i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="main-card mb-3 card">
            <div class="no-gutters row">
                <div class="col-md-4">
                    <div class="widget-content">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-right ml-0 mr-3">
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">Grand Total</div>
                                <div class="widget-numbers text-success" id="total_grand_total">0</div>
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
            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_transfer_barang" data-list="{{route('laporan.transfer_barang.get_data')}}"  data-print="{{route('laporan.transfer_barang.print')}}">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Waktu</th>
                        <th scope="col">No. Transaksi</th>
                        <th scope="col">Asal</th>
                        <th scope="col">Tujuan</th>
                        <th scope="col">No. Ref</th>
                        <th scope="col">Penanggung Jawab</th>
                        <th scope="col">Status Terima</th>
                        <th scope="col">Grand Total(Rp)</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>



<script src="{{asset('assets/js/page/laporan/transfer_barang/laporan_transfer_barang_list.js')}}"></script>

@endsection

