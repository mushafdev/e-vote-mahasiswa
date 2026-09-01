   <div class="app-header__logo">
                <div class="brand">
                    {{-- <img src="{{asset('assets/images/logo-simple-white.png')}}" alt=""> --}}
                    <div class="text">
                        <h1 class="">{{Formatter::identity()['title']}}</h1>
                    </div>
                </div>
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
            {{-- //New Update --}}
            <div class="app-header__content {{!Formatter::theme()['header']?Formatter::theme()['page']:''}} bg-white">
                <div class="app-header-left" style="width: 90%;">
                    <ul class="header-menu nav">
                        <li class="dropdown nav-item">
                            <a href="{{route('admin_login.logout')}}" class="nav-link">
                                <i class="nav-link-icon fa fa-power-off"></i>
                                Log Out
                            </a>
                        </li>
                        
                    </ul>
                </div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="text-right widget-content-left  mr-3 header-user-info">
                                    <div class="widget-heading">
                                    </div>
                                    <div class="widget-subheading">
                                        {{Session::get('nama')}}
                                    </div>
                                </div>
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            @php
                                                $photo=DB::table('users')->select('photo')->where('id',Session::get('id'))->first();
                                                if(!empty($photo->photo)){
                                                    $url_img=asset('storage/images/users/'.$photo->photo);
                                                }else{
                                                    $url_img=asset('assets/images/user.png');
                                                }
                                            @endphp
                                            <img width="42" src="{{$url_img}}" id="img_header" style="border-radius: 7px;border: 1px solid white;">
                                            {{-- <span class="badge badge-danger" style="position: absolute;left: 15px;">1</span> --}}
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            {{-- <div tabindex="-1" class="dropdown-divider"></div> --}}
                                            <a href="#" tabindex="0" id="TooltipDemo" class="dropdown-item btn-open-options"><i class="fa fa-cog mr-2"></i> Tema</a>
                                            {{-- <a href="{{route('accountupdate.main')}}" tabindex="0" class="dropdown-item"><i class="fa fa-user-circle mr-2"></i> Account</a> --}}
                                            <a href="{{route('admin_login.logout')}}" tabindex="0" class="dropdown-item"><i class="fa fa-power-off mr-2"></i> Log Out</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-right header-user-info ml-3">
                                    <!-- <button type="button" class="btn-shadow p-1 btn btn-primary btn-sm show-toastr-example">
                                        <i class="fa text-white fa-calendar pr-1 pl-1"></i>
                                    </button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
            </div>
