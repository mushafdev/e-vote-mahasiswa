
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(Formatter::identity()['cloudflare']=='active')
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
    <link rel="stylesheet" href="{{asset('assets/css/poppins-font.css')}}"/>
    <link href="{{asset('assets/vendor/fontawesome/css/fontawesome-all.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/slick/slick.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/slick/slick-theme.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/slick/slick-custom.css')}}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/login.css')}}?v=2">
	<link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" id="bootstrap-css">
</head>
<body>

{{-- Config untuk JS (tanpa inline script) --}}
<div id="login-config" style="display:none;"
    data-authenticate="{{ route('login.authenticate') }}"
    data-face-verify="{{ route('login.verifikasi_wajah_proses') }}"
    data-csrf="{{ csrf_token() }}"
    data-cloudflare="{{ Formatter::identity()['cloudflare'] }}">
</div>

<div class="container-fluid h-100 p-0">
	<div class="d-flex login-page-3">
        <div class="space-1">
            <div class="h-100 bg-danger d-flex flex-column justify-content-center">
                <div class="p-5">
                    <div class="text-white mb-2">
                        <div class="mb-1" style="font-size:13px;letter-spacing:2px;opacity:.7;text-transform:uppercase;">Platform Pemilihan Digital</div>
                        <h1 class="font-weight-bold mb-1" style="font-size:42px;line-height:1.1;">{{Formatter::identity()['title']}}</h1>
                        <div style="width:40px;height:3px;background:rgba(255,255,255,0.5);margin:10px 0 6px;border-radius:2px;"></div>
                        <div style="font-size:13px;opacity:.75;letter-spacing:1px;">{{Formatter::identity()['organization_name']}}</div>
                    </div>

                    <div class="text-white my-4" style="font-size:14px;opacity:.85;line-height:1.8;max-width:340px;">
                        {{Formatter::identity()['sub_title']}}
                    </div>

                    <div class="text-white" style="margin-top:32px;">
                        <div class="d-flex align-items-start mb-4">
                            <div class="mr-3 mt-1" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold" style="font-size:14px;">Keamanan Terjamin</div>
                                <div style="font-size:12px;opacity:.75;">Data pemilih dienkripsi dan terlindungi</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-4">
                            <div class="mr-3 mt-1" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold" style="font-size:14px;">Verifikasi Wajah</div>
                                <div style="font-size:12px;opacity:.75;">Identitas pemilih diverifikasi secara real-time</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-4">
                            <div class="mr-3 mt-1" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold" style="font-size:14px;">Satu Suara, Satu Pilihan</div>
                                <div style="font-size:12px;opacity:.75;">Setiap pemilih hanya dapat memilih satu kali</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="mr-3 mt-1" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold" style="font-size:14px;">Hasil Transparan</div>
                                <div style="font-size:12px;opacity:.75;">Rekap suara dapat dipantau secara langsung</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <a href="{{route('home')}}" class="text-white" style="font-size:13px;opacity:.8;text-decoration:none;">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-100 d-flex justify-content-center align-items-center space-2">
            <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                <div class="">
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <h4 class="font-weight-bold mb-0">E-Vote</h4>
                        <a href="{{route('home')}}" class="text-muted" style="font-size:13px;text-decoration:none;">
                            <i class="fas fa-home mr-1"></i> Beranda
                        </a>
                    </div>
                    <h6 id="login-subtitle" class="mt-2">Silahkan masuk ke akun anda</h6>
                </div>
                <div class="divider row mt-3"></div>

                {{-- STEP 1: Username & Password --}}
                <div id="step-credentials">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success py-2 mb-3">{{ $message }}</div>
                    @endif
                    <div id="alert-credentials" class="alert alert-danger py-2 mb-3" style="display:none;"></div>

                    {{-- Form wrapper agar password field dikenali browser --}}
                    <form id="form-login" autocomplete="off">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Username</label>
                                <input type="text" id="username" name="username" maxlength="50" class="form-control" placeholder="Username" autocomplete="username" value="{{old('username')}}"/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Password</label>
                            <div class="input-group" id="show_hide_password">
                                <input type="password" id="password" name="password" maxlength="50" class="form-control pw-dots" placeholder="********" autocomplete="current-password"/>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-cs-red show-pass" title="Show/Hide Password">
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            @if(Formatter::identity()['cloudflare']=='active')
                            <div class="cf-turnstile" data-sitekey="{{config('services.cloudflare.turnstile.site_key')}}" data-callback="onTurnstileSuccess"></div>
                            @endif
                        </div>
                    </div>
                    <div class="divider row mt-4"></div>
                    <div class="d-flex align-items-center">
                        <div class="login-btn w-100">
                            <button type="button" id="btn-login" class="btn btn-cs-red btn-md w-100">
                                Masuk <i class="fa fa-arrow-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                    </form>

                    <div class="text-center mt-3">
                        <p class="mt-3">Tidak punya akun? <a href="{{route('registrasi')}}" class="text-red">Daftar Sekarang</a></p>
                    </div>
                </div>

                {{-- STEP 2: Verifikasi Wajah --}}
                <div id="step-face" style="display:none;">
                    <p class="text-muted small mb-2">Halo <strong id="face-nama"></strong>, arahkan wajah ke kamera lalu klik <b>Verifikasi Wajah</b>.</p>

                    <div id="alert-face" class="alert py-2 mb-2" style="display:none;"></div>

                    <div class="position-relative mb-3" style="background:#000;border-radius:8px;overflow:hidden;">
                        <video id="video" autoplay playsinline style="width:100%;display:block;border-radius:8px;max-height:280px;object-fit:cover;"></video>
                        <canvas id="canvas" style="display:none;"></canvas>
                        <div id="cam-placeholder" class="d-flex flex-column align-items-center justify-content-center text-white"
                             style="position:absolute;top:0;left:0;width:100%;height:100%;background:#1a1a2e;min-height:200px;">
                            <i class="fas fa-camera fa-3x mb-2 text-secondary"></i>
                            <small>Kamera belum aktif</small>
                        </div>
                    </div>

                    <button type="button" id="btn-verify-face" class="btn btn-cs-red btn-md w-100" disabled>
                        <i class="fas fa-check-circle mr-1"></i> Verifikasi Wajah
                    </button>
                    <div class="text-center mt-2">
                        <a href="#" id="btn-back" class="text-muted small"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/vendor/jquery/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('assets/js/page/public/custom.js')}}"></script>
<script src="{{asset('assets/js/page/login/login_pemilih.js')}}?v=7"></script>
</body>
</html>
