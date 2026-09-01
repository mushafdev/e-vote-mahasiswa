
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Language" content="en">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
	<title>{{Formatter::identity()['title']}} - Registrasi</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
	<meta name="description" content="">
	<meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" href="{{asset('assets/css/poppins-font.css')}}"/><link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="{{asset('assets/vendor/fontawesome/css/fontawesome-all.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/slick/slick.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/slick/slick-theme.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/slick/slick-custom.css')}}" rel="stylesheet">
    <!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/login.css')}}">
	<link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" id="bootstrap-css">
	

</head>
<body>
<div class="container-fluid">
	<div class="d-flex login-page2">
        <div class="row justify-content-center align-items-center p-md-5 p-4">
            <div class="mx-auto app-login-box col-md-12">
                
                <div>
                    <form role="form" action="{{route('registrasi.store')}}" method="POST" data-reload-captcha="{{route('login.reload_captcha')}}" id="form-registrasi" autocomplete="false" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <img src="{{asset('assets/images/logo-full.png')}}" alt="" width="250px">
                                    <h4 class="font-weight-bold">Registrasi</h4>
                                </div>
                                @if ($respone=='respone_registrasi')
                                <h4 class="mb-0">
                                    <span class="d-block">Aktivasi Akun</span>
                                </h4>
                                <div class="alert-success p-4 mt-3">
                                    <p>Hi, <b>{{$pemilih->nama}}</b></p>
                                    <p>
                                        Form registrasi anda berhasil terinput. <br>Silahkan klik link <b><i>Verifikasi email</i></b> yang telah dikirim ke email <b>{{$pemilih->email}}</b> <br>
                                    </p>
                                    <p><a href="{{route('home')}}" class="text-success">Kembali ke halaman login</a></p>
                                </div>

                                @elseif ($respone=='respone_sukses_verifikasi_email')

                                <h4 class="mb-0">
                                    <span class="d-block text-success">Verifikasi Email Berhasil</span>
                                </h4>
                                <div class="alert-success p-4 mt-3">
                                    <p>Hi, <b>{{$pemilih->nama}}</b></p>
                                    <p>
                                        Selamat anda sukses melakukan verifikasi email.  <br>
                                        <span>Sisa menunggu hasil verifikasi admin untuk mendapatkan email dan password login e-vote.</span>
                                    </p>
                                    <a href="{{route('home')}}" class="text-success">Kembali ke halaman login</a>
                                </div>

                                @elseif($respone=='lupa_password')

                                <h4 class="mb-0">
                                    <span class="d-block text-success">Link Update Akun Sukses Dikirim</span>
                                </h4>
                                <div class="alert-success p-4 mt-3">
                                    <p>Hi, <b>{{$pemilih->nama}}</b></p>
                                    <p>
                                        Selamat link update akun sukses dikirim ke email <b>{{$pemilih->email}}</b>.  <br>
                                        <span>Silahkan update username atau password anda.</span>
                                    </p>
                                    <a href="{{route('home')}}" class="text-success">Kembali ke halaman login</a>
                                </div>

                                @elseif($respone=='respone_update_akun')

                                <h4 class="mb-0">
                                    <span class="d-block text-success">Akun Berhasil Diupdate</span>
                                </h4>
                                <div class="alert-success p-4 mt-3">
                                    <p>Hi, <b>{{$pemilih->nama}}</b></p>
                                    <p>
                                        Selamat akun anda berhasil diupdate. <br>
                                        <span>Silahkan login kembali</span>
                                    </p>
                                    <a href="{{route('home')}}" class="text-success">Kembali ke halaman login</a>
                                </div>

                                @else

                                <h4 class="mb-0">
                                    <span class="d-block text-danger">Terjadi Kesalahan</span>
                                </h4>
                                <div class="alert-danger p-4 mt-3">
                                    <p>
                                        Maaf telah terjadi kesalahan. <br>
                                        {{$error}}
                                        <span></span>
                                    </p>
                                </div>
                                @endif
                            </div>
                           

                        </div>
                        {{-- <div class="position-relative form-check">
                            <input name="check" id="exampleCheck" type="checkbox" onclick="showPass()" class="form-check-input">
                            <label for="exampleCheck" class="form-check-label" >Tampilkan Password</label>
                        </div> --}}
                        <div class="divider row mt-4"></div>
                        <div class="d-flex align-items-center">
                        </div>
                    </form>
                    <div class="text-center mt-3">
                       
                        {{-- <p>Support By <br><b>{{Formatter::identity()['support']}}</b></p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<canvas id="Xiashhay" class="" width="300" height="300" style="width: 100%;display:none"></canvas>
                          
{{-- @php
    Session::flush();
@endphp --}}
</body>
</html>
