<template>
    <div>
        <header class="header">
            <svg class="header__blob header__blob--left" width="834" viewBox="0 0 580 580" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M239.09 2.2745C300.548 15.2906 307.508 106.313 360.065 140.729C417.583 178.394 516.352 146.346 550.358 206.106C582.951 263.382 524.129 330.584 503.767 393.261C483.404 455.937 490.404 542.59 431.465 572.061C370.98 602.304 305.709 537.213 239.09 525.603C188.695 516.821 131.758 542.029 89.9522 512.545C48.0176 482.97 38.4003 426.194 27.1431 376.126C16.8487 330.34 21.0447 285.138 28.4017 238.789C37.2444 183.08 36.6024 122.285 74.1204 80.1683C115.586 33.6195 178.107 -10.6413 239.09 2.2745Z" fill="rgba(255,255,255,.05)"/>
            </svg>
            <svg class="header__blob header__blob--right d-none d-md-block" width="834" viewBox="0 0 580 580" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M254.21 565.785C186.088 581.198 93.3672 596.333 51.3762 540.515C6.14514 480.391 74.927 396.205 70.1474 321.115C67.1248 273.629 12.9607 232.915 28.9441 188.098C44.9406 143.245 107.994 140.941 145.064 111.054C185.272 78.6378 204.3 19.7621 254.21 6.48537C308.593 -7.98132 373.95 1.27748 415.644 39.0759C456.531 76.1424 439.393 143.329 461.12 194.063C486.375 253.032 566.632 295.641 552.3 358.169C538.226 419.568 451.462 425.687 399.777 461.685C349.599 496.633 313.85 552.29 254.21 565.785Z" fill="rgba(255,255,255,.05)"/>
            </svg>
            <div class="header__content py-5 py-md-8">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-lg-5">
                            <div class="text-white">
                                <h1 class="header__title font-weight-bold mb-4">Respalda tu dinero en dólares americános</h1>
                                <p class="header__description mb-4">Con tu <b>Billetera Digital <img src="/assets/img/brand/mia-logo--dark.svg" alt="MIA" height="20"></b>, podrás convertir tu moneda local en dólares y proteger tu dinero de los efectos inflacionarios.</p>
                                <a href="/signin" class="btn btn-outline-light btn-block py-3">Comenzar</a>
                            </div>
                            <hr class="my-4 my-md-5 border-light-alpha">
                            <div class="text-white d-none d-md-block">
                                <div class="row">
                                    <div class="col-6 col-md-6">
                                        <div class="media align-items-center mb-3 mb-md-0">
                                            <div class="d-none d-md-block mr-3">
                                                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="48" height="48" rx="6" fill="white"/>
                                                    <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M15.5172 10.9655L31.6552 15.3104V34.8621H15.5172V10.9655ZM17.5345 17.4828H21.569V21.8276H17.5345V17.4828ZM21.569 24H17.5345V28.3448H21.569V24ZM25.6034 17.4828H29.6379V21.8276H25.6034V17.4828ZM29.6379 24H25.6034V28.3448H29.6379V24Z" fill="#6857E5"/>
                                                    <path d="M23.5862 30.5173C21.358 30.5173 19.5517 32.4625 19.5517 34.8621H11.4828V37.0345H35.6897V34.8621H27.6207C27.6207 32.4625 25.8144 30.5173 23.5862 30.5173Z" fill="#6857E5"/>
                                                </svg>                                                
                                            </div>
                                            <div class="media-body font-weight-bold lh-125 fs-14">
                                                Institución financiera autorizada y regulada
                                                <!-- <a href="" class="text-gradient-secondary text-secondary fs-13">
                                                    Saber más
                                                    <svg class="bi bi-chevron-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                                                    </svg>
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <div class="fs-14 mb-3 mb-md-5">
                                            <img src="/assets/img/trustpilot-score.svg" class="mr-2" alt="3.5/5"> <strong>3.5/5</strong>
                                            <a href="https://www.trustpilot.com/review/americankryptosbank.com" class="d-inline-block text-white fs-13 mt-2" target="_blank">Valóranos en <img src="/assets/img/trustpilot-logo--light.svg" alt=""></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 offset-lg-1">
                            <div class="header__calculator card shadow-lg">
                                <div class="card-body p-md-4 p-lg-5">
                                    <h5 class="card-title text-primary font-weight-bold mb-4">Calculadora</h5>
                                    <form :action="getUrl('/new-transaction')" v-on:submit="storeData" method="post">
                                    
                                        <input type="hidden" name="_token" :value="csrf">
                                        <input type="hidden" name="recomended_price" v-model="recomended_price" value="">
                                        <div class="form-group mb-0">
                                            <div class="input-group flex-nowrap">
                                                <div class="floatingLabel__group flex-grow-1">
                                                    <input type="text"
                                                        class="form-control form-control--currency"
                                                        style="background: url('/img/icons/loadExhanges-32px.gif') no-repeat center left;"
                                                        v-if="loadingSend">
                                                    <input id="send-amount" 
                                                        type="text" 
                                                        class="form-control form-control--currency" 
                                                        placeholder="Si envías" 
                                                        name="to_send"
                                                        v-else
                                                        @focus="changeCalc('send')"
                                                        v-model="formatted_to_send"
                                                        required>
                                                    <label for="send-amount" class="floatingLabel__label">Si envías</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <select class="custom-select h-100" 
                                                            id="__ho_select_sender"
                                                            v-model="sender_country"
                                                            name="sender" v-on:change="getPrice('fromSend', true)">
                                                        <optgroup label="Sur America">
                                                            <option value="Venezuela" data-flag="ve">Venezuela</option>
                                                            <option value="Colombia" data-flag="co">Colombia</option>
                                                            <option value="Perú" data-flag="pe">Perú</option>
                                                            <option value="Chile" data-flag="cl">Chile</option>
                                                            <option value="Argentina" data-flag="ar">Argentina</option>
                                                            <option value="Brazil" data-flag="br">Brazil</option>
                                                            <option value="Ecuador" data-flag="ec">Ecuador</option>
                                                            <option value="Bolivia" data-flag="bo">Bolivia</option>
                                                            <option value="Paragua" data-flag="py">Paraguay</option>
                                                            <option value="Uruguay" data-flag="uy">Uruguay</option>
                                                        </optgroup>
                                                        <optgroup label="Centro América">
                                                            <option value="Panamá" data-flag="pa">Pánama</option>
                                                            <option value="Dominican Republic" data-flag="do">República Dominicana</option>
                                                            <option value="Guatemala" data-flag="gt">Guatemala</option>
                                                            <option value="El Salvador" data-flag="sv">El Salvador</option>
                                                            <option value="Honduras" data-flag="hn">Honduras</option>
                                                            <option value="Nicaragua" data-flag="ni">Nicaragua</option>
                                                            <option value="Costa Rica" data-flag="cr">Costa Rica</option>
                                                            <option value="Belize" data-flag="bz">Belize</option>
                                                        </optgroup>
                                                        <optgroup label="Norte América">
                                                            <option value="México" data-flag="mx">México</option>
                                                            <option value="United States" data-flag="us">Estados Unidos</option>
                                                            <option value="Canada" data-flag="ca">Canada</option>
                                                        </optgroup>
                                                        <optgroup label="Islas del Caribe">
                                                            <option value="Puerto Rico" data-flag="pr">Puerto Rico</option>
                                                            <option value="Aruba" data-flag="aw">Aruba</option>
                                                            <option value="Curacao" data-flag="cw">Curacao</option>
                                                            <option value="Trinidad y Tobago" data-flag="tt">Trinidad y Tobago</option>
                                                            <option value="Bahama" data-flag="bs">Bahamas</option>
                                                            <option value="Barbados" data-flag="bb">Barbados</option>
                                                        </optgroup>
                                                        <optgroup label="Europa">
                                                            <option value="Reino Unido" data-flag="gb">Reino Unido</option>
                                                            <option value="España" data-flag="es">España</option>
                                                            <option value="Portugal" data-flag="pt">Portugal</option>
                                                            <option value="Italia" data-flag="it">Italia</option>
                                                            <option value="Francia" data-flag="fr">Francia</option>
                                                            <option value="Alemania" data-flag="de">Alemania</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="media align-items-center">
                                            <div class="media-body">
                                                <ul class="calculatorInfo mb-0 py-4">
                                                    <li class="calculatorInfo__item">
                                                        <span class="calculatorInfo__icon" title="Tasa de cambio">
                                                            <svg class="bi bi-x" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z"/>
                                                                <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z"/>
                                                            </svg>
                                                        </span>
                                                        <div v-if="to_send !== '' && to_send !== 0 && recomended_price !== '' && loadingRate  === false">
                                                            <b v-if="recomended_price > 1">
                                                                1 {{sender}} = {{recommendedUtility(recomended_price)}} {{receiver}}
                                                            </b>
                                                            <b v-else>
                                                                1 {{receiver}} = {{recommendedUtility(recomended_price)}} {{sender}}
                                                            </b>
                                                        </div>
                                                        <div v-else>
                                                            <b>Loading...</b>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div>
                                                <button type="button" @click="changeValue" class="btn-invert-currency my-2 ml-3" title="Invertir valores">
                                                    <svg class="bi bi-arrow-down-up" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M11 3.5a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5z"/>
                                                        <path fill-rule="evenodd" d="M10.646 2.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L11 3.707 8.354 6.354a.5.5 0 1 1-.708-.708l3-3zm-9 7a.5.5 0 0 1 .708 0L5 12.293l2.646-2.647a.5.5 0 1 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 0-.708z"/>
                                                        <path fill-rule="evenodd" d="M5 2.5a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-1 0V3a.5.5 0 0 1 .5-.5z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <div class="input-group flex-nowrap">
                                                <div class="floatingLabel__group flex-grow-1">
                                                    <input type="text"
                                                            class="form-control form-control-country-big"
                                                            style="background: url('/img/icons/loadExhanges-32px.gif') no-repeat center left;"
                                                            v-if="loadingReceive">
                                                    <input id="receive-amount" 
                                                        type="text" 
                                                        class="form-control form-control--currency" 
                                                        placeholder="Recibirá" 
                                                        name="to_receive"
                                                        v-else
                                                        @focus="changeCalc('receive')"
                                                        v-model="formatted_to_receive"
                                                        required>
                                                    <label for="receive-amount" class="floatingLabel__label">Recibirá</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <select class="custom-select h-100" 
                                                            id="__ho_select_receiver"
                                                            v-model="receiver_country"
                                                            name="receiver" v-on:change="getPrice('fromReceive', true)">
                                                        <optgroup label="Sur America">
                                                            <option value="Venezuela" data-flag="ve">Venezuela</option>
                                                            <option value="Colombia" data-flag="co">Colombia</option>
                                                            <option value="Perú" data-flag="pe">Perú</option>
                                                            <option value="Chile" data-flag="cl">Chile</option>
                                                            <option value="Argentina" data-flag="ar">Argentina</option>
                                                            <option value="Brazil" data-flag="br">Brazil</option>
                                                            <option value="Ecuador" data-flag="ec">Ecuador</option>
                                                            <option value="Bolivia" data-flag="bo">Bolivia</option>
                                                            <option value="Paragua" data-flag="py">Paraguay</option>
                                                            <option value="Uruguay" data-flag="uy">Uruguay</option>
                                                        </optgroup>
                                                        <optgroup label="Centro América">
                                                            <option value="Panamá" data-flag="pa">Pánama</option>
                                                            <option value="Dominican Republic" data-flag="do">República Dominicana</option>
                                                            <option value="Guatemala" data-flag="gt">Guatemala</option>
                                                            <option value="El Salvador" data-flag="sv">El Salvador</option>
                                                            <option value="Honduras" data-flag="hn">Honduras</option>
                                                            <option value="Nicaragua" data-flag="ni">Nicaragua</option>
                                                            <option value="Costa Rica" data-flag="cr">Costa Rica</option>
                                                            <option value="Belize" data-flag="bz">Belize</option>
                                                        </optgroup>
                                                        <optgroup label="Norte América">
                                                            <option value="México" data-flag="mx">México</option>
                                                            <option value="United States" data-flag="us">Estados Unidos</option>
                                                            <option value="Canada" data-flag="ca">Canada</option>
                                                        </optgroup>
                                                        <optgroup label="Islas del Caribe">
                                                            <option value="Puerto Rico" data-flag="pr">Puerto Rico</option>
                                                            <option value="Aruba" data-flag="aw">Aruba</option>
                                                            <option value="Curacao" data-flag="cw">Curacao</option>
                                                            <option value="Trinidad y Tobago" data-flag="tt">Trinidad y Tobago</option>
                                                            <option value="Bahama" data-flag="bs">Bahamas</option>
                                                            <option value="Barbados" data-flag="bb">Barbados</option>
                                                        </optgroup>
                                                        <optgroup label="Europa">
                                                            <option value="Reino Unido" data-flag="gb">Reino Unido</option>
                                                            <option value="España" data-flag="es">España</option>
                                                            <option value="Portugal" data-flag="pt">Portugal</option>
                                                            <option value="Italia" data-flag="it">Italia</option>
                                                            <option value="Francia" data-flag="fr">Francia</option>
                                                            <option value="Alemania" data-flag="de">Alemania</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-secondary btn-gradient btn-block py-3 mt-4">Continuar transacción</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main>       
            <section class="deco-gradient-diagonal py-8">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">

                            <!-- envia section -->
                            <div class="py-6">
                                <div class="row align-items-md-center">
                                    <div class="col-md-5">
                                        <div class="text-center text-md-left mb-4 mb-md-0">
                                            <h1 class="section__title">Envía</h1>
                                            <p class="section__description">Envía dinero a tus familiares y amigos desde donde estés.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-5 offset-md-2">
                                        <div class="px-5 px-md-0">
                                            <img src="/assets/img/sec-img-sd9fds45fs.svg" alt="" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- guarda section -->
                            <div class="py-6">
                                <div class="row flex-column-reverse flex-md-row align-items-md-center">
                                    <div class="col-md-5">
                                        <div class="px-5 px-md-0">
                                            <img src="/assets/img/sec-img-8sd8w7f8g4.svg" alt="" class="img-fluid">
                                        </div>
                                    </div>
                                    <div class="col-md-5 offset-md-2">
                                        <div class="text-center text-md-left mb-4 mb-md-0">
                                            <h1 class="section__title">Guarda</h1>
                                            <p class="section__description">Conserva el valor de tus ingresos, guárdalos en dólares.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- retira section -->
                            <div class="py-6">
                                <div class="row align-items-md-center">
                                    <div class="col-md-5">
                                        <div class="text-center text-md-left mb-4 mb-md-0">
                                            <h1 class="section__title">Retira</h1>
                                            <p class="section__description">Retira tus dólares a cualquiera de las monedas AKB disponibles.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-5 offset-md-2">
                                        <div class="px-5 px-md-0">
                                            <img src="/assets/img/sec-img-8wgeg78f4.svg" alt="" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>

                                                    
                            <!-- compra y vende section -->
                            <div class="py-6">
                                <div class="row flex-column-reverse flex-md-row align-items-md-center">
                                    <div class="col-md-5">
                                        <div class="px-5 px-md-0">
                                            <img src="/assets/img/sec-img-8oiu8g8jh.svg" alt="" class="img-fluid">
                                        </div>
                                    </div>
                                    <div class="col-md-4 offset-md-3">
                                        <div class="text-center text-md-left mb-4 mb-md-0">
                                            <h1 class="section__title">Compra y Vende</h1>
                                            <p class="section__description">Ten acceso al mercado de las criptomonedas utilizando los dólares de tu billetera digital.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <!-- convierte section -->
                            <div class="py-6">
                                <div class="row align-items-md-center">
                                    <div class="col-md-4">
                                        <div class="text-center text-md-left mb-4 mb-md-0">
                                            <h1 class="section__title">Convierte</h1>
                                            <p class="section__description">Desde donde te encuentres, convierte tu moneda local a dólares y luego podras cambiarla a <a class="text-dark link-deco" href="">cualquier moneda</a> disponible en AKB.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-5 offset-md-3">
                                        <div class="px-5 px-md-0">
                                            <img src="/assets/img/sec-img-8re8h4gd5.svg" alt="" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <section class="akb-bg-light position-relative py-9 py-md-10">

                <svg id="sec-s8gf5" class="wave-top" width="100" viewBox="0 0 1280 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1280 0H0V59.5528C33.5356 42.1409 171 20.8489 314 43.6199C457 66.3909 670 64.022 798.468 40.8434C926.936 17.6648 1105.62 -14.5733 1280 70V0Z" fill="#ffffff"/>
                </svg>

                <svg id="sec-8g71f" class="wave-bottom" width="100" viewBox="0 0 1280 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1280 70H0V10.4472C88 0 174 50 401 41C628 32 724 11 914 33C1104 55 1246 11 1280 0V70Z" fill="#ffffff"/>
                </svg>

                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6 col-lg-5 offset-lg-1 col-xl-4 offset-xl-1">
                            <div class="text-center text-md-left">
                                <h6 class="section__subtitle text-gradient-primary mb-4">¿Cómo lo hacemos?</h6>
                                <h1 class="section__title mb-4">¡Envía tu dinero sin comisiones!</h1>
                                <p class="section__description mb-4">Desarrollamos Inteligencia Artificial para que puedas operar desde cualquier parte del mundo y en el momento que lo desees bajo las mejores condiciones del mercado.</p>
                                <p class="section__description text-reset mb-4">¡Solo tú tendrás el control de tus finanzas!</p>
                                <a href="" class="btn btn-primary btn-gradient btn-lg px-4">Enviar ahora</a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-5 offset-lg-1 col-xl-6">
                            <div class="mt-9 mt-md-0">
                                <img src="/assets/img/screen-sdf8sgd4df5s.png" class="img-fluid" style="box-shadow: 0px 15px 50px rgba(139, 141, 188, 0.4)" loading="lazy">
                                <img src="/assets/img/screen-hfgfh2d1h2g.png" class="img-fluid" style="position: absolute; left: 3rem; bottom: -10%; max-width: 210px;" loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- akb flag system section -->
            <section class="py-9">
                <div class="container-fluid">
                    <div class="row align-items-md-center">
                        <div class="col-md-6 col-xl-5">
                            <div class="ml-lg-8">
                                <div class="position-relative text-center text-md-left" style="z-index: 10;">
                                    <h1 class="section__title mb-5">¿Te encuentras en alguno de estos países?</h1>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="mb-2">
                                                <a href="" class="btn btn-secondary btn-gradient btn-lg btn-block py-3 px-xl-4">Guarda tu dinero en dólares</a>
                                            </div>
                                            <div>
                                                <a href="" class="btn btn-outline-secondary btn-block">
                                                    Ver todos los países
                                                    <svg class="bi bi-chevron-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7 mt-5 mt-md-0 ml-md-n9">
                            <div class="solarSystem">
                                <div class="solarSystem__orbit">
                                    <div class="solarSystem__planet">
                                        <img src="/assets/img/planet-cl.png" loading="lazy">
                                    </div>
                                    <div class="solarSystem__planet">
                                        <img src="/assets/img/planet-ve.png" loading="lazy">
                                    </div>
                                    <div class="solarSystem__planet">
                                        <img src="/assets/img/planet-pe.png" loading="lazy">
                                    </div>
                                </div>
                                <div class="solarSystem__orbit">
                                    <div class="solarSystem__planet">
                                        <img src="/assets/img/planet-co.png" loading="lazy">
                                    </div>
                                    <div class="solarSystem__planet">
                                        <img src="/assets/img/planet-sp.png" loading="lazy">
                                    </div>
                                </div>
                                <div class="solarSystem__orbit">
                                    <div class="solarSystem__planet">
                                        <img src="/assets/img/planet-us.png" loading="lazy">
                                    </div>
                                </div>
                                <div class="solarSystem__orbit">
                                    <div class="solarSystem__planet">
                                        <img src="/assets/img/planet-mx.png" loading="lazy">
                                    </div>
                                    <div class="solarSystem__planet">
                                        <img src="/assets/img/planet-ar.png" loading="lazy">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="akb-bg-purple deco-diagonal-img py-7 py-md-9">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-10">
                            <div class="text-center text-white mb-5">
                                <h6 class="section__subtitle mb-4">Te lo hacemos mucho más fácil</h6>
                                <h1 class="section__title">Recarga tu billetera con cualquiera de nuestras formas de pago</h1>
                            </div>
                            <div class="bg-gradient-secondary rounded-lg text-center text-white p-4 mb-4 mb-md-5" style="box-shadow: 0 1rem 3rem rgba(199, 112, 36, 0.25)">
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <img src="/assets/img/cc-master.svg" alt="Tarjeta de crédito Mastercard" height="48px">
                                    </li>
                                    <li class="list-inline-item">
                                        <img src="/assets/img/cc-visa.svg" alt="Tarjeta de crédito Visa" height="48px">
                                    </li>
                                </ul>
                                <h6 class="font-weight-bold mb-0">
                                    Tarjeta de crédito y débito
                                </h6>
                            </div>

                            <h6 class="text-center text-md-left text-white mb-4">Otros métodos de pago:</h6>
                            <div class="position-relative">
                                <div id="payments-swiper" class="swiper-container">
                                    <div class="swiper-wrapper">
                                        <!-- Slides -->
                                        <div class="swiper-slide px-5 px-md-0">
                                            <div class="card card-body px-4 py-3 mb-3">
                                                <div class="media align-items-center">
                                                    <div class="flex-shrink-0 mr-3">
                                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <circle opacity="0.25" cx="16" cy="16" r="16" fill="#6857E5"/>
                                                            <path d="M13.3987 21.5133C15.9914 21.5133 18.313 21.5489 20.6316 21.4947C21.5067 21.4733 21.9995 21.7122 22.3404 22.4418C22.5757 22.9481 23.0335 23.3696 23.388 23.8317C23.5438 24.0356 23.69 24.246 24 24.6717H17.6621V28H14.1132C14.1132 27.135 14.0838 26.2878 14.1235 25.4427C14.1498 24.8864 13.9344 24.6839 13.3064 24.6967C11.8456 24.7281 10.3831 24.691 8.92219 24.7181C8.27282 24.7302 8.08206 24.5184 8.02404 23.9286C7.89051 22.6029 8.31415 21.5574 9.2743 20.5098C12.0371 17.4933 14.6727 14.3819 17.3561 11.3061C17.519 11.12 17.6661 10.9224 17.9013 10.6279C17.5794 10.5936 17.364 10.5523 17.1486 10.5516C14.5837 10.5458 12.018 10.528 9.45311 10.5559C8.73221 10.563 8.39761 10.379 8.4604 9.69364C8.50889 9.16164 8.5033 8.6225 8.46991 8.08908C8.43415 7.51571 8.72745 7.34172 9.32436 7.35099C10.666 7.37239 12.0093 7.33674 13.3509 7.36527C13.9415 7.37811 14.1402 7.1791 14.1219 6.66493C14.0901 5.81629 14.1132 4.96622 14.1132 4.00562C15.218 4.00562 16.2258 3.9821 17.2289 4.0313C17.3807 4.03843 17.62 4.38002 17.6343 4.57756C17.6843 5.27002 17.6803 5.96893 17.6438 6.66353C17.6175 7.17985 17.8147 7.37665 18.4068 7.36453C19.7787 7.33529 21.1514 7.37309 22.5232 7.34599C23.1798 7.33315 23.3658 7.55638 23.4174 8.14187C23.5287 9.40841 23.1257 10.4153 22.2125 11.4187C19.5522 14.3383 17.0127 17.3456 14.4271 20.3187C14.1394 20.6503 13.8549 20.9834 13.3987 21.5133Z" fill="#6857E5"/>
                                                        </svg>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-primary font-weight-bold fs-14">
                                                            Zelle
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide px-5 px-md-0">
                                            <div class="card card-body px-4 py-3 mb-3">
                                                <div class="media align-items-center">
                                                    <div class="flex-shrink-0 mr-3">
                                                        <svg width="33" height="36" viewBox="0 0 33 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M8.51724 4.96533L24.6552 9.31016V28.8619H8.51724V4.96533ZM10.5345 11.4826H14.569V15.8274H10.5345V11.4826ZM14.569 17.9998H10.5345V22.3446H14.569V17.9998ZM18.6034 11.4826H22.6379V15.8274H18.6034V11.4826ZM22.6379 17.9998H18.6034V22.3446H22.6379V17.9998Z" fill="#6857E5"/>
                                                            <path d="M16.5862 24.5171C14.358 24.5171 12.5517 26.4623 12.5517 28.8619H4.48276V31.0343H28.6897V28.8619H20.6207C20.6207 26.4623 18.8144 24.5171 16.5862 24.5171Z" fill="#6857E5"/>
                                                        </svg>                                                
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-primary font-weight-bold fs-14">
                                                            Transferencias nacionales (ABA)
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide px-5 px-md-0">
                                            <div class="card card-body px-4 py-3 mb-3">
                                                <div class="media align-items-center">
                                                    <div class="flex-shrink-0 mr-3">
                                                        <svg width="33" height="36" viewBox="0 0 33 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M8.51724 4.96533L24.6552 9.31016V28.8619H8.51724V4.96533ZM10.5345 11.4826H14.569V15.8274H10.5345V11.4826ZM14.569 17.9998H10.5345V22.3446H14.569V17.9998ZM18.6034 11.4826H22.6379V15.8274H18.6034V11.4826ZM22.6379 17.9998H18.6034V22.3446H22.6379V17.9998Z" fill="#6857E5"/>
                                                            <path d="M16.5862 24.5171C14.358 24.5171 12.5517 26.4623 12.5517 28.8619H4.48276V31.0343H28.6897V28.8619H20.6207C20.6207 26.4623 18.8144 24.5171 16.5862 24.5171Z" fill="#6857E5"/>
                                                        </svg>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-primary font-weight-bold fs-14">
                                                            Transferencias internacionales (SWIFT)
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide px-5 px-md-0">
                                            <div class="card card-body px-4 py-3 mb-3">
                                                <div class="media align-items-center">
                                                    <div class="flex-shrink-0 mr-3">
                                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g opacity="0.25">
                                                            <path d="M8.15952 2.29201L5.77768 2.35555L4.59004 24.7721L8.15952 2.29201Z" fill="#6857E5"/>
                                                            <path d="M4.39314 28.4887L4.35547 29.1998H12.0887L13.5998 19.5999H18.9331L18.9332 19.5959C19.0217 19.5986 19.1106 19.5999 19.1998 19.5999C24.0599 19.5999 27.9997 15.66 27.9997 10.7999C27.9997 5.9399 24.0599 2 19.1998 2H19.1109L19.1109 2.00182C23.7416 2.09644 27.4664 5.87983 27.4664 10.5333C27.4664 15.2461 23.6459 19.0665 18.9331 19.0665H13.2443L11.6443 28.4887H4.39314Z" fill="#6857E5"/>
                                                            </g>
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.1109 2L19.0569 2.00087C19.0157 2.00026 18.9745 2 18.9332 2C18.797 2 18.6616 2.00321 18.527 2.00946L8.17769 2.17778L3.99994 28.4887H11.6443L13.2443 19.0665H18.9332C23.646 19.0665 27.4664 15.2461 27.4664 10.5333C27.4664 5.87983 23.7417 2.09644 19.1109 2.00182L19.1109 2ZM12.711 7.68885L11.8221 13.1999H13.7776L14.311 9.64439C14.4888 9.28884 15.3776 8.75551 15.5554 9.64439L15.0221 13.1999H16.9776L17.511 9.64439C17.6887 9.28884 18.5776 8.75551 18.7554 9.64439L18.2221 13.1999H20.1776L20.8887 8.75551C20.5332 8.0444 19.0221 6.72878 17.5998 8.57773C17.2443 7.86662 15.9643 6.72878 14.3999 8.57773L14.4888 7.68885H12.711Z" fill="#6857E5"/>
                                                        </svg>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-primary font-weight-bold fs-14">
                                                            mPandco
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide px-5 px-md-0">
                                            <div class="card card-body px-4 py-3 mb-3">
                                                <div class="media align-items-center">
                                                    <div class="flex-shrink-0 mr-3">
                                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.25" d="M32 14.873C32 21.9826 24.8707 27.746 16.0763 27.746C12.6931 27.746 9.55634 26.8931 6.97708 25.4387C5.92976 24.8481 0.995191 29.6884 0.152549 28.9163C-0.725256 28.112 2.49847 21.6884 1.88893 20.7244C0.778814 18.9688 0.152641 16.98 0.152641 14.873C0.152641 7.76345 7.28192 2 16.0763 2C24.8707 2 32 7.76345 32 14.873Z" fill="#6857E5"/>
                                                            <path d="M12 14C12 15.1046 11.1046 16 10 16C8.89543 16 8 15.1046 8 14C8 12.8954 8.89543 12 10 12C11.1046 12 12 12.8954 12 14Z" fill="#6857E5"/>
                                                            <path d="M18 14C18 15.1046 17.1046 16 16 16C14.8954 16 14 15.1046 14 14C14 12.8954 14.8954 12 16 12C17.1046 12 18 12.8954 18 14Z" fill="#6857E5"/>
                                                            <path d="M22 16C23.1046 16 24 15.1046 24 14C24 12.8954 23.1046 12 22 12C20.8954 12 20 12.8954 20 14C20 15.1046 20.8954 16 22 16Z" fill="#6857E5"/>
                                                        </svg>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-primary font-weight-bold fs-14">
                                                            Pago asistido
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide px-5 px-md-0">
                                            <div class="card card-body px-4 py-3 mb-3">
                                                <div class="media align-items-center">
                                                    <div class="flex-shrink-0 mr-3">
                                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.25" d="M2 11.9883C2 9.77914 3.79086 7.98828 6 7.98828H26C28.2091 7.98828 30 9.77914 30 11.9883V23.9883C30 26.1974 28.2091 27.9883 26 27.9883H6C3.79086 27.9883 2 26.1974 2 23.9883V11.9883Z" fill="#6857E5"/>
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2 20V24H30V20H2Z" fill="#6857E5"/>
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.52697 4.14935C6.85454 3.96714 7.26358 3.95058 7.60802 4.10557L16 7.88197L24.392 4.10557C24.7364 3.95058 25.1455 3.96714 25.473 4.14935C25.8006 4.33156 26 4.65342 26 5V13C26 13.3466 25.8006 13.6684 25.473 13.8506C25.1455 14.0329 24.7364 14.0494 24.392 13.8944L16 10.118L7.60802 13.8944C7.26358 14.0494 6.85454 14.0329 6.52697 13.8506C6.19939 13.6684 6 13.3466 6 13V5C6 4.65342 6.19939 4.33156 6.52697 4.14935Z" fill="#6857E5"/>
                                                        </svg>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-primary font-weight-bold fs-14">
                                                            American Time Giftcard
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="pay-swiper-prev" class="swiper-button-prev"></div>
                                <div id="pay-swiper-next" class="swiper-button-next"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <section class="akb-bg-light py-9">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-8">
                            <div class="text-center mb-5">
                                <h6 class="section__subtitle text-gradient-secondary mb-4">Testimonios</h6>
                                <h1 class="section__title mb-4">Qué dicen nuestros clientes</h1>
                                <p class="section__description">Si quieres saber más sobre la calidad de nuestro servicio, deja que nuestros mismos usuarios te lo cuenten</p>
                            </div>
                        </div>
                    </div>

                    <div class="reviews-slider position-relative">
                        <!-- reviews slider -->
                        <div id="reviews-swiper" class="swiper-container mb-7">
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                
                                <div class="swiper-slide">
                                    <div class="card card-body fs-14 border-0">
                                        <div class="mb-2">
                                            <img src="/assets/img/trustpilot-score.svg" alt="">
                                        </div>
                                        <h6 class="fs-14"><a class="text-reset" href="">Elisabete Da Paz</a></h6>
                                        <p class="text-muted mb-0">Es un excelente servicio y su personal es muy atento, lo recomiendo 100% además de ser muy confiable.</p>
                                    </div>
                                </div>

                                                        
                                <div class="swiper-slide">
                                    <div class="card card-body fs-14 border-0">
                                        <div class="mb-2">
                                            <img src="/assets/img/trustpilot-score.svg" alt="">
                                        </div>
                                        <h6 class="fs-14"><a class="text-reset" href="">Sineide Cardoso</a></h6>
                                        <p class="text-muted mb-0">Es un excelente servicio y su personal es muy atento, lo recomiendo 100% además de ser muy confiable.</p>
                                    </div>
                                </div>

                                                        
                                <div class="swiper-slide">
                                    <div class="card card-body fs-14 border-0">
                                        <div class="mb-2">
                                            <img src="/assets/img/trustpilot-score.svg" alt="">
                                        </div>
                                        <h6 class="fs-14"><a class="text-reset" href="">Irá Ramos</a></h6>
                                        <p class="text-muted mb-0">Es un excelente servicio y su personal es muy atento, lo recomiendo 100% además de ser muy confiable.</p>
                                    </div>
                                </div>

                                                        
                                <div class="swiper-slide">
                                    <div class="card card-body fs-14 border-0">
                                        <div class="mb-2">
                                            <img src="/assets/img/trustpilot-score.svg" alt="">
                                        </div>
                                        <h6 class="fs-14"><a class="text-reset" href="">Arnoldo Porto</a></h6>
                                        <p class="text-muted mb-0">Es un excelente servicio y su personal es muy atento, lo recomiendo 100% además de ser muy confiable.</p>
                                    </div>
                                </div>

                                                        
                                <div class="swiper-slide">
                                    <div class="card card-body fs-14 border-0">
                                        <div class="mb-2">
                                            <img src="/assets/img/trustpilot-score.svg" alt="">
                                        </div>
                                        <h6 class="fs-14"><a class="text-reset" href="">Elisabete Da Paz</a></h6>
                                        <p class="text-muted mb-0">Es un excelente servicio y su personal es muy atento, lo recomiendo 100% además de ser muy confiable.</p>
                                    </div>
                                </div>

                                                        
                                <div class="swiper-slide">
                                    <div class="card card-body fs-14 border-0">
                                        <div class="mb-2">
                                            <img src="/assets/img/trustpilot-score.svg" alt="">
                                        </div>
                                        <h6 class="fs-14"><a class="text-reset" href="">Sineide Cardoso</a></h6>
                                        <p class="text-muted mb-0">Es un excelente servicio y su personal es muy atento, lo recomiendo 100% además de ser muy confiable.</p>
                                    </div>
                                </div>

                                                        
                                <div class="swiper-slide">
                                    <div class="card card-body fs-14 border-0">
                                        <div class="mb-2">
                                            <img src="/assets/img/trustpilot-score.svg" alt="">
                                        </div>
                                        <h6 class="fs-14"><a class="text-reset" href="">Irá Ramos</a></h6>
                                        <p class="text-muted mb-0">Es un excelente servicio y su personal es muy atento, lo recomiendo 100% además de ser muy confiable.</p>
                                    </div>
                                </div>

                                                        
                                <div class="swiper-slide">
                                    <div class="card card-body fs-14 border-0">
                                        <div class="mb-2">
                                            <img src="/assets/img/trustpilot-score.svg" alt="">
                                        </div>
                                        <h6 class="fs-14"><a class="text-reset" href="">Arnoldo Porto</a></h6>
                                        <p class="text-muted mb-0">Es un excelente servicio y su personal es muy atento, lo recomiendo 100% además de ser muy confiable.</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div id="review-swiper-prev" class="swiper-button-prev"></div>
                        <div id="review-swiper-next" class="swiper-button-next"></div>
                    </div>

                    <div class="text-center">
                        <div class="fs-14">
                            <img src="/assets/img/trustpilot-score.svg" alt="3.5/5" class="img-fluid mr-2"> <strong>3.5/5</strong>
                        </div>
                        <a href="https://www.trustpilot.com/review/americankryptosbank.com" class="d-inline-block text-muted fs-13 mt-2" target="_blank">Valóranos en <img src="/assets/img/trustpilot-logo--dark.svg" class="ml-2" alt="Trustpilot"></a>
                    </div>
                </div>
            </section>

            <section class="cta-bg text-white text-center text-md-left py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <h1 class="section__title mb-4 mb-md-0">Ten el control sobre tus finanzas.</h1>
                        </div>
                        <div class="col-md-5 text-md-right">
                            <a href="" class="btn btn-secondary btn-gradient btn-lg px-4 px-md-5">Registrarme ahora</a>
                        </div>
                    </div>
                </div>
            </section>

        
        </main>
    </div>
