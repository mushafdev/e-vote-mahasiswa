@extends('u.main')

@section('content')

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{ $icon }} text-white">
                </i>
            </div>
            <div>{!! $title!!}
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
            </div>
        </div>
    </div>
</div>

@if($check)
@if (!empty($pemilih->calon_kandidat_2_id))
<div class="row card align-items-center pb-5 pt-5">
    <div class="col-md-12 text-center">
        <div class="alert alert-success">
            <h5>Anda telah memilih kandidat</h5>
        </div>
    </div>
</div>
@else

<div class="row card align-items-center pb-5 pt-5" style="min-height: 500px;">
    <div class="alert alert-info text-center">
        <h3>Selamat Datang </h3>
        <h5>Proses pemilihan bakal calon kandidat tahap 2 telah dimulai </h5>
        <h4>16 Juli Jam 9.00 - 20 Juli Jam 16.00 2024</h4>
        
    </div>
    <div class="col-md-12 text-center">
        <h2 class="font-weight-bold mt-5">Pilih <span class="text-danger">Kandidat</span></h2>
        <h5 class="text-secondary mb-5">Silahkan klik <b>gambar</b> atau <b>Nama</b> untuk memilih kandidat</h5>
    
    </div>
    <div class="col-md-12">
            
        <div class="row gy-4 gy-lg-0  justify-content-center">
            
            @foreach ($calon_kandidats as $dt)
            <div class="col-6 col-md-2 p-3">
                <div class="card border-0" style="box-shadow: 1px 7px 8.0px hsl(0deg 0% 0% / 0.38); border-radius: 10px;">
                    <figure class="card-img-top m-0 overflow-hidden bsb-overlay-hover">
                    <a href="#" class="detail-kandidat" data-id="{{Formatter::encrypt0($dt->id)}}">
                        <img class="img-fluid bsb-scale bsb-hover-scale-up" loading="lazy" src="{{asset('images/kandidat/'.$dt->file_photo)}}" alt="{{$dt->nama}}">
                    </a>
                    </figure>
                    <div class="card-body border bg-danger p-2 " style="border-radius: 0px 0px 10px 10px;">
                        <ul class="nav mb-0 bsb-nav-sep">
                            <li class="nav-item text-secondary">
                            <a class="detail-kandidat nav-link  link-secondary p-0 pe-3 d-inline-flex align-items-center" href="#" data-id="{{Formatter::encrypt0($dt->id)}}">
                                <div class="m-3">
                                    <h1 class="font-we text-white">{{$dt->no_urut}}</h1>
                                </div>
                                <div>
                                    <h2 class="card-title text-white h6 font-we mb-0">{{$dt->nama}}</h2>
                                    <p class="card-text text-white">{{$dt->no_anggota}}</p>
                                </div>
                            </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
    </div>
</div>

<div class="modal fade mt-3" id="modal-update" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-sm mt-5" role="document">
      <div class="modal-content">
          <div class="modal-header tit-up">
            <h4 class="modal-title" id="form-title">Pilih Kandidat</h4>
          </div>
          <div class="modal-body customer-box">
              <div class="buttonload modal-load">
                      <i class="fa fa-spinner fa-spin modal-load-spinner" style="    margin-top: 10%;"></i>
              </div>
              <!-- Tab panes -->
              <form role="form" name="form-pilih" id="form-pilih" data-detail-kandidat="{{route('account.review_kandidat')}}" data-pilih-kandidat="{{route('account.pilih_kandidat_2_update')}}" class="form-horizontal">
              {{csrf_field()}}
              <input type="hidden" class="form-control kab_id"> 
              <input type="hidden" class="form-control kec_id"> 
              <input type="hidden" class="form-control kel_id"> 
             
                 <input type="hidden" id="id" name="id" readonly />
                  <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col p-3">
                                <div class="card border-0" style="box-shadow: 1px 7px 8.0px hsl(0deg 0% 0% / 0.38); border-radius: 10px;">
                                    <figure class="card-img-top m-0 overflow-hidden bsb-overlay-hover">
                                    <a href="#" class="">
                                        <img class="img-fluid bsb-scale bsb-hover-scale-up" id="img_i" loading="lazy" src="" alt="">
                                    </a>
                                    </figure>
                                    <div class="card-body border bg-danger p-2 " style="border-radius: 0px 0px 10px 10px;">
                                        <ul class="nav mb-0 bsb-nav-sep">
                                            <li class="nav-item text-secondary">
                                            <a class=" nav-link  link-secondary p-0 pe-3 d-inline-flex align-items-center" href="#">
                                                <div class="m-3">
                                                    <h1 class="font-we text-white" id="no_urut_i"></h1>
                                                </div>
                                                <div>
                                                    <h2 class="card-title text-white h6 font-we mb-0" id="nama_i"></h2>
                                                    <p class="card-text text-white" id="no_anggota_i"></p>
                                                </div>
                                            </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center hide">
                        <div class="cf-turnstile" data-sitekey="{{config('services.cloudflare.turnstile.site_key')}}" data-callback="onTurnstileSuccess">
                            
                        </div>
                    </div>
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
<script src="{{asset('assets/js/page/kandidat/pilih_calon_kandidat_2.js')}}"></script>
<script src="{{asset('assets/js/page/public/custom.js')}}"></script>

@endif
@else
<div class="row p-3">
    <div class="col-md-12 text-center">

        <h5>Selamat datang <b>{{Session::get('name')}}</b> di</h5>
        <h4 style="color: #bc2439;font-weight: 600;margin-bottom: 0px;">Ikatan Arsitek Indonesia</h4>
        <h4>Sulawesi Selatan</h4>

        <div class="alert alert-info ">
            <h5>Silahkan menunggu proses pemilihan bakal calon kandidat tahap 2 yang akan dimulai pada</h5>
            <h4>16 Juli Jam 9.00 - 20 Juli Jam 16.00 2024</h4>
            
        </div>


    </div>
</div>
@endif





<!-- Menampilkan Data -->

@endsection

