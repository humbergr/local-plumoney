<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>American Kryptos Bank</title>

    <!-- Stripe -->
    <script src="https://js.stripe.com/v3/"></script>

    <!-- Scripts -->
    <script src="{{ url(mix('js/admin.js')) }}" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbNcXJyuUzJg--_ZWtLQps6YD9ndXR_Ks&libraries=places"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito|Mukta:300,400,700|Poppins:400,500,700"
          rel="stylesheet"
          type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body>

  <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
  <script>

    Notification.requestPermission().then(function(result) {
      console.log(result);
    });
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('889fce6a69a9c7050bd3', {
      cluster: 'us2',
      forceTLS: true
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('notification-to-{{Auth::user()->id}}', function(data) {

      // Let's check if the browser supports notifications
      if (!("Notification" in window)) {
        console.warn("This browser does not support system notifications");
      }

      // Let's check whether notification permissions have already been granted
      else if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        var notification = new Notification('Plumoney', {
          body: data.message,
          icon: "{{ asset('assets/images/favicon.ico') }}",
        });
        notification.onclick = function(event) {
        event.preventDefault(); // prevent the browser from focusing the Notification's tab
        notification.close();
        }
      }
    });
  </script>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/dashboard') }}">
                    Dashboard
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ URL::to('/dashboard') }}">
                                        {{ __('Dashboard') }}
                                    </a>

                                    @if(Auth::user()->role_id == 1)
                                      <a class="dropdown-item" href="{{URL::to('/create-user')}}">
                                          {{ __('Invite user') }}
                                      </a>

                                      <a class="dropdown-item" href="{{URL::to('/transactions')}}">
                                          {{ __('Transactions') }}
                                      </a>
                                    @endif

                                    <a class="dropdown-item" href="{{URL::to('/search')}}">
                                        {{ __('Search Ads') }}
                                    </a>

                                    <a class="dropdown-item" href="{{URL::to('/create-antifraud')}}">
                                        {{ __('Create Antifraud') }}
                                    </a>

                                    <a class="dropdown-item" href="{{URL::to('/antifraud-forms')}}">
                                        {{ __('Forms List') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                @if(Session::has('error'))
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Error!</strong> {{ Session::get('error') }}
                    </div>
                </div>
                @elseif(Session::has('success'))
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success!</strong> {{ Session::get('success') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @yield('content')
        @yield('page-script')
    </main>
</div>

<script src="{{ asset('js/jquery.min.js') }}" charset="utf-8"></script>
@yield('js')
<!--@include('notifications-creator')-->
</body>
</html>
