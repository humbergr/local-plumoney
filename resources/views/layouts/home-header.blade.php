  <header class="header--home">
    <div class="topbar">
        <div class="container">
            <ul class="nav justify-content-end align-items-center">
              @if(isset(Auth::user()->id))
                  <li class="nav-item">
                      <i class="fa fa-user mr-2"></i>{{Auth::user()->name}}
                  </li>
                  <li class="nav-item mx-1">/</li>
                  <li class="nav-item">
                      <a href="/logout" class="text-secondary">Salir</a>
                  </li>
                @else
                <!-- only show this item below when not logged in -->
                <li class="nav-item d-none d-lg-block">
                    <a href="{{URL::to('/signin')}}" class="text-light"><i class="fa fa-user mr-2"></i>Ingresar</a>
                </li>
                <li class="nav-item d-lg-none">
                    <button class="btn btn-secondary btn-sm ml-3 px-3" data-toggle="modal" data-target="#loginMobileModal">
                        <i class="fa fa-user mr-2"></i>Ingresa
                    </button>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <nav id="home-main-navbar" class="navbar navbar-expand-md navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="img/cb-img/coinbank-logo-light.png" alt="American Kryptos Bank Logo" title="American Kryptos Bank" class="logo img-fluid">
            </a>
            <div>
                <div class="d-inline-block nav-item dropdown border-right border-mob-right-0 d-md-none">
                    <a class="nav-link py-md-0 mx-1 text-secondary" href="#" id="lang-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 18 18"><g><g><path fill="#fff" d="M12.325 15.26c1.002-1.66 1.427-3.908 1.504-5.835h2.75a7.13 7.13 0 0 1-4.254 5.834zm-2.82.576c-1.959 0-2.848-4.403-2.934-6.41h5.869c-.086 1.992-.978 6.41-2.935 6.41zm-3.932-1.173a.694.694 0 0 0-.686-.046L2.42 15.81l1.194-2.47a.694.694 0 0 0-.047-.685 7.072 7.072 0 0 1-1.145-3.23h2.76c.077 1.936.505 4.186 1.509 5.843a7.086 7.086 0 0 1-1.118-.605zM6.681 2.201c-.996 1.656-1.424 3.898-1.5 5.836h-2.76a7.13 7.13 0 0 1 4.26-5.836zm2.817-.583h.022c1.952.027 2.835 4.406 2.92 6.42H6.57c.092-2.164 1.023-6.406 2.928-6.42zm7.08 6.42H13.83c-.076-1.947-.505-4.18-1.496-5.831a7.13 7.13 0 0 1 4.246 5.83zM9.529.23h-.035A8.496 8.496 0 0 0 1 8.688c-.003.052-.002.04 0 .083a8.464 8.464 0 0 0 1.196 4.311l-1.894 3.92c-.285.589.336 1.212.927.926l3.916-1.894a8.462 8.462 0 0 0 4.355 1.2c4.698 0 8.5-3.803 8.5-8.503A8.496 8.496 0 0 0 9.529.23z"/></g></g></svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="lang-menu">
                        <a class="dropdown-item text-primary" href="#">Español</a>
                        <a class="dropdown-item text-primary" href="#">Inglés</a>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navigation" aria-controls="main-navigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon custom-toggler"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="main-navigation">
                <ul class="navbar-nav ml-auto align-items-md-center">
                    <li class="nav-item border-right border-mob-right-0">
                        <a href="{{URL::to('/about-us')}}" class="nav-link py-md-0 mx-1">Quiénes somos</a>
                    </li>
                    <li class="nav-item border-right border-mob-right-0">
                        <a href="{{URL::to('/contact')}}" class="nav-link py-md-0 mx-1">Contacto</a>
                    </li>
                    <li class="nav-item border-right border-mob-right-0">
                        <a href="help.html" class="nav-link py-md-0 mx-1">Ayuda</a>
                    </li>
                    <li class="nav-item dropdown border-right border-mob-right-0 d-none d-md-block">
                        <a class="nav-link py-md-0 mx-1 text-secondary" href="#" id="lang-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><g><g><path fill="#fff" d="M12.325 15.26c1.002-1.66 1.427-3.908 1.504-5.835h2.75a7.13 7.13 0 0 1-4.254 5.834zm-2.82.576c-1.959 0-2.848-4.403-2.934-6.41h5.869c-.086 1.992-.978 6.41-2.935 6.41zm-3.932-1.173a.694.694 0 0 0-.686-.046L2.42 15.81l1.194-2.47a.694.694 0 0 0-.047-.685 7.072 7.072 0 0 1-1.145-3.23h2.76c.077 1.936.505 4.186 1.509 5.843a7.086 7.086 0 0 1-1.118-.605zM6.681 2.201c-.996 1.656-1.424 3.898-1.5 5.836h-2.76a7.13 7.13 0 0 1 4.26-5.836zm2.817-.583h.022c1.952.027 2.835 4.406 2.92 6.42H6.57c.092-2.164 1.023-6.406 2.928-6.42zm7.08 6.42H13.83c-.076-1.947-.505-4.18-1.496-5.831a7.13 7.13 0 0 1 4.246 5.83zM9.529.23h-.035A8.496 8.496 0 0 0 1 8.688c-.003.052-.002.04 0 .083a8.464 8.464 0 0 0 1.196 4.311l-1.894 3.92c-.285.589.336 1.212.927.926l3.916-1.894a8.462 8.462 0 0 0 4.355 1.2c4.698 0 8.5-3.803 8.5-8.503A8.496 8.496 0 0 0 9.529.23z"/></g></g></svg>
                            </span>Español
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="lang-menu">
                            <a class="dropdown-item" href="#">Inglés</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="home-sub-navbar" class="navbar navbar-submenu multi__submenu p-0 mt-md-n3">
        <div class="container-fluid px-0">
            <!-- desktop submenu -->
            <ul class="nav justify-content-center d-none d-md-flex w-100 mb-md-n5">
                <li class="nav-item mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="#">Personal</a>
                </li>
                <li class="nav-item mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="#">Negocios</a>
                </li>
                <li class="nav-item mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="#">Inversionistas</a>
                </li>
            </ul>

            <!-- mobile submenu -->
            <ul class="nav justify-content-center d-md-none w-100 mb-md-n5">
                <li class="nav-item mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="#" data-toggle="collapse" data-target="#subnavbar-personas" aria-controls="subnavbar-personas" aria-expanded="false">Personal</a>
                </li>
                <li class="nav-item mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="#" data-toggle="collapse" data-target="#subnavbar-negocios" aria-controls="subnavbar-negocios" aria-expanded="false">Negocios</a>
                </li>
                <li class="nav-item mx-md-1">
                    <a class="nav-link py-2 py-md-1" href="#" data-toggle="collapse" data-target="#subnavbar-inversionistas" aria-controls="subnavbar-inversionistas" aria-expanded="false">Inversionistas</a>
                </li>
            </ul>

            <!-- personas menu -->
            <div class="collapse navbar-collapse multi__submenu__item" id="subnavbar-personas">
                <ul class="navbar-nav py-4 px-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Enviar dinero
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">A quienes les puedes enviar dinero?</a>
                            <a class="dropdown-item" href="#">Métodos de pago para el envío</a>
                            <a class="dropdown-item" href="#">Métodos para recibir dinero</a>
                            <a class="dropdown-item" href="#">Rastrea tu remesa</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Convierte dinero</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Compra y Venta de Criptomonedas
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Item 0</a>
                            <a class="dropdown-item" href="#">Item 1</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Genera Rentabilidad
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Item 0</a>
                            <a class="dropdown-item" href="#">Item 1</a>
                            <a class="dropdown-item" href="#">Item 2</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="#">Genera Rentabilidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="#">Tarjeta de Débito</a>
                    </li>
                </ul>
            </div>
            <!-- negocios menu -->
            <div class="collapse navbar-collapse multi__submenu__item" id="subnavbar-negocios">
                <ul class="navbar-nav py-4 px-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Enviar dinero
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">A quienes les puedes enviar dinero?</a>
                            <a class="dropdown-item" href="#">Métodos de pago para el envío</a>
                            <a class="dropdown-item" href="#">Métodos para recibir dinero</a>
                            <a class="dropdown-item" href="#">Rastrea tu remesa</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Convierte dinero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="#">Compra y Venta de Criptomonedas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="#">Genera Rentabilidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="#">Tarjeta de Débito</a>
                    </li>
                </ul>
            </div>
            <!-- inversionistas menu -->
            <div class="collapse navbar-collapse multi__submenu__item" id="subnavbar-inversionistas">
                <ul class="navbar-nav py-4 px-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Enviar dinero
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">A quienes les puedes enviar dinero?</a>
                            <a class="dropdown-item" href="#">Métodos de pago para el envío</a>
                            <a class="dropdown-item" href="#">Métodos para recibir dinero</a>
                            <a class="dropdown-item" href="#">Rastrea tu remesa</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Convierte dinero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="#">Compra y Venta de Criptomonedas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="#">Genera Rentabilidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white " href="#">Tarjeta de Débito</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
