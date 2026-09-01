@extends('u.main')

@section('content')
<?php
$m=ltrim(date('m'), '0');
$month=date('m');
$year=date('Y');
$id_anggota=Auth::guard('pemilih')->user()->id;
$pemilihs=DB::table('pemilihs')->where('id',$id_anggota)->first();
?>

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon-small bg-malibu-beach">
                <i class="{{$icon}} text-white">
                </i>
            </div>
            <div>{{$title}}
            </div>
        </div>
        <div class="page-title-actions">
        <div class="text-right">
        </div>
        </div>
    </div>
</div>

<div class="row p-3">
    <div class="col-md-12 text-center">

        <h5>Selamat datang <b>{{Session::get('name')}}</b> di</h5>
        <h4 style="color: #bc2439;font-weight: 600;margin-bottom: 0px;">Ikatan Arsitek Indonesia</h4>
        <h4>Sulawesi Selatan</h4>

        <div class="alert alert-success">
            <h5>Selamat anda telah melakukan aktivasi akun</h5>
        </div>
        <div class="alert alert-info ">
            <h5>Silahkan menunggu proses penjaringan kandidat di mulai</h5>
            <!--@if (Auth::guard('pemilih')->user()->id==51791)-->
            <!--<h4 class="text-success">Penjaringan kandidat Tahap 2 telah dibuka</h4>-->
            <!--<a href="{{route('account.pilih_calon_kandidat_2')}}" class="btn btn-danger">Silahkan Pilih Kandidat Anda</a>-->
            
            <!--@endif-->
           
        </div>


    </div>
</div>

@endsection

