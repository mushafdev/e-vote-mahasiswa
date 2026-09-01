    <div class="app-header__logo">
                <div class="brand">
                    {{-- <img src="{{asset('assets/images/logo-simple-white.png')}}" alt=""> --}}
                    <div class="text">
                        <h1 class="">{{Formatter::identity()['title']}}</h1>
                        <h2>{{Formatter::identity()['sub_title']}}</h2>
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
                    <button type="button" class="btn-icon btn-icon-only btn btn-danger btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>    
            <div class="app-header__content {{!Formatter::theme()['header']?Formatter::theme()['page']:''}} bg-white">
                <div class="app-header-left">
                    <ul class="header-menu nav">
                        <li class="dropdown nav-item">
                            <a href="{{route('login.logout')}}" class="nav-link">
                                <i class="nav-link-icon fa fa-power-off"></i>
                                Log Out
                            </a>
                        </li>
                    </ul>        </div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="text-right widget-content-left  mr-3 header-user-info">
                                    <div class="widget-heading">
                                    </div>
                                    <div class="widget-subheading">

                                    </div>
                                </div>
                                <div class="widget-content-left">

                                    {{Auth::guard('pemilih')->user()->nama}}
                                    <div class="d-inline-block dropdown">
                                        
                                    </div>

                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            @php

                                                $photo=DB::table('pemilihs')->select('photo')->where('id',Auth::guard('pemilih')->user()->id)->first();
                                                if(!empty($photo->photo)){
                                                    $url_img=asset('storage/images/teams/'.$photo->photo);
                                                }else{
                                                    $url_img=asset('assets/images/user.png');
                                                }
                                            @endphp
                                            <img width="42" src="{{$url_img}}" id="img_header" style="border-radius: 7px;border: 1px solid white;">
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            {{-- <div tabindex="-1" class="dropdown-divider"></div> --}}
                                            {{-- <a href="{{route('account.view')}}" tabindex="0" class="dropdown-item"><i class="fa fa-user-circle mr-2"></i> Account</a> --}}
                                            <a href="{{route('login.logout')}}" tabindex="0" class="dropdown-item"><i class="fa fa-power-off mr-2"></i> Log Out</a>
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
