import * as lodash from "lodash";
import * as numeral from "numeral";
import axios from "axios";

let request = null;

export default {

    props: {
        wallets: String,
        user: String,
        //From Add Card
        transTracking: String,
        userActiveWallet: String,
        qbpay: String
    },

    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            currency: 'USD',
            currency_names: {
                'USD': 'Dólares',
                'BTC': 'Bitcoins',
                'GBP': 'Libras',
                'EUR': 'Euros',
            },
            disableButton: false,
            //From Send Money
            payment_method: '',
            payment_method_country: '',
            formUrl: '',
            recomended_price: '',
            original_price: '',
            payment_total: '',
            forbiddenByAmount: 2,
            forbiddenByHours: 2, //Two is Simple. It's forbidden with without message.
            forbiddenChat: 2, //Two is Simple. It's forbidden with without message.
            currencies: {
                "Chile": "CLP",
                "Venezuela": "VES",
                "Argentina": "ARS",
                "Colombia": "COP",
                "Peru": "PEN",
                "Mexico": "MXN",
                "Dominican Republic": "DOP",
                "Costa Rica": "CRC",
                "Panama": "PAB",
                "United States": "USD",
                "Canada": "CAD",
                "Spain": "EUR",
                "Portugal": "EUR",
                "Italy": "EUR",
                "France": "EUR",
                "United Kingdom": "GBP",
            },
            to_send: '',
            fees: 0,
            sender_country: 'Venezuela',
            receiver_country: 'United States',
            getPriceInterval: null,
            no_viable_country: false,
            price_data_id: null,
            loadingSend: true,
            noDuplicate: false,
            usd_ves_price: 0,
            open_rates: [],
            force_loading: false
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
            return window.location.origin + '/wallets?identity=' + this.activeWallet.hash;
        },

        chargeUrl: function () {
            return window.location.origin + '/wallets/charge?identity=' + this.activeWallet.hash;
        },

        sendUrl: function () {
            return window.location.origin + '/wallets/send?identity=' + this.activeWallet.hash;
        },

        /**
         * @return {string}
         */
        TransferUrl: function () {
            return window.location.origin + '/wallets/transfer?identity=' + this.activeWallet.hash;
        },

        /**
         * @return {string}
         */
        WithdrawUrl: function () {
            return window.location.origin + '/wallets/withdraw?identity=' + this.activeWallet.hash;
        },

        currencyName: function () {
            return this.currency_names[this.currency];
        },
        //From Add Card
        userObject: function () {
            return JSON.parse(this.user);
        },
        sender: function () {
            return this.currencies[this.sender_country];
        },
        receiver: function () {
            return 'USD';
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

            if (this.payment_method === 'card') {
                this.calculateFees();
            }
            return numeral(this.to_receive).format('0,0.[00]');
        },
        minAmountBoolean: function () {
            if (this.sender === 'USD' && this.to_send >= 15) {
                return true;
            } else if (this.sender !== 'VES' && this.to_send / this.open_rates[this.sender] >= 15) {
                return true;
            } else if (this.sender === 'VES' && Number.parseFloat(this.to_send * this.usd_ves_price) >= 15.00) {
                return true;
            }
            return false;
        },
    },

    methods: {
        /**
         * Verify and format the input.
         *
         * @param event
         */
        verifyMaxTransfer(event) {
            let vueObject    = this,
                intentAmount = 0;

            vueObject.disableButton = true;
            vueObject.loadingSend   = true;

            if (event.type === 'keyup') {
                vueObject.formatCurrency($(event.target));
                intentAmount = this.parseValue($(event.target).val());
            } else {
                vueObject.formatCurrency($(event.target), 'blur');
                intentAmount = this.parseValue($(event.target).val());
            }

            vueObject.transferAmount = $(event.target).val();
            vueObject.to_send        = intentAmount;
            $('#sending-amount').val(intentAmount);

            // if (intentAmount > 100 && this.userObject.profileCompletition !== 1) {
            //     $(event.target).siblings('.invalid-feedback').slideDown();
            //     vueObject.forbiddenByAmount = 1;
            // } else {
            $(event.target).siblings('.invalid-feedback').slideUp();
            vueObject.forbiddenByAmount = 0;
            // }

            clearInterval(this.getPriceInterval);
            this.getPriceInterval = setInterval(function () {
                vueObject.getPrice();
                clearInterval(vueObject.getPriceInterval)
            }, 2000);
        },
        changeCurrency(val) {
            this.currency = val;
        },
        formatMoney(amount) {
            return numeral(amount).format('0,0.00');
        },
        formatDate(date) {
            let current_datetime = new Date(date);
            return this.appendLeadingZeroes((current_datetime.getMonth() + 1)) + '-' + this.appendLeadingZeroes(current_datetime.getDate()) + '-' + current_datetime.getFullYear();
        },
        appendLeadingZeroes(n) {
            if (n <= 9) {
                return "0" + n;
            }
            return n
        },
        parseValue(value) {
            let floatValue = parseFloat(value.replace(/,/g, ''));

            if (!isNaN(floatValue)) {
                return floatValue;
            }

            return 0;
        },
        formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        formatCurrency(input, blur) {
            let input_val = input.val();

            if (input_val === "") {
                return;
            }

            let original_len = input_val.length,
                caret_pos    = input.prop("selectionStart");

            if (input_val.indexOf(".") >= 0) {

                let decimal_pos = input_val.indexOf("."),
                    left_side   = input_val.substring(0, decimal_pos),
                    right_side  = input_val.substring(decimal_pos);

                left_side  = this.formatNumber(left_side);
                right_side = this.formatNumber(right_side);

                if (blur === "blur") {
                    right_side += "00";
                }

                right_side = right_side.substring(0, 2);
                input_val  = left_side + "." + right_side;

            } else {
                input_val = this.formatNumber(input_val);
                if (blur === "blur") {
                    input_val += ".00";
                }
            }
            input.val(input_val);

            let updated_len = input_val.length;

            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        },
        //From Add card
        getUrl(variable, value) {
            let fullUrl = new URL(window.location.href);
            fullUrl.searchParams.append(variable, value);

            return fullUrl;
        },
        calculateFees(country) {
            this.disableButton = true;
            let vueObject      = this;
            $.ajaxSetup({cache: false}); // assures the cache is empty
            if (request != null) {
                request.abort();
                request = null;
            }
            request = $.get('/get-fees?sendCurrency=' + this.sender + '&receiveCurrency=' + this.receiver
                + '&amount=' + this.to_send + '&receive=' + this.to_receive + '&cardCountry=' + country,
                function (data) {
                    vueObject.disableButton = false;
                    vueObject.fees          = data[0];
                    vueObject.payment_total = data[1];
                });
        },
        onPaymentMethodUpdate(paymentMethod) {
            this.payment_method = paymentMethod;

            if (paymentMethod === 'cash') {
                this.payment_total -= this.fees;
                this.fees    = 0;
                this.formUrl = this.getUrl('paymentMethod', 'cash');
            }

            if (paymentMethod === 'not_completed') {
                this.payment_method = '';
                this.payment_total -= this.fees;
                this.fees           = 0;
                this.formUrl        = this.getUrl('');
            }

            if (Array.isArray(paymentMethod)) {
                this.payment_method         = paymentMethod[1].id;
                this.payment_method_country = paymentMethod[1].country;
                this.calculateFees(paymentMethod[1].country);
                this.formUrl = this.getUrl('paymentMethod', 'card');
            }

            if (paymentMethod !== 'cash' && !Array.isArray(paymentMethod)) {
                this.payment_total -= this.fees;
                this.fees    = 0;
                this.formUrl = this.getUrl('paymentMethod', paymentMethod);
            }
        },
        getPrice() {
            this.disableButton          = true;
            this.force_loading          = true;
            this.payment_method         = '';
            this.payment_method_country = '';
            /**
             * Get price will Lock the operation if the user have no a complete profile
             */
            axios.get(window.location.origin + '/wallets-get-price', {
                params: {
                    amount: this.to_send,
                    sender: this.sender,
                    receiver: this.receiver,
                    sender_country: this.sender_country,
                    receiver_country: this.receiver_country,
                }
            })
                .then(
                    re => {
                        if (this.to_send !== '' && this.to_send > 0) {
                            this.disableButton = false;
                        }
                        this.recomended_price = re.data[0];
                        this.original_price   = re.data['original'];
                        // if (re.data[1] > 100 && this.userObject.profileCompletition !== 1) {
                        //     this.forbiddenByAmount = 1;
                        // } else {
                        this.forbiddenByAmount = 0;
                        // }

                        // if (this.payment_method_country !== '') {
                        //     this.calculateFees(this.payment_method_country);
                        // }

                        let noViable = $('#sm-origen option[value^="' + this.sender_country + '"]')
                            .attr('data-nb') ? $('#sm-origen option[value^="' + this.sender_country + '"]')
                            .attr('data-nb') : null;

                        this.force_loading     = false;
                        this.no_viable_country = noViable !== null;
                        this.loadingSend       = false;
                        this.price_data_id     = re.data[2];
                    }
                )
        },
        recommendedUtility() {
            if (this.recomended_price < 1) {
                return numeral(1 / this.recomended_price).format('0,0.[00]');
            }

            return numeral(this.recomended_price).format('0,0.[00]');
        },
        getCountry(iso2) {
            $.each(this.countriesJson, function (index, value) {
                if (value.code === iso2) {
                    return value.name;
                }
            });
        },
        formatMoneyNumeral(amount) {
            return numeral(amount).format('0,0.[00]');
        },
        submitForm() {
            this.noDuplicate = true;
            this.$refs.form.submit();
        },
        getCountriesData() {
            return new Promise((resolve, reject) => {
                axios.get(window.location.origin + '/get-op-countries').then( //?without[]=United+States').then(
                    re => {
                        this.currencies = re.data[0];
                        $('#sm-origen').html(re.data[1]);
                        resolve();
                    }
                )
            });
        },
        checkHours() {
            axios.get(window.location.origin + '/check-hours').then(
                re => {
                    this.forbiddenByHours = re.data['close'];
                    this.forbiddenChat    = re.data['close_support_chat'];
                }
            );
        },
        getVesUsdPrice() {
            axios.get(window.location.origin + '/get-price', {
                params: {
                    amount: 0,
                    sender: 'VES',
                    receiver: 'USD',
                    sender_country: 'Venezuela',
                    receiver_country: 'United States',
                }
            })
                .then(
                    re => {
                        this.usd_ves_price = re.data[0];
                    }
                )
        },
        getOpenExchangeRates() {
            axios.get(window.location.origin + '/exchange-rates'
            ).then(
                re => {
                    this.open_rates = re.data.rates;
                }
            )
        }
    },
    mounted() {
        let vueObject = this;
        this.$nextTick(function () {
            vueObject.checkHours();
            this.getCountriesData().then(function () {
                vueObject.getPrice();
                vueObject.getVesUsdPrice();
                vueObject.getOpenExchangeRates();
            });
            // this.checkHours();
        });
    },
    updated() {
        // Fired every second, should always be true
        // console.log(this.payment_method);
        // console.log(this.disableButton);
        // console.log(this.forbiddenByAmount);
        // console.log(this.forbiddenByHours);
        // console.log(this.minAmountBoolean);
    },
}
