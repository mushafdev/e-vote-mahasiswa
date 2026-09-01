
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Language" content="en">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
	<title>{{Formatter::identity()['title']}} - Login</title>
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
<div class="container-fluid h-100 p-0">
	<div class="d-flex login-page">
        <div class="space-1">
            <div class="slide-x h-100">
                @if ($sliders->count()>0)
                    @foreach ($sliders as $dt )
                        <div class="slide">
                            <div class="slide__img">
                                <img src="" alt="" data-lazy="{{asset('storage/images/slider/'.$dt->file)}}" class="full-image animated h-100" data-animation-in="zoomInImage" style="width: 100% !important;"/>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="slide">
                        <div class="slide__img">
                            <img src="" alt="" data-lazy="{{asset('assets/images/slide/pexels-fauxels-3183185.jpg')}}" class="full-image animated h-100" data-animation-in="zoomInImage" style="width: 100% !important;"/>
                        </div>
                    </div>
                    <div class="slide">
                        <div class="slide__img">
                            <img src="" alt="" data-lazy="{{asset('assets/images/slide/pexels-lukas-669623.jpg')}}" class="full-image animated h-100" data-animation-in="zoomInImage" style="width: 100% !important;"/>
                        </div>
                    </div>
                    <div class="slide">
                        <div class="slide__img">
                            <img src="" alt="" data-lazy="{{asset('assets/images/slide/pexels-mikael-blomkvist-6476250.jpg')}}" class="full-image animated h-100" data-animation-in="zoomInImage" style="width: 100% !important;"/>
                        </div>
                    </div>
                @endif
                
            </div>
        </div>
        <div class="h-100 d-flex justify-content-center align-items-center space-2">
            <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                <div class="">
                    <div>
                        <img src="{{asset('assets/images/logo-full.png')}}" alt="" width="250px">
                        <h4 class="font-weight-bold mb-3">E-Vote</h4>
                        {{-- <h5 class="mb-2 text-danger">Panlih IAI</h5> --}}
                    </div>
                    <h6>Silahkan masuk ke akun anda</h6>
                  </div>
                <div class="divider row mt-3"></div>
                <div>
                    <form action="{{route('admin_login.authenticate')}}" method="POST" data-reload-captcha="{{route('login.reload_captcha')}}" id="login" autocomplete="false">
                    {{ csrf_field() }}
                    <div style="display:none">
                        <input type="password" tabindex="-1"/>
                      </div>
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail" class="">Username</label>
                                    <input type="text" name="username" id="username" maxlength="50" class="form-control"  placeholder="Username" autocomplete="off" value="{{old('username')}}"/>
                                    @if($errors->has('username'))
                                    <p class="text-danger">{{ $errors->first('username') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">

                                <label for="examplePassword" class="">Password</label>
                                <div class="input-group" id="show_hide_password">
                                    <input type="password" name="password" id="password" maxlength="50" class="form-control pw-dots" placeholder="********" autocomplete="off" value=""/>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-cs-red show-pass" title="Show/Hide Password"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                    </div>

                                </div>
                                @if($errors->has('password'))
                                <p class="text-danger">{{ $errors->first('password') }}</p>
                                @endif
                                @if($errors->has('password_enc'))
                                <p class="text-danger">{{ $errors->first('password_enc') }}</p>
                                @endif
                            </div>
                            <div class="col-md-12">
                                @if ($message = Session::get('error'))
                                <p class="text-danger">{{ $message }}</p>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 text-right mt-4">
                                        <div class="form-group mb-0">
                                            <div class="captcha">
                                                <span>{!! captcha_img() !!}</span>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input type="text" id="captcha" name="captcha" class="form-control" placeholder="Masukkan Captcha">
                                            <div class="input-group-append">
                                                <button title="Reload" class="btn btn-cs-red reload" type="button" id="reload"><i class="fa fa-sync" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        @if($errors->has('captcha'))
                                        <p class="text-danger">{{ $errors->first('captcha') }}</p>
                                        @endif
                                    </div>
                                </div>

                                
                                
                            </div>
                            
                        </div>
                        {{-- <div class="position-relative form-check">
                            <input name="check" id="exampleCheck" type="checkbox" onclick="showPass()" class="form-check-input">
                            <label for="exampleCheck" class="form-check-label" >Tampilkan Password</label>
                        </div> --}}
                        <div class="divider row mt-4"></div>
                        <div class="d-flex align-items-center">
                            <div class="login-btn w-100">
                                <button type="submit" value="Login" id="btn-login"  class="btn btn-cs-red btn-md w-100">Masuk</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        {{-- <p class="mt-3">Tidak punya akun? <a href="{{route('registrasi')}}" class="text-red">Daftar Sekarang</a></p> --}}
              
                        {{-- <p>Support By <br><b>{{Formatter::identity()['support']}}</b></p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/vendor/jquery/jquery-3.3.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/slick/slick.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/slick/slick-animation.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/slick/slick-custom.js')}}"></script>
<script src="{{asset('assets/js/page/login/login.js')}}"></script>

</body>
</html>
