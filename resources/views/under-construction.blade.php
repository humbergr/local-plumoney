@extends('layouts.main')

@section('content')

<head>
    <title>Under Construction | American Kryptos Bank</title>
</head>
<body>




  <section id="under-construction" class="under-construnction-template">
      <div id="particles-js" class="uc__header">
          <div class="margin-bottom-2">
              <img src="{{ asset('img/cb-img/american-kryptos-bank.png') }}" alt="American Kryptos Bank" style=" position: relative;max-height: 70px; z-index: 10;">
          </div>
          <div class="info__container grid-container margin-top-2">
              <div class="grid-x">
                  <div class="cell medium-6 large-3 padding-horizontal-1">
                      <div class="info__item media-object">
                          <div class="media-object-section">
                              <img src= "{{ asset('img/cb-img/map-icon.svg') }}">
                          </div>
                          <div class="media-object-section main-section text-left">
                              <h6 class="info__item__text"  style="font-size: 15px">3517 NW 115TH AVE. DORAL FL 33178</h6>
                          </div>
                      </div>
                  </div>
                  <div class="cell medium-6 large-3 padding-horizontal-1">
                      <div class="info__item media-object">
                          <div class="media-object-section">
                              <img src= "{{ asset('img/cb-img/phone-icon.png') }}">
                          </div>
                          <div class="media-object-section main-section text-left">
                              <h6 class="info__item__text"   style="font-size: 15px">+1 (786) 245-8123.</h6>
                          </div>
                      </div>
                  </div>
                  <div class="cell medium-6 large-3 padding-horizontal-1">
                      <div class="info__item media-object">
                          <div class="media-object-section">
                              <img src= "{{ asset('img/cb-img/msg-icon.png') }}">
                          </div>
                          <div class="media-object-section main-section text-left">
                              <h6 class="info__item__text">
                                  <a href="mailto:info@coinbankusa.com"  style="font-size: 15px">info@americankryptosbank.com</a>
                              </h6>
                          </div>
                      </div>
                  </div>
                  <div class="cell medium-6 large-3 padding-horizontal-1">
                      <div class="info__item media-object">
                          <div class="media-object-section">
                              <img src= "{{ asset('img/cb-img/global-icon.png') }}">
                          </div>
                          <div class="media-object-section main-section text-left">
                              <h6 class="info__item__text">
                                  <a href="https://www.americankryptosbank.com.com" style="font-size: 15px" target="_blank">
                                      www.americankryptosbank.com
                                  </a>
                              </h6>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="uc__footer">
          <div class="row">
              <h1 class="uc__footer__title">Under construction</h1>
              <h5 class="uc__footer__subtitle">Soon we will be with you</h5>
              <ul class="social-icons">
                  <li><a href="#" class="social-link" target="_blank"><img src="{{ asset('img/cb-img/fb-icon.png') }}" title="Facebook"></a></li>
                  <li><a href="#" class="social-link" target="_blank"><img src="{{ asset('img/cb-img/twitter-icon.png') }}" title="Twitter"></a></li>
                  <li><a href="#" class="social-link" target="_blank"><img src="{{ asset('img/cb-img/ig-icon.png') }}" title="Instagram"></a></li>
                  <li><a href="#" class="social-link" target="_blank"><img src="{{ asset('img/cb-img/lkin-icon.png') }}" title="Linkedin"></a></li>
              </ul>
          </div>
      </div>
  </section>

  <!-- particles.js plugin -->
<script src="{{ asset('js/particles/particles.min.js') }}"></script>

<script src="assets/js/app.js"></script>
</body>

@endsection
