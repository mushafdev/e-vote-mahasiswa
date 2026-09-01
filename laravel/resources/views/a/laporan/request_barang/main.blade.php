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
        <form target="_blank" name="form_rekap_rb" action="{{route('laporan.request_barang.print')}}">
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
                    <div class="form-group form-filter-btn">
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
            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_request_barang" data-list="{{route('laporan.request_barang.get_data')}}" data-detail-rekap-rb="{{route('laporan.request_barang.get_detail')}}" data-print="{{route('laporan.request_barang.print')}}">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Satuan</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Sub Kategori</th>
                        <th scope="col">Total(Rp)</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade mt-3" id="modal-rekap-rb" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-xl mt-5" role="document">
      <div class="modal-content">
          <div class="modal-header tit-up">
            <h4 class="modal-title" id="form-title">Detal Rekap <span class="text-danger">Request Barang</span></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body customer-box">
              <div class="buttonload modal-load">
                      <i class="fa fa-spinner fa-spin modal-load-spinner"></i>
              </div>
              <div class="row">
                    <div class="col-md-12">
                        <table class="w-100">
                            <tr>
                                <td width="15%">Unit Kerja</td>
                                <th id="unit_kerja_show">-</th>
                            </tr>
                            <tr>
                                <td width="15%">Kode Barang</td>
                                <th id="kode_show">-</th>
                            </tr>
                            <tr>
                                <td>Nama Barang</td>
                                <th id="nama_barang_show">-</th>
                            </tr>
                            <tr>
                                <td>Satuan</td>
                                <th id="satuan_show">-</th>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <form target="_blank" name="form_detail_rekap_rb" action="{{route('laporan.request_barang.print_detail')}}" method="GET">
                            <div class="row">
                                <input type="hidden" class="form-control" name="uk" id="uk" value="" placeholder="" required>
                                <input type="hidden" class="form-control" name="barang_id" id="barang_id" value="" placeholder="" required>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-md-3 mt-2">Dari <span class="red"></span></label>
                                        <div class="col-md-8 p-0">
                                            <input type="date" class="form-control" name="start_date" id="d_start_date" readonly placeholder="dd/mm/yyyy" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-md-4 mt-2">Sampai <span class="red"></span></label>
                                        <div class="col-md-8 p-0">
                                            <input type="date" class="form-control" name="end_date" id="d_end_date" readonly  placeholder="dd/mm/yyyy" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group row mr-2 ml-2">
                                        <button type="submit" class="btn-shadow btn btn-md btn-warning col-md-4 mr-2">
                                            <i class="fa fa-print"></i> Print
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <div class="table-place-full">
                            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_kartu_stok" >
                                <thead class="thead-light">
                                    <tr>
                                    <th scope="col">Waktu</th>
                                    <th scope="col">No. Transaksi</th>
                                    <th scope="col">Status Request</th>
                                    <th scope="col">Unit Kerja</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-md" name="cancel" id="cancel" data-dismiss="modal">
              <i class="fa fa-ban"></i> Batal</button>
               
          </div>
      </div>
    </div>
  </div>
</div>


<script src="{{asset('assets/js/page/laporan/request_barang/laporan_request_barang_list.js')}}"></script>

@endsection

