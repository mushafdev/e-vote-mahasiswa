<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
<title>{{Formatter::identity()['title']}} - Pemilih</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
<meta name="description" content="">
<meta name="msapplication-tap-highlight" content="no">
<link href="{{asset('assets/vendor/architectui/css/main.css')}}" rel="stylesheet">
<link href="{{asset('assets/vendor/architectui/css/custom.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/vendor/architectui/css/dataTables.bootstrap4.min.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/vendor/architectui/css/responsive.bootstrap4.min.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/vendor/architectui/css/sweetalert2.min.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/vendor/select2/bootstrap4-select2-theme.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/select2/select2.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrapdp/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

<script type="text/javascript" src="{{asset('assets/vendor/jquery/jquery-3.3.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendor/highchart/code/highcharts.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/highchart/code/modules/exporting.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/highchart/code/modules/export-data.js')}}"></script>
<!-- Include Editor style. -->
<!-- <link href='{{asset('assets/vendor/froala-editor/froala_editor.pkgd.min.css')}}' rel='stylesheet' type='text/css' /> -->

</head>

<body class="{{Formatter::theme()['page']}}" data-theme="{{Formatter::theme()['page']}}">
@php
   $inbox=0;
   $pars['inbox']=$inbox;
@endphp
    <div class="app-container  body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow {{Formatter::theme()['header']}}" data-theme="{{Formatter::theme()['header']}}">
             <!-- Header  -->
             @include('u.header',$pars)
             <!-- Header -->
    </div>
    <div class="app-main {{Formatter::theme()['page']}}" data-theme="{{Formatter::theme()['page']}}">
                <!-- Sidebar  -->
                @include('u.menu',$pars)
                <!-- End Sidebar -->
                <div class="app-main__outer">
                <!-- Content -->
                <div class="app-main__inner mb-5">
                        <!-- <div id="loading" class="buttonload load">
                        <i class="pe-7s-refresh-2 fa-spin load-spinner"></i>
                        </div> -->
                        @yield('content')
                </div>
                <!-- End Content -->

                </div>
    </div>
    <div class="app-wrapper-footer d-none" style="position: absolute;
    bottom: 0;
    width: 100%;">
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
                            <b>Ikatan Arsitek Indonesia</b> Sulawesi Selatan
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/perfect-scrollbar.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/dataTables.responsive.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/bootstrapdp/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/bootstrapdp/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/parsley.min.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/id.js')}}"></script> --}}
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/sweetalert2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/metismenu.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendor/select2/select2.min.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendor/architectui/scripts/custom.js')}}"></script>

</body>
</html>
