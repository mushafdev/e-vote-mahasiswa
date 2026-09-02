
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Language" content="en">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
	<title>{{Formatter::identity()['title']}} - Verifikasi OTP</title>
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
    @if(Formatter::identity()['cloudflare']=='active')
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
	

</head>
<body>
<div class="container-fluid">
	<div class="d-flex login-page2" style="max-width: 500px;">
        <div class="row justify-content-center align-items-center p-md-5 p-4">
            <div class="mx-auto app-login-box col-md-12">
                
                <div>
                    <form role="form" action="#" data-save="{{route('login.verifikasi_otp')}}" data-request-otp="{{route('login.kirim_ulang_otp')}}" id="form-verifikasi" autocomplete="false">
                    {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <img src="{{asset('assets/images/logo-full.png')}}" alt="" width="250px">
                                    <h4 class="font-weight-bold">Verifikasi Kode OTP</h4>
                                </div>
                                <div class="alert-info p-4 mt-3 text-center">
                                    <p>
                                        Silahkan masukkan kode OTP terlebih dahulu yang dikirim ke email <b>{{Session::get('email')}}</b> 
                                    </p>
                                    <input type="number" name="kode_otp" id="kode_otp" autofocus class="form-control text-center" style="font-size: 20pt !important;letter-spacing: 8px;font-weight: 600;" autocomplete="false">
                                    <a href="#" id="request-otp" class="w-100 mt-2"><small>Kirim ulang Kode OTP</small></a>
                                    @if(Formatter::identity()['cloudflare']=='active')
                                    <div class="cf-turnstile" data-sitekey="{{config('services.cloudflare.turnstile.site_key')}}" data-callback="onTurnstileSuccess">

                                    </div>
                                    @endif
                                    <button type="button" id="verifikasi" name="verifikasi" {{Formatter::identity()['cloudflare']=='active'?'disabled="true"':''}} class="btn btn-success w-100 mt-2">Verifikasi</button>
                                </div>
                            </div>
                           

                        </div>
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
                          
                             
<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/vendor/jquery/jquery-3.3.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/parsley.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/sweetalert2.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/vendor/architectui/css/sweetalert2.min.css')}}"/>
<script src="{{asset('assets/js/page/login/verifikasi_otp.js')}}"></script>
<script src="{{asset('assets/js/page/public/custom.js')}}"></script>

</body>
</html>
