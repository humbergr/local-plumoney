@extends('layouts.main')

@section('content')
<head>
  <title>Under Construction | American Kryptos Bank</title>
</head>
  <div class="grid-container full">
      <div class="grid-x medium-up-2">
          <div class="cell message-image">
              <h2>
                  UNDER
                  <small>CONSTRUCTION</small>
              </h2>
              <img src="{{ asset('img/laptop-construction.png') }}" alt="Under Construction">
          </div>
          <div class="cell general-info">
              <img src="{{ asset('img/American Kryptos Bank-Logo.png') }}" alt="American Kryptos Bank">
              <p class="alter-text">Our service will be available very soon</p>
              <div class="social-links">
                  <a href="https://www.facebook.com/plumoneyoficial" target="_blank">
                      <img src="{{ asset('img/Facebook.png') }}" alt="Follow us on Facebook">
                  </a>
                  <a href="https://www.instagram.com/plu.money" target="_blank">
                      <img src="{{ asset('img/Instagram.png') }}" alt="Follow us on Instagram">
                  </a>
                  <a href="https://twitter.com/PluMoney" target="_blank">
                      <img src="{{ asset('img/Twitter.png') }}" alt="Follow us in Twitter">
                  </a>
              </div>
          </div>
      </div>
  </div>
@endsection
