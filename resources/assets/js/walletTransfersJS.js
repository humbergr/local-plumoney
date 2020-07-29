import * as numeral from "numeral";
import * as lodash from "lodash";
import Cleave from 'vue-cleave-component';

export default {

    props: {
        wallets: String,
        userActiveWallet: String
    },
    components: {
        Cleave
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
            maxTransferAmount: 0,
            transferAmount: 0,
            receiverEmail: '',
            transferDescription: '',
            validAmount: 0,
            options: {
                numeral: true
            }
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

        selfDestinyWallets: function () {
            let selfDestinyWallets = this.walletsObject,
                vueObject          = this;

            lodash.remove(selfDestinyWallets, function (value) {
                return value.hash === vueObject.activeWallet.hash;
            });

            return selfDestinyWallets;
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
        showNextSteps(type) {
            if (type === 1) {
                $("#friend-transfer").hide();
                $("#self-transfer").show();
                $("#friend-wallet").removeClass("--active");
                $('#self-wallet').addClass("--active");
                $("#money-to-receive").show();
            } else {
                $("#self-transfer").hide();
                $("#friend-transfer").show();
                $("#self-wallet").removeClass("--active");
                $('#friend-wallet').addClass("--active");
                $("#money-to-receive").hide();
            }
            $('#third-step').show();
        },
        /**
         * Verify and format the input.
         *
         * @param event
         */
        verifyMaxTransfer(event) {
            let vueObject    = this,
                intentAmount = 0;

            if (event.type === 'keyup') {
                vueObject.formatCurrency($(event.target));
                intentAmount = this.parseValue($(event.target).val());
            } else {
                vueObject.formatCurrency($(event.target), 'blur');
                intentAmount = this.parseValue($(event.target).val());
            }

            vueObject.transferAmount = $(event.target).val();
            $('#sending-amount').val(intentAmount);

            if (intentAmount > vueObject.maxTransferAmount) {
                $(event.target).siblings('.invalid-feedback').slideDown();
                vueObject.validAmount = 0;
            } else {
                $(event.target).siblings('.invalid-feedback').slideUp();
                vueObject.validAmount = 1;
            }

            vueObject.verifyInfo();
        },
        verifyInfo() {
            let submitBtn = $('#transfer-submit');

            if (this.validAmount === 1 && this.receiverEmail !== '') {
                submitBtn.removeClass('disabled');
            } else {
                if (!submitBtn.hasClass('disabled')) {
                    submitBtn.addClass('disabled');
                }
            }
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
        }
    },
    watch: {
        destinations: function () {
            this.resetSlick();
        },
        transferAmount: function () {
            let vueObject = this,
                selector  = '#sending-amount';

            $(selector).val(vueObject.transferAmount);

            if (parseFloat(vueObject.transferAmount) > vueObject.maxTransferAmount) {
                $(selector).siblings('.invalid-feedback').slideDown();
                vueObject.validAmount = 0;
            } else {
                $(selector).siblings('.invalid-feedback').slideUp();
                vueObject.validAmount = 1;
            }

            vueObject.verifyInfo();
        }
    }

}
