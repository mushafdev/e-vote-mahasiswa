
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
    @if(Formatter::identity()['cloudflare']=='active')
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
    <style>
        ul.timeline {
            list-style-type: none;
            position: relative;
        }
        ul.timeline:before {
            content: ' ';
            background: #d41833;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }
        ul.timeline > li {
            margin: 20px 0;
            padding-left: 20px;
        }
        ul.timeline > li:before {
            content: ' ';
            background: #d41833;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #d41833;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }
        a {
            color: #d41833;
            text-decoration: none;
            background-color: transparent;
            -webkit-text-decoration-skip: objects;
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="container-fluid" style="height: auto !important;">
	<div class="d-flex login-page2">
        <div class="row justify-content-center align-items-center p-md-5 p-4">
            <div class="mx-auto app-login-box col-md-12">
                
                <div>
                    <form role="form" data-save="{{route('registrasi.store')}}" method="POST" data-reload-captcha="{{route('login.reload_captcha')}}" id="form-registrasi" autocomplete="false" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-7">
                                <div>
                                    <a href="{{route('home')}}" style="text-decoration:none;">
                                        <span class="font-weight-bold text-danger" style="font-size:32px;letter-spacing:-1px;">E-Vote</span>
                                        <span class="text-muted ml-2" style="font-size:14px;font-weight:600;">{{Formatter::identity()['organization_name']}}</span>
                                    </a>
                                    <h4 class="font-weight-bold mt-2">Registrasi</h4>
                                </div>
                                <h6>Silahkan isi data di bawah</h6>
                                <p class="mt-3">Sudah punya akun? <a href="{{route('login')}}" class="text-red">Silahkan login</a></p>
                                <p class="mt-3"><button type="button" data-toggle="modal" data-target="#modalTahapan" class="btn btn-primary btn-sm">Lihat tahapan registrasi</button></p>
                                <div class=" alert alert-info"><p class="mb-0"><i class="fa fa-info-circle"></i> Jika ada kendala silahkan hubungi panitia</p></div>
                                @if ($message = Session::get('error'))
                                <div class=" alert alert-danger">{{ $message }}</div>
                                @endif
                                @if ($message = Session::get('success'))
                                <div class=" alert alert-danger">{{ $message }}</div>
                                @endif
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="nama" class="">Nama Lengkap <span class="text-info">*Sesuai kartu identitas</span></label>
                                            <div class="d-flex align-items-start">
                                                <div class="mr-2 col-10 p-0">
                                                    <input type="text" name="nama" id="nama" maxlength="50" class="form-control"  placeholder="Nama Lengkap" autocomplete="off" value="{{old('nama')}}"/>
                                                    @if($errors->has('nama'))
                                                    <p class="text-danger">{{ $errors->first('nama') }}</p>
                                                    @endif
                                                </div>
                                                <button type="button" class="btn btn-warning col-2" style="height:38px;" id="search-data"><i class="fa fa-search mr-1"></i><span class="d-none d-md-inline">Cari</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="no_anggota" class="">NIM / No. Identitas</label>
                                            <input type="text" name="no_anggota" id="no_anggota" maxlength="20" class="form-control" placeholder="NIM / No. Identitas" autocomplete="off" value="{{old('no_anggota')}}"/>
                                            @if($errors->has('no_anggota'))
                                            <p class="text-danger">{{ $errors->first('no_anggota') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="position-relative form-group">
                                            <label for="telp" class="">No. WA <span class="text-info">*Harus WA aktif</span></label>
                                            <input type="tel" name="telp" id="telp" maxlength="50" class="form-control"  placeholder="Contoh:085***" autocomplete="off" value="{{old('telp')}}"/>
                                            @if($errors->has('telp'))
                                            <p class="text-danger">{{ $errors->first('telp') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="position-relative form-group">
                                    <label for="email" class="">Email <span class="text-info">*Gunakan email aktif</span></label>
                                    <input type="email" name="email" id="email" maxlength="50" class="form-control" placeholder="user@gmail.com" autocomplete="off" value="{{old('email')}}"/>
                                    @if($errors->has('email'))
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="username" class="">Username</label>
                                            <input type="text" name="username" id="username" maxlength="50" class="form-control" placeholder="Username untuk login" autocomplete="off" value="{{old('username')}}"/>
                                            @if($errors->has('username'))
                                            <p class="text-danger">{{ $errors->first('username') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="password" class="">Password</label>
                                            <div class="input-group" id="show_hide_password_reg">
                                                <input type="password" name="password" id="password" maxlength="50" class="form-control" placeholder="Min. 8 karakter" autocomplete="new-password"/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text show-pass" style="cursor:pointer;">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            @if($errors->has('password'))
                                            <p class="text-danger">{{ $errors->first('password') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="position-relative form-group">
                                    <label for="ktp" class="">Upload KTP / Kartu Identitas</label>
                                    <span class="text-info"><small>*KTP, KTM, atau kartu identitas lain. Format png, jpg, pdf. Maks. 2 MB</small></span>
                                    <input type="file" name="ktp" id="ktp" accept="image/*,application/pdf" class="form-control"  placeholder="ktp" autocomplete="off" value="{{old('ktp')}}"/>
                                    @if($errors->has('ktp'))
                                    <p class="text-danger">{{ $errors->first('ktp') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="position-relative form-group mb-1">
                                    <label class="mb-0">Foto Wajah <span class="text-info"><small>*5 foto dari sudut berbeda</small></span></label>
                                    <p class="text-danger mb-1" id="file_photo"></p>

                                    {{-- Progress 5 foto --}}
                                    <div class="d-flex justify-content-center mb-2" id="photo-progress">
                                        <div class="text-center mx-1" id="step-1">
                                            <div class="rounded-circle border d-flex align-items-center justify-content-center mx-auto mb-1" style="width:36px;height:36px;font-size:12px;" id="dot-1">1</div>
                                            <small style="font-size:10px;">Depan</small>
                                        </div>
                                        <div class="text-center mx-1" id="step-2">
                                            <div class="rounded-circle border d-flex align-items-center justify-content-center mx-auto mb-1" style="width:36px;height:36px;font-size:12px;" id="dot-2">2</div>
                                            <small style="font-size:10px;">Kiri</small>
                                        </div>
                                        <div class="text-center mx-1" id="step-3">
                                            <div class="rounded-circle border d-flex align-items-center justify-content-center mx-auto mb-1" style="width:36px;height:36px;font-size:12px;" id="dot-3">3</div>
                                            <small style="font-size:10px;">Kanan</small>
                                        </div>
                                        <div class="text-center mx-1" id="step-4">
                                            <div class="rounded-circle border d-flex align-items-center justify-content-center mx-auto mb-1" style="width:36px;height:36px;font-size:12px;" id="dot-4">4</div>
                                            <small style="font-size:10px;">Menunduk</small>
                                        </div>
                                        <div class="text-center mx-1" id="step-5">
                                            <div class="rounded-circle border d-flex align-items-center justify-content-center mx-auto mb-1" style="width:36px;height:36px;font-size:12px;" id="dot-5">5</div>
                                            <small style="font-size:10px;">Mendongak</small>
                                        </div>
                                    </div>

                                    {{-- Panduan pose --}}
                                    <div class="text-center mb-1">
                                        <span class="badge badge-info" id="pose-guide">Hadap depan</span>
                                    </div>
                                </div>

                                {{-- Kamera --}}
                                <div style="position:relative;background:#000;min-height:220px;">
                                    <div id="cam-placeholder" class="d-flex align-items-center justify-content-center" style="position:absolute;top:0;left:0;width:100%;height:100%;min-height:220px;background:#1a1a2e;z-index:2;">
                                        <div class="text-center text-white">
                                            <i class="fa fa-camera fa-3x mb-2 d-block text-secondary"></i>
                                            <small>Kamera belum aktif</small>
                                        </div>
                                    </div>
                                    <video id="video" width="300" height="300" autoplay playsinline muted style="width:100%;display:none;"></video>
                                    <canvas id="canvas" width="300" height="300" style="width:100%;display:none;"></canvas>
                                </div>

                                <div class="mt-2">
                                    <button type="button" class="btn btn-info w-100" id="start-camera">
                                        <i class="fa fa-video mr-1"></i> Aktifkan Kamera
                                    </button>
                                    <button type="button" class="btn btn-success w-100 mt-1" id="click-photo" style="display:none">
                                        <i class="fa fa-camera mr-1"></i> Ambil Foto <span id="photo-count-label">(1/5)</span>
                                    </button>
                                    <button type="button" class="btn btn-warning w-100 mt-1" id="retake-photo" style="display:none">
                                        <i class="fa fa-redo mr-1"></i> Ulangi Foto Ini
                                    </button>
                                </div>

                                {{-- Preview thumbnail 5 foto --}}
                                <div class="d-flex mt-2 justify-content-between" id="preview-wrap">
                                    <div class="text-center" style="width:18%">
                                        <canvas id="prev-1" width="80" height="80" style="width:100%;border:2px dashed #ccc;border-radius:4px;"></canvas>
                                        <small style="font-size:9px;">Depan</small>
                                    </div>
                                    <div class="text-center" style="width:18%">
                                        <canvas id="prev-2" width="80" height="80" style="width:100%;border:2px dashed #ccc;border-radius:4px;"></canvas>
                                        <small style="font-size:9px;">Kiri</small>
                                    </div>
                                    <div class="text-center" style="width:18%">
                                        <canvas id="prev-3" width="80" height="80" style="width:100%;border:2px dashed #ccc;border-radius:4px;"></canvas>
                                        <small style="font-size:9px;">Kanan</small>
                                    </div>
                                    <div class="text-center" style="width:18%">
                                        <canvas id="prev-4" width="80" height="80" style="width:100%;border:2px dashed #ccc;border-radius:4px;"></canvas>
                                        <small style="font-size:9px;">Menunduk</small>
                                    </div>
                                    <div class="text-center" style="width:18%">
                                        <canvas id="prev-5" width="80" height="80" style="width:100%;border:2px dashed #ccc;border-radius:4px;"></canvas>
                                        <small style="font-size:9px;">Mendongak</small>
                                    </div>
                                </div>

                                {{-- Hidden inputs untuk 5 foto --}}
                                <input type="hidden" name="face_images[]" id="face_image_1">
                                <input type="hidden" name="face_images[]" id="face_image_2">
                                <input type="hidden" name="face_images[]" id="face_image_3">
                                <input type="hidden" name="face_images[]" id="face_image_4">
                                <input type="hidden" name="face_images[]" id="face_image_5">

                                @if(Formatter::identity()['cloudflare']=='active')
                                <div class="col-md-12 mt-2 p-0">
                                    <div class="cf-turnstile" data-sitekey="{{config('services.cloudflare.turnstile.site_key')}}" data-callback="onTurnstileSuccess"></div>
                                </div>
                                @endif
                            </div>

                        </div>
                        <div class="divider row mt-4"></div>
                        <div class="d-flex align-items-center">
                            <div class="login-btn w-100">
                                <button type="button" value="Registrasi" id="registrasi" {{Formatter::identity()['cloudflare']=='active'?'disabled="true"':''}}  class="btn btn-cs-red btn-md w-100">Registrasi</button>
                            </div>
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
                        
<!-- Modal -->
<div class="modal fade" id="modalTahapan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-body">
            <h4>Tahapan Registrasi</h4>
			<ul class="timeline">
				<li>
					<a href="#">Isi form registrasi</a>
					<p>Lengkapi data diri: nama, NIM / No. Identitas, nomor WA, email, kartu identitas, dan foto wajah</p>
				</li>
				<li>
					<a href="#">Ambil foto wajah</a>
					<p>Aktifkan kamera dan ambil foto wajah yang jelas untuk keperluan verifikasi saat voting</p>
				</li>
				<li>
					<a href="#">Akun langsung aktif</a>
					<p>Setelah submit, akun anda langsung dapat digunakan tanpa perlu menunggu verifikasi email</p>
				</li>
				<li>
					<a href="#">Login ke Aplikasi</a>
					<p>Gunakan username dan password yang anda daftarkan untuk masuk ke sistem</p>
				</li>
			</ul>
            <div class="alert alert-info" role="alert">
                <p class="mb-0"><i class="fa fa-info-circle"></i> Pastikan NIM / No. Identitas yang anda masukkan sudah benar sebelum submit</p>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Mengerti</button>
        </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalPencarian" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-body">
            <div class="row align-items-center pb-5 pt-5">
                <div class="col-md-12 text-center">
                    <h2 class="font-weight-bold mt-5">Cari <span class="text-danger">Data Anda</span></h2>
                    <h6 class="text-secondary mb-5">Silahkan ketik <b>Nama</b> atau <b>NIM / No. Identitas</b> untuk mencari data</h6>
                </div>
                <div class="col-md-12">
                        <form role="form" name="form-search" id="form-search" data-search-kandidat="{{route('account.search_kandidat')}}" data-detail-kandidat="{{route('account.get_detail_kandidat')}}">
                            <div class="form-group text-left">
                                <div class="input-group mb-2"> 
                                    <input type="text" class="form-control text-center" id="keyword" name="keyword" placeholder="Ketikkan Nama / NIM / No. Identitas" style="border-radius: 100px 0px 0px 100px;height: 39px;" required data-parsley-errors-container="#search-errors" minlength="3">
                                    <span class="input-group-btn"> <button class="btn btn-primary" style="line-height: 28px;border-radius: 0px 100px 100px 10px;width: 50px;" type="button" id="search-button"><i class="fa fa-search"></i></button> </span>
                                </div>    
                                <span id="search-errors"></span>
                                <span class="text-muted w-100 " id="count_show"></span>
                                
                            </div>
                        </form>
                    <div class="loading-data">
                        <h6 class="text-muted">sedang diproses</h6>
                        <div class="dot-typing ml-auto mr-auto mt-4 mb-5"></div>
                    </div>
                    
                    
                </div>
                <div class="col-md-12">
                              
                    <div id="table-result"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
        </div>
    </div>
</div>
                             
<script src="{{asset('assets/vendor/jquery/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/parsley.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/sweetalert2.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/vendor/architectui/css/sweetalert2.min.css')}}"/>
<script src="{{asset('assets/js/page/registrasi/registrasi.js')}}?v=6"></script>
<script src="{{asset('assets/js/page/public/custom.js')}}"></script>

</body>
</html>
