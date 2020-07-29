<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- STYLES -->
    <link rel="stylesheet" href="{{ asset('css/coinbank.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/cb-main.css') }}">

    <!-- toastr notifications -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Mukta:300,400,600,700" rel="stylesheet">

    <title>American Kryptos Bank</title>
</head>
<body class="">

    <header class="py-4">
        <section id="logo-section" class="container">
            <a href="/" class="navbar-brand mb-3 mb-md-0">
                <img src="{{ asset('img/cb-img/coinbank-logo.png') }}" alt="American Kryptos Bank Logo" title="American Kryptos Bank" class="img-fluid" style="max-height: 55px">
            </a>
        </section>
    </header>

    <div id="app">
      <section id="login" class="d-flex align-items-center" style="min-height: calc(100vh - 113px - 113px)">
          <div class="container">
              <div class="row">
                  <div class="col-md-10 mx-auto">
                      <div class="row justify-content-center">
                          <div class="col-md-6 col-lg-6 px-md-0">
                              <div class="login-bg rounded shadow d-none d-md-flex">
                                  <div class="d-block mr-2 mr-lg-5">
                                      <img src="{{ asset('img/cb-img/coinbank-logo-light.png') }}" class="">
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6 col-lg-4 px-md-0">
                              <h4 class="text-primary font-weight-bold ml-4 my-3">Register American Kryptos Bank</h4>
                              <div class="card">
                                @if ($errors->has('email') || $errors->has('password'))
                                  <div class="alert alert-danger alert-dismissible fade show rounded-0 mb-0" role="alert">
                                      <i class="fa fa-exclamation-circle mr-2"></i>Incorrect email or password
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                @endif
                                  <div class="card-body px-4 py-4 py-lg-5">
                                      <form method="POST" id="register-form" action="{{ URL::to('/register-merchant') }}" aria-label="{{ __('Login') }}">
                                        @csrf
                                          <div class="form-group">
                                              <label for="fullname">Full name</label>
                                              <input type="text" name="name" id="fullname" class="form-control" placeholder="Full name" required>
                                          </div>
                                          <div class="form-group">
                                              <label for="email">Email</label>
                                              <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Email address" required>
                                          </div>
                                          <login-transaction></login-transaction>
                                          <button class="btn btn-outline-primary btn-pill btn-block mt-4">Register</button>
                                      </form>
                                  </div>
                              </div>
                              <div class="my-3 ml-4">
                                  <a href="{{URL::to('/login')}}" class="small">Do you already have an account?</a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
    </div>
  <!-- SCRIPTS -->
    <script src="js/jquery.min.js" charset="utf-8"></script>
    <script src="js/popper.min.js" charset="utf-8"></script>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script type="text/javascript">
    // $(document).ready(function() {
    //   $('#register-form').submit(function() {
    //     //removing from local storage
    //     localStorage.removeItem('sender');
    //     localStorage.removeItem('receiver');
    //     localStorage.removeItem('to_send');
    //     localStorage.removeItem('to_receive');
    //     localStorage.removeItem('recomended_price');
    //
    //     return true;
    //   });
    // })
    </script>

    @include('notifications-creator')
</body>
</html>
