<header class="header--home">
    <nav class="d-none d-md-flex navbar navbar-expand navbar-dark bg-none">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav  ml-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link px-3" href="{{URL::to('settings')}}" role="button">
                                <span class="d-inline-block nav-avatar align-middle">
                                    <img src="{{ asset('img/cb-img/avatar.png') }}" class="object-cover">
                                </span>
                            {{Auth::user()->name}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <form id="logout-form"
                              action="{{ route('logout') }}"
                              method="POST">
                            @csrf
                            <button class="nav-link" type="submit"><i class="fa fa-sign-out"></i></button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section id="logo-section" class="container text-center text-md-left">
        <a href="/" class="navbar-brand mb-3 mb-md-0 mr-0">
            <img src="img/cb-img/coinbank-logo-light.png" alt="American Kryptos Bank Logo" title="American Kryptos Bank" class="img-fluid">
        </a>
    </section>
    <nav id="mobile-nav" class="mobile-nav d-block d-md-none">
        <div class="container">
            <ul class="navbar-nav my-auto">
                <li class="nav-item text-center">
                    <a href="login2.html" class="custom-navbar-toggler d-inline-block text-primary collapsed"
                       data-toggle="collapse" data-target="#navbarSupportedContent"
                       aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <img src="/img/landing/navbar-button.svg" alt="">
                    </a>
                </li>
            </ul>
            {{--<ul class="navbar-nav my-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#!"><img src="{{ asset('img/cb-img/cb-not-icon.svg') }}"></a>
                </li>
                <li class="nav-item mr-3 mr-md-5">
                    <a class="nav-link" href="#!"><img src="{{ asset('img/cb-img/not-local-icon.png') }}"></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3" href="{{URL::to('user-settings')}}" id="navbarDropdown"
                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-inline-block nav-avatar align-middle">
                                    <img src="{{ asset('img/cb-img/avatar.png') }}" class="object-cover">
                                </span>
                        {{Auth::user()->name}}
                    </a>
                    <div class="dropdown-menu text-right dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @if(Auth::user()->role_id == 4)
                            <a class="dropdown-item" href="{{URL::to('/workroom')}}">WorkRoom</a>
                        @endif
                        <a class="dropdown-item" href="{{URL::to('user-settings')}}">Settings</a>
                        <a class="dropdown-item" href="{{URL::to('/dashboard')}}">Dashboard</a>
                        @if(Auth::user()->role_id == 1)
                            <a class="dropdown-item" href="{{URL::to('/create-user')}}">Invite User</a>
                            <a class="dropdown-item" href="{{URL::to('/transactions')}}">Transactions</a>
                            <a class="dropdown-item" href="{{URL::to('/error-transactions')}}">Error
                                Transactions</a>
                        @endif
                        <a class="dropdown-item" href="{{URL::to('/search')}}">Search Ads</a>
                        <a class="dropdown-item" href="{{URL::to('/create-antifraud')}}">Create Antifraud</a>
                        <a class="dropdown-item" href="{{URL::to('/antifraud-forms')}}">Forms List</a>
                    </div>
                </li>
                <li class="nav-item">
                    <form id="logout-form"
                          action="{{ route('logout') }}"
                          method="POST">
                        @csrf
                        <button class="nav-link" type="submit"><i class="fa fa-sign-out"></i></button>
                    </form>
                </li>
            </ul>--}}
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="d-flex justify-content-between mb-3 mb-md-0">
                    <div class="wallet__item media align-items-center px-0">
                        <img src="/img/cb-img/usd-wallet.svg" class="img-fluid mr-2">
                        <div class="media-body">
                            <h6 class="wallet__text text-primary font-12 mb-0">En su billetera</h6>
                            <h6 class="wallet__amount text-primary mb-0">$ 3.500 USD</h6>
                        </div>
                    </div>
                    <div>
                        <form id="logout-form"
                              action="{{ route('logout') }}"
                              class="d-flex justify-content-between align-items-center"
                              method="POST">
                            @csrf
                            {{Auth::user()->name}}
                            <button class="nav-item nav-link text-primary small" type="submit"><i
                                        class="fa fa-sign-out"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    @if (Auth::user()->role_id === 5)
        {{-- To Users Wallets--}}
        <merchant-wallets-component></merchant-wallets-component>
    @endif
</header>
