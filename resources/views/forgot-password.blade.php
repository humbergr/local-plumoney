<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- STYLES -->
    <link rel="stylesheet" href="css/coinbank.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/slick-theme.css">
    <link rel="stylesheet" href="css/animate.css">


    <!-- toastr notifications -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link rel="stylesheet" href="css/cb-main.css">

    <!-- google fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Mukta:300,400,600,700" rel="stylesheet"> -->

    <title>American Kryptos Bank</title>

    <link rel="icon" type="image/png" href="img/cb-img/favicon.png" />
</head>
<body class="bg-white">

    <header class="header--home">
        <nav class="d-none d-md-flex navbar navbar-expand navbar-dark bg-none">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto align-items-center">
                        <li class="nav-item">
                            <a href="{{URL::to('signin')}}" class="nav-link"><i class="fa fa-user mr-2"></i>Ingresar</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <section id="logo-section" class="container text-center text-md-left">
            <a href="/" class="navbar-brand mb-3 mb-md-0">
                <img src="img/cb-img/coinbank-logo-light.png" alt="American Kryptos Bank Logo" title="American Kryptos Bank" class="img-fluid">
            </a>
        </section>
        <nav id="mobile-nav" class="mobile-nav d-block d-md-none">
            <div class="container">
                <ul class="navbar-nav my-auto">
                    <li class="nav-item text-center">
                        <a href="login.html" class="nav-link">
                            <img src="img/landing/user-secondary-icon.svg" alt="">
                            <small class="d-block">Iniciar sesión</small>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div id="app">
      <main>
          <div class="container">
              <div class="row">
                  <div class="col-lg-9 col-xl-10 mx-auto px-0 px-md-3">
                      <div class="card mt-5 mt-md-n5 mt-5 mb-4 wow fadeInUp" style="z-index: 10">
                          <nav id="login-tabs" class="row no-gutters">
                              <div class="col-12">
                                  <a class="btn btn-secondary btn-block rounded-0 d-flex flex-column justify-content-center align-items-center h-100" href="#!">
                                      <div><i class="fa fa-user mr-2 d-none d-md-inline-block"></i> <span>Recuperar contraseña</span></div>
                                      <small class="d-block">Se te enviara un correo para continuar con el proceso.</small>
                                  </a>
                              </div>
                          <!--    <div class="col-6">
                                  <a class="btn btn-secondary btn-block rounded-0 d-flex flex-column justify-content-center align-items-center h-100" href="#"><div><img src="img/landing/enviar-dinero.svg" class="d-none d-md-inline-block mr-2">Enviar dinero</div></a>
                              </div> -->
                          </nav>
                          <div class="card-body py-4 py-lg-5">
                              <div class="row">
                                  <div class="col-md-3"></div>
                                  <div class="col-md-6 px-lg-5 mb-4 mb-md-0">
                                      <form method="POST" id="login-form" action="{{ URL::to('forgot-password') }}">
                                        @csrf
                                        <login-transaction></login-transaction>
                                          <h6 class="text-primary font-weight-bold mb-4">Ingresa tu email para recuperar tu contraseña</h6>
                                          <div class="input-group mb-3">
                                              <div class="input-group-prepend">
                                                  <span class="input-group-text">
                                                      <i class="fa fa-envelope-o text-muted"></i>
                                                  </span>
                                              </div>
                                              <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required>
                                          </div>
                                          <button class="btn btn-outline-secondary btn-pill btn-block py-2">Enviar</button>
                                      </form>
                                      <hr class="d-md-none">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </main>
    </div>

    <footer class="py-section-3 pb-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="text-center">
                        <img src="img/cb-img/coinbank-logo.png" alt="American Kryptos Bank Logo" class="img-fluid mb-4 wow fadeInUp" style="max-height: 55px">
                        <div class="wow fadeInUp"><span class="text-primary font-weight-bold">Creciendo</span> <span class="text-secondary">Contigo.</span></div>
                    </div>
                    <div class="row py-5">
                        <div class="col-md-4">
                            <div class="media mb-3 mb-md-0 wow fadeInUp">
                                <i class="fa fa-phone fa-3x text-primary mr-3"></i>
                                <div class="media-body my-auto">
                                    <h5 class="text-primary">
                                        <a href="tel:+1(786)245-8123">+1 (786) 245-8123</a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="media mb-3 mb-md-0 wow fadeInUp">
                                <i class="fa fa-envelope fa-3x text-primary mr-3"></i>
                                <div class="media-body my-auto">
                                    <h5 class="text-primary">
                                        <a href="mailto:info@coinbankusa.com">info@coinbankusa.com</a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="media mb-3 mb-md-0 wow fadeInUp">
                                <i class="fa fa-map-marker fa-3x text-primary mr-3"></i>
                                <div class="media-body my-auto">
                                    <h5 class="text-primary text-uppercase">3517 Nw 115th Ave. Doral FL 33178</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-primary py-5">
            <div class="text-center">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item wow fadeIn">
                        <a href="" class="text-light">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-facebook fa-stack-1x text-primary"></i>
                            </span>
                        </a>
                    </li>
                    <li class="list-inline-item wow fadeIn">
                        <a href="" class="text-light">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-instagram fa-stack-1x text-primary"></i>
                            </span>
                        </a>
                    </li>
                    <li class="list-inline-item wow fadeIn">
                        <a href="" class="text-light">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-twitter fa-stack-1x text-primary"></i>
                            </span>
                        </a>
                    </li>
                    <li class="list-inline-item wow fadeIn">
                        <a href="" class="text-light">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-linkedin fa-stack-1x text-primary"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="js/jquery.min.js" charset="utf-8"></script>
    <script src="js/popper.min.js" charset="utf-8"></script>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>

    <script src="js/Chart.min.js" charset="utf-8"></script>
    <script src="js/bootstrap-datepicker.min.js" charset="utf-8"></script>
    <script src="js/bootstrap-datepicker.es.min.js" charset="utf-8"></script>
    <script src="js/slick.js" charset="utf-8"></script>
    <script src="js/wow.min.js" charset="utf-8"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="js/main.js" charset="utf-8"></script>

    <script type="text/javascript">
    $(document).ready(function() {
      // $('#login-form').submit(function() {
      //   //removing from local storage
      //   localStorage.removeItem('sender');
      //   localStorage.removeItem('receiver');
      //   localStorage.removeItem('to_send');
      //   localStorage.removeItem('to_receive');
      //   localStorage.removeItem('recomended_price');
      //
      //   return true;
      // });
      //
      // $('#register-form').submit(function() {
      //   //removing from local storage
      //   localStorage.removeItem('sender');
      //   localStorage.removeItem('receiver');
      //   localStorage.removeItem('to_send');
      //   localStorage.removeItem('to_receive');
      //   localStorage.removeItem('recomended_price');
      //
      //   return true;
      // });
    })
    </script>

    @include('notifications-creator')
</body>
</html>
