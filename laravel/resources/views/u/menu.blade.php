@php
$pemilihs=DB::table('pemilihs')->where('id',Auth::guard('pemilih')->user()->id)->first();
@endphp
<div class="app-sidebar sidebar-shadow {{Formatter::theme()['sidebar']?Formatter::theme()['sidebar']:'sidebar-blue sidebar-shadow sidebar-text-white'}}" data-theme="{{Formatter::theme()['sidebar']}}">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-danger btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>

    <div class="scrollbar-sidebar" style="margin-top: 0px;">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu" style="" id="navigation">
                <li class="app-sidebar__heading">Dashboard</li>
                <li>
                    <a href="{{route('account','home')}}" class="navigation-list {{ request()->is('account/*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-home"></i>
                        Home
                    </a>
                </li>
                {{-- <li>
                    <a href="{{route('account.pilih_calon_kandidat')}}" class="navigation-list {{ request()->is('pilih-calon-kandidat') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-users"></i>
                        Pilih Calon Kandidat
                    </a>
                </li> --}}
                @if ($pemilihs->verifikasi=='Y')
                
                @endif
                <li class="app-sidebar__heading">My Akun</li>
                {{-- <li>
                    <a href="{{route('account.view')}}" class="navigation-list {{ request()->is('accountview','accountview/*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-user"></i>
                        Akun
                    </a>
                </li> --}}
               

                <li>
                    <a href="{{route('login.logout')}}" class="navigation-list">
                        <i class="metismenu-icon pe-7s-power  icon-gradient  bg-love-kiss"></i>
                        Logout
                    </a>
                </li>


            </ul>
        </div>
    </div>
</div>

<script>

</script>
