@php
    $level=Session::get('role');
    $unit_kerja_id=Session::get('unit_kerja_id');
    $role_name=array('c'=>'Unit Kerja','sc'=>'Sub Unit Kerja','at'=>'Audit','sp'=>'Supervisor Unit Kerja','kp'=>'Unit Kerja','a' =>'Administrator');
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
            <button type="button" class="btn-icon btn-icon-only btn btn-warning btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
   

    <div class="scrollbar-sidebar" style="margin-top: 0px;">
        <div class="app-sidebar__inner" style="margin-top: 15px;">
            <ul class="vertical-nav-menu" id="navigation">
                
                @can('pemilih registrasi')
                <li>
                    <a href="{{route('pemilih.registration_list')}}" class="navigation-list {{ request()->is('pemilih/registration-list') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-angle-right-circle"></i>
                        Notifikasi Pendaftar
                    </a>
                </li>
                @endcan
                @can('pemilih vote-list-notifikasi')
                <li>
                    <a href="{{route('pemilih.vote_list_notifikasi')}}" class="navigation-list {{ request()->is('pemilih/vote-list-notifikasi') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-angle-right-circle"></i>
                        Notifikasi Suara Masuk
                    </a>
                </li>
                @endcan
                @can('pemilih vote-list')
                <li>
                    <a href="{{route('pemilih.vote_list')}}" class="navigation-list {{ request()->is('pemilih/vote-list') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-angle-right-circle"></i>
                        Suara Masuk
                    </a>
                </li>
                @endcan
                @can('pemilih view')
                <li>
                    <a href="{{route('pemilih.main')}}" class="navigation-list {{ request()->is('pemilih') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-angle-right-circle"></i>
                        Data Pemilih
                    </a>
                </li>
                @endcan
                @can('kandidat hasil-kandidat')
                <li>
                    <a href="{{route('kandidat.hasil_kandidat')}}" class="navigation-list {{ request()->is('hasil-kandidat') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-angle-right-circle"></i>
                        Hasil perolehan suara Kandidat
                    </a>
                </li>
                @endcan
                {{-- @can('kandidat calon-kandidat-2')
                <li>
                    <a href="{{route('kandidat.calon_kandidat_2')}}" class="navigation-list {{ request()->is('calon-kandidat-2') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-angle-right-circle"></i>
                        Data Calon Kandidat Thp.2
                    </a>
                </li>
                @endcan
                @can('kandidat calon-kandidat')
                <li>
                    <a href="{{route('kandidat.calon_kandidat')}}" class="navigation-list {{ request()->is('calon-kandidat') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-angle-right-circle"></i>
                        Data Calon Kandidat
                    </a>
                </li> 
                @endcan --}}
                @can('kandidat view')
                <li>
                    <a href="{{route('kandidat.main')}}" class="navigation-list {{ request()->is('kandidat') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-angle-right-circle"></i>
                        Kandidat
                    </a>
                </li>    
                @endcan
                

                <li>
                    <a href="{{route('face_log.main')}}" class="navigation-list {{ request()->is('face-verify-log*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-camera"></i>
                        Log Verifikasi Wajah
                    </a>
                </li>

                @can('users view')
                <li>
                    <a href="{{route('users.main')}}" class="navigation-list {{ request()->is('users','users/*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-users"></i>
                        User
                    </a>
                </li> 
                @endcan
                
                @can('permission view')
                <li>
                    <a href="{{route('permissions.main')}}" class="navigation-list {{ request()->is('permissions','permissions/*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-angle-right-circle"></i>
                        Permisions
                    </a>
                </li>
                @endcan
               
                @can('role view')
                <li>
                    <a href="{{route('role.main')}}" class="navigation-list {{ request()->is('role','role/*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-angle-right-circle"></i>
                        Role
                    </a>
                </li>
                @endcan


               
                


                <li class="app-sidebar__heading">My Akun</li>
                {{-- <li>
                    <a href="{{route('accountupdate.main')}}" class="navigation-list {{ request()->is('accountupdate','accountupdate/*') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-user"></i>
                        Akun
                    </a>
                </li> --}}
               

                <li>
                    <a href="{{route('admin_login.logout')}}" class="navigation-list">
                        <i class="metismenu-icon pe-7s-power  icon-gradient  bg-love-kiss"></i>
                        Logout
                    </a>
                </li>


            </ul>
        </div>
    </div>
</div>
