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
                <a href="{{route('pendukung.main')}}"   class="btn-shadow btn btn-success">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-list fa-w-20"></i>
                    </span>
                    Data Pendukung
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row card align-items-center pb-5 pt-5" style="min-height: 500px;">
    <div class="col-md-12 text-center">
        <h2 class="font-weight-bold mt-5">Cari <span class="text-danger">Pendukung</span></h2>
        <h5 class="text-secondary mb-5">Silahkan ketik nama atau NIK pendukung untuk mencari data</h5>
    </div>
    <div class="col-md-8">
            <form role="form" name="form-search" id="form-search" data-search-pendukung="{{route('pendukung.search_pendukung')}}" data-detail-pendukung="{{route('pendukung.get_detail_pendukung')}}">
                <div class="form-group text-left">
                    <div class="input-group mb-2"> 
                        <input type="text" class="form-control text-center" id="keyword" name="keyword" placeholder="Ketikkan Nama/NIK" style="border-radius: 100px 0px 0px 100px;height: 39px;" required data-parsley-errors-container="#search-errors" minlength="3"> 
                        <span class="input-group-btn"> <button class="btn btn-primary" style="line-height: 28px;border-radius: 0px 100px 100px 10px;width: 50px;" type="button" id="search-button"><i class="fa fa-search"></i></button> </span>
                    </div>    
                    <span id="search-errors"></span>
                    <span class="text-muted w-100 " id="count_show"></span>
                    
                </div>
            </form>
        <div class="loading-data hide">
            <h6 class="text-muted">sedang diproses</h6>
            <div class="dot-typing ml-auto mr-auto mt-4 mb-5"></div>
        </div>
        
        
    </div>
    <div class="col-md-12">
                  
        <div id="table-result"></div>
    </div>
    <div class="col-md-12 mt-5">
        <h5 class="font-weight-bold mb-3">Terakhir <span class="text-danger">diinvite</span></h5>
        <div class="table-responsive">
            <table class="table table-bordered nowrap table-striped table-hover" style="width:100%" id="t_last_invited">
            <thead class="thead-light">
            <tr>
                <th scope="col" class="text-center">No</th>
                <th scope="col">Nama</th>
                <th scope="col">NIK</th>
                <th scope="col">Kabupaten</th>
                <th scope="col">Kecamatan</th>
                <th scope="col">Kel/Desa</th>
                <th scope="col">TPS</th>
                <th scope="col">Kategori Pendukung</th>
                <th scope="col">No. Telp</th>
                <th scope="col">Direferensikan oleh</th>
            </tr>
            </thead>
            <tbody>
            @if ($last_invited->count()>0)

            @foreach($last_invited as $dt)
                
                <tr>
                    <td scope="col" class="text-center">{{$loop->iteration}}</td>
                    <td scope="col">{{$dt->nama}}</td>
                    <td scope="col">{{$dt->nik}}</td>
                    <td scope="col">{{$dt->kabupaten}}</td>
                    <td scope="col">{{$dt->kecamatan}}</td>
                    <td scope="col">{{$dt->kelurahan}}</td>
                    <td scope="col">{{$dt->tps}}</td>
                    <td scope="col" class="text-center">
                    @if ($dt->kategori_pendukung=='S')
                        <span class="badge badge-danger">Strong</span>
                    @else
                        <span class="badge badge-warning">Weak</span>
                    @endif
                    </td>
                    <td scope="col">{{$dt->telp}}</td>
                    <td scope="col">{{$dt->referensi}}</td>
                </tr>
    
            @endforeach
            @else
            <tr>
                <td scope="col" class="text-center text-danger" colspan="12">Belum ada data</td>
            </tr>
            @endif
            </tbody>
            </table>
            </div>
    </div>
</div>

<div class="modal fade mt-3" id="modal-update" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-lg mt-5" role="document">
      <div class="modal-content">
          <div class="modal-header tit-up">
            <h4 class="modal-title" id="form-title">Invite Pendukung</h4>
          </div>
          <div class="modal-body customer-box">
              <div class="buttonload modal-load">
                      <i class="fa fa-spinner fa-spin modal-load-spinner" style="    margin-top: 10%;"></i>
              </div>
              <!-- Tab panes -->
              <form role="form" name="form-invite" id="form-invite" data-invite-pendukung="{{route('pendukung.invite_pendukung_update')}}" class="form-horizontal">
              {{csrf_field()}}
              <input type="hidden" class="form-control kab_id"> 
              <input type="hidden" class="form-control kec_id"> 
              <input type="hidden" class="form-control kel_id"> 
             
                 <input type="hidden" id="id" name="id" readonly />
                  <div class="row">
                    <div class="col-md-7">
                        <table id="t_invite" style="width: 100%">
                            <tr>
                                <td class="font-weight-bold" width="25%">NIK</td>
                                <td width="75%" id="nik_i" class="edited">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Nama</td>
                                <td id="nama_i" class="edited">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Jenis Kelamin</td>
                                <td id="jenis_kelamin_i" class="edited">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Kabupaten</td>
                                <td id="kabupaten_i" class="edited">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Kecamatan</td>
                                <td id="kecamatan_i" class="edited">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Kel/Desa</td>
                                <td id="kelurahan_i" class="edited">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">RW/RT</td>
                                <td id="rw_rt_i" class="edited">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Alamat</td>
                                <td id="alamat_i" class="edited">-</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>TPS<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="tps" id="tps" Placeholder="0" required >
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>No. Telp<span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="telp" id="telp" Placeholder="No. Telp" required >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Direferensikan Oleh<span class="text-danger">*</span></label>
                            <select class="form-control select2-ref" name="tim_id" id="tim_id" data-parsley-errors-container="#ref-errors" required style="width: 100%">
                                <option value="">Semua</option>
                            </select>

                            <span id="ref-errors"></span>
                       
                        </div>
                        <div class="form-group">
                            <label>Kategori Pendukung <span class="text-danger">*</span></label>
                            <select class="form-control" name="kategori_pendukung" id="kategori_pendukung" required>
                                <option value="">Pilih</option>
                                <option value="S">Strong</option>
                                <option value="W">Weak</option>
                            </select>
                        </div>
                    </div>

                  </div>
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-md" name="cancel" id="cancel" data-dismiss="modal">
              <i class="fa fa-ban"></i> Batal</button>
              <button type="button" class="btn btn-primary btn-md" name="invite" id="invite">
                  <i class="fa fa-save"></i> Invite
              </button>
          </div>
      </div>
    </div>
  </div>
</div>


<!-- Menampilkan Data -->

<script src="{{asset('assets/js/page/pendukung/pendukung.js')}}"></script>
@endsection

