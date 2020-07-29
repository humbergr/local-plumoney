@php
    $userPublicist = Auth::user() ? Auth::user()->isPublicist() : null;
@endphp
<header class="fixed-top">
    <nav id="dashboard-navbar" class="navbar navbar-expand navbar-dark bg-primary py-1">
        @include('layouts.activeOperation')
        <div class="container">
            <a href="/home" class="navbar-brand">
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
                                <!-- <img src="/img/landing/awaiting-profile.svg" alt="Awaiting" data-toggle="tooltip" data-delay='{"show": 500, "hide": 100}' title="Verificación en proceso" class="user-status"> -->
                                    <!-- <img src="/img/landing/error-profile.svg" alt="Error" data-toggle="tooltip" data-delay='{"show": 500, "hide": 100}' title="Error en verificación. Ingrese a Configuración para saber más." class="user-status"> -->
                                    <!-- <img src="/img/landing/not-verified-profile.svg" alt="No verified" data-toggle="tooltip" data-delay='{"show": 500, "hide": 100}' title="Sin verificar" class="user-status"> -->
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
                                    <!-- <img src="/img/landing/awaiting-profile.svg" alt="Awaiting" data-toggle="tooltip" data-delay='{"show": 500, "hide": 100}' title="Verificación en proceso" class="user-status"> -->
                                        <!-- <img src="/img/landing/error-profile.svg" alt="Error" data-toggle="tooltip" data-delay='{"show": 500, "hide": 100}' title="Error en verificación. Ingrese a Configuración para saber más." class="user-status"> -->
                                        <!-- <img src="/img/landing/not-verified-profile.svg" alt="No verified" data-toggle="tooltip" data-delay='{"show": 500, "hide": 100}' title="Sin verificar" class="user-status"> -->
                                    </div>
                                    <div class="media-body">
                                        <div class="text-primary font-weight-bold">{{Auth::user()->name}}</div>
                                    </div>
                                </div>
                            </h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{URL::to('user-info')}}">Configuración</a>
                            <!--<a class="dropdown-item" href="directory.html">Directorio</a> -->
                            <a class="dropdown-item" href="{{URL::to('logout')}}">Cerrar sesión</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="dashboard__navigation text-white shadow-sm py-1">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <ul class="navbar-nav align-items-center flex-row">
                    <li class="nav-item mr-2">
                        <a class="nav-link ws-nowrap mx-0 active" href="{{URL::to('/home')}}">Home</a>
                    </li>
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
                        <a class="nav-link" href="{{URL::to('/wallets')}}">Billeteras</a>
                        <a class="nav-link" href="{{URL::to('/send-money')}}">Enviar Dinero</a>
                        <a class="nav-link" href="{{URL::to('/convert-money')}}">Cambiar Dinero</a>
                        <a class="nav-link" href="{{URL::to('/transactions-history')}}">Transacciones</a>
                        @if ($userPublicist)
                            <a href="{{URL::to('/referrals')}}" class="nav-link">Datos de referidos</a>
                        @endif
                        <a class="nav-link" href="{{URL::to('/support')}}">Soporte</a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
