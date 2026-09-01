
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

    <style>
        table tbody tr td{
            padding: 5px;
        }
        table{
            width: 100% !important;
        }
    </style>
  </head>
  <body class="bg-light">
    <div class="container">
        <header class="blog-header py-3">
          <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-6 pt-1">
                <a href="{{route('home')}}">
                    <img src="{{asset('assets/images/logo-full.png')}}" alt="" width="200px">
                </a>
            </div>
            <div class="col-6 d-flex justify-content-end align-items-center">
              
              <a class="btn btn-sm btn-outline-secondary me-2" href="{{route('registrasi')}}">Daftar Segara</a>
              <a class="btn btn-sm btn-danger" href="{{route('login')}}">Masuk</a>
            </div>
          </div>
        </header>
    </div>
    <!-- Team 2 - Bootstrap Brain Component -->
    <section class="py-2 py-md-2 py-xl-2" style="">
        <div class="container">
        
        </div>
    
        <div class="container overflow-hidden">
            <div class="row gy-4 gy-lg-0">
                <div class="col-md-4">
                    <img class="img-fluid bsb-scale bsb-hover-scale-up" loading="lazy" src="{{asset('images/kandidat/'.$kandidat->file_photo)}}" alt="{{$kandidat->nama}}">
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-inline-flex">
                                <h1 class="fw-bolder me-3 display-4" style="background: #bb1f35;color: #FFF;padding: 10px;border-radius: 10px;width: 62px;height: 62px;font-size: 58px;text-align: center;line-height: 36px;">{{$kandidat->no_urut}}</h1>
                                <div>
                                    <p class="h3 mb-0 mb-md-0 fw-bolder">{{$kandidat->nama}}</p>
                                    <p class="h3 mb-1 mb-md-1 fw-bolder text-danger">{{$kandidat->no_anggota}}</p>
                                </div>
                            </div>
                            <hr class="w-50 border-dark-subtle">
                            <div class="table-responsive">
                                {!!$kandidat->deskripsi!!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
  </body>
</html>