</template>

<script>
import * as numeral from "numeral";
import anime from 'animejs';

export default {
        data() {
        return {
            formattedToSendObject: '',
            formattedToReceiveObject: '',
            formatted_to_send: 15,
            formatted_to_receive: '',
            to_send: '',
            to_receive: '',
            recomended_price: '',
            original_price: '',
            calcTo: 'send',//'receive'
            csrf: $('meta[name="csrf-token"]').attr("content"),
            currencies: {
                'Venezuela': 'VES',
                'Colombia': 'COP',
                'Panamá': 'PAB',
                'Perú': 'PEN',
                'Chile': 'CLP',
                'Argentina': 'ARS',
                'Reino Unido': 'GBP',
                'España': 'EUR',
                'Portugal': 'EUR',
                'Italia': 'EUR',
                'Francia': 'EUR',
                'Alemania': 'EUR',
                'United States': 'USD',
                "México": "MXN",
                "Brazil": "BRL",
                "Costa Rica": "CRC",
                "Dominican Republic": "DOP",
                "Canada": "CAD",
            },
            sender_country: 'United States',
            receiver_country: 'Chile',
            getPriceInterval: null,
            loadingRate: false,
            loadingReceive: false,
            loadingSend: false
        }
    },
    mounted() {
        let self = this;
        if (this.calcTo !== 'receive') {
            this.to_send           = numeral(this.formatted_to_send).value();
            this.formatted_to_send = numeral(this.formatted_to_send).format('0,0[.]00');
            clearInterval(this.getPriceInterval);
            this.getPriceInterval = setInterval(function () {
                self.getPrice('fromSend');
                clearInterval(self.getPriceInterval)
            }, 1000);
        }
        
        $("#__ho_select_sender").select2({
            placeholder: "Seleccione",
            theme: 'bootstrap4',
            width: '120px',
            dropdownAutoWidth: true,
            templateResult: setCountry,
            templateSelection: setCurrency,
        }).on('select2:select', function () {       
            var value = $("#__ho_select_sender").select2('data');
            self.sender_country = value[0].id;
            self.getPrice('fromSend', true);
        });

        $("#__ho_select_receiver").select2({
            placeholder: "Seleccione",
            theme: 'bootstrap4',
            width: '120px',
            dropdownAutoWidth: true,
            templateResult: setCountry,
            templateSelection: setCurrency,
        }).on('select2:select', function () {       
            var value = $("#__ho_select_receiver").select2('data');
            self.receiver_country = value[0].id;
            self.getPrice('fromReceive', true);
        });  

        $(function() {
            var reviewsSwiper = new Swiper ('#reviews-swiper', {
                // Optional parameters
                speed: 500,
                direction: 'horizontal',
                loop: true,
                slidesPerView: 1,
                spaceBetween: 12,
                grabCursor: true,
                // // autoplay
                autoplay: {
                    delay: 2000,
                },
            
                // Navigation arrows
                navigation: {
                    nextEl: '#review-swiper-next',
                    prevEl: '#review-swiper-prev',
                },
                // responsive
                breakpoints: {
                    // when window width is >= 320px
                    375: {
                        slidesPerView: 2,
                        spaceBetween: 16
                    },
                    // when window width is >= 480px
                    425: {
                        slidesPerView: 2,
                        spaceBetween: 16
                    },
                    // when window width is >= 640px
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    },
                    // when window width is >= 992px
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 24
                    }
                }
            })
            var paymentsSwiper = new Swiper ('#payments-swiper', {
                // Optional parameters
                speed: 1000,
                direction: 'horizontal',
                loop: true,
                slidesPerView: 1,
                spaceBetween: 12,
                grabCursor: true,
                // // autoplay
                autoplay: {
                    delay: 1000,
                },
            
                // Navigation arrows
                navigation: {
                    nextEl: '#pay-swiper-next',
                    prevEl: '#pay-swiper-prev',
                },
                // responsive
                breakpoints: {
                    // when window width is >= 320px
                    375: {
                        slidesPerView: 1,
                        spaceBetween: 16
                    },
                    // when window width is >= 480px
                    425: {
                        slidesPerView: 1,
                        spaceBetween: 16
                    },
                    // when window width is >= 640px
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24
                    },
                    // when window width is >= 992px
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    }
                }
            })
        })
        // select2 calculator currencies
        function setCountry(currency) {   
            if (!currency.id) { return currency.text; }
            var $currency = $(  `<span class='currencyFlag'>
                                    <img class='img-fluid' src='https://lipis.github.io/flag-icon-css/flags/4x3/${currency.element.dataset.flag}.svg'>
                                </span> <span>${currency.text}</span>`);
            return $currency;
        };
        function setCurrency(currency) {           
            if (!currency.id) { return currency.text; }
            var $currency = $(  `<span class='currencyFlag mr-2' style='width: 28px;'>
                                    <img class='img-fluid' src='https://lipis.github.io/flag-icon-css/flags/4x3/${currency.element.dataset.flag}.svg'>
                                </span> <b>${self.currencies[currency.element.value]}</b>`);
            return $currency;
        };
        
        // anime.js for section waves
        const   waveTop1 = "M1280 0H0V59.5528C33.5356 42.1409 171 20.8489 314 43.6199C457 66.3909 670 64.022 798.468 40.8434C926.936 17.6648 1105.62 -14.5733 1280 70V0Z",
                waveTop2 = "M1280 0H0V51.3002C31 65.4683 177 63.0906 314 37.5753C451 12.0599 630.468 17.0936 762 42.2098C893.532 67.326 1112 80.1125 1280 60.2997V0Z",
                waveTop3 = "M1280 0H0V59.5528C88 70 184 48.8941 401 29C618 9.10587 683 79.9042 882 44C1081 8.09581 1193 70 1280 70V0Z",
                waveTop4 = "M1280 0H0V59.5528C88 70 174 20 401 29C628 38 724 59 914 37C1104 15 1246 59 1280 70V0Z",
                waveBottom1 = "M1280 70H0V10.4472C88 0 174 50 401 41C628 32 724 11 914 33C1104 55 1246 11 1280 0V70Z",
                waveBottom2 = "M1280 70H0V10.4472C88 0 184 21.1059 401 41C618 60.8941 683 -9.90419 882 26C1081 61.9042 1193 0 1280 0V70Z",
                waveBottom3 = "M1280 70H0V18.6998C31 4.53174 177 6.90943 314 32.4247C451 57.9401 630.468 52.9064 762 27.7902C893.532 2.67397 1112 -10.1125 1280 9.70028V70Z",
                waveBottom4 = "M1280 70H0V10.4472C33.5356 27.8591 171 49.1511 314 26.3801C457 3.60913 670 5.978 798.468 29.1566C926.936 52.3352 1105.62 84.5733 1280 0V70Z";
        anime({
            targets: '.wave-top > path',
            easing: 'linear',
            duration: 7500,
            loop: true,
            d: [
                { value: [waveTop1, waveTop3] },
                { value: waveTop2 },
                { value: waveTop4 },
                { value: waveTop1 },
            ],
        });
            
        anime({
            targets: '.wave-bottom > path',
            easing: 'linear',
            duration: 7500,
            loop: true,
            d: [
                { value: [waveBottom1, waveBottom3] },
                { value: waveBottom2 },
                { value: waveBottom4 },
                { value: waveBottom1 },
            ],
        });
        // header blobs animation
        const   headerBlobLeft_1 = "M239.09 2.2745C300.548 15.2906 307.508 106.313 360.065 140.729C417.583 178.394 516.352 146.346 550.358 206.106C582.951 263.382 524.129 330.584 503.767 393.261C483.404 455.937 490.404 542.59 431.465 572.061C370.98 602.304 305.709 537.213 239.09 525.603C188.695 516.821 131.758 542.029 89.9522 512.545C48.0176 482.97 38.4003 426.194 27.1431 376.126C16.8487 330.34 21.0447 285.138 28.4017 238.789C37.2444 183.08 36.6024 122.285 74.1204 80.1683C115.586 33.6195 178.107 -10.6413 239.09 2.2745Z",
                headerBlobLeft_2 = "M299.967 1.74884C367.51 9.03515 423.451 56.4811 468.334 107.468C506.426 150.74 528.761 206.526 530.939 264.13C532.784 312.938 492.091 350.283 479.296 397.421C465.427 448.515 495.013 517.557 453.297 550.166C411.884 582.538 352.398 533.807 299.967 537.602C240.627 541.898 183.518 598.767 129.61 573.598C76.1057 548.617 54.7475 479.376 49.3334 420.585C44.4779 367.859 94.1392 327.231 102.102 274.885C112.73 205.014 61.4937 125.29 102.958 68.0522C144.623 10.5377 229.349 -5.86932 299.967 1.74884Z",
                headerBlobLeft_3 = "M342.937 0.058779C393.938 -2.11912 423.458 56.7483 462.545 89.5883C498.129 119.486 547.231 138.98 561.089 183.348C574.906 227.585 535.154 269.794 533.294 316.102C530.286 391.021 591.25 474.609 546.965 535.108C504.958 592.495 413.946 579.984 342.937 576.154C275.163 572.498 204.399 559.721 154.297 513.924C106.595 470.321 104.59 399.637 83.1311 338.671C60.9518 275.658 -8.32177 208.35 26.7248 151.481C64.4338 90.2911 165.706 130.536 230.527 99.4962C276.777 77.3488 291.706 2.2465 342.937 0.058779Z",
                headerBlobLeft_4 = "M325.79 14.2155C393.912 -1.19788 486.633 -16.3328 528.624 39.4849C573.855 99.6095 505.073 183.795 509.853 258.885C512.875 306.371 567.039 347.085 551.056 391.902C535.059 436.755 472.006 439.059 434.936 468.946C394.728 501.362 375.7 560.238 325.79 573.515C271.407 587.981 206.05 578.723 164.356 540.924C123.469 503.858 140.607 436.671 118.88 385.937C93.6254 326.968 13.368 284.359 27.7005 221.831C41.774 160.432 128.538 154.313 180.223 118.315C230.401 83.3666 266.15 27.7097 325.79 14.2155Z",
                headerBlobRight_1 = "M254.21 565.785C186.088 581.198 93.3672 596.333 51.3762 540.515C6.14514 480.391 74.927 396.205 70.1474 321.115C67.1248 273.629 12.9607 232.915 28.9441 188.098C44.9406 143.245 107.994 140.941 145.064 111.054C185.272 78.6378 204.3 19.7621 254.21 6.48537C308.593 -7.98132 373.95 1.27748 415.644 39.0759C456.531 76.1424 439.393 143.329 461.12 194.063C486.375 253.032 566.632 295.641 552.3 358.169C538.226 419.568 451.462 425.687 399.777 461.685C349.599 496.633 313.85 552.29 254.21 565.785Z",
                headerBlobRight_2 = "M238.063 579.941C187.062 582.119 157.542 523.252 118.455 490.412C82.8707 460.514 33.7689 441.02 19.9112 396.652C6.09408 352.415 45.8463 310.206 47.7057 263.898C50.7138 188.979 -10.2498 105.391 34.0349 44.8923C76.0424 -12.4952 167.054 0.0155127 238.063 3.84624C305.837 7.5025 376.601 20.2794 426.703 66.0757C474.405 109.679 476.41 180.363 497.869 241.329C520.048 304.342 589.322 371.65 554.275 428.519C516.566 489.709 415.294 449.464 350.473 480.504C304.223 502.651 289.294 577.754 238.063 579.941Z",
                headerBlobRight_3 = "M280.033 578.251C212.49 570.965 156.549 523.519 111.666 472.532C73.5735 429.26 51.2385 373.474 49.0608 315.87C47.2155 267.062 87.9086 229.717 100.704 182.579C114.573 131.485 84.9872 62.4435 126.703 29.8343C168.116 -2.53808 227.602 46.1934 280.033 42.3979C339.373 38.1021 396.482 -18.7667 450.39 6.40215C503.894 31.3827 525.252 100.624 530.667 159.415C535.522 212.141 485.861 252.769 477.898 305.115C467.27 374.986 518.506 454.71 477.042 511.948C435.377 569.462 350.651 585.869 280.033 578.251Z",
                headerBlobRight_4 = "M341.91 577.725C280.452 564.709 273.492 473.687 220.935 439.271C163.416 401.606 64.6478 433.653 30.6422 373.894C-1.9506 316.618 56.8707 249.416 77.2334 186.739C97.5961 124.063 90.5957 37.4095 149.535 7.93878C210.02 -22.3044 275.291 42.7871 341.91 54.3969C392.305 63.1794 449.242 37.9714 491.048 67.4549C532.982 97.0296 542.6 153.806 553.857 203.874C564.151 249.66 559.955 294.862 552.598 341.211C543.756 396.92 544.398 457.715 506.879 499.832C465.414 546.38 402.893 590.641 341.91 577.725Z";
        anime({
            targets: '.header__blob--left > path',
            easing: 'linear',
            duration: 15000,
            loop: true,
            d: [
                { value: [headerBlobLeft_1, headerBlobLeft_2] },
                { value: headerBlobLeft_3 },
                { value: headerBlobLeft_4 },
                { value: headerBlobLeft_1 }
            ]
        })
        anime({
            targets: '.header__blob--right > path',
            easing: 'linear',
            duration: 15000,
            loop: true,
            d: [
                { value: [headerBlobRight_1, headerBlobRight_2] },
                { value: headerBlobRight_3 },
                { value: headerBlobRight_4 },
                { value: headerBlobRight_1 }
            ]
        })   
    },
    computed: {
            sender: function () {
                return this.currencies[this.sender_country];
            },

            receiver: function () {
                return this.currencies[this.receiver_country];
            }
    },
    methods: {
        recommendedUtility() {
            if (this.recomended_price < 1) {
                return numeral(1 / this.recomended_price).format('0,0.[00]');
            }
            return numeral(this.recomended_price).format('0,0.[00]');
        },
        getPrice(origin, lRates = false) { 
            if (this.to_send !== '' || this.to_receive !== '') {
                if (lRates) {
                    if (origin === 'fromSend') {
                        this.loadingReceive = true;
                    } else {
                        this.loadingSend = true;
                    }
                }
                this.loadingRate = true;
                axios.get(window.location.origin + '/get-price', {
                    params: {
                        amount: this.to_send === '' ? 0 : this.to_send,
                        receiving: this.to_receive === '' ? 0 : this.to_receive,
                        sender: this.sender,
                        receiver: this.receiver,
                        sender_country: this.sender_country,
                        receiver_country: this.receiver_country,
                    }
                }).then(
                    re => {
                        this.loadingRate      = false;
                        this.loadingReceive   = false;
                        this.loadingSend      = false;
                        this.recomended_price = re.data[0];
                        this.original_price   = re.data['original'];
                        if (origin === 'fromSend') {
                            this.toReceive();
                        } else {
                            this.toSend();
                        }
                    }
                )
            }
        },
        getUrl(endpoint) {
            return window.location.origin + endpoint;
        },
        storeData() {
            localStorage.setItem("to_send", this.to_send);
            localStorage.setItem("to_receive", this.to_receive);
            localStorage.setItem("sender_country", this.sender_country);
            localStorage.setItem("receiver_country", this.receiver_country);
            localStorage.setItem("recomended_price", this.recomended_price);
        },
        toReceive: function () {
            if (this.to_send === '') {
                return '';
            }
            if (this.original_price > 1 && this.recomended_price < 1) {
                this.to_receive = Math.round(this.to_send / this.recomended_price * 100) / 100;
            } else {
                this.to_receive = Math.round(this.to_send * this.recomended_price * 100) / 100;
            }
            this.formatted_to_receive = numeral(this.to_receive).format('0,0.[00]');
            this.storeData();
        },
        toSend: function () {
            if (this.to_receive === '') {
                return '';
            }
            this.to_send           = Math.round(this.to_receive / this.recomended_price * 100) / 100;
            this.formatted_to_send = numeral(this.to_send).format('0,0.[00]');
            this.storeData();
        },
        changeCalc(toCalc) {
            this.calcTo = toCalc;
        },
        testPrices() {
            let combinations = require('../tests/combination.json'),
                dataAr       = [];
            $.when($.each(combinations, function (key, value) {
                axios.get(window.location.origin + '/get-price', {
                    params: {
                        sender: value[0],
                        receiver: value[1],
                        sender_country: value[2],
                        receiver_country: value[3],
                    }
                }).then(
                    re => {
                        dataAr.push(re.data[0]);
                    }
                )
            })).then(function () {
                localStorage.setItem('testPrices', JSON.stringify(dataAr));
            });
        },
        getCountriesData() {
            axios.get(window.location.origin + '/get-op-countries').then(
                re => {
                    this.currencies = re.data[0];
                    $('#__ho_select_sender').html(re.data[1]);
                    $('#__ho_select_receiver').html(re.data[2]);
                }
            )
        },
        changeValue(){
            let send    = this.sender_country,
                receive = this.receiver_country;
            this.sender_country     = receive;
            this.receiver_country   = send;
            $('#__ho_select_sender').val(receive).trigger('change.select2');
             $('#__ho_select_receiver').val(send).trigger('change.select2');
            this.getPrice('fromSend');
        }
    },
    watch: {
        formatted_to_send: function () {
            let vueObject = this;
            if (this.calcTo !== 'receive') {
                this.to_send           = numeral(this.formatted_to_send).value();
                this.formatted_to_send = numeral(this.formatted_to_send).format('0,0[.]00');

                clearInterval(this.getPriceInterval);
                this.getPriceInterval = setInterval(function () {
                    vueObject.getPrice('fromSend');
                    clearInterval(vueObject.getPriceInterval)
                }, 1000);
            }
        },
        formatted_to_receive: function () {
            let vueObject = this;
            if (this.calcTo !== 'send') {
                this.to_receive           = numeral(this.formatted_to_receive).value();
                this.formatted_to_receive = numeral(this.formatted_to_receive).format('0,0[.]00');
                clearInterval(this.getPriceInterval);
                this.getPriceInterval = setInterval(function () {
                    vueObject.getPrice('fromReceive');
                    clearInterval(vueObject.getPriceInterval)
                }, 1000);
            }
        }
    }
}
</script>

<style>

</style>