<script src="../walletTransfersJS.js"></script>

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

        <nav id="wallets-nav"
             class="nav nav-fill flex-nowrap align-items-center border-top border-bottom bg-white mx-n3 mx-md-0 sticky-top">
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3 border-right" :href="transactionsUrl">
                <div>Historial</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3" :href="chargeUrl">
                <img src="/img/landing/recharge-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Cargar {{currencyName}}</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3" :href="WithdrawUrl">
                <img src="/img/landing/withdraw-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Retirar {{currencyName}}</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3 active" :href="TransferUrl">
                <img src="/img/landing/transfer-blue.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Transferir {{currencyName}}</div>
            </a>
        </nav>

        <!-- transactions history -->
        <div class="py-4">
            <form id="logout-form" action="" method="POST">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-9 mx-auto">
                                <div class="small text-center mb-5">
                                    <div>
                                        <span class="text-primary font-weight-bold">Transferir dólares.</span> Podrás
                                        hacer transferencias de dinero a otras billeteras dentro de American Kryptos
                                        bank.
                                        <!--
                                                o hacer transferencias entre tus billeteras de diferentes divisas. Recuerda
                                                que la tasa de cambio varía con frecuencia.
                                            -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-primary font-weight-bold mb-0">Paso 1.</h5>
                        <label class="text-primary">Elige el tipo de transferencia</label>
                        <div class="row my-3">
                            <!-- <div class="col-6 mb-3 px-1"
                                  data-toggle="tooltip"
                                  :title="selfDestinyWallets.length === 0 ? 'No tienes otras billeteras creadas': ''">
                                 <div id="self-wallet"
                                      @click="showNextSteps(1)"
                                      class="cardBank mx-2"
                                      v-bind:class="{ 'total-disabled': selfDestinyWallets.length === 0 }"
                                 >
                                     <h6 class="cardBank__title mb-0">A una billetera propia</h6>
                                 </div>
                             </div> -->
                            <div class="col-6 mb-3 px-1">
                                <div id="friend-wallet"
                                     @click="showNextSteps(2)"
                                     class="cardBank mx-2">
                                    <h6 class="cardBank__title mb-0">A una billetera</h6>
                                </div>
                            </div>
                        </div>

                        <div id="self-transfer" style="display: none;">
                            <h5 class="text-primary font-weight-bold mb-0">Paso 2.</h5>
                            <label class="text-primary">
                                Elige la billetera a la que quieres transferir tus dólares
                            </label>
                            <div class="row my-3">
                                <div class="col-6 col-md-3 mb-3 px-1">
                                    <div class="cardBank --active mx-2">
                                        <img src="/img/landing/wallet-bss-primary.svg" alt="BsS Icon"
                                             class="mb-2 mx-auto"
                                             style="height: 22px;">
                                        <h6 class="cardBank__title">Bolívares</h6>
                                        <div class="cardBank__info">1.255.256 BsS</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-3 px-1">
                                    <div class="cardBank mx-2">
                                        <img src="/img/landing/wallet-eur-primary.svg" alt="EUR Icon"
                                             class="mb-2 mx-auto"
                                             style="height: 22px;">
                                        <h6 class="cardBank__title">Euros</h6>
                                        <div class="cardBank__info">1.255 EUR</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-3 px-1">
                                    <div class="cardBank mx-2">
                                        <img src="/img/landing/wallet-btc-primary.svg" alt="BTC Icon"
                                             class="mb-2 mx-auto"
                                             style="height: 22px;">
                                        <h6 class="cardBank__title">Bitcoin</h6>
                                        <div class="cardBank__info">1.00354 BTC</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-3 px-1">
                                    <div class="cardBank mx-2">
                                        <img src="/img/landing/wallet-gbp-primary.svg" alt="GBP Icon"
                                             class="mb-2 mx-auto"
                                             style="height: 22px;">
                                        <h6 class="cardBank__title">Libras</h6>
                                        <div class="cardBank__info">0.00 GBP</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="friend-transfer" style="display: none;">
                            <label class="text-primary">
                                Ingresa los datos del usuario de tu contacto en American Kryptos Bank
                            </label>
                            <div class="row">
                                <!--
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="friend-name" class="text-primary">Nombres</label>
                                        <input id="friend-name" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="friend-lastname" class="text-primary">Apellidos</label>
                                        <input id="friend-lastname" type="text" class="form-control">
                                    </div>
                                </div>
                                -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="friend-email" class="text-primary">Correo electrónico</label>
                                        <input id="friend-email"
                                               type="email"
                                               name="receiver-email"
                                               @keyup="verifyInfo"
                                               v-model="receiverEmail"
                                               class="form-control"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="transfer-description" class="text-primary">
                                            Descripción
                                        </label>
                                        <textarea id="transfer-description"
                                               name="transfer-description"
                                               v-model="transferDescription"
                                                  class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div id="third-step" style="display: none" class="row">
                            <div class="col-12">
                                <h5 class="text-primary font-weight-bold mb-0">Paso 2.</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="sm-monto" class="text-primary">Ingresa el monto a transferir</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">{{activeWallet['currency']}}</div>
                                        </div>
                                        <cleave v-model="transferAmount"
                                                :options="options"
                                                id="sm-monto"
                                                class="form-control form-control-font-lg"
                                                placeholder="000,000.00"></cleave>
                                    </div>
                                    <input type="hidden"
                                           id="sending-amount"
                                           name="sending-amount">
                                    <div class="invalid-feedback">Monto inválido</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="money-to-receive" class="form-group">
                                    <label for="sm-deliver" class="text-primary">Entregamos</label>
                                    <input id="sm-deliver" type="text" class="form-control custom-readonly"
                                           placeholder="Ingrese monto a enviar" value="BsS 180.000.00" readonly="">
                                </div>
                            </div>
                            <input type="hidden"
                                   :value="currency"
                                   name="sending-currency">
                        </div>
                    </div>
                    <aside class="col-md-4">
                        <div class="card body-bg-color rounded text-primary sticky-top" style="top: 2rem;">
                            <div class="px-3 px-md-4 py-3">
                                <h6 class="text-primary font-weight-bold text-uppercase mb-4">
                                    Resumen de la transacción
                                </h6>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="small text-truncate mr-2">Monto a Transferir</div>
                                    <div class="font-14 font-weight-bold text-truncate">
                                        {{transferAmount + ' ' + currency}}
                                    </div>
                                </div>
                            </div>
                            <div class="px-3 px-md-4 py-4 border-top border-primary">
                                <button id="transfer-submit"
                                        class="btn btn-secondary btn-pill btn-block disabled">
                                    <span class="mr-2">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="32"
                                           viewBox="0 0 28 32"><g><g><g><g><g><g><g><path
                                              fill="#fff"
                                              d="M21.999 12.993l-.057-3.237-9.84.172A11.872 11.872 0 0 0 1.413 17.13l-.207.5-.016-.928C1.084 10.66 5.919 5.654 11.96 5.548l9.905-.173-.051-2.935L26.2 7.652zm-1.012-8.487l-9.041.157C5.429 4.777.213 10.178.326 16.695l.087 4.985 1.805-4.219c1.679-3.98 5.583-6.595 9.9-6.67l8.976-.157.085 4.834 6.123-7.835-6.393-7.638z"/></g></g></g></g></g></g><g><g><g><g><g><g><g><path
                                              fill="#fff"
                                              d="M27.256 25.467h-4.209c-.965 0-1.756-.79-1.756-1.756 0-.965.79-1.756 1.756-1.756h4.21zm-4.196-4.062a2.291 2.291 0 1 0 0 4.585h4.746v-4.585z"/></g></g></g></g></g><g><g><g><g><g><path
                                              fill="#fff"
                                              d="M23.918 23.698c0 .375-.308.67-.67.67a.672.672 0 0 1-.67-.67c0-.362.308-.67.67-.67.362 0 .67.294.67.67z"/></g></g></g></g></g><g><g><path
                                              fill="#fff"
                                              d="M26.546 21.674v-2.32a3.127 3.127 0 0 0-3.124-3.123H7.295V31.5h16.127a3.127 3.127 0 0 0 3.124-3.124v-2.64h-.536v2.64a2.589 2.589 0 0 1-2.588 2.588H7.832V16.767h15.59a2.589 2.589 0 0 1 2.588 2.587v2.32z"/></g><g><path
                                              fill="#fff" d="M18.194 18.121H9.936v.536h12.628v-.536h-3.62z"/></g></g><g><g><g><g><g><path
                                              fill="#fff" d="M9.936 29.462v-.536h12.628v.536z"/></g></g></g></g></g></g></g></g></svg>
                                    </span>
                                    Transferir Dólares
                                </button>
                            </div>
                        </div>
                    </aside>
                </div>
                <input type="hidden" name="_token" :value="csrf">
            </form>
        </div>
    </div>
</template>

<style lang="scss">
    .total-disabled {
        background-color: #e2e2e2;
        box-shadow: 0 1px 2px 0 rgba(173, 173, 173, 0.2);
        color: #999;
        pointer-events: none;

        * {
            pointer-events: none;
        }
    }

    .disabled {
        pointer-events: none;
        position: relative;

        * {
            pointer-events: none;
        }
    }
</style>
