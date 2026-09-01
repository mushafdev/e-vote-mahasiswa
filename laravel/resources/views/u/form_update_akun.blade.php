
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Language" content="en">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
	<title>IAI Sulawesi Selatan - Registrasi</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
	<meta name="description" content="">
	<meta name="msapplication-tap-highlight" content="no">

    <link href="{{asset('assets/vendor/fontawesome/css/fontawesome-all.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/slick/slick.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/slick/slick-theme.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/slick/slick-custom.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/custom/css/login.css')}}">
	<link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" id="bootstrap-css">
	<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/vendor/jquery/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendor/slick/slick.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendor/slick/slick-animation.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendor/slick/slick-custom.js')}}"></script>
	<!------ Include the above in your HEAD tag ---------->
    <style>


    </style>
</head>
<body>
<div class="container-fluid h-100 p-0">
	<div class="d-flex h-100">
        <div class="space-1">
            <div class="slide-x h-100">
                <div class="slide">
                    <div class="slide__img">
                        <img src="" alt="" data-lazy="{{asset('storage/images/slide-x/pexels-tima-miroshnichenko-6474449.jpg')}}" class="full-image animated h-100" data-animation-in="zoomInImage" style="width: 100% !important;"/>
                    </div>
                </div>
                <div class="slide">
                    <div class="slide__img">
                        <img src="" alt="" data-lazy="{{asset('storage/images/slide-x/pexels-thirdman-5582867.jpg')}}" class="full-image animated h-100" data-animation-in="zoomInImage" style="width: 100% !important;"/>
                    </div>
                </div>
                <div class="slide">
                    <div class="slide__img">
                        <img src="" alt="" data-lazy="{{asset('storage/images/slide-x/pexels-berk-ozdemir-3779817.jpg')}}" class="full-image animated h-100" data-animation-in="zoomInImage" style="width: 100% !important;"/>
                    </div>
                </div>
                <div class="slide">
                    <div class="slide__img">
                        <img src="" alt="" data-lazy="{{asset('storage/images/slide-x/pexels-tima-miroshnichenko-6615093.jpg')}}" class="full-image animated h-100" data-animation-in="zoomInImage" style="width: 100% !important;"/>
                    </div>
                </div>
                <div class="slide">
                    <div class="slide__img">
                        <img src="" alt="" data-lazy="{{asset('storage/images/slide-x/pexels-refargotohp-8850284.jpg')}}" class="full-image animated h-100" data-animation-in="zoomInImage" style="width: 100% !important;"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-100 d-flex justify-content-center align-items-center space-2">
            <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                <div class="app-logo">
                    <img src="{{asset('assets/images/logo.png')}}" alt="">
                </div>
                <h4 class="mb-0">
                    <span class="d-block">Update Akun</span>
                </h4>
                @if ($status=='expired')
                <div class="alert-danger p-4 mt-3">
                    <p>Hi, <b>{{$team->nama}}</b></p>
                    <p>
                        Mohon maaf, link ini sudah expired. Batas waktu hanya 1 jam. <br>
                        <span>Silahkan kirim ulang request link update akun</span>
                    </p>
                    <a href="{{route('login.lupa_akun')}}" class="text-danger">Request link update akun</a>
                </div>

                @else
                <p>
                    <span>Silahkan isi data dibawah</span></p>
                <div class="divider row"></div>
                <div>
                    <form action="{{route('login.update_akun')}}" method="POST" id="login">
                    {{ csrf_field() }}
                    <input type="hidden" name="param" id="param"  class="form-control" placeholder="********" value="{{Formatter::encrypt0(base64_encode($team->id))}}"/>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="examplePassword" class="">Password</label>
                                    <input type="password" name="password" id="password" maxlength="20" class="form-control" placeholder="********" value="{{old('password')}}"/>
                                </div>
                                @if($errors->has('password'))
                                <p class="text-danger">{{ $errors->first('password') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleKonfiramasiPassword" class="">Konfiramasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" maxlength="20" class="form-control" placeholder="********" value=""/>
                                </div>
                            </div>
                            <div class="col-md-12">
                                @if ($message = Session::get('error'))
                                <p class="text-danger">{{ $message }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="divider row"></div>
                        <div class="d-flex align-items-center">
                            <div class="login-btn">
                                <a href="{{route('login')}}" class="btn-md btn btn-link btn-link-dark">Kembali ke halaman login </a>
                                <button type="submit" value="Login"  class="btn btn-cs-red btn-md">Update Password</button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
<script>
function showPass() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
</body>
</html>
