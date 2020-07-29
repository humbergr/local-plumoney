<script src="../add-card.js"></script>

<template lang="html">
    <div class="loader--wrapper" :class="{ '--loading': loader }">
        <vue-toastr ref="toastr"></vue-toastr>

        <input type="hidden" v-model="pay_method" name="pay_method" value="">

        <div id="payment-method" class="mt-4">
            <h6 class="text-primary font-weight-bold mb-2">Destino</h6>
            <div class="font-14 text-primary">Elige el método de pago</div>
            <div class="row mt-3">
                <div class="col-3 col-xl-2">
                  <span data-toggle="tooltip" data-placement="left" title="Registrar Nuevo Método de Pago">
                      <button type="button" class="btn-as-card p-3 mb-4" style="height: 131px"
                              data-toggle="modal" data-target="#add-paymethod-modal">
                          <img src="img/landing/add-new-payment.svg" alt="Registrar Nuevo Método de Pago">
                          <div class="sr-only">Registrar Nuevo Método de Pago</div>
                      </button>
                  </span>
                </div>
                <div class="col-9 col-xl-10">
                    <slick id="payment-slider" class="mb-0" ref="slick" :options="slickOptions">
                        <div class="cardBank selective mx-2" v-on:click="selectCash()"
                             :class="{'--active' : (pay_method === 'cash')}">
                            <img src="img/landing/cc-primary.svg" alt="Person Icon" class="mb-2 mx-auto">
                            <h6 class="cardBank__title">Pagar en Efectivo</h6>
                            <div class="cardBank__info">Depositos o Transferencias</div>
                        </div>
                        <div v-for="card in cards" class="cardBank selective mx-2" v-on:click="selectCard(card.id)"
                             :class="{'--active' : (pay_method === card.id)}">
                            <img src="img/landing/cc-primary.svg" alt="Person Icon" class="mb-2 mx-auto">
                            <h6 class="cardBank__title">{{card.brand}}</h6>
                            <div class="cardBank__info">{{card.funding}} {{card.object}}<br>....{{card.last4}}</div>
                            <button type="button" class="cardBank__edit text-muted">
                                <i class="fa fa-sliders fa-rotate-90"></i>
                            </button>
                        </div>
                    </slick>
                </div>
            </div>
        </div>

        <!-- add payment method modal -->
        <div class="modal fade" id="add-paymethod-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header px-lg-4">
                        <div>
                            <h6 class="modal-title text-primary font-weight-bold">Agregar un método de pago</h6>
                            <div class="text-primary font-14">
                                Métodos de transferencia directa, su pago se verá reflejado de inmediato
                            </div>
                        </div>
                    </div>
                    <div class="modal-body px-lg-4">
                        <div class="inputRadio__wrapper">
                            <input type="radio" id="credit-card" name="payMethod" class="inputRadio" checked>
                            <label for="credit-card" class="inputRadio__label">Tarjeta de crédito o débito</label>
                            <div class="inputRadio__content p-3">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="name" class="text-primary">Datos del titular</label>
                                            <input type="text" id="name" class="form-control"
                                                   placeholder="Nombre del titular">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="id" class="text-primary">&nbsp;</label>
                                            <input type="text" id="id" class="form-control"
                                                   placeholder="ID del titular">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="cc-number" class="text-primary">Datos de la tarjeta</label>
                                            <div class="form-control" style="height:34.5px" ref="card">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--      <div class="inputRadio__wrapper">
                                  <input type="radio" id="debit-card" name="payMethod" class="inputRadio">
                                  <label for="debit-card" class="inputRadio__label">Tarjeta de débito</label>
                                  <div class="inputRadio__content p-3">
                                      <div class="row">
                                          <div class="col-6">
                                              <div class="form-group">
                                                  <label for="name" class="text-primary">Datos del titular</label>
                                                  <input type="text" id="name" class="form-control" placeholder="Nombre del titular">
                                              </div>
                                          </div>
                                          <div class="col-6">
                                              <div class="form-group">
                                                  <label for="id" class="text-primary">&nbsp;</label>
                                                  <input type="text" id="id" class="form-control" placeholder="ID del titular">
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="cc-number" class="text-primary">Datos de la tarjeta</label>
                                                  <input type="text" id="cc-number" class="form-control" placeholder="Número de tarjeta">
                                              </div>
                                          </div>
                                          <div class="col-6 col-md-3">
                                              <div class="form-group">
                                                  <label for="expiry-mm" class="text-primary">Fecha de vencimiento</label>
                                                  <div class="double-input">
                                                      <input type="text" id="expiry-mm" class="form-control" placeholder="MM">
                                                      <input type="text" id="expiry-aa" class="form-control" placeholder="AA">
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-6 col-md-3">
                                              <div class="form-group">
                                                  <label for="sec-code" class="text-primary">Código de seguridad<img src="img/landing/qmark-primary.svg" class="ml-1" title="¿Qué es esto?"></label>
                                                  <input type="text" id="sec-code" class="form-control" placeholder="AA">
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="inputRadio__wrapper">
                                  <input type="radio" id="paypal" name="payMethod" class="inputRadio">
                                  <label for="paypal" class="inputRadio__label">Paypal</label>
                                  <div class="inputRadio__content p-3">
                                      Paypal
                                  </div>
                              </div> -->
                    </div>
                    <div class="modal-footer justify-content-center mb-3">
                        <button type="button" class="btn btn-secondary btn-pill px-4" data-dismiss="modal"
                                v-on:click="addCard">Agregar este método
                        </button>
                    </div>
                    <a href="payment-other.html" class="modal-footer--cta">
                        <span>Agregar un método de pago diferente</span> <span><i
                            class="fa fa-angle-right text-dark"></i></span>
                    </a>
                    <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">
                        <span class="d-none d-md-inline">Cerrar</span>
                        <span class="d-md-none">&times;</span>
                    </button>
                    <img src="img/landing/add-paymethod-modal.png"
                         class="d-none d-md-block modal-animated-img animated flipInY">
                </div>
            </div>
        </div>

        <div class="loader">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>

<style lang="css">
</style>
