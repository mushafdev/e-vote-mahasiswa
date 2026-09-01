
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Language" content="en">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{Formatter::identity()['title']}} - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
	<meta name="description" content="">
	<meta name="msapplication-tap-highlight" content="no">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/cheatsheet/">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{asset('assets/images/favicon.png')}}" sizes="180x180">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" sizes="16x16" type="image/png">

    <link rel="mask-icon" href="{{asset('assets/images/favicon.png')}}" color="#7952b3">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}">
    <meta name="theme-color" content="#7952b3">

	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/team-2.css')}}">

    <script src="{{asset('assets/vendor/amcharts4/core.js')}}"></script>
    <script src="{{asset('assets/vendor/amcharts4/charts.js')}}"></script>
    <script src="{{asset('assets/vendor/amcharts4/animated.js')}}"></script>

    <style>
        .photo-placeholder {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #c0392b 0%, #8e1a0e 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            transition: background 0.3s ease;
        }
        .photo-placeholder::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0);
            transition: background 0.3s ease;
        }
        .photo-placeholder:hover::after {
            background: rgba(0,0,0,0.45);
        }
        .photo-placeholder svg { opacity: 0.9; position: relative; z-index: 1; }
        .photo-placeholder span {
            color: rgba(255,255,255,0.75);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }
    </style>
  </head>
  <body class="bg-light">
    <div class="container">
        <header class="blog-header py-3">
          <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-6 pt-1">
                <a href="{{route('home')}}" style="text-decoration:none;">
                    <span class="font-weight-bold text-danger" style="font-size:32px;letter-spacing:-1px;">E-Vote</span>
                    <span class="text-muted ml-2" style="font-size:14px;font-weight:600;">{{Formatter::identity()['organization_name']}}</span>
                </a>
            </div>
            <div class="col-6 d-flex justify-content-end align-items-center">

              <a class="btn btn-sm btn-outline-secondary me-2 tool_tip" href="{{route('registrasi')}}" data-toggle="tooltip" data-placement="left" title="Klik ini jika belum melakukan aktifasi akun">Daftar Segera</a>
              <a class="btn btn-sm btn-danger" href="{{route('login')}}">Masuk</a>
            </div>
          </div>
        </header>
    </div>
    <!-- Team 2 - Bootstrap Brain Component -->
    <section class="py-2 py-md-2 py-xl-2" style="">
        <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6">
            <p class="display-6 mb-0 mb-md-0 text-center fw-bolder">Pemilihan Ketua</p>
            <p class="display-6 mb-1 mb-md-1 text-center fw-bolder text-danger">{{ Formatter::identity()['title'] }}</p>
            <p class="display-7 mb-1 mb-md-1 text-center fw-bolder text-secondary">{{ Formatter::identity()['sub_title'] }}</p>
            <hr class="w-50 mx-auto mb-1 mb-xl-2 border-dark-subtle">
            </div>
        </div>
        </div>

        <div class="container overflow-hidden">
        <div class="row gy-4 gy-lg-0 justify-content-center">

            @foreach ($calon_kandidats as $dt)
            <div class="col-6 col-md-3 p-3">
                <div class="card border-0" style="box-shadow: 1px 7px 8.0px hsl(0deg 0% 0% / 0.38); border-radius: 10px;">
                    <figure class="card-img-top m-0 overflow-hidden bsb-overlay-hover">
                    <a href="{{route('home.kandidat_detail',Formatter::encrypt0($dt->id))}}">
                        @if($dt->file_photo)
                        <img class="img-fluid bsb-scale bsb-hover-scale-up" loading="lazy" src="{{asset('images/kandidat/'.$dt->file_photo)}}" alt="{{$dt->nama}}" style="width:100%;height:220px;object-fit:cover;">
                        @else
                        <div class="photo-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="white" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                            </svg>
                            <span>Foto belum tersedia</span>
                        </div>
                        @endif
                    </a>
                    <figcaption>
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-eye text-white bsb-hover-fadeInLeft" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                        </svg>
                        <h4 class="h6 text-white bsb-hover-fadeInRight mt-2">Read More</h4>
                    </figcaption>
                    </figure>
                    <div class="card-body border bg-danger p-2 " style="border-radius: 0px 0px 10px 10px;min-height:120px;">
                        <ul class="nav mb-0 bsb-nav-sep">
                            <li class="nav-item text-secondary">
                            <a class="nav-link link-secondary p-0 pe-3 d-inline-flex align-items-center w-100" href="{{route('home.kandidat_detail',Formatter::encrypt0($dt->id))}}">
                                <div>
                                    <h2 class="card-title text-white h5 fw-bolder mb-0 fs-6 fs-md-5">{{$dt->nama}}</h2>
                                    <p class="card-text text-white mb-0">{{$dt->no_anggota}}</p>
                                </div>
                            </a>
                            <a href="{{route('home.kandidat_detail',Formatter::encrypt0($dt->id))}}" class="display-6 card-text text-white text-center" style="font-size: 12px;position: absolute;bottom: 16px;left: 10px;">
                                Lihat Profil Calon</a>
                                <h1 class="fw-bolder text-white" style="position: absolute;right: 12px;bottom: -8px;font-size: 71px;">{{$dt->no_urut}}</h1>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  </body>
</html>
