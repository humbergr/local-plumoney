import Slick from 'vue-slick';
import * as numeral from "numeral";

export default {
    name: 'slick-slider',

    props: {
        wallets: String,
        userActiveWallet: String
    },

    components: {
        Slick
    },

    data() {
        return {
            currency: 'USD',
            currency_names: {
                'USD': 'Dólares',
                'BTC': 'Bitcoins',
                'GBP': 'Libras',
                'EUR': 'Euros',
            },
            slickOptions: {
                dots: true,
                speed: 300,
                infinite: false,
                autoplay: false,
                slidesToShow: 4,
                slidesToScroll: 3,
                appendArrows: $('#recipients-slider-arrows'),
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            arrows: false,
                            slidesToShow: 3,
                            slidesToScroll: 2,
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            arrows: false,
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        }
                    }
                ]
            },
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            maxTransferAmount: 0,
            transferAmount: 0,
            validAmount: 0
        }
    },

    computed: {
        walletsObject: function () {
            return JSON.parse(this.wallets);
        },

        activeWallet: function () {
            let activeWallet       = JSON.parse(this.userActiveWallet);
            this.maxTransferAmount = activeWallet.numbers.available;
            return activeWallet;
        },

        transactionsUrl: function () {
            return window.location.origin + '/wallets?currency=' + this.currency;
        },

        chargeUrl: function () {
            return window.location.origin + '/charge?currency=' + this.currency;
        },

        sendUrl: function () {
            return window.location.origin + '/send?currency=' + this.currency;
        },

        TransferUrl: function () {
            return window.location.origin + '/transfer?currency=' + this.currency;
        },

        WithdrawUrl: function () {
            return window.location.origin + '/withdraw?currency=' + this.currency;
        },

        currencyName: function () {
            return this.currency_names[this.currency];
        }
    },

    methods: {
        changeCurrency(val) {
            this.currency = val;
        },
        formatMoney(amount) {
            return numeral(amount).format('0,0.00');
        },
        appendLeadingZeroes(n) {
            if (n <= 9) {
                return "0" + n;
            }
            return n
        },
    }

}