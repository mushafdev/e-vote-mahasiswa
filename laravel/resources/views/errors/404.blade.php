<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"/>
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
    <title>{{Formatter::identity()['title']}} - Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="{{asset('assets/css/poppins-font.css')}}"/>
    <link href="{{asset('assets/vendor/fontawesome/css/fontawesome-all.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f4f6f9; margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-404 { background: #fff; border-radius: 16px; box-shadow: 0 8px 40px rgba(0,0,0,0.10); padding: 56px 48px; max-width: 480px; width: 100%; text-align: center; }
        .num-404 { font-size: 96px; font-weight: 800; color: #e8e8e8; line-height: 1; letter-spacing: -4px; }
        .num-404 span { color: #c0392b; }
        .icon-404 { width: 72px; height: 72px; background: #fdecea; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .icon-404 i { font-size: 30px; color: #c0392b; }
        .brand { text-decoration: none; display: inline-block; margin-bottom: 32px; }
        .brand-name { font-size: 26px; font-weight: 800; color: #c0392b; letter-spacing: -1px; }
        .brand-org { font-size: 12px; color: #999; font-weight: 600; margin-left: 6px; }
        .divider { width: 40px; height: 3px; background: #c0392b; border-radius: 2px; margin: 16px auto; }
        .btn-home { background: #c0392b; color: #fff; border: none; border-radius: 8px; padding: 12px 32px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-block; margin-top: 28px; transition: background .2s; }
        .btn-home:hover { background: #a93226; color: #fff; text-decoration: none; }
        .btn-back { color: #999; font-size: 13px; text-decoration: none; display: block; margin-top: 14px; }
        .btn-back:hover { color: #666; }
    </style>
</head>
<body>
    <div class="card-404">

        <a href="{{url('/')}}" class="brand">
            <span class="brand-name">E-Vote</span>
            <span class="brand-org">{{Formatter::identity()['organization_name']}}</span>
        </a>

        <div class="num-404">4<span>0</span>4</div>

        <div class="divider"></div>

        <div class="icon-404 mt-3">
            <i class="fas fa-map-marker-alt"></i>
        </div>

        <h5 class="font-weight-bold mb-1" style="color:#2d3436;">Halaman Tidak Ditemukan</h5>
        <p class="text-muted" style="font-size:13px;line-height:1.7;">
            Halaman yang Anda cari tidak tersedia atau telah dipindahkan.<br>
            Silahkan kembali ke beranda.
        </p>

        <a href="{{url('/')}}" class="btn-home">
            <i class="fas fa-home mr-2"></i> Kembali ke Beranda
        </a>

        <a href="javascript:history.back()" class="btn-back">
            <i class="fas fa-arrow-left mr-1"></i> Halaman sebelumnya
        </a>

    </div>
</body>
</html>
