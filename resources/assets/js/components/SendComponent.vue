<script src="../walletTraSendJS.js"></script>

<template lang="html">
    <div class="card-body py-4 py-lg-4">
        <div class="row justify-content-center mt-4 mb-4">
            <div v-for="wallet in walletsObject" class="col-3 col-md-3 col-xl-2 px-1">
                <a href="javascript:void(0);"
                   class="cardBank mb-2 mb-md-0px-1"
                   v-on:click="changeCurrency(wallet['currency'])"
                   :class="{'--active': (currency === wallet['currency'])}">
                    <img :src="'/img/landing/wallet-'+ wallet['currency'] +'-primary.svg'"
                         alt="GBP Icon"
                         class="d-inline-block mb-2">
                    <h6 class="cardBank__title" v-if="wallet['currency'] === 'USD'">Dólares</h6>
                    <h6 class="cardBank__title" v-if="wallet['currency'] === 'GBP'">Libras Esterlinas</h6>
                    <h6 class="cardBank__title" v-if="wallet['currency'] === 'EUR'">Euros</h6>
                    <div class="cardBank__info">
                        {{formatMoney(wallet['numbers']['available'])}} {{wallet['currency']}}
                    </div>
                </a>
            </div>
        </div>

        <nav id="wallets-nav" class="nav nav-fill flex-nowrap align-items-center border-top border-bottom">
            <a class="nav-item nav-link text-truncate px-2 py-3 border-right active"
               :href="transactionsUrl">Historial</a>
            <a class="nav-item nav-link text-truncate px-2 py-3" :href="chargeUrl"><img
                    src="/img/landing/recharge-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">Cargar
                {{currencyName}}</a>
            <a class="nav-item nav-link text-truncate px-2 py-3" :href="sendUrl"><img
                    src="/img/landing/send-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">Enviar
                {{currencyName}}</a>
            <a class="nav-item nav-link text-truncate px-2 py-3" :href="TransferUrl"><img
                    src="/img/landing/transfer-blue.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">Transferir
                {{currencyName}}</a>
            <a class="nav-item nav-link text-truncate px-2 py-3" :href="WithdrawUrl"><img
                    src="/img/landing/withdraw-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">Retirar
                {{currencyName}}</a>
        </nav>

        <!-- transactions history -->
        <div class="py-4">
            <div class="row">
                <div class="col-md-8">

                    <h6 class="text-primary font-weight-bold mb-3">Elige el país y el monto que vas a enviar</h6>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sm-monto" class="text-primary">Monto a enviar</label>
                                <input type="text" id="sm-monto" class="form-control form-control-font-lg"
                                       value="USD 450.00" placeholder="Escriba monto a enviar">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sm-destino" class="text-primary">Destino</label>
                                <select class="custom-select flag-selector flag-selector--full">
                                    <optgroup label="Latino América">
                                        <option value="VE" data-flag="img/landing/flags/ve.svg" selected>Venezuela
                                        </option>
                                        <option value="AR" data-flag="img/landing/flags/ar.svg">Argentina</option>
                                        <option value="BO" data-flag="img/landing/flags/bo.svg">Bolivia</option>
                                        <option value="BR" data-flag="img/landing/flags/br.svg">Brasil</option>
                                        <option value="CL" data-flag="img/landing/flags/cl.svg">Chile</option>
                                        <option value="CO" data-flag="img/landing/flags/co.svg">Colombia</option>
                                        <option value="PE" data-flag="img/landing/flags/pe.svg">Perú</option>
                                        <option value="PY" data-flag="img/landing/flags/py.svg">Paraguay</option>
                                        <option value="UY" data-flag="img/landing/flags/uy.svg">Uruguay</option>
                                    </optgroup>
                                    <optgroup label="Otros">
                                        <option value="US" data-flag="img/landing/flags/us.svg">United States</option>
                                        <option value="CA" data-flag="img/landing/flags/ca.svg">Canada</option>
                                        <option value="ES" data-flag="img/landing/flags/es.svg">España</option>
                                        <option value="PT" data-flag="img/landing/flags/pt.svg">Portugal</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sm-amount" class="text-primary">Entregamos</label>
                                <input type="text" class="form-control custom-readonly"
                                       placeholder="Ingrese monto a enviar" value="BsS 180.000.00" readonly>
                            </div>
                        </div>
                    </div>

                    <section id="recipient" class="mt-4">
                        <h5 class="text-primary font-weight-bold mb-4">¿A quién vas a enviar dinero?</h5>
                        <div class="row">
                            <div class="col-6 col-md-4 col-xl-3 px-2">
                                <div class="cardBank mx-2" id="person-btn" data-target="#contact-wrapper">
                                    <div class="media align-items-center">
                                        <div>
                                            <img src="/img/landing/person-primary.svg" alt="Person Icon" class="mr-2">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="cardBank__title mb-0">Persona</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-xl-3 px-2">
                                <div class="cardBank mx-2" id="company-btn" data-target="#contact-wrapper">
                                    <div class="media align-items-center">
                                        <div>
                                            <img src="/img/landing/company-primary.svg" alt="Person Icon" class="mr-2">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="cardBank__title mb-0">Empresa</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div id="contact-wrapper" class="slideTab --active">
                            <div>
                                <div class="d-flex justify-content-between">
                                    <div class="font-14 text-primary">Estos son tus contactos personales en Venezuela,
                                        elige uno.
                                    </div>
                                    <div id="recipients-slider-arrows" class="position-relative"></div>
                                </div>
                                <slick id="recipients-slider" ref="slick" class="mt-3 mb-0" :options="slickOptions">
                                    <div class="cardBank --active mx-2">
                                        <img src="/img/landing/person-primary.svg" alt="Person Icon"
                                             class="mb-2 mx-auto">
                                        <h6 class="cardBank__title">Angela<br>Anzola</h6>
                                        <div class="cardBank__info">Banesco<br>....0589</div>
                                        <button type="button" class="cardBank__edit text-muted">
                                            <i class="fa fa-sliders fa-rotate-90"></i>
                                        </button>
                                    </div>
                                    <div class="cardBank mx-2">
                                        <img src="/img/landing/person-primary.svg" alt="Person Icon"
                                             class="mb-2 mx-auto">
                                        <h6 class="cardBank__title">Karen<br>Bohorquez</h6>
                                        <div class="cardBank__info">Banesco<br>....0589</div>
                                        <button type="button" class="cardBank__edit text-muted">
                                            <i class="fa fa-sliders fa-rotate-90"></i>
                                        </button>
                                    </div>
                                    <div class="cardBank mx-2">
                                        <img src="/img/landing/person-primary.svg" alt="Person Icon"
                                             class="mb-2 mx-auto">
                                        <h6 class="cardBank__title">Joaquin<br>Quijano</h6>
                                        <div class="cardBank__info">Banesco<br>....0589</div>
                                        <button type="button" class="cardBank__edit text-muted">
                                            <i class="fa fa-sliders fa-rotate-90"></i>
                                        </button>
                                    </div>
                                    <div class="cardBank mx-2">
                                        <img src="/img/landing/person-primary.svg" alt="Person Icon"
                                             class="mb-2 mx-auto">
                                        <h6 class="cardBank__title">Angela<br>Anzola</h6>
                                        <div class="cardBank__info">Banesco<br>....0589</div>
                                        <button type="button" class="cardBank__edit text-muted">
                                            <i class="fa fa-sliders fa-rotate-90"></i>
                                        </button>
                                    </div>
                                    <div class="cardBank mx-2">
                                        <img src="/img/landing/person-primary.svg" alt="Person Icon"
                                             class="mb-2 mx-auto">
                                        <h6 class="cardBank__title">Karen<br>Bohorquez</h6>
                                        <div class="cardBank__info">Banesco<br>....0589</div>
                                        <button type="button" class="cardBank__edit text-muted">
                                            <i class="fa fa-sliders fa-rotate-90"></i>
                                        </button>
                                    </div>
                                    <div class="cardBank mx-2">
                                        <img src="/img/landing/person-primary.svg" alt="Person Icon"
                                             class="mb-2 mx-auto">
                                        <h6 class="cardBank__title">Angela<br>Anzola</h6>
                                        <div class="cardBank__info">Banesco<br>....0589</div>
                                        <button type="button" class="cardBank__edit text-muted">
                                            <i class="fa fa-sliders fa-rotate-90"></i>
                                        </button>
                                    </div>
                                </slick>
                                <button id="btnAdd-contact-person" class="btn btn-secondary btn-block"><img
                                        src="/img/landing/add-person-light.svg" class="img-fluid mr-2">Crear nuevo
                                    contacto
                                </button>
                            </div>
                        </div>
                    </section>

                </div>

                <aside class="col-md-4">
                    <div class="card body-bg-color rounded text-primary sticky-top" style="top: 2rem;">
                        <div class="px-3 px-md-4 py-3">
                            <h6 class="text-primary font-weight-bold text-uppercase mb-4">Resumen de la transacción</h6>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="small text-truncate mr-2">Monto a recargar</div>
                                <div class="font-14 font-weight-bold text-truncate">450.00 USD</div>
                            </div>
                        </div>
                        <div class="px-3 px-md-4 py-4 border-top border-primary">
                            <button class="btn btn-secondary btn-pill btn-block">
                              <span class="mr-2">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="27" viewBox="0 0 28 27"><g><g><g><g><g><g><g><path
                                          fill="#fff"
                                          d="M11.88 26.237c-.302 0-.583-.022-.863-.065-1.662-.237-3.13-1.1-4.101-2.417a6.142 6.142 0 0 1-1.209-4.619c.432-3.065 3.087-5.374 6.173-5.374.28 0 .583.021.863.065a6.232 6.232 0 0 1 5.31 7.036c-.432 3.064-3.087 5.374-6.173 5.374zm-.022-13.339c-3.518 0-6.54 2.634-7.014 6.108a7.072 7.072 0 0 0 1.36 5.267 6.982 6.982 0 0 0 4.683 2.762c.324.043.67.065.993.065 3.518 0 6.54-2.633 7.014-6.13.54-3.863-2.158-7.467-6.043-8.007a7.667 7.667 0 0 0-.993-.065z"/></g></g></g></g></g><g><g><g><g><g><g><path
                                          fill="#fff"
                                          d="M9.657 18.532c0-.972.69-1.662 1.834-1.749V15.64h.648v1.165a3.346 3.346 0 0 1 1.942.928l-.496.604c-.54-.518-1.23-.798-1.835-.798-.712 0-1.23.345-1.23.95 0 .69.54.906 1.425 1.187 1.036.345 1.985.669 1.985 1.79 0 .929-.647 1.62-1.813 1.684v1.209h-.626v-1.252c-.582-.108-1.208-.367-1.618-.69l.475-.583c.496.367 1.143.561 1.597.561.669 0 1.122-.302 1.122-.863 0-.605-.583-.82-1.381-1.08-1.144-.388-2.03-.69-2.03-1.92z"/></g></g></g></g></g></g><g><g><g><g><g><path
                                          fill="#fff"
                                          d="M22.304 13.481v-3.237h-9.841A11.872 11.872 0 0 0 1.65 17.258l-.216.497v-.928c0-6.044 4.92-10.965 10.964-10.965h9.906V2.927L26.6 8.215zm-.863-8.503h-9.043C5.88 4.978.57 10.287.57 16.805v4.986l1.877-4.187c1.748-3.95 5.698-6.497 10.015-6.497h8.978v4.835l6.26-7.727L21.44.467z"/></g></g></g></g></g></g></g></svg>
                              </span>Enviar dolares
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</template>

<style lang="scss">

</style>
