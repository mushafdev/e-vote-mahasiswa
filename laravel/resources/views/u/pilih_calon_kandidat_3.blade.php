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
@if (!empty($pemilih->kandidat_id))
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
        <h5>Proses pemilihan ketua telah dimulai </h5>
        <h4>15 Agustus Jam 10.30 - 16 Agustus Jam 16.00 2024</h4>
        
    </div>
    <div class="col-md-12 text-center">
        <h2 class="font-weight-bold mt-5">Pilih <span class="text-danger">Kandidat</span></h2>
        <h5 class="text-secondary mb-5">Silahkan klik <b>gambar</b> atau <b>Nama</b> untuk memilih kandidat</h5>
    
    </div>
    <div class="col-md-12">
            
        <div class="row gy-4 gy-lg-0  justify-content-center">
            
            @foreach ($kandidats as $dt)
            <div class="col-6 col-md-2 p-3">
                <div class="card border-0" style="box-shadow: 1px 7px 8.0px hsl(0deg 0% 0% / 0.38); border-radius: 10px;">
                    <figure class="card-img-top m-0 overflow-hidden bsb-overlay-hover">
                    <a href="{{route('account.pilih_calon_kandidat_3_detail',Formatter::encrypt0($dt->id))}}" class="detail-kandidat">
                        <img class="img-fluid bsb-scale bsb-hover-scale-up" loading="lazy" src="{{asset('images/kandidat/'.$dt->file_photo)}}" alt="{{$dt->nama}}" onerror="this.src='{{asset('assets/images/no-image.png')}}';this.onerror=null;" style="min-height:150px;object-fit:cover;background:#eee;">
                    </a>
                    </figure>
                    <div class="card-body border bg-danger p-2 " style="border-radius: 0px 0px 10px 10px;">
                        <ul class="nav mb-0 bsb-nav-sep">
                            <li class="nav-item text-secondary">
                            <a class="detail-kandidat nav-link  link-secondary p-0 pe-3 d-inline-flex align-items-center" href="{{route('account.pilih_calon_kandidat_3_detail',Formatter::encrypt0($dt->id))}}">
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


@endif
@else
<div class="row p-3">
    <div class="col-md-12 text-center">

        <h5>Selamat datang <b>{{Session::get('name')}}</b> di</h5>
        <h4 style="color: #bc2439;font-weight: 600;margin-bottom: 0px;">Ikatan Arsitek Indonesia</h4>
        <h4>Sulawesi Selatan</h4>

        <div class="alert alert-info ">
            <h5>Silahkan menunggu proses pemilihan ketua {{Formatter::identity()['organization_name']}} yang akan dimulai pada</h5>
            <h4>15 Agustus Jam 10.30 - 16 Agustus Jam 14.00 2024</h4>
            
        </div>


    </div>
</div>
@endif





<!-- Menampilkan Data -->

@endsection

