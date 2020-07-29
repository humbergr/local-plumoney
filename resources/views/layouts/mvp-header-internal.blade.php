@php
$userPublicist = Auth::user() ? Auth::user()->isPublicist() : null;
@endphp
<header class="header--internal">
    <div class="topbar">
        @include('layouts.activeOperation')
        <div class="container">
            <ul class="nav justify-content-end align-items-center">
                @if(isset(Auth::user()->id))
                <li class="nav-item dropdown">
                    <a href="javascript:void(0);" style="color: #fff;"
                       class="dropdown-toggle"
                       id="dropdownUserLink"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user mr-2"></i>{{Auth::user()->name}}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownUserLink">
                        <a class="dropdown-item" href="/send-money">Enviar Dinero</a>
                        <a class="dropdown-item" href="/convert-money">Convertir Dinero</a>
                        <a class="dropdown-item" href="/wallets">Billeteras</a>
                        <a class="dropdown-item" href="/user-info">Configuración</a>
                        <a class="dropdown-item" href="/transactions-history">Historial de transacciones</a>
                        @if ($userPublicist)
                        <a class="dropdown-item" href="/referrals">Datos de referidos</a>
                        @endif
                        <a class="dropdown-item" href="/support">Soporte</a>
                    </div>
                </li>
                <li class="nav-item mx-1">/</li>
                <li class="nav-item">
                    <a href="/logout" class="text-secondary">Salir</a>
                </li>
                @else
                <!-- only show this item below when not logged in -->
                <li class="nav-item dropdown">
                    <button class="btn btn-secondary btn-sm btn-pill ml-3 px-3 px-lg-5" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user mr-2"></i>@lang('mvp-header.login')
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" style="width: 310px"
                         aria-labelledby="dropdownMenuButton">
                        <div class="py-3 px-4">
                            <form method="POST" id="login-form" action="{{ route('login') }}"
                                  aria-label="{{ __('Login') }}">
                                @csrf
                                <h6 class="text-primary font-weight-bold text-center mb-3">
                                    @lang('mvp-header.login_account')</h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-user-o text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           class="form-control"
                                           placeholder="Email">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-lock text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="Password">
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-secondary btn-pill">@lang('mvp-header.login')</button>
                                </div>
                                <div class="clearfix mt-3">
                                    <a href="{{URL::to('signin')}}" class="text-secondary float-left small">@lang('mvp-header.create_account')</a>
                                    <a href="{{URL::to('forgotten-password')}}"
                                       class="text-secondary float-right small">@lang('mvp-header.forgot_password')</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <nav id="home-main-navbar" class="navbar navbar-expand-md navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/home">
                <img src="/img/cb-img/coinbank-logo-light.png" alt="American Kryptos Bank Logo"
                     title="American Kryptos Bank" class="logo img-fluid">
            </a>
            <div>
                <div class="d-inline-block nav-item dropdown border-right border-mob-right-0 d-md-none">
                    <a class="nav-link py-md-0 mx-1 text-secondary" href="javascript:void(0);" id="lang-menu"
                       role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 18 18">
                            <g>
                                <g>
                                    <path fill="#fff"
                                          d="M12.325 15.26c1.002-1.66 1.427-3.908 1.504-5.835h2.75a7.13 7.13 0 0 1-4.254 5.834zm-2.82.576c-1.959 0-2.848-4.403-2.934-6.41h5.869c-.086 1.992-.978 6.41-2.935 6.41zm-3.932-1.173a.694.694 0 0 0-.686-.046L2.42 15.81l1.194-2.47a.694.694 0 0 0-.047-.685 7.072 7.072 0 0 1-1.145-3.23h2.76c.077 1.936.505 4.186 1.509 5.843a7.086 7.086 0 0 1-1.118-.605zM6.681 2.201c-.996 1.656-1.424 3.898-1.5 5.836h-2.76a7.13 7.13 0 0 1 4.26-5.836zm2.817-.583h.022c1.952.027 2.835 4.406 2.92 6.42H6.57c.092-2.164 1.023-6.406 2.928-6.42zm7.08 6.42H13.83c-.076-1.947-.505-4.18-1.496-5.831a7.13 7.13 0 0 1 4.246 5.83zM9.529.23h-.035A8.496 8.496 0 0 0 1 8.688c-.003.052-.002.04 0 .083a8.464 8.464 0 0 0 1.196 4.311l-1.894 3.92c-.285.589.336 1.212.927.926l3.916-1.894a8.462 8.462 0 0 0 4.355 1.2c4.698 0 8.5-3.803 8.5-8.503A8.496 8.496 0 0 0 9.529.23z"/>
                                </g>
                            </g>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="lang-menu">
                        <a class="dropdown-item text-primary" href="{{URL::to('setlocale/sp')}}">Español</a>
                        <a class="dropdown-item text-primary" href="{{URL::to('setlocale/en')}}">English</a>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navigation"
                        aria-controls="main-navigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon custom-toggler"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="main-navigation">
                <ul class="navbar-nav ml-auto align-items-md-center">
                    <li class="nav-item border-right border-mob-right-0">
                        <a href="{{URL::to('/send-money')}}" class="nav-link py-md-0 mx-1">@lang('mvp-header.send_money')</a>
                    </li>
                    <li class="nav-item border-right border-mob-right-0">
                        <a href="{{URL::to('/convert-money')}}" class="nav-link py-md-0 mx-1">@lang('mvp-header.convert_money')</a>
                    </li>
                    <li class="nav-item border-right border-mob-right-0">
                        <a href="{{URL::to('/wallets')}}" class="nav-link py-md-0 mx-1">@lang('mvp-header.wallets')</a>
                    </li>
                    @if ($userPublicist)
                    <li class="nav-item border-right border-mob-right-0">
                        <a href="{{URL::to('/referrals')}}" class="nav-link py-md-0 mx-1">Datos de referidos</a>
                    </li>
                    @endif
                    <!--
                    <li class="nav-item border-right border-mob-right-0">
                        <a href="{{URL::to('/about-us')}}" class="nav-link py-md-0 mx-1">@lang('mvp-header.about_us')</a>
                    </li>
                    <li class="nav-item border-right border-mob-right-0">
                        <a href="{{URL::to('/contact')}}" class="nav-link py-md-0 mx-1">@lang('mvp-header.contact_us')</a>
                    </li>
                    <li class="nav-item border-right border-mob-right-0">
                        <a href="{{URL::to('/help')}}" class="nav-link py-md-0 mx-1">@lang('mvp-header.help')</a>
                    </li>
                    -->
                    @if(Session::get('locale') == 'sp' || !Session::has('locale'))
                    <li class="nav-item dropdown border-right border-mob-right-0 d-none d-md-block">
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
                    <li class="nav-item dropdown border-right border-mob-right-0 d-none d-md-block">
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
                </ul>
            </div>
        </div>
    </nav>

    <!--
    <div id="home-sub-navbar" class="navbar navbar-submenu multi__submenu p-0 mt-md-n3 mb-2">
        <div class="container-fluid px-0">
            <ul class="nav justify-content-center d-none d-md-flex w-100">
                <li class="nav-item dropdown mx-md-1">
                    <a class="nav-link py-2 py-md-1"
                       href="javascript:void(0);"
                       role="button"
                       data-toggle="dropdown"
                       aria-haspopup="true"
                       aria-expanded="false">
                        @lang('mvp-header.personal')
                    </a>
                    <div class="dropdown-menu font-14">
                        <a class="dropdown-item" href="/services/send-money">Enviar Dinero</a>
                        <a class="dropdown-item" href="/services/exchange-money">Convertir Dinero</a>
                        <a class="dropdown-item" href="/services/crypto-market">Compra y Venta de Criptomonedas</a>
                        <a class="dropdown-item" href="/services/savings">Ahorrar</a>
                        <a class="dropdown-item" href="/services/transfer-money">Transferir Dinero</a>
                        <a class="dropdown-item" href="/services/investments">Inversión</a>
                        <a class="dropdown-item" href="javascript:void(0);">Tarjeta de Débito</a>
                    </div>
                </li>
                <li class="nav-item dropdown mx-md-1">
                    <a class="nav-link py-2 py-md-1"
                       href="javascript:void(0);"
                       role="button"
                       data-toggle="dropdown"
                       aria-haspopup="true"
                       aria-expanded="false">@lang('mvp-header.business')</a>
                    <div class="dropdown-menu font-14">
                        <a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Convertir Dinero</a>
                        <a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Recibe Pagos con Criptomonedas</a>
                        <a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Compra y Venta de Criptomonedas</a>
                        <a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Franquicia Kryptos</a>
                        <a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Ahorra</a>
                        <div class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle"
                               href="javascript:void(0);">Transfiere Dinero</a>
                            <ul class="dropdown-menu font-14">
                                <li><a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Transferencias Domésticas</a></li>
                                <li><a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Transferencias Internacionales</a></li>
                                <li><a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Pagos de Servicios</a></li>
                                <li><a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Pagos a Proveedores</a></li>
                                <li><a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Pagos de Nómina</a></li>
                            </ul>
                        </div>
                        <a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Inversiones</a>
                        <a class="dropdown-item" data-toggle="tooltip" title="Coming Soon" href="javascript:void(0);">Tarjeta de Débito</a>
                    </div>
                </li>
                {{--<li class="nav-item dropdown mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="javascript:void(0);">@lang('mvp-header.investors')</a>
                    <div class="dropdown-menu font-14">
                        <a class="dropdown-item" href="javascript:void(0);">Planes de Inversiones</a>
                    </div>
                </li>--}}
            </ul>


            <ul id="mobile-submenu" class="nav justify-content-center d-md-none w-100 mb-md-n5">
                <li class="nav-item mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="javascript:void(0);" data-toggle="collapse"
                       data-target="#subnavbar-personas" aria-controls="subnavbar-personas" aria-expanded="false">@lang('mvp-header.personal')</a>
                </li>
                <li class="nav-item mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon">@lang('mvp-header.business')</a>
                </li>
                {{--<li class="nav-item mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="javascript:void(0);" data-toggle="collapse"
                       data-target="#subnavbar-negocios" aria-controls="subnavbar-negocios" aria-expanded="false">@lang('mvp-header.business')</a>
                </li>
                <li class="nav-item mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="javascript:void(0);" data-toggle="collapse"
                       data-target="#subnavbar-inversionistas" aria-controls="subnavbar-inversionistas"
                       aria-expanded="false">@lang('mvp-header.investors')</a>
                </li>--}}
            </ul>

            <div class="collapse navbar-collapse multi__submenu__item" id="subnavbar-personas">
                <ul class="navbar-nav py-4 px-4">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/services/send-money">Enviar Dinero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/services/exchange-money">Convertir Dinero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/services/crypto-market">Compra y Venta de Criptomonedas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/services/savings">Ahorrar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/services/transfer-money">Transferir Dinero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/services/investments">Inversión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="javascript:void(0);">Tarjeta de Débito</a>
                    </li>
                </ul>
            </div>

            <div class="collapse navbar-collapse multi__submenu__item" id="subnavbar-negocios">
                <ul class="navbar-nav py-4 px-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="javascript:void(0);" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Enviar dinero
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="javascript:void(0);">A quienes les puedes enviar dinero?</a>
                            <a class="dropdown-item" href="javascript:void(0);">Métodos de pago para el envío</a>
                            <a class="dropdown-item" href="javascript:void(0);">Métodos para recibir dinero</a>
                            <a class="dropdown-item" href="javascript:void(0);">Rastrea tu remesa</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="javascript:void(0);">Convierte dinero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="javascript:void(0);">Compra y Venta de Criptomonedas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="javascript:void(0);">Genera Rentabilidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="javascript:void(0);">Tarjeta de Débito</a>
                    </li>
                </ul>
            </div>

            {{--<div class="collapse navbar-collapse multi__submenu__item" id="subnavbar-inversionistas">
                <ul class="navbar-nav py-4 px-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="javascript:void(0);" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Enviar dinero
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="javascript:void(0);">A quienes les puedes enviar dinero?</a>
                            <a class="dropdown-item" href="javascript:void(0);">Métodos de pago para el envío</a>
                            <a class="dropdown-item" href="javascript:void(0);">Métodos para recibir dinero</a>
                            <a class="dropdown-item" href="javascript:void(0);">Rastrea tu remesa</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="javascript:void(0);">Convierte dinero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="javascript:void(0);">Compra y Venta de Criptomonedas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="javascript:void(0);">Genera Rentabilidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="javascript:void(0);">Tarjeta de Débito</a>
                    </li>
                </ul>
            </div>--}}
        </div>
    </div>
    -->
</header>
