<header class="dashboard__header fixed-top">
    <nav id="dashboard-navbar" class="navbar navbar-expand navbar-dark bg-primary py-1 px-md-4">
        <a href="/" class="navbar-brand">
            <img src="{{ asset('img/cb-img/coinbank-logo-light.png') }}" style="max-height: 46px" alt="American Kryptos Bank Logo" title="American Kryptos Bank" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#dashboardNav" aria-controls="dashboardNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="dashboardNav">
            <ul class="navbar-nav ml-auto align-items-center">
                <!-- icono sin nuevas notificaciones
                <li class="nav-item mr-1">
                    <a href="#!" class="nav-link nav-link-rounded" title="No hay nuevas notificaciones">
                            <span class="position-relative">
                                <img src="img/landing/bell-empty.svg" alt="No hay nuevas notificaciones" class="img-fluid">
                                <span class="dashboard__notificationDot"></span>
                            </span>
                    </a>
                </li> -->
                <!-- icono nueva notificacion
                <li class="nav-item mr-1">
                    <a href="#!" class="nav-link nav-link-rounded dashboard__notification--new" title="Tienes nuevas notificaciones">
                            <span class="position-relative">
                                <img src="img/landing/bell-duotone.svg" alt="Tienes nuevas notificaciones" class="img-fluid">
                                <span class="dashboard__notificationDot"></span>
                            </span>
                    </a>
                </li> -->
                <li class="nav-item dropdown mr-1">
                    <a class="nav-link dropdown-toggle px-3" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-inline-block nav-avatar align-middle">
                                @if(Auth::user()->personProfile !== null &&
                                    Auth::user()->personProfile->selfie !== null)
                                    <img src="{{asset('$personProfile->selfie')}}" class="object-cover">
                                @else
                                    <img src="{{asset('img/cb-img/avatar.png')}}" class="object-cover">
                                @endif
                            </span>
                        <span class="d-none d-md-inline">{{Auth::user()->name}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown" style="min-width: 15rem;">
                       <!-- <h6 class="dropdown-header">Trader</h6>
                        <a class="dropdown-item" href="#">Overview</a>
                        <a class="dropdown-item" href="#">Analytics</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Profile</a>
                        <a class="dropdown-item" href="#">Account Settings</a> -->
                       <!-- <a class="dropdown-item" href="{{URL::to('/logout')}}">Logout</a> -->
                        <form id="logout-form"
                              action="{{ route('logout') }}"
                              method="POST">
                            @csrf
                            <button class="dropdown-item" type="submit">Logout</button>
                        </form>
                    </div>
                </li>
                <!-- <li class="nav-item">
                    <a href="#!" class="nav-link px-3"><span class="d-none d-md-inline">Logout</span> <i class="fa fa-power-off ml-1 va-middle"></i></a>
                </li> -->
            </ul>
        </div>
    </nav>
    <div class="dashboard__navigation text-white shadow-sm py-1">
        <div class="container-fluid px-md-4">
            <div class="d-flex align-items-center justify-content-between">
                <btc-wallet></btc-wallet>
                <nav-header></nav-header>
            </div>
        </div>
    </div>
</header>
