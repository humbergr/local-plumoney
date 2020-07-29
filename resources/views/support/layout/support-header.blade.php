<header class="fixed-top">
    <nav id="dashboard-navbar" class="navbar navbar-expand navbar-dark bg-primary py-1">
        @include('layouts.activeOperation')
        <div class="container">
            <a href="/" class="navbar-brand">
                <img src="/img/cb-img/coinbank-logo-light.png" style="max-height: 46px" alt="American Kryptos Bank Logo"
                     title="American Kryptos Bank" class="img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#dashboardNav"
                    aria-controls="dashboardNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="dashboardNav">
                <ul class="navbar-nav ml-auto align-items-center">
                    <!-- icono sin nuevas notificaciones -->
                    <!-- <li class="nav-item mr-1">
                        <a href="#!" class="nav-link nav-link-rounded" title="No hay nuevas notificaciones">
                            <span class="position-relative">
                                <img src="/img/landing/bell-empty.svg" alt="No hay nuevas notificaciones" class="img-fluid">
                                <span class="dashboard__notificationDot"></span>
                            </span>
                        </a>
                    </li> -->
                    <!-- icono nueva notificacion -->
                    <!-- <li class="nav-item mr-1">
                         <a href="#!" class="nav-link nav-link-rounded dashboard__notification--new" title="Tienes nuevas notificaciones">
                                 <span class="position-relative">
                                     <img src="/img/landing/bell-duotone.svg" alt="Tienes nuevas notificaciones" class="img-fluid">
                                     <span class="dashboard__notificationDot"></span>
                                 </span>
                         </a>
                     </li> -->
                    @if(Auth::user())
                    <li class="nav-item dropdown mr-1">
                        <a class="nav-link dropdown-toggle px-3" href="#" id="profileDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-inline-block nav-avatar align-middle">
                                    @if(isset(Auth::user()->personProfile->selfie))
                                        <img src="{{Auth::user()->personProfile->selfie}}"
                                             class="object-cover rounded-circle">
                                        <img src="/img/landing/verified-profile.svg" alt="Verified"
                                             data-toggle="tooltip" data-delay='{"show": 500, "hide": 100}'
                                             title="Verificado" class="user-status">
                                    @else
                                        <img src="/img/cb-img/avatar.png" class="object-cover rounded-circle">
                                    @endif                                
                                </span>
                            <span class="d-none d-md-inline">{{ucwords(Auth::user()->name)}}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown"
                             style="min-width: 15rem;">
                            <h6 class="dropdown-header">
                                <div class="media align-items-center">
                                    <div class="nav-avatar mr-2">
                                        @if(isset(Auth::user()->personProfile->selfie))
                                            <img src="{{Auth::user()->personProfile->selfie}}"
                                                 class="object-cover rounded-circle">
                                            <img src="/img/landing/verified-profile.svg" alt="Verified"
                                                 data-toggle="tooltip" data-delay='{"show": 500, "hide": 100}'
                                                 title="Verificado" class="user-status">
                                        @else
                                            <img src="/img/cb-img/avatar.png" class="object-cover rounded-circle">
                                        @endif
                                    </div>
                                    <div class="media-body">
                                        <div class="text-primary font-weight-bold">{{Auth::user()->name}}</div>
                                    </div>
                                </div>
                            </h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{URL::to('user-info')}}">Configuración</a>
                            <a class="dropdown-item" href="{{URL::to('logout')}}">Cerrar sesión</a>
                        </div>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a href="{{URL::to('/login')}}" class="btn btn-secondary btn-sm btn-pill ml-3 px-3 px-lg-5" id="dropdownMenuButton">
                            <i class="fa fa-user mr-2"></i>@lang('mvp-header.login')
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="dashboard__navigation text-white shadow-sm py-1">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <ul class="navbar-nav align-items-center flex-row">
                    @if(Auth::user())
                    <li class="nav-item mr-2">
                        <a class="nav-link ws-nowrap mx-0" href="{{URL::to('/home')}}">Home</a>
                    </li>
                    @endif
                </ul>
                <nav class="nav navbar-expand-md nav-underline navbar-dark align-items-center flex-nowrap">
                    <button class="sidebar__toggler d-md-none" type="button" data-target="#mobile-navbar"
                            data-open="false" aria-label="Toggle navigation">
                        <svg class="sidebar__togglerIcon" width="26" height="20" viewBox="0 0 26 17" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <line y1="0" x2="26" y2="0" stroke="white"/>
                            <line y1="9" x2="26" y2="9" stroke="white"/>
                            <line y1="18" x2="26" y2="18" stroke="white"/>
                        </svg>
                    </button>
                    <div class="collapse navbar-collapse" id="desktop-navbar">
                    @if(Auth::user())
                        <a class="nav-link active" href="{{URL::to('/my-tickets')}}">@lang('support.my_tickets') @if (isset($data->open))
                            ({{$data->open}})
                        @endif</a>
                    @endif
                        <a class="nav-link " href="{{URL::to('/create-ticket')}}">@lang('support.create_ticket')</a>
                        <a class="nav-link" href="{{URL::to('/help')}}">@lang('support.faq')</a>
                        @if(Session::get('locale') == 'sp' || !Session::has('locale'))
                        <li class="nav-item dropdown">
                            <a class="nav-link py-md-0 mx-1 text-secondary" href="javascript:void(0);" id="lang-menu"
                               role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                    <g><g><path
                                                    fill="#fff"
                                                    d="M12.325 15.26c1.002-1.66 1.427-3.908 1.504-5.835h2.75a7.13 7.13 0 0 1-4.254 5.834zm-2.82.576c-1.959 0-2.848-4.403-2.934-6.41h5.869c-.086 1.992-.978 6.41-2.935 6.41zm-3.932-1.173a.694.694 0 0 0-.686-.046L2.42 15.81l1.194-2.47a.694.694 0 0 0-.047-.685 7.072 7.072 0 0 1-1.145-3.23h2.76c.077 1.936.505 4.186 1.509 5.843a7.086 7.086 0 0 1-1.118-.605zM6.681 2.201c-.996 1.656-1.424 3.898-1.5 5.836h-2.76a7.13 7.13 0 0 1 4.26-5.836zm2.817-.583h.022c1.952.027 2.835 4.406 2.92 6.42H6.57c.092-2.164 1.023-6.406 2.928-6.42zm7.08 6.42H13.83c-.076-1.947-.505-4.18-1.496-5.831a7.13 7.13 0 0 1 4.246 5.83zM9.529.23h-.035A8.496 8.496 0 0 0 1 8.688c-.003.052-.002.04 0 .083a8.464 8.464 0 0 0 1.196 4.311l-1.894 3.92c-.285.589.336 1.212.927.926l3.916-1.894a8.462 8.462 0 0 0 4.355 1.2c4.698 0 8.5-3.803 8.5-8.503A8.496 8.496 0 0 0 9.529.23z"/></g></g>
                                </svg>
                            </span>Español
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="lang-menu">
                                <a class="dropdown-item" href="{{URL::to('setlocale/en')}}">English</a>
                            </div>
                        </li>
                    @elseif(Session::get('locale') == 'en')
                        <li class="nav-item dropdown">
                            <a class="nav-link py-md-0 mx-1 text-secondary" href="javascript:void(0);" id="lang-menu"
                               role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                    <g><g><path
                                                    fill="#fff"
                                                    d="M12.325 15.26c1.002-1.66 1.427-3.908 1.504-5.835h2.75a7.13 7.13 0 0 1-4.254 5.834zm-2.82.576c-1.959 0-2.848-4.403-2.934-6.41h5.869c-.086 1.992-.978 6.41-2.935 6.41zm-3.932-1.173a.694.694 0 0 0-.686-.046L2.42 15.81l1.194-2.47a.694.694 0 0 0-.047-.685 7.072 7.072 0 0 1-1.145-3.23h2.76c.077 1.936.505 4.186 1.509 5.843a7.086 7.086 0 0 1-1.118-.605zM6.681 2.201c-.996 1.656-1.424 3.898-1.5 5.836h-2.76a7.13 7.13 0 0 1 4.26-5.836zm2.817-.583h.022c1.952.027 2.835 4.406 2.92 6.42H6.57c.092-2.164 1.023-6.406 2.928-6.42zm7.08 6.42H13.83c-.076-1.947-.505-4.18-1.496-5.831a7.13 7.13 0 0 1 4.246 5.83zM9.529.23h-.035A8.496 8.496 0 0 0 1 8.688c-.003.052-.002.04 0 .083a8.464 8.464 0 0 0 1.196 4.311l-1.894 3.92c-.285.589.336 1.212.927.926l3.916-1.894a8.462 8.462 0 0 0 4.355 1.2c4.698 0 8.5-3.803 8.5-8.503A8.496 8.496 0 0 0 9.529.23z"/></g></g>
                                </svg>
                            </span>English
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="lang-menu">
                                <a class="dropdown-item" href="{{URL::to('setlocale/sp')}}">Español</a>
                            </div>
                        </li>
                    @endif
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
<br><br><br><br><br>