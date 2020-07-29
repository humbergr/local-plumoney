import * as numeral from "numeral";
import * as $ from "jquery";

window.$ = window.jQuery = $;
import * as moment from "moment";
import * as daterangepicker from "daterangepicker";

export default {

    props: {
        wallets: String
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
            startDate: null,
            endDate: null,
            loader: false,
            effectiveTransactions: [],
            inHoldTransactions: []
        }
    },
    mounted: function () {
        this.$nextTick(function () {
            // let vueObject = this;
            //
            // $.each(this.walletsObject, function (index, value) {
            //     if (value.currency === 'USD') {
            //         vueObject.activeWallet = value;
            //     }
            // });
            let vueObject  = this,
                startDate  = moment('2019-01-01'),
                endDate    = moment();
            this.startDate = startDate.format('YYYY-MM-D HH:mm:ss');
            this.endDate   = endDate.format('YYYY-MM-D HH:mm:ss');

            function cb(start, end) {
                if (start !== vueObject.startDate || end !== vueObject.endDate) {
                    vueObject.changeFilterDate(start, end);
                }
            }

            $('#creation-date-filter').daterangepicker({
                opens: 'center',
                startDate: startDate,
                endDate: endDate
            }, cb);

            cb(startDate, endDate);

            this.filterTransactions();
        });
    },
    computed: {
        walletsObject: function () {
            return JSON.parse(this.wallets);
        },

        activeWallet: function () {
            let activeWallet = null;
            $.each(this.walletsObject, function (index, value) {
                if (value.currency === 'USD') {
                    activeWallet = value;
                }
            });

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
        }
    },

    methods: {
        changeCurrency(val) {
            this.currency = val;
        },
        formatMoney(amount) {
            return numeral(amount).format('0,0.00');
        },
        secretHash(string) {
            let firstPart = string.slice(0, 7),
                lastPart  = string.slice(-4);

            return firstPart + '****' + lastPart;
        },
        formatDate(date) {
            return moment.utc(date).subtract({minutes: 300}).format('MM-DD-YYYY HH:mm:ss');
        },
        changeFilterDate(start, end) {
            this.startDate = start.format('YYYY-MM-D HH:mm:ss');
            this.endDate   = end.format('YYYY-MM-D HH:mm:ss');
            this.loader    = true;

            axios.get(window.location.origin + '/filter-wallets-transactions', {
                params: {
                    wallet_id: this.activeWallet.id,
                    start: this.startDate,
                    end: this.endDate,
                    zoneO: start.utcOffset()
                }
            }).then(re => {
                this.activeWallet.relatedTransactions = re.data;
                this.filterTransactions();
                this.loader = false;
            })
        },
        filterTransactions() {
            this.inHoldTransactions    = this.activeWallet.customerTransactions.inHold;
            this.effectiveTransactions = this.activeWallet.customerTransactions.completed;
        },
        getHumanMonth(monthNumber) {
            moment.locale('es');
            return moment().month(monthNumber).format('MMMM');
        }
    }

}
