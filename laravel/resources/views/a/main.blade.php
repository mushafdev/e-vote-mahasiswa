<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
{{-- <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}"> --}}
<title>{{Formatter::identity()['title']}}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
<meta name="description" content="">
<meta name="msapplication-tap-highlight" content="no">

<link rel="stylesheet" href="{{asset('assets/css/poppins-font.css')}}"/>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/css/three-dots.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/vendor/architectui/css/sweetalert2.min.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/vendor/select2/bootstrap4-select2-theme.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/select2/select2.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrapdp/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />
{{-- <link rel="stylesheet" href="{{asset('assets/vendor/jquery/jquery.dataTables.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/vendor/architectui/css/responsive.bootstrap4.min.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/vendor/architectui/css/dataTables.bootstrap4.min.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/vendor/select2/select.dataTables.min.css')}}" rel="stylesheet" /> --}}

{{-- New --}}
<link rel="stylesheet" href="{{asset('assets/vendor/jquery/dataTables.dataTables.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/vendor/jquery/dataTables.bootstrap4.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/vendor/jquery/responsive.dataTables.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/vendor/jquery/responsive.bootstrap4.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/vendor/jquery/buttons.dataTables.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/vendor/jquery/stateRestore.dataTables.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/vendor/jquery/rowGroup.dataTables.css')}}" rel="stylesheet" />
{{-- New --}}
<link href="{{asset('assets/vendor/architectui/css/main.css')}}" rel="stylesheet">
<link href="{{asset('assets/vendor/architectui/css/custom.css')}}" rel="stylesheet">
<script type="text/javascript" src="{{asset('assets/vendor/jquery/jquery-3.3.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/jquery.scannerdetection.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- Include Editor style. -->

</head>
<body class="{{Formatter::theme()['page']}}" data-theme="{{Formatter::theme()['page']}}">
<div class="app-container  body-tabs-shadow fixed-sidebar fixed-header">
    <div class="app-header header-shadow {{Formatter::theme()['header']}}" data-theme="{{Formatter::theme()['header']}}">
             <!-- Header  -->
             @include('a.header')
             <!-- Header -->
    </div>
    <div class="ui-theme-settings">
        {{-- <button type="button"  class="btn-open-options btn btn-warning">
            <i class="fa fa-cog fa-w-16 fa-spin fa-2x"></i>
        </button> --}}
        <div class="theme-settings__inner">
            <div class="scrollbar-container">
                <div class="theme-settings__options-wrapper">
                    <h3 class="themeoptions-heading">
                        <div>
                            Pilihan Header 
                        </div>
                        <button type="button" class="btn-pill btn-shadow btn-wide ml-auto btn btn-focus btn-sm switch-header-cs-class" data-class="">
                            Pengaturan Awal
                        </button>
                    </h3>
                    <div class="p-3">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <h5 class="pb-2">Choose Color Scheme
                                </h5>
                                <div class="theme-settings-swatches">
                                    <div class="swatch-holder bg-primary switch-header-cs-class" data-class="bg-primary header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-secondary switch-header-cs-class" data-class="bg-secondary header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-success switch-header-cs-class" data-class="bg-success header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-info switch-header-cs-class" data-class="bg-info header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-warning switch-header-cs-class" data-class="bg-warning header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-danger switch-header-cs-class" data-class="bg-danger header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-light switch-header-cs-class" data-class="bg-light header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-dark switch-header-cs-class" data-class="bg-dark header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-focus switch-header-cs-class" data-class="bg-focus header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-alternate switch-header-cs-class" data-class="bg-alternate header-text-light">
                                    </div>
                                    <div class="divider">
                                    </div>
                                    <div class="swatch-holder bg-vicious-stance switch-header-cs-class" data-class="bg-vicious-stance header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-midnight-bloom switch-header-cs-class" data-class="bg-midnight-bloom header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-night-sky switch-header-cs-class" data-class="bg-night-sky header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-slick-carbon switch-header-cs-class" data-class="bg-slick-carbon header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-asteroid switch-header-cs-class" data-class="bg-asteroid header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-royal switch-header-cs-class" data-class="bg-royal header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-warm-flame switch-header-cs-class" data-class="bg-warm-flame header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-night-fade switch-header-cs-class" data-class="bg-night-fade header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-sunny-morning switch-header-cs-class" data-class="bg-sunny-morning header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-tempting-azure switch-header-cs-class" data-class="bg-tempting-azure header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-amy-crisp switch-header-cs-class" data-class="bg-amy-crisp header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-heavy-rain switch-header-cs-class" data-class="bg-heavy-rain header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-mean-fruit switch-header-cs-class" data-class="bg-mean-fruit header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-malibu-beach switch-header-cs-class" data-class="bg-malibu-beach header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-deep-blue switch-header-cs-class" data-class="bg-deep-blue header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-ripe-malin switch-header-cs-class" data-class="bg-ripe-malin header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-arielle-smile switch-header-cs-class" data-class="bg-arielle-smile header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-plum-plate switch-header-cs-class" data-class="bg-plum-plate header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-happy-fisher switch-header-cs-class" data-class="bg-happy-fisher header-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-happy-itmeo switch-header-cs-class" data-class="bg-happy-itmeo header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-mixed-hopes switch-header-cs-class" data-class="bg-mixed-hopes header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-strong-bliss switch-header-cs-class" data-class="bg-strong-bliss header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-grow-early switch-header-cs-class" data-class="bg-grow-early header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-love-kiss switch-header-cs-class" data-class="bg-love-kiss header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-premium-dark switch-header-cs-class" data-class="bg-premium-dark header-text-light">
                                    </div>
                                    <div class="swatch-holder bg-happy-green switch-header-cs-class" data-class="bg-happy-green header-text-light">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <h3 class="themeoptions-heading">
                        <div>Pilihan Sidebar</div>
                        <button type="button" class="btn-pill btn-shadow btn-wide ml-auto btn btn-focus btn-sm switch-sidebar-cs-class" data-class="">
                            Pengaturan Awal
                        </button>
                    </h3>
                    <div class="p-3">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <h5 class="pb-2">Choose Color Scheme
                                </h5>
                                <div class="theme-settings-swatches">
                                    <div class="swatch-holder bg-primary switch-sidebar-cs-class" data-class="bg-primary sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-secondary switch-sidebar-cs-class" data-class="bg-secondary sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-success switch-sidebar-cs-class" data-class="bg-success sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-info switch-sidebar-cs-class" data-class="bg-info sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-warning switch-sidebar-cs-class" data-class="bg-warning sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-danger switch-sidebar-cs-class" data-class="bg-danger sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-light switch-sidebar-cs-class" data-class="bg-light sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-dark switch-sidebar-cs-class" data-class="bg-dark sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-focus switch-sidebar-cs-class" data-class="bg-focus sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-alternate switch-sidebar-cs-class" data-class="bg-alternate sidebar-text-light">
                                    </div>
                                    <div class="divider">
                                    </div>
                                    <div class="swatch-holder bg-vicious-stance switch-sidebar-cs-class" data-class="bg-vicious-stance sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-midnight-bloom switch-sidebar-cs-class" data-class="bg-midnight-bloom sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-night-sky switch-sidebar-cs-class" data-class="bg-night-sky sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-slick-carbon switch-sidebar-cs-class" data-class="bg-slick-carbon sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-asteroid switch-sidebar-cs-class" data-class="bg-asteroid sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-royal switch-sidebar-cs-class" data-class="bg-royal sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-warm-flame switch-sidebar-cs-class" data-class="bg-warm-flame sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-night-fade switch-sidebar-cs-class" data-class="bg-night-fade sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-sunny-morning switch-sidebar-cs-class" data-class="bg-sunny-morning sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-tempting-azure switch-sidebar-cs-class" data-class="bg-tempting-azure sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-amy-crisp switch-sidebar-cs-class" data-class="bg-amy-crisp sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-heavy-rain switch-sidebar-cs-class" data-class="bg-heavy-rain sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-mean-fruit switch-sidebar-cs-class" data-class="bg-mean-fruit sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-malibu-beach switch-sidebar-cs-class" data-class="bg-malibu-beach sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-deep-blue switch-sidebar-cs-class" data-class="bg-deep-blue sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-ripe-malin switch-sidebar-cs-class" data-class="bg-ripe-malin sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-arielle-smile switch-sidebar-cs-class" data-class="bg-arielle-smile sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-plum-plate switch-sidebar-cs-class" data-class="bg-plum-plate sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-happy-fisher switch-sidebar-cs-class" data-class="bg-happy-fisher sidebar-text-dark">
                                    </div>
                                    <div class="swatch-holder bg-happy-itmeo switch-sidebar-cs-class" data-class="bg-happy-itmeo sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-mixed-hopes switch-sidebar-cs-class" data-class="bg-mixed-hopes sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-strong-bliss switch-sidebar-cs-class" data-class="bg-strong-bliss sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-grow-early switch-sidebar-cs-class" data-class="bg-grow-early sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-love-kiss switch-sidebar-cs-class" data-class="bg-love-kiss sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-premium-dark switch-sidebar-cs-class" data-class="bg-premium-dark sidebar-text-light">
                                    </div>
                                    <div class="swatch-holder bg-happy-green switch-sidebar-cs-class" data-class="bg-happy-green sidebar-text-light">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <h3 class="themeoptions-heading">
                        <div>Pilihan Thema Halaman</div>
                    </h3>
                    <div class="p-3">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <h5 class="pb-2">Light Mode/Dark Mode
                                </h5>
                                <div class="theme-settings-swatches">
                                    <div class="swatch-holder bg-light switch-page-cs-class" data-class="page-light">
                                    </div>
                                    <div class="swatch-holder bg-dark  switch-page-cs-class" data-class="page-dark">
                                    </div>
                                   
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="p-3">
                        <button class="btn btn-info w-100" id="save-theme" data-url="{{route('users.update_theme')}}" data-token="{{csrf_token()}}">Simpan dan Lihat Perubahan</button>
                        <button class="btn btn-light w-100 mt-2" id="close-theme" >Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <div class="app-main {{Formatter::theme()['page']}}" data-theme="{{Formatter::theme()['page']}}">
                <!-- Sidebar  -->
                @include('a.menu')
                <!-- End Sidebar -->
                <div class="app-main__outer">
                <!-- Content -->
                <div class="app-main__inner mb-5">
                        <div id="loading" class="buttonload load" style="display: none">
                        <i class="pe-7s-refresh-2 fa-spin load-spinner"></i>
                        </div>
                        @yield('content')
                </div>
                <!-- End Content -->

                </div>
    </div>
    <div class="app-wrapper-footer mt-auto" style="">
        <div class="app-footer {{Formatter::theme()['page']}}" data-theme="{{Formatter::theme()['page']}}">
            <div class="app-footer__inner">
                <div class="app-footer-left">
                    <ul class="nav">
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link"></a>
                        </li>
                    </ul>
                </div>
                <div class="app-footer-right">
                    <ul class="nav">
                        <li class="nav-item">
                            Support By <b>{{Formatter::identity()['support']}}</b> {{Formatter::identity()['sub_title']}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{asset('assets/vendor/jquery/dataTables.js')}}"></script>

{{-- Datatable Button --}}
<script type="text/javascript" src="{{asset('assets/vendor/jquery/dataTables.bootstrap4.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/responsive.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/responsive.bootstrap4.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/dataTables.stateRestore.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/stateRestore.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/dataTables.buttons.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/buttons.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/buttons.print.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/buttons.colVis.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/dataTables.rowGroup.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/jquery/rowGroup.dataTables.js')}}"></script>
{{-- Datatable Button --}}
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/perfect-scrollbar.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/bootstrapdp/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/bootstrapdp/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/parsley.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/id.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/sweetalert2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/metismenu.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/select2/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/popper/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/custom.js')}}"></script>
</body>
</html>
