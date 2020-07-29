<template>
    <div class="row">
        <div class="col-6 border-right border-secondary">
            <div class="d-flex justify-content-center wow fadeInUp">
                <div class="media flex-column flex-lg-row justify-content-center justify-content-lg-start align-items-center align-items-lg-start">
                    <div>
                        <img src="img/landing/bitcoinSell-secondary.svg" alt="Sell Bitcoin Icon"
                             class="marketRate__img img-fluid mr-lg-4 mb-3 my-lg-auto">
                    </div>
                    <div class="media-body text-center text-md-left marketRate__text">
                        <h5 class="font-weight-bold mb-0 mb-md-2">{{lang.sell_btc}} <i
                                class="fa fa-angle-right ml-2 d-none d-md-inline-block"></i></h5>
                        <h2 class="mb-0">$ <span class="usdPriceBtcSale">0.00</span></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="d-flex justify-content-center wow fadeInUp">
                <div class="media flex-column flex-lg-row justify-content-center justify-content-lg-start align-items-center align-items-lg-start">
                    <div>
                        <img src="img/landing/bitcoinBuy-secondary.svg" alt="Sell Bitcoin Icon"
                             class="marketRate__img img-fluid mr-lg-4 mb-3 my-lg-auto">
                    </div>
                    <div class="media-body text-center text-md-left marketRate__text">
                        <h5 class="font-weight-bold mb-0 mb-md-2">{{lang.buy_btc}} <i
                                class="fa fa-angle-right ml-2 d-none d-md-inline-block"></i></h5>
                        <h2 class="mb-0">$ <span class="usdPriceBtcBuy">0.00</span></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as numeral from "numeral";

    export default {
        props: ['lang'],

        data() {
            return {
                salePrice: 0,
                buyPrice: 0
            }
        },
        computed: {},
        methods: {
            //Dynamic Prices
            firstCall() {
                $.get('https://www.bitstamp.net/api/ticker/', function (data) {
                    let salePrice = parseFloat(data.last) - (parseFloat(data.last) * 1 / 100),
                        buyPrice  = parseFloat(data.last) + (parseFloat(data.last) * 4 / 100);
                    $('.usdPriceBtcSale').text(salePrice.toFixed(2));
                    $('.usdPriceBtcBuy').text(buyPrice.toFixed(2));
                });
            },
            initWebsocket() {
                let vueObject = this,
                    subscribeMsg = {
                        "event": "bts:subscribe",
                        "data": {
                            "channel": "live_trades_btcusd"
                        }
                    },
                    ws           = new WebSocket("wss://ws.bitstamp.net");

                ws.onopen = function () {
                    ws.send(JSON.stringify(subscribeMsg));
                };

                ws.onmessage = function (evt) {
                    let response = JSON.parse(evt.data);
                    /**
                     * This switch statement handles message logic. It processes data in case of trade event
                     * and it reconnects if the server requires.
                     */
                    switch (response.event) {
                        case 'trade': {
                            let salePrice = response.data.price - (response.data.price * 1 / 100),
                                buyPrice  = response.data.price + (response.data.price * 4 / 100);
                            $('.usdPriceBtcSale').text(numeral(salePrice.toFixed(2)).format('0,0.00'));
                            $('.usdPriceBtcBuy').text(numeral(buyPrice.toFixed(2)).format('0,0.00'));
                            break;
                        }
                        case 'bts:request_reconnect': {
                            vueObject.initWebsocket();
                            break;
                        }
                    }

                };
                /**
                 * In case of unexpected close event, try to reconnect.
                 */
                ws.onclose = function () {
                    console.log('Websocket connection closed');
                    vueObject.initWebsocket();
                };

            }
        },
        updated: function () {
        },
        watch: {},
        mounted() {
            this.$nextTick(function () {
                this.initWebsocket();
                this.firstCall();
            });
        }
    }
</script>