<template lang="html">
    <div>
        <vue-toastr ref="toastr"></vue-toastr>
        <section id="advertisements" class="py-section-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 mx-auto">
                        <div class="text-center mb-5">
                            <h3 class="font-weight-bold">Advertisements</h3>
                            <h5 class="font-weight-lighter">Search advertisements in the whole world</h5>
                        </div>
                        <div class="card mb-4">
                            <div class="btn-group btn-group-toggle">
                                <button v-on:click="sellBtc" class="btn btn-outline-secondary py-3 rounded-0"
                                        :class="{active: (operation == 'sell')}">
                                    Sell Bitcoins
                                </button>
                                <button v-on:click="buyBtc" class="btn btn-outline-secondary py-3 rounded-0"
                                        :class="{active: (operation == 'buy')}">
                                    Buy Bitcoins
                                </button>
                            </div>
                            <div class="card-body px-md-5">
                                <form class="d-flex flex-column flex-md-row">
                                    <div class="form-group flex-grow-1 mr-3 mb-3 mb-md-0">
                                        <label for="select-country">Select Country</label>
                                        <model-select id="select-country" v-model="country" :options="countries" class="custom-select">
                                        </model-select>
                                    </div>

                                    <div class="form-group flex-grow-1 mr-3 mb-3 mb-md-0">
                                        <label for="select-bank">Bank</label>
                                        <input type="text" v-model="bank_string" v-on:change="regExp"
                                               class="form-control" name="" value="">

                                    </div>

                                    <div class="form-group flex-grow-1 mr-3 mb-3 mb-md-0">
                                        <label for="type-amount">Type Amount</label>
                                        <input type="text" v-model="amount" class="form-control" name="" value="">
                                    </div>

                                    <div class="mt-auto">
                                        <button type="button" v-on:click="getAdvertisements"
                                                class="btn btn-secondary btn-pill btn-block btn-md-inline-block px-3">
                                            Filter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <section id="adv-list">
                            <div class="loader--wrapper" :class="{ '--loading': loader }">
                                <div class="border-bottom mb-3">
                                </div>
                                <div class="row">
                                    <!-- waiting for trader banner -->
                                    <!--<div class="col-md-6">
                                        <div class="ad__waiting-banner--wrapper mb-4">
                                            <div class="ad__waiting-banner p-4 bg-secondary text-white">
                                                <img src="img/cb-img/ad-waiting-icon.svg"
                                                     class="waiting-trader img-fluid mb-4">
                                                <h6 class="mb-4">Waiting for your trader</h6>
                                                <h4 class="font-weight-bold mb-0">CoinBank USA</h4>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- advertisements list -->
                                    <div v-for="advertisement in advertisements" class="col-md-6">
                                        <div class="card mb-4">
                                            <a :href="advertisement.actions.public_view">
                                                <div class="ad__header card-header py-4 bg-primary text-white">
                                                    <h6 class="ad__user mb-2 mb-md-0">{{
                                                        advertisement.data.profile.username }}({{
                                                        advertisement.data.profile.feedback_score }}%, {{
                                                        advertisement.data.profile.trade_count }})</h6>
                                                    <h6 class="small mb-2 mb-md-0 ">
                                                        {{parseFloat(advertisement.data.temp_price).toLocaleString('en')}}
                                                        {{advertisement.data.currency}}/BTC</h6>
                                                </div>
                                            </a>
                                            <div class="card-body border-bottom">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="ad__desc mb-3 mb-md-0">Bank:
                                                            {{advertisement.data.bank_name}}</p>
                                                    </div>
                                                    <div class="col-md-5 offset-md-1">
                                                        <div class="ad__minmax text-md-right mb-md-4">
                                                            <small class="text-muted">Min.</small>
                                                            {{parseFloat(advertisement.data.min_amount).toLocaleString("en")}}
                                                        </div>
                                                        <div class="ad__minmax text-md-right">
                                                            <small class="text-muted">Max.</small>
                                                            {{parseFloat(advertisement.data.max_amount).toLocaleString("en")}}
                                                        </div>
                                                        <div class="ad__minmax text-md-right">
                                                            <small class="text-muted">Avail.</small>
                                                            {{parseFloat(advertisement.data.max_amount_available).toLocaleString("en")}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12"
                                                         v-if="advertisement.data.limit_to_fiat_amounts">
                                                        <small class="text-muted">Amounts.</small>
                                                        {{formatAmounts(advertisement.data.limit_to_fiat_amounts)}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body pb-3">
                                                <div class="row" v-if="advertisement.data.currency !== 'USD'">
                                                    <div class="col-sm-6 border-right">
                                                        <h6><span class="text-primary font-weight-bold">Rate:</span> {{
                                                            (Math.round((advertisement.data.temp_price / bitstamp) *
                                                            100) / 100).toLocaleString('en') }} {{advertisement.data.currency}}/USD</h6>
                                                    </div>
                                                    <div class="col-sm-6 text-md-right">
                                                        <h6><span class="text-primary font-weight-bold">Dollars:</span>
                                                            {{(Math.round(((advertisement.data.max_amount_available /
                                                            advertisement.data.temp_price) * bitstamp)) / 100 *
                                                            100).toLocaleString('en')}} USD</h6>
                                                    </div>
                                                </div>
                                                <div class="row" v-if="advertisement.data.currency !== 'USD'">
                                                    <div class="col-sm-12 border-top pt-2">
                                                        <h6><span class="text-primary font-weight-bold">bitstamp:</span>
                                                          {{bitstamp.toLocaleString('en')}} USD/BTC</h6>
                                                    </div>
                                                </div>
                                                <div class="row"
                                                     v-if="advertisement.data.currency !== 'USD' && advertisement.data.currency !== 'VES'">
                                                  <div class="col-sm-12 border-top pt-2">
                                                      <h6><span class="text-primary font-weight-bold">Central Bank Dollar rate:</span>
                                                        {{
                                                            (Math.round((advertisement.data.temp_price / open_rates[advertisement.data.currency]) *
                                                            100) / 100).toLocaleString('en')
                                                        }} USD/BTC</h6>
                                                  </div>
                                                </div>

                                                <div class="text-secondary text-center mt-3">
                                                    <button class="btn btn-secondary btn-pill btn-sm"
                                                            data-toggle="modal" data-target="#assign-modal" v-on:click="openAssignModal(advertisement)">
                                                        Assign
                                                    </button>
                                                </div>
                                                <!--    <div class="text-secondary text-center mt-3">
                                                        <small>Trader:</small> <span class="h6 font-weight-bold mb-0">Ignacio Salcedo</span>
                                                    </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="loader">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                         role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>

                                <div v-if="url != '' && !loader" class="text-center">
                                    <button class="btn btn-secondary btn-pill btn-sm" v-on:click="getNextPage">Get
                                        more
                                    </button>
                                </div>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>


        <!-- Modal -->
        <div class="modal fade" id="assign-modal" tabindex="-1" role="dialog" aria-labelledby="transactionsDetailsModal"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="text-primary font-weight-bold mb-4">Select a Customer Transaction for this contact:</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless table-selectable mb-4">
                                    <thead>
                                    <tr>
                                        <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Transaction</h5></th>
                                        <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">To Send</h5></th>
                                        <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">To Receive</h5></th>
                                        <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Rate</h5></th>
                                        <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Bank</h5></th>
                                        <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Date</h5></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="exchange in exchanges"
                                        v-if="exchange.receiver_fiat === modal_currency"
                                        :class="{'table-row--selected': exchange_ids.includes(exchange.id)}"
                                        style="cursor: pointer"
                                        v-on:click="selectExchange(exchange.id)">
                                        <th class="text-center">
                                            #{{exchange.tracking_id}}
                                        </th>
                                        <td class="text-center">
                                            {{exchange.sender_fiat}}
                                            {{parseFloat(exchange.sender_fiat_amount).toLocaleString('en')}}
                                        </td>
                                        <td class="text-center">
                                            {{exchange.receiver_fiat}}
                                            {{parseFloat(exchange.receiver_fiat_amount).toLocaleString('en')}}
                                        </td>
                                        <td class="text-center">
                                            {{exchange.exchange_rate}}
                                            {{exchange.sender_fiat}}/{{exchange.receiver_fiat}}
                                        </td>
                                        <td class="text-center">
                                            {{exchange.destination_account.bank_name}}
                                        </td>
                                        <td class="text-center">{{getDate(exchange.created_at)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <div class="row">
                            <div class="col-md-4 text-center">
                                <h5 class="text-primary font-weight-bold mb-0">Advertisement ID</h5>
                                <h5 class="text-secondary font-weight-bold">{{modal_ad}}</h5>
                            </div>
                            <div class="col-md-4 text-center" v-if="modal_ad_rate">
                                <h5 class="text-primary font-weight-bold mb-0">Advertisement rate</h5>
                                <h5 class="text-secondary font-weight-bold">{{modal_ad_rate}}</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 offset-md-5 text-center">
                                <button type="button" style="margin-top:19px" class="btn btn-secondary btn-pill btn-sm"
                                        data-dismiss="modal" name="button" v-on:click="assignTrader">Assign
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">Close
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import * as numeral from "numeral";

    import { ModelSelect } from 'vue-search-select'

    export default {
        components: {
          ModelSelect
        },

        props: [
            'countries',
            'traders',
            'initexchanges',
        ],

        data() {
            return {
                operation: 'buy',
                country: 'VE',
                advertisements: null,
                bank: '',
                amount: '',
                loader: false,
                url: '',
                bank_string: '',
                bitstamp: '',
                modal_ad: '',
                modal_ad_rate: '',
                modal_currency: '',
                open_rates: [],
                exchange_ids: [],
                exchanges: this.initexchanges,
            }
        },

        methods: {

            sellBtc() {
                this.operation = 'sell';
                this.getAdvertisements(this.country);
            },

            buyBtc() {
                this.operation = 'buy';
                this.getAdvertisements(this.country);
            },

            regExp() {
                this.bank = '(' + this.bank_string.toLowerCase().split('').join('.*') + ')i';
            },

            getAdvertisements() {
                this.loader = true;
                axios.get(window.location.origin + '/get-ads/' + this.country, {
                    params: {
                        bank: this.bank,
                        amount: this.amount,
                        operation: this.operation
                    }
                })
                    .then(
                        re => {
                            this.advertisements = re.data.ads;
                            var j               = Object.keys(this.advertisements).length;
                            this.url            = re.data.next_page;
                            if (j < 20) {
                                this.syncNetx(j);
                            } else {
                                this.loader = false;
                            }
                        }
                    )
            },

            getNextPage() {
                this.loader = true;
                var j       = 0;
                this.syncNetx(j);
            },

            selectExchange(id) {
                if (!this.exchange_ids.includes(id)) {
                    this.exchange_ids.push(id);
                    return;
                }

                this.exchange_ids = this.exchange_ids.filter(function(value, index, arr){
                    return value != id;
                })
            },

            syncNetx(count) {
                var j      = count;
                var lenght = Object.keys(this.advertisements).length;
                axios.get(window.location.origin + '/get-next/', {
                    params: {
                        bank: this.bank,
                        amount: this.amount,
                        url: this.url
                    }
                })
                    .then(
                        re => {
                            if (typeof re.data.error !== 'undefined') {
                                this.loader = false;
                            }
                            for (var i = 0; i < re.data.ads.length; i++) {
                                this.advertisements[lenght + i] = re.data.ads[i];
                                j++;
                            }
                            this.url = re.data.next_page;
                            if (j < 20) {
                                this.syncNetx(j);
                            } else {
                                this.loader = false;
                            }
                        }
                    )
            },

            getBitPrice() {
                axios.get(window.location.origin + '/api/bit-now')
                    .then(
                        re => {
                            this.bitstamp = re.data;
                        }
                    )
            },

            formatAmountValue(domElement) {
                let userInputVal = $(domElement.target).val(),
                    numeralVar   = numeral(userInputVal);

                this.modal_amount = numeralVar.value();
                $(domElement.target).val(numeralVar.format('0,0'));
            },

            assignTrader() {
                if (this.exchange_ids.length === 0) {
                    this.$refs.toastr.e('Please, select some exchange transaction.');
                    return;
                }
                axios.post(window.location.origin + '/open-contact', {
                    params: {
                        ad_id: this.modal_ad,
                        exchange_ids: this.exchange_ids,
                        _token: $('meta[name="csrf-token"]').attr("content")
                    }
                }).then(
                    re => {
                        if (re.data.status === 'error') {
                            const assign_response = JSON.parse(re.data.info);
                            let   that            = this;
                            const entries         = Object.keys(assign_response.error.errors);
                            that.$refs.toastr.e(assign_response.error.message);

                            entries.forEach(function(val){
                                that.$refs.toastr.e(val + ': ' + assign_response.error.errors[val]);
                            });

                        } else if (re.data.status === 'success'){
                            this.$refs.toastr.s('Contact has been created and assigned to you.');
                            this.exchanges = re.data.exchanges;
                            $('.modal-amount').val('');
                        }
                    }
                )
            },
            openAssignModal(advertisement) {
                this.modal_ad       = advertisement.data.ad_id;
                this.modal_currency = advertisement.data.currency;
                if (advertisement.data.currency === 'USD'){
                    this.modal_ad_rate = null;
                } else {
                    this.modal_ad_rate = (Math.round((advertisement.data.temp_price / this.bitstamp) *
                        100) / 100).toLocaleString('en') + advertisement.data.currency + '/USD';
                }
            },

            formatAmounts(amounts) {
                let amountsArray   = amounts.split(","),
                    formattedArray = [];

                amountsArray.forEach(function (currentValue, index) {
                    formattedArray.push(parseFloat(currentValue).toLocaleString("en"));
                });

                return formattedArray.join(' - ');
            },

            getOpenExchangeRates() {
              axios.get(window.location.origin + '/exchange-rates'
              ).then(
                  re => {
                      this.open_rates = re.data.rates;
                  }
              )
            },

            getDate(date) {
                let newDate = new Date(date);

                return (newDate.getMonth() + 1)
                    + '/' + newDate.getDate()
                    + '/' + newDate.getFullYear()
                    + ' - ' + (newDate.getHours() < 10 ? '0' : '') + newDate.getHours()
                    + ':' + (newDate.getMinutes() < 10 ? '0' : '') + newDate.getMinutes();
            },

        },

        mounted() {
            this.getBitPrice();
            this.getAdvertisements();
            this.getOpenExchangeRates();
        }

    }
</script>

<style lang="css">
</style>
