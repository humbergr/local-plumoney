<header class="header--internal mb-4">
        <nav class="navbar navbar-expand navbar-dark bg-none">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto align-items-center">
                        <li class="nav-item active">
                            <a class="nav-link" href="#!"><img src="{{ asset('img/cb-img/cb-not-icon.svg') }}"></a>
                        </li>
                        <li class="nav-item mr-3 mr-md-5">
                            <a class="nav-link" href="#!"><img src="{{ asset('img/cb-img/not-local-icon.png') }}"></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-3" href="{{URL::to('user-settings')}}" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-inline-block nav-avatar align-middle">
                                    <img src="{{ asset('img/cb-img/avatar.png') }}" class="object-cover">
                                </span>
                                {{Auth::user()->name}}
                            </a>
                            <div class="dropdown-menu text-right dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->role_id == 1)
                                    <a class="dropdown-item" href="{{URL::to('/app')}}">Home - Admin</a>
                                    <a class="dropdown-item" href="{{URL::to('/traders')}}">Traders</a>
                                    <a class="dropdown-item" href="{{URL::to('/profiles-to-approve')}}">Profiles</a>
                                @endif
                                @if(Auth::user()->role_id == 4)
                                <a class="dropdown-item" href="{{URL::to('/workroom')}}">WorkRoom</a>
                                @endif
                                <a class="dropdown-item" href="{{URL::to('user-settings')}}">Settings</a>
                                <a class="dropdown-item" href="{{URL::to('/dashboard')}}">Dashboard</a>
                                @if(Auth::user()->role_id == 1)
                                <a class="dropdown-item" href="{{URL::to('/create-user')}}">Invite User</a>
                                <a class="dropdown-item" href="{{URL::to('/user-profiles')}}">Users</a>
                                <a class="dropdown-item" href="{{URL::to('/exchange-transactions-list')}}">Customers Transactions</a>
                                <a class="dropdown-item" href="{{URL::to('/transactions')}}">Transactions</a>
                                <a class="dropdown-item" href="{{URL::to('/error-transactions')}}">Error Transactions</a>
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
                    </ul>
                </div>
            </div>
        </nav>
        <remaining-alert></remaining-alert>
        <section id="logo-section" class="container">
            <a href="/app" class="navbar-brand mb-3 mb-md-0">
                <img src="{{ asset('img/cb-img/coinbank-logo-light.png') }}" alt="American Kryptos Bank Logo" title="American Kryptos Bank" class="img-fluid">
            </a>
        </section>
        @if (Auth::user()->role_id === 1)
          <wallets-component></wallet-components>
        @endif
    </header>
