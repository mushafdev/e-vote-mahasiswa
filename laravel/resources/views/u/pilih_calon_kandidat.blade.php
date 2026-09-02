@extends('u.main')

@section('content')

@if(Formatter::identity()['cloudflare']=='active')
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
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

@if (!empty($pemilih->calon_kandidat_id))
<div class="row card align-items-center pb-5 pt-5">
    <div class="col-md-12 text-center">
        <div class="alert alert-success">
            <h5>Anda telah memilih kandidat</h5>
        </div>
    </div>
</div>
@else
<div class="row card align-items-center pb-5 pt-5" style="min-height: 500px;">
    <div class="col-md-12 text-center">
        <h2 class="font-weight-bold mt-5">Cari <span class="text-danger">Kandidat</span></h2>
        <h5 class="text-secondary mb-5">Silahkan ketik <b>Nama</b> atau <b>NIM/No. Identitas</b> kandidat untuk mencari data</h5>
    </div>
    <div class="col-md-8">
            <form role="form" name="form-search" id="form-search" data-search-kandidat="{{route('account.search_kandidat')}}" data-detail-kandidat="{{route('account.get_detail_kandidat')}}">
                <div class="form-group text-left">
                    <div class="input-group mb-2"> 
                        <input type="text" class="form-control text-center" id="keyword" name="keyword" placeholder="Ketikkan Nama/NIM/No. Identitas" style="border-radius: 100px 0px 0px 100px;height: 39px;" required data-parsley-errors-container="#search-errors" minlength="3"> 
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
</div>

<div class="modal fade mt-3" id="modal-update" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-md mt-5" role="document">
      <div class="modal-content">
          <div class="modal-header tit-up">
            <h4 class="modal-title" id="form-title">Pilih Kandidat</h4>
          </div>
          <div class="modal-body customer-box">
              <div class="buttonload modal-load">
                      <i class="fa fa-spinner fa-spin modal-load-spinner" style="    margin-top: 10%;"></i>
              </div>
              <!-- Tab panes -->
              <form role="form" name="form-pilih" id="form-pilih" data-pilih-kandidat="{{route('account.pilih_kandidat_update')}}" data-face-registered="{{auth()->guard('pemilih')->user()->face_registered ? '1' : '0'}}" class="form-horizontal">
              {{csrf_field()}}
              <input type="hidden" class="form-control kab_id">
              <input type="hidden" class="form-control kec_id">
              <input type="hidden" class="form-control kel_id">
              <input type="hidden" id="face_image" name="face_image">
                 <input type="hidden" id="id" name="id" readonly />
                  <div class="row">
                    <div class="col-md-12">
                        <table id="t_pilih" style="width: 100%">
                            <tr>
                                <td class="font-weight-bold text-center">NIM/No. Identitas</td>
                            </tr>
                            <tr>
                                <td id="no_anggota_i" class="edited text-center" style="font-size: 12pt;">-</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-center">Nama</td>
                            </tr>
                            <tr>
                                <td id="nama_i" class="edited text-center text-info" style="font-size: 14pt;">-</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Seksi kamera verifikasi wajah --}}
                    @if(auth()->guard('pemilih')->user()->face_registered)
                    <div class="col-md-12 mt-3" id="face-verify-section">
                        <p class="text-center font-weight-bold mb-1"><i class="fa fa-camera"></i> Verifikasi Wajah</p>
                        <p class="text-center text-muted" style="font-size:12px;">Arahkan wajah Anda ke kamera, lalu tekan <b>Ambil Foto</b></p>
                        <div class="text-center" style="background:#000;border-radius:8px;overflow:hidden;">
                            <video id="face-video" autoplay playsinline style="width:100%;max-height:200px;object-fit:cover;"></video>
                            <canvas id="face-canvas" width="320" height="240" style="display:none;width:100%;"></canvas>
                        </div>
                        <div class="text-center mt-2">
                            <button type="button" id="btn-start-face-cam" class="btn btn-info btn-sm mr-2">
                                <i class="fa fa-video-camera"></i> Aktifkan Kamera
                            </button>
                            <button type="button" id="btn-capture-face" class="btn btn-success btn-sm" style="display:none">
                                <i class="fa fa-camera"></i> Ambil Foto
                            </button>
                            <button type="button" id="btn-retake-face" class="btn btn-warning btn-sm" style="display:none">
                                <i class="fa fa-refresh"></i> Ulangi
                            </button>
                        </div>
                        <div id="face-status" class="text-center mt-2" style="font-size:12px;"></div>
                    </div>
                    @endif

                    @if(Formatter::identity()['cloudflare']=='active')
                    <div class="col-md-12 text-center mt-2">
                        <div class="cf-turnstile" data-sitekey="{{config('services.cloudflare.turnstile.site_key')}}" data-callback="onTurnstileSuccess">

                        </div>
                    </div>
                    @endif
                  </div>
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-md" name="cancel" id="cancel" data-dismiss="modal">
              <i class="fa fa-ban"></i> Batal</button>
              <button type="button" class="btn btn-primary btn-md"  {{Formatter::identity()['cloudflare']=='active'?'disabled="true"':''}} name="pilih" id="pilih">
                  <i class="fa fa-save"></i> Pilih Kandidat Ini
              </button>
          </div>
      </div>
    </div>
  </div>
</div>
<script src="{{asset('assets/js/page/kandidat/pilih_calon_kandidat.js')}}"></script>
<script src="{{asset('assets/js/page/public/custom.js')}}"></script>

@endif




<!-- Menampilkan Data -->

@endsection

