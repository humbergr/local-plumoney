import Vue from "vue";
import Slick from "vue-slick";
import VCalendar from "v-calendar";
import * as numeral from "numeral";
import vueMask from "vue-jquery-mask";
import IdleVue from "idle-vue";
import axios from "axios";

let eventsHub = new Vue();

let request = null,
    options = {
        eventEmitter: eventsHub,
        idleTime: 240000
    };

Vue.use(vueMask);
Vue.use(IdleVue, options);

export default {
    name: "slick-slider",

    components: {
        Slick,
        VCalendar,
        vueMask
    },
    props: {
        transTracking: String,
        user: String,
        bonus: Number,
        qbpay: String
    },

    data() {
        return {
            slickOptions: {
                dots: true,
                infinite: true,
                arrows: false,
                speed: 300,
                autoplay: false,
                slidesToShow: 3,
                slidesToScroll: 3,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    }
                ]
            },
            countriesJson: require("./countries-en.json"),
            countriesCurrencies: [],
            id: "",
            name: "",
            lastname: "",
            email: "",
            phonecode: "+58",
            office_phone_code: "+58",
            phonenumber: "",
            office_phonenumber: "",
            tax_id_number: "",
            pre_id: "V-",
            id_number: "",
            ein: "",
            dba: "",
            website: "",
            country: "",
            bank_name: "",
            account_number: "",
            street: "",
            id_origin_state: "",
            account_type: "2",
            zip_code: "",
            id_end_date: "",
            id_origin_date: "",
            city: "",
            id_type: "1",
            id_origin_country: "",
            birthday: "",
            aba_number: "",
            state: "",
            type: 1,
            destinations: [],
            loader: true,
            destination_id: "",
            formatted_to_send: "",
            to_send: "",
            to_receive: "",
            fees: 0,
            payment_total: "",
            recomended_price: "",
            original_price: "",
            selected_account: null,
            payment_method: "",
            payment_method_country: "",
            formUrl: "",
            csrf: $('meta[name="csrf-token"]').attr("content"),
            alertForIdle: null,
            idleTimeOut: null,
            forbiddenByAmount: 2, //Two is Simple. It's forbidden with without message.
            forbiddenByHours: 2, //Two is Simple. It's forbidden with without message.
            forbiddenChat: 2, //Two is Simple. It's forbidden with without message.
            getPriceInterval: null,
            currencies: {
                Venezuela: "VES",
                Colombia: "COP",
                Panamá: "PAB",
                Perú: "PEN",
                Chile: "CLP",
                Argentina: "ARS",
                "Reino Unido": "GBP",
                España: "EUR",
                Portugal: "EUR",
                Italia: "EUR",
                Francia: "EUR",
                Alemania: "EUR",
                "United States": "USD",
                México: "MXN",
                Brazil: "BRL"
                //"Costa Rica": "CRC",
                //"Mexico": "MXN",
                //"Dominican Republic": "DOP",
                //"Canada": "CAD",
            },
            sender_country: "United States",
            receiver_country: "Venezuela",
            bonus_recomended_amount: 0,
            price_data_id: null,
            open_rates: [],
            usd_ves_price: 0,
            maskOptions: {
                translation: {
                    r: {
                        pattern: /[1-9]/
                    }
                }
            },
            no_viable_country: false,
            loadingSend: false,
            noDuplicate: false,
            force_loading: false,
            bonusActive: 0
        };
    },
    onIdle() {
        if (this.alertForIdle === null) {
            this.alertForIdle = $.alert({
                title: "¡Alerta!",
                content:
                    "Tienes 4 minutos de inactividad. <br> En 60 segundos cerraremos tu sesión por seguridad.",
                type: "red"
            });

            this.idleTimeOut = setTimeout(function() {
                axios
                    .get(window.location.origin + "/logout")
                    .then(function(response) {
                        document.cookie =
                            "laravel_session= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
                        window.location.href = window.location.origin;
                    });
            }, 60000);
        }
    },
    onActive() {
        if (this.alertForIdle) {
            this.alertForIdle.close();
            this.alertForIdle = null;
            clearTimeout(this.idleTimeOut);
            this.idleTimeOut = null;
        }
    },
    methods: {
        submitForm() {
            this.noDuplicate = true;
            this.$refs.form.submit();
        },

        getUrl(endpoint) {
            return window.location.origin + endpoint;
        },

        getPrice() {
            /**
             * Get price will Lock the operation if the user have no a complete profile
             */
            this.force_loading = true;
            this.payment_method = "";
            this.payment_method_country = "";

            axios
                .get(window.location.origin + "/get-price", {
                    params: {
                        amount: this.to_send === "" ? 0 : this.to_send,
                        receiving: this.to_receive === "" ? 0 : this.to_receive,
                        sender: this.sender,
                        receiver: this.receiver,
                        sender_country: this.sender_country,
                        receiver_country: this.receiver_country
                    }
                })
                .then(re => {
                  /*   console.log(re); */
                    this.recomended_price = re.data[0];
                    this.original_price = re.data["original"];
                    if (
                        re.data[1] > 100 &&
                        this.userObject.profileCompletition !== 1
                    ) {
                        this.forbiddenByAmount = 1;
                    } else {
                        this.forbiddenByAmount = 0;
                    }

                    if (this.payment_method_country !== "") {
                        this.calculateFees(this.payment_method_country);
                    } else {
                        this.payment_total = this.to_send;
                    }

                    let noViable = $(
                        '#sm-origen option[value^="' +
                            localStorage.getItem("sender_country") +
                            '"]'
                    ).attr("data-nb")
                        ? $(
                              '#sm-origen option[value^="' +
                                  localStorage.getItem("sender_country") +
                                  '"]'
                          ).attr("data-nb")
                        : null;

                    this.force_loading = false;
                    this.no_viable_country = noViable !== null;
                    this.loadingSend = false;
                    this.storeData();
                    this.price_data_id = re.data[2];
                });
        },

        getBonusPrice() {
            // if (this.receiver === 'VES') {
            //   axios.get(window.location.origin + '/get-price-ves', {
            //       params: {
            //           amount: 5,
            //           sender: 'USD',
            //           receiver: this.receiver,
            //           sender_country: this.sender_country,
            //           receiver_country: this.receiver_country,
            //       }
            //   })
            //       .then(
            //           re => {
            //               this.bonus_recomended_amount = re.data[0];
            //           }
            //       )
            // } else {
            axios
                .get(window.location.origin + "/get-price", {
                    params: {
                        amount: 5,
                        sender: "USD",
                        receiver: this.receiver,
                        sender_country: this.sender_country,
                        receiver_country: this.receiver_country
                    }
                })
                .then(re => {
                    this.bonus_recomended_amount = re.data[0];
                });
            // }
        },

        createDestination(object) {
            let vueObject = this,
                containerID = "";

            if (vueObject.type === 1) {
                containerID = "#add-contact-person";
            } else {
                containerID = "#add-contact-company";
            }

            let formData = new FormData(object.target),
                postData = {};

            for (let [key, val] of formData.entries()) {
                Object.assign(postData, { [key]: val });
            }

            if ("__pre_id" in postData) {
                postData.id_number = postData["__pre_id"] + postData.id_number;
                delete postData["__pre_id"];
            }

            vueObject.loader = true;
            axios
                .post(
                    window.location.origin + "/api/create-destination-account",
                    postData
                )
                .then(re => {
                    vueObject.destinations = re.data;
                    vueObject.loader = false;

                    $.each(vueObject.destinations[vueObject.type], function(
                        index,
                        value
                    ) {
                        if (value.account_number === postData.account_number) {
                            vueObject.destination_id = value.id;
                            vueObject.selected_account = value;
                        }
                    });

                    //if (window.matchMedia("(max-width: 640px)").matches) {
                    let scrollToTop =
                        document.body.scrollTop ||
                        document.documentElement.scrollTop;
                    let topOriginal =
                        $(containerID)[0].getBoundingClientRect().top +
                        scrollToTop;
                    $("html, body")
                        .stop()
                        .animate({ scrollTop: topOriginal }, 800, "swing");
                    //}

                    $(".card--overlaid").removeClass("--show");
                    $(containerID)
                        .siblings(".card-body")
                        .css("filter", "none");
                });
        },

        gettingAccounts() {
            this.loader = true;
            axios
                .get(
                    window.location.origin +
                        "/api/getting-destination-accounts?currency=" +
                        this.receiver
                )
                .then(re => {
                    this.destinations = re.data;
                    this.loader = false;
                });
        },

        destinationHandler() {
            this.loadingSend = true;
            this.gettingAccounts();
            this.getPrice();

            if (this.bonus === 0) {
                this.getBonusPrice();
            }
        },

        selectDestination(id, dest) {
            this.destination_id = id;
            this.selected_account = dest;

            this.resetSlick();
        },
        calculateFees(country) {
            let vueObject = this;
            $.ajaxSetup({ cache: false }); // assures the cache is empty
            if (request != null) {
                request.abort();
                request = null;
            }
            request = $.get(
                "/get-fees?sendCurrency=" +
                    this.sender +
                    "&receiveCurrency=" +
                    this.receiver +
                    "&amount=" +
                    this.to_send +
                    "&receive=" +
                    this.to_receive +
                    "&cardCountry=" +
                    country,
                function(data) {
                    vueObject.fees = data[0];
                    vueObject.payment_total = data[1];
                }
            );
        },
        getFromLocalstorage() {
            this.to_send = localStorage.getItem("to_send");
            this.formatted_to_send = numeral(this.to_send).format("0,0.[00]");
            this.payment_total = this.to_send;
            this.sender_country = localStorage.getItem("sender_country");
            this.receiver_country = localStorage.getItem("receiver_country");
            let flag = $(
                    '#sm-origen option[value^="' +
                        localStorage.getItem("sender_country") +
                        '"]'
                ).attr("data-flag"),
                rFlag = $(
                    '#sm-destino option[value^="' +
                        localStorage.getItem("receiver_country") +
                        '"]'
                ).attr("data-flag");
            $("#sm-origen").css("background-image", "url(" + flag + ")");
            $("#sm-destino").css("background-image", "url(" + rFlag + ")");
        },
        onPaymentMethodUpdate(paymentMethod) {
            this.payment_method = paymentMethod;

            if (paymentMethod === "cash") {
                this.payment_total -= this.fees;
                this.fees = 0;
                this.formUrl = this.getUrl("/make-purchase?paymentMethod=cash");
            }

            if (paymentMethod === "not_completed") {
                this.payment_method = "";
                this.payment_total -= this.fees;
                this.fees = 0;
                this.formUrl = this.getUrl("");
            }

            if (Array.isArray(paymentMethod)) {
                this.payment_method = paymentMethod[1].id;
                this.payment_method_country = paymentMethod[1].country;
                this.calculateFees(paymentMethod[1].country);
                this.formUrl = this.getUrl("/make-purchase?paymentMethod=card");
            }

            if (paymentMethod !== "cash" && !Array.isArray(paymentMethod)) {
                this.payment_total -= this.fees;
                this.fees = 0;
                this.formUrl = this.getUrl(
                    "/make-purchase?paymentMethod=" + paymentMethod
                );
            }
        },
        getStates(statesType) {
            let vueObject = this;

            if (statesType === "holder" || statesType === "all") {
                let countryISO = "",
                    actualState = $(".__holder-dynamic_states").attr(
                        "data-holder-state"
                    );

                if (vueObject.type === 1) {
                    countryISO = vueObject.country
                        ? vueObject.country
                        : $("#dest-country").val();
                } else {
                    countryISO = vueObject.country
                        ? vueObject.country
                        : $("#comp-country").val();
                }

                $.get("/get-countries?countryISO=" + countryISO, function(
                    data
                ) {
                    actualState = actualState ? actualState : vueObject.state;
                    if ($.type(data) === "object") {
                        $(document)
                            .find("#dest-select_state")
                            .remove();
                        $(document)
                            .find("#comp-select_state")
                            .remove();
                        $(".__holder-dynamic_states.person").append(
                            '<select id="dest-select_state" class="custom-select" required name="state"></select>'
                        );
                        $(".__holder-dynamic_states.company").append(
                            '<select id="comp-select_state" class="custom-select" required name="state"></select>'
                        );
                        $(document)
                            .find("#dest-select_state")
                            .html(vueObject.generateOptions(data, actualState));
                        $(document)
                            .find("#comp-select_state")
                            .html(vueObject.generateOptions(data, actualState));
                    } else {
                        $(document)
                            .find("#dest-select_state")
                            .remove();
                        $(document)
                            .find("#comp-select_state")
                            .remove();
                        $(".__holder-dynamic_states.person").append(
                            '<input class="form-control" required type="text" value="' +
                                actualState +
                                '" name="state" id="dest-select_state" />'
                        );
                        $(".__holder-dynamic_states.company").append(
                            '<input class="form-control" required type="text" value="' +
                                actualState +
                                '" name="state" id="comp-select_state" />'
                        );
                    }
                });
            }

            if (statesType === "id_doc" || statesType === "all") {
                let countryISO = vueObject.id_origin_country
                        ? vueObject.id_origin_country
                        : $("#dest-id_origin_country").val(),
                    actualState = $(".__id-dynamic_states").attr(
                        "data-id-state"
                    );

                $.get("/get-countries?countryISO=" + countryISO, function(
                    data
                ) {
                    actualState = actualState
                        ? actualState
                        : vueObject.id_origin_state;
                    if ($.type(data) === "object") {
                        $(document)
                            .find("#dest-id_origin_state")
                            .remove();
                        $(".__id-dynamic_states").append(
                            '<select id="dest-id_origin_state" class="custom-select" required name="id_origin_state"></select>'
                        );
                        $(document)
                            .find("#dest-id_origin_state")
                            .html(vueObject.generateOptions(data, actualState));
                    } else {
                        $(document)
                            .find("#dest-id_origin_state")
                            .remove();
                        $(".__id-dynamic_states").append(
                            '<input class="form-control" required type="text" value="' +
                                actualState +
                                '" name="id_origin_state" id="dest-id_origin_state" />'
                        );
                    }
                });
            }
        },
        generateOptions(data, actualState) {
            let html = "";
            $.each(data, function(index, value) {
                let selected = "";
                if (actualState === index) {
                    selected = "selected";
                }
                html +=
                    "<option " +
                    selected +
                    ' value="' +
                    index +
                    '">' +
                    value +
                    "</option>";
            });

            return html;
        },
        getCountriesCurrencyTable() {
            let vueObject = this;
            axios
                .get(window.location.origin + "/currencies-tables")
                .then(re => {
                    vueObject.countriesCurrencies = re.data;

                    if (vueObject.countriesCurrencies[vueObject.receiver]) {
                        vueObject.country =
                            vueObject.countriesCurrencies[vueObject.receiver];
                        vueObject.id_origin_country =
                            vueObject.countriesCurrencies[vueObject.receiver];

                        let countryISO = vueObject.country,
                            flag = $(
                                "#dest-id_origin_country option[value^=" +
                                    countryISO +
                                    "]"
                            ).attr("data-flag");

                        $("#dest-id_origin_country").css(
                            "background-image",
                            "url(" + flag + ")"
                        );
                        $("#dest-country").css(
                            "background-image",
                            "url(" + flag + ")"
                        );
                        $("#comp-country").css(
                            "background-image",
                            "url(" + flag + ")"
                        );
                        // $('#dest-id_origin_country option[value^=' + countryISO + ']').attr('selected', true);
                        // $('#dest-country option[value^=' + countryISO + ']').attr('selected', true);
                        // $('#comp-country option[value^=' + countryISO + ']').attr('selected', true);
                        $("#dest-id_origin_country")
                            .val(countryISO)
                            .change();
                        $("#dest-country")
                            .val(countryISO)
                            .change();
                        $("#comp-country")
                            .val(countryISO)
                            .change();
                        // console.log('changed');

                        vueObject.getStates("all");
                    }
                });
        },
        justFlag() {
            /**
             * Only update Flag Background after edition is Called.
             */
            let countryISO = this.country,
                idCountryISO = this.id_origin_country,
                flag = $(
                    "#dest-id_origin_country option[value^=" + countryISO + "]"
                ).attr("data-flag"),
                flag2 = $(
                    "#dest-id_origin_country option[value^=" +
                        idCountryISO +
                        "]"
                ).attr("data-flag"),
                mobileValue = this.phonecode,
                cOfficeValue = this.office_phone_code,
                flagMobile = $(
                    ".__flag_mobile option[value^=\\" + mobileValue + "]"
                ).attr("data-flag"),
                flagOffice = $(
                    ".__flag_office option[value^=\\" + cOfficeValue + "]"
                ).attr("data-flag");

            $(".__flag_mobile").css(
                "background-image",
                "url(" + flagMobile + ")"
            );
            $(".__flag_Office").css(
                "background-image",
                "url(" + flagOffice + ")"
            );
            $("#dest-id_origin_country").css(
                "background-image",
                "url(" + flag2 + ")"
            );
            $("#dest-country").css("background-image", "url(" + flag + ")");
            $("#comp-country").css("background-image", "url(" + flag + ")");
        },
        closeOverlaid() {
            let addContact = "";
            if (this.type === 1) {
                addContact = $("#add-contact-person");
            } else {
                addContact = $("#add-contact-company");
            }

            // if (window.matchMedia("(max-width: 640px)").matches) {
            let scrollToTop =
                document.body.scrollTop || document.documentElement.scrollTop;
            let topOriginal =
                addContact[0].getBoundingClientRect().top + scrollToTop;
            $("html, body")
                .stop()
                .animate({ scrollTop: topOriginal }, 800, "swing");
            // }

            $(".card--overlaid").removeClass("--show");
            // remove blur to content
            addContact.siblings(".card-body").css("filter", "none");
        },
        openPersonRow(event) {
            this.type = 1;
            let btn = $("[data-target='#contact-wrapper']");

            btn.removeClass("--active");
            $(event.target).addClass("--active");
            $("#contact-wrapper").addClass("--active");

            if (this.destinations[this.type]) {
                this.destination_id = this.destinations[this.type][0].id;
                this.selected_account = this.destinations[this.type][0];
            }

            this.resetSlick();
        },
        openPersonModal() {
            this.type = 1;
            $("#add-contact-conditionalModal").modal("hide");
            setTimeout(function() {
                $("#add-contactPerson-modal").modal("show");
            }, 320);
        },
        openCompanyRow(event) {
            this.type = 2;
            let btn = $("[data-target='#contact-wrapper']");

            this.resetSlick();

            btn.removeClass("--active");
            $(event.target).addClass("--active");
            $("#contact-wrapper").addClass("--active");

            if (this.destinations[this.type]) {
                this.destination_id = this.destinations[this.type][0].id;
                this.selected_account = this.destinations[this.type][0];
            }
        },
        openCompanyModal() {
            this.type = 2;
            $("#add-contact-conditionalModal").modal("hide");
            setTimeout(function() {
                $("#add-contactCompany-modal").modal("show");
            }, 320);
        },
        openNewContactForm() {
            let addContact = "";
            if (this.type === 1) {
                addContact = $("#add-contact-person");
            } else {
                addContact = $("#add-contact-company");
            }

            this.clearAll();

            let mobileValue = this.phonecode,
                cOfficeValue = this.office_phone_code,
                flagMobile = $(
                    ".__flag_mobile option[value^=\\" + mobileValue + "]"
                ).attr("data-flag"),
                flagOffice = $(
                    ".__flag_office option[value^=\\" + cOfficeValue + "]"
                ).attr("data-flag");

            $(".__flag_mobile").css(
                "background-image",
                "url(" + flagMobile + ")"
            );
            $(".__flag_office").css(
                "background-image",
                "url(" + flagOffice + ")"
            );

            addContact.toggleClass("--show");
            let scrollToTop =
                document.body.scrollTop || document.documentElement.scrollTop;
            addContact.css("top", scrollToTop - 100);
            // addContact.siblings(".card-body").css("filter", "blur(1px)");
        },
        editDestination(destinationId) {
            let vueObject = this;
            this.loader = true;

            $("#contact-wrapper").addClass("--loading");
            axios
                .get(
                    window.location.origin +
                        "/get-destination-info?id=" +
                        destinationId
                )
                .then(re => {
                    $.each(re.data, function(index, value) {
                        vueObject[index] = value;
                    });

                    //Process Venezuelan Cédula
                    if (vueObject.id_number) {
                        let idSplit = vueObject.id_number.split("-");
                        if (idSplit[0] === "V" || idSplit[0] === "E") {
                            vueObject.pre_id = idSplit[0] + "-";
                            vueObject.id_number = idSplit[1];
                        }
                    }

                    let phoneNumber = re.data.phone_number.split(" ");
                    vueObject.phonecode = phoneNumber[0];
                    vueObject.phonenumber = phoneNumber[1] + phoneNumber[2];

                    vueObject.getStates("all");
                    vueObject.justFlag();

                    if (re.data.type === 1) {
                        //Personal
                        let birthday = new Date(vueObject.birthday),
                            idOriginDate = new Date(vueObject.id_origin_date);

                        vueObject.birthday = birthday; //.format('MM/DD/YYYY');
                        vueObject.id_origin_date = idOriginDate; //.format('MM/DD/YYYY');
                        // $('#dest-birthday').datepicker('update', vueObject.birthday);
                        // $('#dest-id_origin_date').datepicker('update', vueObject.id_origin_date);

                        if (this.id_end_date) {
                            let idEndDate = new Date(vueObject.id_end_date);
                            vueObject.id_end_date = idEndDate; //.format('MM/DD/YYYY');
                            // $('#dest-id_end_date').datepicker('update', vueObject.id_end_date);
                        }

                        let addContact = $("#add-contact-person");
                        addContact.toggleClass("--show");
                        let scrollToTop =
                            document.body.scrollTop ||
                            document.documentElement.scrollTop;
                        addContact.css("top", scrollToTop - 100);
                        $("#contact-wrapper").removeClass("--loading");
                        // addContact.siblings(".card-body").css("filter", "blur(1px)");
                    } else {
                        //Company
                        let officePhone = re.data.office_phone_number.split(
                            " "
                        );
                        vueObject.office_phone_code = officePhone[0];
                        vueObject.office_phonenumber =
                            officePhone[1] + officePhone[2];

                        let addContact = $("#add-contact-company");
                        addContact.toggleClass("--show");
                        let scrollToTop =
                            document.body.scrollTop ||
                            document.documentElement.scrollTop;
                        addContact.css("top", scrollToTop - 100);
                        $("#contact-wrapper").removeClass("--loading");
                        // addContact.siblings(".card-body").css("filter", "blur(1px)");
                    }
                    this.loader = false;
                });
        },
        deleteDestination(destinationId) {
            let vueObject = this;
            this.loader = true;

            axios
                .get(
                    window.location.origin +
                        "/api/delete-destination?id=" +
                        destinationId +
                        "&currency=" +
                        this.receiver
                )
                .then(re => {
                    let addContact = null;
                    if (this.type === 1) {
                        addContact = $("#add-contact-person");
                    } else {
                        addContact = $("#add-contact-company");
                    }

                    // if (window.matchMedia("(max-width: 640px)").matches) {
                    let scrollToTop =
                        document.body.scrollTop ||
                        document.documentElement.scrollTop;
                    let topOriginal =
                        addContact[0].getBoundingClientRect().top + scrollToTop;
                    $("html, body")
                        .stop()
                        .animate({ scrollTop: topOriginal }, 800, "swing");
                    // }

                    vueObject.destinations = re.data;
                    this.loader = false;
                    addContact.removeClass("--show");
                    addContact.removeClass("--show");
                });
        },
        storeData() {
            localStorage.setItem("to_send", this.to_send);
            localStorage.setItem("to_receive", this.to_receive);
            localStorage.setItem("sender", this.sender);
            localStorage.setItem("receiver", this.receiver);
            localStorage.setItem("sender_country", this.sender_country);
            localStorage.setItem("receiver_country", this.receiver_country);
            localStorage.setItem("recomended_price", this.recomended_price);

            //this.getCountriesCurrencyTable();
        },
        clearAll() {
            this.id = "";
            this.name = "";
            this.lastname = "";
            this.email = "";
            this.phonecode = "+58";
            this.office_phone_code = "+58";
            this.phonenumber = "";
            this.office_phonenumber = "";
            this.tax_id_number = "";
            this.id_number = "";
            this.ein = "";
            this.dba = "";
            this.website = "";
            this.country = "";
            this.bank_name = "";
            this.account_number = "";
            this.street = "";
            this.id_origin_state = "";
            this.account_type = "2";
            this.zip_code = "";
            this.id_end_date = "";
            this.id_origin_date = "";
            this.city = "";
            this.id_type = "1";
            this.id_origin_country = "";
            this.birthday = "";
            this.aba_number = "";
            this.state = "";

            this.getCountriesCurrencyTable();
            let mask = '(r00) 000-0000';
            $('#dest-main-mobile').mask(mask, this.maskOptions);
            $('#comp-cellphone').mask(mask, this.maskOptions);
            $('#comp-main-office_phone').mask(mask, this.maskOptions);
        },
        resetSlick() {
            let currIndex = this.$refs.slick.currentSlide();

            this.$refs.slick.destroy();
            this.$nextTick(() => {
                this.$refs.slick.create();
                this.$refs.slick.goTo(currIndex, true);
            });
        },
        recommendedUtility() {
         //Evento donde detectamos el cambio del monto
         
            
            if (this.recomended_price < 1) {
                return numeral(1 / this.recomended_price).format("0,0.[00]");
            }
         //   console.log(numeral(this.recomended_price).format("0,0.[00]"));
            return numeral(this.recomended_price).format("0,0.[00]");
        },
        formatMoneyNumeral(amount) {
            return numeral(amount).format("0,0.[00]");
        },
        getCountry(iso2) {
            $.each(this.countriesJson, function(index, value) {
                if (value.code === iso2) {
                    return value.name;
                }
            });
        },
        checkHours() {
            axios.get(window.location.origin + "/check-hours").then(re => {
                this.forbiddenByHours = re.data["close"];
                this.forbiddenChat = re.data["close_support_chat"];
            });
        },
        getOpenExchangeRates() {
            axios.get(window.location.origin + "/exchange-rates").then(re => {
                this.open_rates = re.data.rates;
            });
        },
        getVesUsdPrice() {
            axios
                .get(window.location.origin + "/get-price", {
                    params: {
                        amount: 0,
                        sender: "VES",
                        receiver: "USD",
                        sender_country: "Venezuela",
                        receiver_country: "United States"
                    }
                })
                .then(re => {
                    this.usd_ves_price = re.data[0];
                });
        },
        getCountriesData() {
            return new Promise((resolve, reject) => {
                axios
                    .get(window.location.origin + "/get-op-countries")
                    .then(re => {
                        this.currencies = re.data[0];
                        $("#sm-origen").html(re.data[1]);
                        $("#sm-destino").html(re.data[2]);
                        resolve();
                    });
            });
        },
        minAmountBonus: function() {
            if (this.bonus !== 1) {
                if (this.sender === "USD" && this.to_send >= 100) {
                    this.bonusActive = 0;
                } else if (
                    this.sender !== "VES" &&
                    this.to_send / this.open_rates[this.sender] >= 100
                ) {
                    this.bonusActive = 0;
                } else if (
                    this.sender === "VES" &&
                    Number.parseFloat(this.to_send * this.usd_ves_price) >= 100
                ) {
                    this.bonusActive = 0;
                } else {
                    this.bonusActive = 1;
                }
            }
        },
        mobileCountry(type) {
            let masks = ['(r00) 000-0000', 'r000-000-000-0000'];

            if (this.phonecode === '+54') {
                if (type === 'person') {
                    $('#dest-main-mobile').mask(masks[1], this.maskOptions);
                } else {
                    $('#comp-cellphone').mask(masks[1], this.maskOptions);
                }
            } else {
                if (type === 'person') {
                    $('#dest-main-mobile').mask(masks[0], this.maskOptions);
                } else {
                    $('#comp-cellphone').mask(masks[0], this.maskOptions);
                }
            }

            if (this.office_phone_code === '+54') {
                $('#comp-main-office_phone').mask(masks[1], this.maskOptions);
            } else {
                $('#comp-main-office_phone').mask(masks[0], this.maskOptions);
            }
        }
    },

    created() {
        this.$nextTick(function() {});
    },
    mounted() {
        this.$nextTick(function() {
            let vueObject = this;
            this.bonusActive = this.bonus;
            if (localStorage.getItem("to_send") !== null) {
                this.getCountriesData().then(function() {
                    vueObject.getFromLocalstorage();
                    vueObject.getOpenExchangeRates();
                    vueObject.getVesUsdPrice();
                    vueObject.gettingAccounts();
                    vueObject.getPrice();
                    vueObject.checkHours();

                    if (vueObject.bonus === 0) {
                        vueObject.getBonusPrice();
                    }
                });
            } else {
                this.getCountriesData().then(function() {
                    vueObject.getOpenExchangeRates();
                    vueObject.getVesUsdPrice();
                    vueObject.gettingAccounts();
                    vueObject.getPrice();
                    vueObject.checkHours();

                    if (vueObject.bonus === 0) {
                        vueObject.getBonusPrice();
                    }
                });
            }
        });
    },

    updated() {
        this.$nextTick(function() {});
    },

    computed: {
        userObject: function() {
            return JSON.parse(this.user);
        },
        toReceive: function() {
            if (this.to_send === "") {
                return "";
            }

            if (this.original_price > 1 && this.recomended_price < 1) {
                this.to_receive =
                    Math.round((this.to_send / this.recomended_price) * 100) /
                    100;
            } else {
                this.to_receive =
                    Math.round(this.to_send * this.recomended_price * 100) /
                    100;
            }

            this.storeData();
            return numeral(this.to_receive).format("0,0.[00]");
        },

        formated_bonus: function() {
            let bonus_total = Math.round(5 * this.recomended_price * 100) / 100;

            return numeral(bonus_total).format("0,0.[00]");
        },

        formateBonusRecomended: function() {
            return numeral(this.bonus_recomended_amount).format("0,0.[00]");
        },

        totalWithBonus: function() {
            let total =
                parseFloat(this.toReceive.replace(/,/g, "")) +
                parseFloat(this.formated_bonus.replace(",", ""));

            return numeral(total).format("0,0.[00]");
        },

        sender: function() {
            return this.currencies[this.sender_country];
        },

        receiver: function() {
            return this.currencies[this.receiver_country];
        },

        minAmountBoolean: function() {
            if (this.sender === "USD" && this.to_send >= 15) {
                return true;
            } else if (
                this.sender !== "VES" &&
                this.to_send / this.open_rates[this.sender] >= 15
            ) {
                return true;
            } else if (
                this.sender === "VES" &&
                Number.parseFloat(this.to_send * this.usd_ves_price) >= 15.0
            ) {
                return true;
            }
            return false;
        }
    },

    watch: {
        formatted_to_send: function() {
            this.to_send = numeral(this.formatted_to_send).value();
            this.formatted_to_send = numeral(this.formatted_to_send).format(
                "0,0.[00]"
            );
            let vueObject = this;
            this.loadingSend = true;
            this.forbiddenByAmount = 2;
            this.minAmountBonus();
            clearInterval(this.getPriceInterval);
            this.getPriceInterval = setInterval(function() {
                vueObject.getPrice();
                clearInterval(vueObject.getPriceInterval);
            }, 2000);
        },
        destinations: function() {
            this.resetSlick();
        }
    }
};
