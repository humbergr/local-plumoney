@extends('layouts.main')

@section('content')

<head>
  <title>Login | American Kryptos Bank</title>
</head>
  <div class="login-view">
      <header class="grid-container">
          <div class="grid-x">
              <div class="cell"><a href="/"><img src="{{ asset('img/American Kryptos Bank-Logo.png') }}" alt="American Kryptos Bank"></a></div>
          </div>
      </header>
      <div class="grid-container login-wrapper">
          <div class="grid-x">
              <div class="medium-8 cell medium-offset-2">
                  <div class="login-zone">
                      <div class="image">
                          <img src="{{ asset('img/American Kryptos Bank-Inv.png') }}" alt="American Kryptos Bank">
                      </div>
                      <div class="login-form">
                          <h4>Login American Kryptos Bank</h4>
                          <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                            @csrf

                              @if(Session::has('success'))
                                <p style="color:green">Success, {{ Session::get('success') }}.</p>
                              @endif
                              <label>
                                  Email
                                  <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                              </label>
                              @if ($errors->has('email'))
                                  <span class="invalid-feedback" role="alert">
                                      <strong style="font-size:12px;color:#FA5858">{{ $errors->first('email') }}</strong>
                                  </span>
                              @endif

                              <label>
                                  Password
                                  <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                              </label>
                              @if ($errors->has('password'))
                                  <span class="invalid-feedback" role="alert">
                                      <strong style="font-size:12px;color:#FA5858">{{ $errors->first('password') }}</strong>
                                  </span>
                              @endif
                              <input type="submit" class="button" value="Login">
                          </form>
                          <div class="links">
                              <a href="#">Forgot your password?</a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection
