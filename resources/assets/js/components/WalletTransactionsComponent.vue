<script src="../walletTransactionsJS.js"></script>

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
            <!--<div class="col-3 col-md-3 col-xl-2 px-1">
                <a href="#!" class="cardBank mb-2 mb-md-0px-1" v-on:click="changeCurrency('BTC')"
                   :class="{'&#45;&#45;active': (currency === 'BTC')}">
                    <img src="/img/landing/wallet-btc-primary.svg" alt="BTC Icon" class="d-inline-block mb-2">
                    <h6 class="cardBank__title">Bitcoin</h6>
                    <div class="cardBank__info">0.00000000 BTC</div>
                </a>
            </div>-->
        </div>

        <nav id="wallets-nav" class="nav nav-fill flex-nowrap align-items-center border-top border-bottom bg-white mx-n3 mx-md-0 sticky-top">
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3 border-right active" :href="transactionsUrl">
                <div>Historial</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3" :href="chargeUrl">
                <img src="img/landing/recharge-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Cargar {{currencyName}}</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3" :href="WithdrawUrl">
                <img src="img/landing/withdraw-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Retirar {{currencyName}}</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3" :href="TransferUrl">
                <img src="img/landing/transfer-blue.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Transferir {{currencyName}}</div>
            </a>
        </nav>

        <!-- transactions history -->
        <div class="d-flex justify-content-between mt-4 mb-3">
            <h5 class="text-primary font-weight-bold">Historial de transacciones</h5>
            <div class="d-flex">
                <!--<div class="input-group mx-2">
                    <input type="date" class="form-control" placeholder="Fecha" style="max-width: 150px">
                    <div class="input-group-append">
                      <span class="input-group-text text-muted">
                          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="19"
                               height="18" viewBox="0 0 19 18"><defs>
                              <path id="zvx4a"
                                    d="M318.766 687.386h-16.579v-11.667h16.58zm-4.022-15.658a1.538 1.538 0 0 0-1.504-1.228.307.307 0 1 0 0 .614.922.922 0 0 1 0 1.842.307.307 0 1 0 0 .614c.741 0 1.361-.528 1.504-1.228h4.022v2.763h-16.579v-2.763h5.219a.307.307 0 1 0 0-.614h-.557a.92.92 0 0 1 1.786.307.922.922 0 0 1-.921.921.307.307 0 1 0 0 .614c.846 0 1.535-.688 1.535-1.535 0-.846-.689-1.535-1.535-1.535-.742 0-1.362.528-1.504 1.228h-4.637V688h17.807v-16.272z"/>
                              <path
                                  id="zvx4b"
                                  d="M309.556 671.114a.922.922 0 0 1 0 1.842.307.307 0 1 0 0 .614c.846 0 1.535-.688 1.535-1.535 0-.846-.689-1.535-1.535-1.535a.307.307 0 1 0 0 .614z"/>
                              <path
                                  id="zvx4c"
                                  d="M311.398 671.114a.922.922 0 0 1 0 1.842.307.307 0 1 0 0 .614c.846 0 1.535-.688 1.535-1.535 0-.846-.689-1.535-1.535-1.535a.307.307 0 1 0 0 .614z"/><path
                              id="zvx4d" d="M308.02 677.868a.307.307 0 1 1 .615 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4e" d="M310.17 677.868a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4f" d="M312.319 677.868a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4g" d="M314.468 677.868a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4h" d="M316.617 677.868a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4i" d="M303.722 680.325a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4j" d="M305.872 680.325a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4k" d="M308.02 680.325a.307.307 0 1 1 .615 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4l" d="M310.17 680.325a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4m" d="M312.319 680.325a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4n" d="M314.468 680.325a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4o" d="M316.617 680.325a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4p" d="M303.722 682.474a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4q" d="M305.872 682.474a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4r" d="M308.02 682.474a.307.307 0 1 1 .615 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4s" d="M310.17 682.474a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4t" d="M312.319 682.474a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4u" d="M314.468 682.474a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4v" d="M316.617 682.474a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4w" d="M303.722 684.93a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4x" d="M305.872 684.93a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4y" d="M308.02 684.93a.307.307 0 1 1 .615 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4z" d="M310.17 684.93a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/><path
                              id="zvx4A" d="M312.319 684.93a.307.307 0 1 1 .614 0 .307.307 0 0 1-.614 0z"/></defs><g><g
                              transform="translate(-301 -670)"><g><use fill="#303392" xlink:href="#zvx4a"/></g><g><use
                              fill="#303392" xlink:href="#zvx4b"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4c"/></g><g><use
                              fill="#303392" xlink:href="#zvx4d"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4e"/></g><g><use
                              fill="#303392" xlink:href="#zvx4f"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4g"/></g><g><use
                              fill="#303392" xlink:href="#zvx4h"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4i"/></g><g><use
                              fill="#303392" xlink:href="#zvx4j"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4k"/></g><g><use
                              fill="#303392" xlink:href="#zvx4l"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4m"/></g><g><use
                              fill="#303392" xlink:href="#zvx4n"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4o"/></g><g><use
                              fill="#303392" xlink:href="#zvx4p"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4q"/></g><g><use
                              fill="#303392" xlink:href="#zvx4r"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4s"/></g><g><use
                              fill="#303392" xlink:href="#zvx4t"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4u"/></g><g><use
                              fill="#303392" xlink:href="#zvx4v"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4w"/></g><g><use
                              fill="#303392" xlink:href="#zvx4x"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4y"/></g><g><use
                              fill="#303392" xlink:href="#zvx4z"/></g><g><use fill="#303392"
                                                                              xlink:href="#zvx4A"/></g></g></g></svg>
                      </span>
                    </div>
                </div>-->
                <div class="input-group">
                    <input type="text"
                           id="creation-date-filter"
                           class="form-control"
                           aria-label="Creation date filter"
                           aria-describedby="creation-date-filter">
                    <div class="input-group-append">
                        <span class="input-group-text bg-white text-muted">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <hr class="d-md-none mb-3">
        <h6 class="text-primary font-weight-bold mt-4 mt-md-5 mb-2" v-if="inHoldTransactions.length">En espera</h6>
        <ul class="walletHistory list-unstyled">
            <!--In Hold-->
            <li class="walletHistory__item mb-2" v-for="transaction in inHoldTransactions">
                <div class="walletHistory__item__body">
                    <div class="media">
                        <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                             title="Salida en espera"
                             v-if="transaction.purpose === 2">
                            <svg width="25" height="21" viewBox="0 0 25 21" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 13L14 1M14 1H7.14286M14 1V7" stroke="#FFC107" stroke-width="2"
                                      stroke-linecap="square"/>
                                <path d="M12 19.7877C12 19.7877 15 17.1141 18 18.7877C21 20.4612 24 17.7877 24 17.7877"
                                      stroke="#FFC107" stroke-width="2"/>
                                <path d="M12 15C12 15 15 12.3265 18 14C21 15.6735 24 13 24 13" stroke="#FFC107"
                                      stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                             title="Entrada en espera"
                             v-else>
                            <svg width="24" height="22" viewBox="0 0 24 22" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M13 2L1 14M1 14H7.85714M1 14V8" stroke="#FFC107" stroke-width="2"
                                      stroke-linecap="square"/>
                                <path d="M11.0002 20.7877C11.0002 20.7877 14.0002 18.1141 17.0002 19.7877C20.0002 21.4612 23.0002 18.7877 23.0002 18.7877"
                                      stroke="#FFC107" stroke-width="2"/>
                                <path d="M11.0002 16C11.0002 16 14.0002 13.3265 17.0002 15C20.0002 16.6736 23.0002 14 23.0002 14"
                                      stroke="#FFC107" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="media-body">
                            <div class="d-flex justify-content-between"
                                 v-if="transaction.purpose === 2">
                                <h5 class="walletHistory__item__amount mb-0">
                                    - {{formatMoney(transaction['sender_fiat_amount'])}}
                                    {{transaction['sender_fiat']}}
                                    <small>
                                        => {{formatMoney(transaction['receiver_fiat_amount'])}}
                                        {{transaction['receiver_fiat']}}
                                    </small>
                                </h5>
                                <div class="walletHistory__item__date text-muted">
                                    {{formatDate(transaction['created_at'])}}
                                </div>
                            </div>
                            <div class="d-flex justify-content-between"
                                 v-else>
                                <h5 class="walletHistory__item__amount mb-0">
                                    + {{formatMoney(transaction['sender_fiat_amount'])}}
                                    {{transaction['sender_fiat']}}
                                    <small>
                                        => {{formatMoney(transaction['receiver_fiat_amount'])}}
                                        {{transaction['receiver_fiat']}}
                                    </small>
                                </h5>
                                <div class="walletHistory__item__date text-muted">
                                    {{formatDate(transaction['created_at'])}}
                                </div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="text-uppercase"
                                     v-if="transaction['exchange_related'] === 1 && transaction.purpose === 2">
                                    Envío en espera a
                                    <span class="font-weight-bold">
                                        {{transaction['related_wallet_transaction']['related_transaction']['destination_account']['name']}}
                                        {{transaction['related_wallet_transaction']['related_transaction']['destination_account']['lastname']}}
                                    </span>
                                </div>
                                <div class="text-uppercase"
                                     v-else-if="!transaction['exchange_related'] && transaction.purpose === 2">
                                    Retiro en espera a la cuenta de
                                    <span class="font-weight-bold">
                                        {{transaction['related_transaction']['destination_account']['name']}}
                                        {{transaction['related_transaction']['destination_account']['lastname']}}
                                    </span>
                                </div>
                                <div class="text-uppercase"
                                     v-else>
                                    Recarga en espera de aprobación
                                </div>
                                <div v-if="transaction.related_transaction">
                                    Tracking ID:
                                    <a class="text-primary font-weight-bold"
                                       :href="'/transaction-success/' + transaction.related_transaction.id">
                                        {{transaction['related_transaction']['tracking_id']}}<i
                                            class="fa fa-angle-right ml-1"></i>
                                    </a>
                                </div>
                                <div v-if="!transaction['exchange_related'] && transaction.purpose === 1">
                                    Tracking ID:
                                    <a class="text-primary font-weight-bold"
                                       :href="'/wallets/details/' + transaction.id">
                                        {{transaction['tracking_id']}}
                                        <i class="fa fa-angle-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <hr class="my-4 my-md-5">
        <div v-for="(yearArray, yearNumber) in effectiveTransactions"
             class="loader--wrapper"
             :class="{ '--loading': loader }">
            <ul class="walletHistory list-unstyled" v-for="(monthArray, monthNumber) in yearArray">
                <!--End In Hold-->
                <li class="font-13 text-muted mt-4 mt-md-5 mb-2"
                    style="text-transform: capitalize">{{getHumanMonth(monthNumber)}} {{yearNumber}}
                </li>
                <li class="walletHistory__item mb-2" v-for="transaction in monthArray">
                    <div class="walletHistory__item__body">
                        <div class="media">
                            <!--Conditional Icon-->
                            <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                                 title="Envío"
                                 v-if="(transaction.type === 2 && transaction.status === 1) ||
                                 (transaction.type === 4 && transaction.purpose === 2)">
                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 16.2727L17.2727 1M17.2727 1H8.54545M17.2727 1V8.63636" stroke="#38A1DC"
                                          stroke-width="2" stroke-linecap="square"/>
                                </svg>
                            </div>
                            <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                                 title="Recibido"
                                 v-if="(transaction.type === 1 && transaction.status === 1) ||
                                 (transaction.type === 4 && transaction.purpose === 1)">
                                <svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.6364 2.36362L1.36362 17.6364M1.36362 17.6364H10.0909M1.36362 17.6364V9.99999"
                                          stroke="#1DBA44" stroke-width="2" stroke-linecap="square"/>
                                </svg>
                            </div>
                            <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                                 title="Transacción rechazada"
                                 v-if="transaction.status === 3">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 15L15 1" stroke="#DC3545" stroke-width="2"/>
                                    <path d="M15 15L1 1" stroke="#DC3545" stroke-width="2"/>
                                </svg>
                            </div>
                            <!--End Conditional Icon-->
                            <div class="media-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="walletHistory__item__amount mb-0"
                                        :class="transaction.status === 3 ? 'text-strike' : ''">
                                        <!--Out Symbol-->
                                        {{(transaction.purpose !== null && transaction.purpose === 2) ||
                                        (transaction.purpose === null && transaction.type === 2) ? '-' : ''}}
                                        <!--In Symbol-->
                                        {{(transaction.purpose !== null && transaction.purpose === 1) ||
                                        (transaction.purpose === null && transaction.type === 1) ? '+' : ''}}
                                        <!--Out-->
                                        <span v-if="(transaction.purpose !== null && transaction.purpose === 2) ||
                                        (transaction.purpose === null && transaction.type === 2)">
                                            {{formatMoney(transaction['amount'])}}
                                            {{transaction['currency']}}
                                            <small v-if="transaction['receiver_fiat_amount']">
                                                => {{formatMoney(transaction['receiver_fiat_amount'])}}
                                                {{transaction['receiver_fiat']}}
                                            </small>
                                        </span>
                                        <!--In-->
                                        <span v-if="(transaction.purpose !== null && transaction.purpose === 1) ||
                                        (transaction.purpose === null && transaction.type === 1)">
                                            <span v-if="transaction['sender_fiat_amount']">
                                                {{formatMoney(transaction['sender_fiat_amount'])}}
                                                {{transaction['sender_fiat']}}
                                            </span>
                                            <span v-else>
                                                {{formatMoney(transaction['amount'])}}
                                                {{transaction['currency']}}
                                            </span>
                                            <small v-if="transaction.exchange_related !== 1">
                                                => {{formatMoney(transaction['receiver_fiat_amount'])}}
                                                {{transaction['receiver_fiat']}}
                                            </small>
                                        </span>
                                    </h5>
                                    <div class="walletHistory__item__date text-muted">
                                        {{formatDate(transaction['created_at'])}}
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="text-uppercase"
                                         v-if="transaction.related_transaction &&
                                         (transaction.purpose === 2 || transaction.type === 2)">
                                        Envío a
                                        <span class="font-weight-bold">
                                            {{transaction['related_transaction']['destination_account']['name']}}
                                            {{transaction['related_transaction']['destination_account']['lastname']}}
                                        </span>
                                    </div>
                                    <div class="text-uppercase"
                                         v-else-if="transaction.type === 4 && transaction.purpose === 2">
                                        Envío a
                                        <span class="font-weight-bold">
                                            {{transaction['metadata']['related_email']}}
                                        </span>
                                        <span v-if="transaction['metadata']['description']">
                                            <br>
                                            Descripción: {{transaction['metadata']['description']}}
                                        </span>
                                    </div>
                                    <div class="text-uppercase"
                                         v-else-if="transaction.type === 4 && transaction.purpose === 1">
                                        Recibido desde
                                        <span class="font-weight-bold">
                                            {{transaction['metadata']['related_email']}}
                                        </span>
                                        <span v-if="transaction['metadata']['description']">
                                            <br>
                                            Descripción: {{transaction['metadata']['description']}}
                                        </span>
                                    </div>
                                    <div class="text-uppercase"
                                         v-else-if="transaction.type === 1 && transaction.exchange_related === 0">
                                        Recarga a Billetera personal.
                                    </div>
                                    <div class="text-uppercase" v-else></div>
                                    <div v-if="transaction.related_transaction">
                                        Tracking ID:
                                        <a class="text-primary font-weight-bold"
                                           :href="'/transaction-success/' + transaction.related_transaction.id">
                                            {{transaction['related_transaction']['tracking_id']}}<i
                                                class="fa fa-angle-right ml-1"></i>
                                        </a>
                                    </div>
                                    <div v-if="transaction.type === 4">
                                        Tracking ID:
                                        <a class="text-primary font-weight-bold"
                                           :href="'/wallets/details/' + transaction.id">
                                            {{transaction['tracking_id']}}
                                            <i class="fa fa-angle-right ml-1"></i>
                                        </a>
                                    </div>
                                    <div v-else-if="!transaction['exchange_related'] && transaction.tracking_id">
                                        Tracking ID:
                                        <a class="text-primary font-weight-bold"
                                           :href="'/wallets/details/' + transaction.id">
                                            {{transaction['tracking_id']}}
                                            <i class="fa fa-angle-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="text-danger font-weight-bold"
                                     v-if="transaction.status === 3">
                                    Su operación fue rechazada o cancelada.
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</template>

<style lang="scss">

</style>
