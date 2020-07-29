import Slick from 'vue-slick';
import * as numeral from "numeral";
import * as lodash from "lodash";
import axios from "axios";

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
            //From Send Money
            countriesCurrencies: [],
            id: '',
            name: '',
            lastname: '',
            email: '',
            phonecode: '+1',
            office_phone_code: '+1',
            phonenumber: '',
            office_phonenumber: '',
            tax_id_number: '',
            pre_id: 'V-',
            id_number: '',
            ein: '',
            dba: '',
            website: '',
            country: '',
            bank_name: '',
            account_number: '',
            street: '',
            id_origin_state: '',
            account_type: '2',
            zip_code: '',
            id_end_date: '',
            id_origin_date: '',
            city: '',
            id_type: '1',
            id_origin_country: '',
            birthday: '',
            aba_number: '',
            state: '',
            type: 1,
            destinations: [],
            loader: true,
            destination_id: '',
            formatted_to_send: '',
            to_send: '',
            to_receive: '',
            fees: 0,
            payment_total: '',
            recomended_price: '',
            original_price: '',
            selected_account: null,
            payment_method: '',
            formUrl: '',
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
            sender_country: 'United States',
            receiver_country: 'Chile',
            forbiddenByAmount: 1,
            forbiddenByHours: 2, //Two is Simple. It's forbidden with without message.
            price_data_id: null,
            getPriceInterval: null,
            loadingReceive: true,
            noDuplicate: false,
            maskOptions: {
                translation: {
                    'r': {
                        pattern: /[1-9]/
                    },
                }
            },
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
        //From Send Money
        sender: function () {
            return this.currencies[this.sender_country];
        },
        receiver: function () {
            return this.currencies[this.receiver_country];
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

            return numeral(this.to_receive).format('0,0.[00]');
        },
        minAmountBoolean: function () {
            return this.to_send >= 10;
        },
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
        /**
         * Verify and format the input.
         *
         * @param event
         */
        verifyMaxTransfer(event) {
            let vueObject       = this,
                intentAmount    = 0;
            this.loadingReceive = true;

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

            if (intentAmount > vueObject.maxTransferAmount) {
                $(event.target).siblings('.invalid-feedback').slideDown();
                vueObject.forbiddenByAmount = 1;
            } else {
                $(event.target).siblings('.invalid-feedback').slideUp();
                vueObject.forbiddenByAmount = 0;
            }

            clearInterval(this.getPriceInterval);
            this.getPriceInterval = setInterval(function () {
                vueObject.getPrice();
                clearInterval(vueObject.getPriceInterval)
            }, 2000);
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
        //From Send Money
        destinationHandler() {
            this.loadingReceive = true;
            this.gettingAccounts();
            this.getPrice();
        },
        gettingAccounts() {
            this.loader = true;
            axios.get(window.location.origin + '/api/getting-destination-accounts?currency=' + this.receiver
            )
                .then(re => {
                    this.destinations = re.data;
                    this.loader       = false;
                })
        },
        getPrice() {
            this.recomended_price = 0;
            /**
             * Get price will Lock the operation if the user have no a complete profile
             */
            axios.get(window.location.origin + '/wallets-get-price/withdraw', {
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
                        this.recomended_price = re.data[0];
                        this.original_price   = re.data['original'];
                        // this.exchange_p_id    = re.data[2];
                        //
                        this.loadingReceive = false;
                        this.price_data_id  = re.data[2];
                    }
                )
        },
        openPersonRow(event) {
            this.type = 1;
            let btn   = $("[data-target='#contact-wrapper']");

            btn.removeClass("--active");
            $(event.target).addClass("--active");
            $('#contact-wrapper').addClass("--active");

            if (this.destinations[this.type]) {
                this.destination_id   = this.destinations[this.type][0].id;
                this.selected_account = this.destinations[this.type][0];
            }

            this.resetSlick();
        },
        openPersonModal() {
            this.type = 1;
            $("#add-contact-conditionalModal").modal("hide");
            setTimeout(function () {
                $("#add-contactPerson-modal").modal("show");
            }, 320);
        },
        openCompanyRow(event) {
            this.type = 2;
            let btn   = $("[data-target='#contact-wrapper']");

            this.resetSlick();

            btn.removeClass("--active");
            $(event.target).addClass("--active");
            $('#contact-wrapper').addClass("--active");

            if (this.destinations[this.type]) {
                this.destination_id   = this.destinations[this.type][0].id;
                this.selected_account = this.destinations[this.type][0];
            }
        },
        openCompanyModal() {
            this.type = 2;
            $("#add-contact-conditionalModal").modal("hide");
            setTimeout(function () {
                $("#add-contactCompany-modal").modal("show");
            }, 320);
        },
        openNewContactForm() {
            let addContact = '';
            if (this.type === 1) {
                addContact = $('#add-contact-person');
            } else {
                addContact = $('#add-contact-company');
            }

            this.clearAll();

            let mobileValue  = this.phonecode,
                cOfficeValue = this.office_phone_code,
                flagMobile   = $('.__flag_mobile option[value^=\\' + mobileValue + ']').attr('data-flag'),
                flagOffice   = $('.__flag_office option[value^=\\' + cOfficeValue + ']').attr('data-flag');

            $('.__flag_mobile').css('background-image', 'url(' + flagMobile + ')');
            $('.__flag_Office').css('background-image', 'url(' + flagOffice + ')');

            addContact.toggleClass("--show");
            let scrollToTop  = document.body.scrollTop || document.documentElement.scrollTop,
                offsetParent = $('.__overlay_container').offset().top;
            addContact.css('top', scrollToTop - offsetParent);
        },
        editDestination(destinationId) {
            let vueObject = this;
            this.loader   = true;

            $('#contact-wrapper').addClass('--loading');
            axios.get(window.location.origin + '/get-destination-info?id=' + destinationId
            )
                .then(re => {
                    $.each(re.data, function (index, value) {
                        vueObject[index] = value;
                    });
                    let phoneNumber       = re.data.phone_number.split(' ');
                    vueObject.phonecode   = phoneNumber[0];
                    vueObject.phonenumber = phoneNumber[1];

                    vueObject.getStates('all');
                    vueObject.justFlag();

                    if (re.data.type === 1) { //Personal
                        let birthday     = new Date(vueObject.birthday),
                            idOriginDate = new Date(vueObject.id_origin_date);

                        vueObject.birthday       = birthday;//.format('MM/DD/YYYY');
                        vueObject.id_origin_date = idOriginDate;//.format('MM/DD/YYYY');
                        // $('#dest-birthday').datepicker('update', vueObject.birthday);
                        // $('#dest-id_origin_date').datepicker('update', vueObject.id_origin_date);

                        if (this.id_end_date) {
                            let idEndDate         = new Date(vueObject.id_end_date);
                            vueObject.id_end_date = idEndDate;//.format('MM/DD/YYYY');
                            // $('#dest-id_end_date').datepicker('update', vueObject.id_end_date);
                        }

                        let addContact = $('#add-contact-person');
                        addContact.toggleClass("--show");
                        let scrollToTop  = document.body.scrollTop || document.documentElement.scrollTop,
                            offsetParent = $('.__overlay_container').offset().top;
                        addContact.css('top', scrollToTop - offsetParent);
                        $('#contact-wrapper').removeClass('--loading');
                        // addContact.siblings(".card-body").css("filter", "blur(1px)");
                    } else { //Company
                        let officePhone              = re.data.office_phone_number.split(' ');
                        vueObject.office_phone_code  = officePhone[0];
                        vueObject.office_phonenumber = officePhone[1];

                        let addContact = $('#add-contact-company');
                        addContact.toggleClass("--show");
                        let scrollToTop  = document.body.scrollTop || document.documentElement.scrollTop,
                            offsetParent = $('.__overlay_container').offset().top;
                        addContact.css('top', scrollToTop - offsetParent);
                        $('#contact-wrapper').removeClass('--loading');
                        // addContact.siblings(".card-body").css("filter", "blur(1px)");
                    }
                    this.loader = false;
                })
        },
        deleteDestination(destinationId) {
            let vueObject = this;
            this.loader   = true;

            axios.get(window.location.origin + '/api/delete-destination?id=' + destinationId +
                '&currency=' + this.receiver
            ).then(re => {
                let addContact = null;
                if (this.type === 1) {
                    addContact = $('#add-contact-person');
                } else {
                    addContact = $('#add-contact-company');
                }

                // if (window.matchMedia("(max-width: 640px)").matches) {
                let scrollToTop = document.body.scrollTop || document.documentElement.scrollTop;
                let topOriginal = addContact[0].getBoundingClientRect().top + scrollToTop;
                $("html, body").stop().animate({scrollTop: topOriginal}, 800, 'swing');
                // }

                vueObject.destinations = re.data;
                this.loader            = false;
                addContact.removeClass("--show");
                addContact.removeClass("--show");
            })
        },
        resetSlick() {
            let currIndex = this.$refs.slick.currentSlide();

            this.$refs.slick.destroy();
            this.$nextTick(() => {
                this.$refs.slick.create();
                this.$refs.slick.goTo(currIndex, true);
            })
        },
        clearAll() {
            this.id                 = '';
            this.name               = '';
            this.lastname           = '';
            this.email              = '';
            this.phonecode          = '+1';
            this.office_phone_code  = '+1';
            this.phonenumber        = '';
            this.office_phonenumber = '';
            this.tax_id_number      = '';
            this.id_number          = '';
            this.ein                = '';
            this.dba                = '';
            this.website            = '';
            this.country            = '';
            this.bank_name          = '';
            this.account_number     = '';
            this.street             = '';
            this.id_origin_state    = '';
            this.account_type       = '2';
            this.zip_code           = '';
            this.id_end_date        = '';
            this.id_origin_date     = '';
            this.city               = '';
            this.id_type            = '1';
            this.id_origin_country  = '';
            this.birthday           = '';
            this.aba_number         = '';
            this.state              = '';

            this.getCountriesCurrencyTable();
        },
        getCountriesCurrencyTable() {
            let vueObject = this;
            axios.get(window.location.origin + '/currencies-tables'
            )
                .then(re => {
                    vueObject.countriesCurrencies = re.data;

                    if (vueObject.countriesCurrencies[vueObject.receiver]) {
                        vueObject.country           = vueObject.countriesCurrencies[vueObject.receiver];
                        vueObject.id_origin_country = vueObject.countriesCurrencies[vueObject.receiver];

                        let countryISO = vueObject.country,
                            flag       = $('#dest-id_origin_country option[value^=' + countryISO + ']')
                                .attr('data-flag');

                        $('#dest-id_origin_country').css('background-image', 'url(' + flag + ')');
                        $('#dest-country').css('background-image', 'url(' + flag + ')');
                        $('#comp-country').css('background-image', 'url(' + flag + ')');
                        $('#dest-id_origin_country option[value^=' + countryISO + ']').attr('selected', true);
                        $('#dest-country option[value^=' + countryISO + ']').attr('selected', true);
                        $('#comp-country option[value^=' + countryISO + ']').attr('selected', true);

                        vueObject.getStates('all');
                    }
                })
        },
        getStates(statesType) {
            let vueObject = this;

            if (statesType === 'holder' || statesType === 'all') {
                let countryISO  = '',
                    actualState = $('.__holder-dynamic_states').attr('data-holder-state');

                if (vueObject.type === 1) {
                    countryISO = vueObject.country ? vueObject.country : $('#dest-country').val();
                } else {
                    countryISO = vueObject.country ? vueObject.country : $('#comp-country').val();
                }

                $.get('/get-countries?countryISO=' + countryISO, function (data) {
                    actualState = actualState ? actualState : vueObject.state;
                    if ($.type(data) === 'object') {
                        $(document).find('#dest-select_state').remove();
                        $(document).find('#comp-select_state').remove();
                        $('.__holder-dynamic_states.person').append('<select id="dest-select_state" class="custom-select" required name="state"></select>');
                        $('.__holder-dynamic_states.company').append('<select id="comp-select_state" class="custom-select" required name="state"></select>');
                        $(document).find('#dest-select_state').html(vueObject.generateOptions(data, actualState));
                        $(document).find('#comp-select_state').html(vueObject.generateOptions(data, actualState));
                    } else {
                        $(document).find('#dest-select_state').remove();
                        $(document).find('#comp-select_state').remove();
                        $('.__holder-dynamic_states.person').append('<input class="form-control" required type="text" value="' + actualState + '" name="state" id="dest-select_state" />');
                        $('.__holder-dynamic_states.company').append('<input class="form-control" required type="text" value="' + actualState + '" name="state" id="comp-select_state" />');
                    }
                });
            }

            if (statesType === 'id_doc' || statesType === 'all') {
                let countryISO  = vueObject.id_origin_country ? vueObject.id_origin_country :
                    $('#dest-id_origin_country').val(),
                    actualState = $('.__id-dynamic_states').attr('data-id-state');

                $.get('/get-countries?countryISO=' + countryISO, function (data) {
                    actualState = actualState ? actualState : vueObject.id_origin_state;
                    if ($.type(data) === 'object') {
                        $(document).find('#dest-id_origin_state').remove();
                        $('.__id-dynamic_states').append('<select id="dest-id_origin_state" class="custom-select" required name="id_origin_state"></select>');
                        $(document).find('#dest-id_origin_state').html(vueObject.generateOptions(data, actualState));
                    } else {
                        $(document).find('#dest-id_origin_state').remove();
                        $('.__id-dynamic_states').append('<input class="form-control" required type="text" value="' + actualState + '" name="id_origin_state" id="dest-id_origin_state" />');
                    }
                });
            }
        },
        generateOptions(data, actualState) {
            let html = '';
            $.each(data, function (index, value) {
                let selected = '';
                if (actualState === index) {
                    selected = 'selected';
                }
                html += '<option ' + selected + ' value="' + index + '">' + value + '</option>';
            });

            return html;
        },
        closeOverlaid() {
            let addContact = '';
            if (this.type === 1) {
                addContact = $('#add-contact-person');
            } else {
                addContact = $('#add-contact-company');
            }

            // if (window.matchMedia("(max-width: 640px)").matches) {
            let scrollToTop = document.body.scrollTop || document.documentElement.scrollTop;
            let topOriginal = addContact[0].getBoundingClientRect().top + scrollToTop;
            $("html, body").stop().animate({scrollTop: topOriginal}, 800, 'swing');
            // }

            $('.card--overlaid').removeClass('--show');
            // remove blur to content
            addContact.siblings(".card-body").css("filter", "none");
        },
        selectDestination(id, dest) {
            this.destination_id   = id;
            this.selected_account = dest;

            this.resetSlick();
        },
        justFlag() {
            /**
             * Only update Flag Background after edition is Called.
             */
            let countryISO   = this.country,
                idCountryISO = this.id_origin_country,
                flag         = $('#dest-id_origin_country option[value^=' + countryISO + ']')
                    .attr('data-flag'),
                flag2        = $('#dest-id_origin_country option[value^=' + idCountryISO + ']')
                    .attr('data-flag'),
                mobileValue  = this.phonecode,
                cOfficeValue = this.office_phone_code,
                flagMobile   = $('.__flag_mobile option[value^=\\' + mobileValue + ']').attr('data-flag'),
                flagOffice   = $('.__flag_office option[value^=\\' + cOfficeValue + ']').attr('data-flag');

            $('.__flag_mobile').css('background-image', 'url(' + flagMobile + ')');
            $('.__flag_Office').css('background-image', 'url(' + flagOffice + ')');
            $('#dest-id_origin_country').css('background-image', 'url(' + flag2 + ')');
            $('#dest-country').css('background-image', 'url(' + flag + ')');
            $('#comp-country').css('background-image', 'url(' + flag + ')');
        },
        createDestination(object) {
            let vueObject   = this,
                containerID = '';

            if (vueObject.type === 1) {
                containerID = '#add-contact-person';
            } else {
                containerID = '#add-contact-company';
            }

            let formData = new FormData(object.target),
                postData = {};

            for (let [key, val] of formData.entries()) {
                Object.assign(postData, {[key]: val})
            }

            vueObject.loader = true;
            axios.post(window.location.origin + '/api/create-destination-account/', postData)
                .then(re => {
                    vueObject.destinations = re.data;
                    vueObject.loader       = false;

                    $.each(vueObject.destinations[vueObject.type], function (index, value) {
                        if (value.account_number === postData.account_number) {
                            vueObject.destination_id   = value.id;
                            vueObject.selected_account = value;
                        }
                    });

                    //if (window.matchMedia("(max-width: 640px)").matches) {
                    let scrollToTop = document.body.scrollTop || document.documentElement.scrollTop;
                    let topOriginal = $(containerID)[0].getBoundingClientRect().top + scrollToTop;
                    $("html, body").stop().animate({scrollTop: topOriginal}, 800, 'swing');
                    //}

                    $('.card--overlaid').removeClass('--show');
                    $(containerID).siblings(".card-body").css("filter", "none");
                })
        },
        submitForm() {
            this.noDuplicate = true;
            this.$refs.form.submit();
        },
        getCountriesData() {
            return new Promise((resolve, reject) => {
                axios.get(window.location.origin + '/get-op-countries').then(
                    re => {
                        this.currencies = re.data[0];
                        $('#sm-destino').html(re.data[2]);
                        resolve();
                    }
                )
            });
        },
        checkHours() {
            axios.get(window.location.origin + '/check-hours').then(
                re => {
                    this.forbiddenByHours = re.data['close'];
                    //this.forbiddenChat    = re.data['close_support_chat'];
                }
            );
        },
    },
    mounted() {
        let vueObject = this;
        this.$nextTick(function () {
            this.checkHours();
            this.getCountriesData().then(function () {
                vueObject.gettingAccounts();
                vueObject.getPrice();
            });
        });
    },

    watch: {
        destinations: function () {
            this.resetSlick();
        }
    }
}
