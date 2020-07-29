<header class="header--internal" style="position: relative; z-index: 0;">
    <nav class="d-none d-md-flex navbar navbar-expand navbar-dark bg-none">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto align-items-center">
                    @if(Auth::user() === null)
                        <li class="nav-item">
                            <a href="{{ URL::to('/signin') }}" class="nav-link"><i class="fa fa-user mr-2"></i>Ingresar</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{URL::to('settings')}}" role="button">
                                <span class="d-inline-block nav-avatar align-middle">
                                    <img src="{{ asset('img/cb-img/avatar.png') }}" class="object-cover">
                                </span>
                                {{Auth::user()->name}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{URL::to('/logout')}}" class="nav-link"><i class="fa fa-sign-out"></i></a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <section id="logo-section" class="container text-center text-md-left">
        <a href="/" class="navbar-brand mb-3 mb-md-0">
            <img src="{{ asset('img/cb-img/american-kryptos-bank-logo-light.png') }}" alt="Kryptos Bank Logo"
                 title="Kryptos Bank" class="img-fluid">
        </a>
    </section>
    <nav id="mobile-nav" class="mobile-nav d-block d-md-none">
        <div class="container">
            <ul class="navbar-nav my-auto">
                <li class="nav-item text-center">
                    <a href="{{URL::to('/signin')}}" class="nav-link">
                        <img src="img/landing/user-secondary-icon.svg" alt="">
                        <small class="d-block">Iniciar sesión</small>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

{{--    @if (Auth::user()->role_id === 5)--}}
{{--        --}}{{-- To Users Wallets--}}
{{--        <merchant-wallets-component></merchant-wallets-component>--}}
{{--    @endif--}}
</header>
