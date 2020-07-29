class VnzlaSaleUSD {
    constructor(
        id,
        vzlaSaleUSD_cost,
        vzlaSaleUSD_instantPrice,
        vzlaSaleUSD_72Price,
        vzlaSaleUSD_7dPrice,
        vzlaSaleUSD_15dPrice,
        vzlaSaleUSD_dateTime,
        vzlaSaleUSD_dateHuman,
        vzlaSaleUSD_saleUsdBank,
        vzlaSaleUSD_tDescription,
        vzlaSaleUSD_validity
    ) {
        this.id                       = id;
        this.vzlaSaleUSD_cost         = vzlaSaleUSD_cost;
        this.vzlaSaleUSD_instantPrice = vzlaSaleUSD_instantPrice;
        this.vzlaSaleUSD_72Price      = vzlaSaleUSD_72Price;
        this.vzlaSaleUSD_7dPrice      = vzlaSaleUSD_7dPrice;
        this.vzlaSaleUSD_15dPrice     = vzlaSaleUSD_15dPrice;
        this.vzlaSaleUSD_dateTime     = vzlaSaleUSD_dateTime;
        this.vzlaSaleUSD_dateHuman    = vzlaSaleUSD_dateHuman;
        this.vzlaSaleUSD_saleUsdBank  = vzlaSaleUSD_saleUsdBank;
        this.vzlaSaleUSD_tDescription = vzlaSaleUSD_tDescription;
        this.vzlaSaleUSD_validity     = vzlaSaleUSD_validity;
    }
}

class VnzlaBuyUSD {
    constructor(
        id,
        vzlaBuyUSD_from1,
        vzlaBuyUSD_to1,
        vzlaBuyUSD_price1,
        vzlaBuyUSD_from2,
        vzlaBuyUSD_to2,
        vzlaBuyUSD_price2,
        vzlaBuyUSD_buyUsdDateTime,
        vzlaBuyUSD_dateHuman,
        vzlaBuyUSD_tWarning,
        vzlaBuyUSD_buyValidity,
    ) {
        this.id                        = id;
        this.vzlaBuyUSD_from1          = vzlaBuyUSD_from1;
        this.vzlaBuyUSD_to1            = vzlaBuyUSD_to1;
        this.vzlaBuyUSD_price1         = vzlaBuyUSD_price1;
        this.vzlaBuyUSD_from2          = vzlaBuyUSD_from2;
        this.vzlaBuyUSD_to2            = vzlaBuyUSD_to2;
        this.vzlaBuyUSD_price2         = vzlaBuyUSD_price2;
        this.vzlaBuyUSD_buyUsdDateTime = vzlaBuyUSD_buyUsdDateTime;
        this.vzlaBuyUSD_dateHuman      = vzlaBuyUSD_dateHuman;
        this.vzlaBuyUSD_tWarning       = vzlaBuyUSD_tWarning;
        this.vzlaBuyUSD_buyValidity    = vzlaBuyUSD_buyValidity;
    }
}

/**
 * Types:
 * 1 - Envío de Remesas a Venezuela
 * 2 - Envío de Remesas al país
 * 3 - Venta de USD
 * 4 - Venta de BTC
 */
class CountryOffersModel {
    constructor(
        id,
        type,
        msrp,
        dateTime,
        dateHuman,
        validity,
        country,
        countryIso,
        countryCurr,
        humanCurr
    ) {
        this.id          = id;
        this.type        = type;
        this.msrp        = msrp;
        this.dateTime    = dateTime;
        this.dateHuman   = dateHuman;
        this.validity    = validity;
        this.country     = country;
        this.countryIso  = countryIso;
        this.countryCurr = countryCurr;
        this.humanCurr   = humanCurr;
    }
}

let vueData = {};

if (localStorage.getItem('imagesData')) {
    vueData = JSON.parse(localStorage.getItem('imagesData'));
} else {
    vueData                    = {};
    vueData['VnzlaBuyUSD']     = new VnzlaBuyUSD(
        -1,
        0,
        0,
        0,
        0,
        0,
        0,
        '',
        {},
        '',
        ''
    );
    vueData['VnzlaSaleUSD']    = new VnzlaSaleUSD(
        -1,
        0,
        0,
        0,
        0,
        0,
        '',
        {},
        '',
        '',
        ''
    );
    vueData['activeCountries'] = null;
    vueData['countries']       = {
        co: {
            iso: 'co',
            slug: 'colombia',
            name: 'Colombia',
            currency: 'COP',
            currency_r: 'Peso Colombiano',
            enabled: 1
        },
        pe: {
            iso: 'pe',
            slug: 'peru',
            name: 'Perú',
            currency: 'PEN',
            currency_r: 'Sol',
            enabled: 1
        },
        us: {
            iso: 'us',
            slug: 'estados_unidos',
            name: 'Estados Unidos',
            currency: 'USD',
            currency_r: 'Dólar',
            enabled: 1
        },
        pt: {
            iso: 'pt',
            slug: 'portugal',
            name: 'Portugal',
            currency: 'EUR',
            currency_r: 'Euro',
            enabled: 1
        },
        es: {
            iso: 'es',
            slug: 'espana',
            name: 'España',
            currency: 'EUR',
            currency_r: 'Euro',
            enabled: 1
        },
        pa: {
            iso: 'pa',
            slug: 'panama',
            name: 'Panamá',
            currency: 'USD',
            currency_r: 'Dólar',
            enabled: 1
        },
        br: {
            iso: 'br',
            slug: 'brasil',
            name: 'Brasil',
            currency: 'BRL',
            currency_r: 'Real brasileño',
            enabled: 1
        },
        ar: {
            iso: 'ar',
            slug: 'argentina',
            name: 'Argentina',
            currency: 'ARA',
            currency_r: 'Austral',
            enabled: 1
        },
        cl: {
            iso: 'cl',
            slug: 'chile',
            name: 'Chile',
            currency: 'CLP',
            currency_r: 'Peso chileno',
            enabled: 1
        },
    };
    $.get('/tools/images-generator/getInitialValues').done(function (data) {
        if (data.latestVnzlaBuyUSD) {
            vueData['VnzlaBuyUSD'] = JSON.parse(data.latestVnzlaBuyUSD.attributes);
        }
        if (data.latestVnzlaSaleUSD) {
            vueData['VnzlaSaleUSD'] = JSON.parse(data.latestVnzlaSaleUSD.attributes);
        }

        if (data.latestForeignOffers.length > 0) {
            initiateActiveCountries(data.latestForeignOffers, vueData);
        }
    });
}

function initiateActiveCountries(data, vueData) {
    $.each(data, function (index, value) {
        let dbOffer         = JSON.parse(value.attributes),
            countryInfo     = vueData.countries[value.country_iso],
            activeCountries = {};

        if (vueData['activeCountries'] === null) {
            activeCountries[countryInfo.iso]           = {};
            activeCountries[countryInfo.iso]['name']   = countryInfo.name;
            activeCountries[countryInfo.iso]['offers'] = [];
            activeCountries[countryInfo.iso]['offers'].push(dbOffer);
        } else {
            if (vueData['activeCountries'].hasOwnProperty(countryInfo.iso)) {
                activeCountries = vueData['activeCountries'];
                activeCountries[countryInfo.iso]['offers'].push(dbOffer);
            } else {
                activeCountries                            = vueData['activeCountries'];
                activeCountries[countryInfo.iso]           = {};
                activeCountries[countryInfo.iso]['name']   = countryInfo.name;
                activeCountries[countryInfo.iso]['offers'] = [];
                activeCountries[countryInfo.iso]['offers'].push(dbOffer);
            }
        }

        vueData['countries'][countryInfo.iso].enabled = 0;
        vueData['activeCountries']                    = activeCountries;
    });
}


export default {
    data() {
        return vueData;
    },
    updated() {
        this.$nextTick(function () {
            $('input[type=datetime-local]').each(function () {
                if ($(this).val() === '') {
                    $(this).val(moment().format('YYYY-MM-DDTHH:mm:ss'));
                }
            });
        });
    },
    mounted() {
        $('input[type=datetime-local]').each(function () {
            if ($(this).val() === '') {
                $(this).val(moment().format('YYYY-MM-DDTHH:mm:ss'));
            }
        });
    },
    methods: {
        saveLocalStorage() {
            let JsonData = JSON.stringify(this.$data);
            localStorage.setItem('imagesData', JsonData);

            this.$forceUpdate();
        },
        saveVzlaSaleUSD(object) {
            let objectKeys = Object.keys(this.VnzlaSaleUSD),
                vueObject  = this;
            $.each(objectKeys, function (index, value) {
                let selector = '#' + value;
                if (value === 'vzlaSaleUSD_dateTime') {
                    let momentMonth = moment($(selector).val()).locale('es').format("MMMM"),
                        momentDay   = moment($(selector).val()).format("D"),
                        momentYear  = moment($(selector).val()).format("YYYY"),
                        momentHour  = moment($(selector).val()).format("h:mm a");

                    vueObject.VnzlaSaleUSD.vzlaSaleUSD_dateHuman = {
                        month: momentMonth,
                        day: momentDay,
                        year: momentYear,
                        hour: momentHour
                    };
                }

                if (value !== 'vzlaSaleUSD_dateHuman' && value !== 'id') {
                    vueObject.VnzlaSaleUSD[value] = $(selector).val() !== '' ? $(selector).val() : 0;
                }
            });

            setTimeout(function () {
                $.post('/tools/images-generator/save-saleUSDven', {
                    '_token': $('meta[name="csrf-token"]').attr("content"),
                    id: vueObject.VnzlaSaleUSD.id,
                    vzlaSaleUSD_cost: vueObject.VnzlaSaleUSD.vzlaSaleUSD_cost,
                    vzlaSaleUSD_instantPrice: vueObject.VnzlaSaleUSD.vzlaSaleUSD_instantPrice,
                    vzlaSaleUSD_72Price: vueObject.VnzlaSaleUSD.vzlaSaleUSD_72Price,
                    vzlaSaleUSD_7dPrice: vueObject.VnzlaSaleUSD.vzlaSaleUSD_7dPrice,
                    vzlaSaleUSD_15dPrice: vueObject.VnzlaSaleUSD.vzlaSaleUSD_15dPrice,
                    vzlaSaleUSD_dateTime: vueObject.VnzlaSaleUSD.vzlaSaleUSD_dateTime,
                    vzlaSaleUSD_dateHuman: vueObject.VnzlaSaleUSD.vzlaSaleUSD_dateHuman,
                    vzlaSaleUSD_saleUsdBank: vueObject.VnzlaSaleUSD.vzlaSaleUSD_saleUsdBank,
                    vzlaSaleUSD_tDescription: vueObject.VnzlaSaleUSD.vzlaSaleUSD_tDescription,
                    vzlaSaleUSD_validity: vueObject.VnzlaSaleUSD.vzlaSaleUSD_validity
                }).done(function ($data) {
                    vueObject.VnzlaSaleUSD.id = $data;
                });

                html2canvas(document.querySelector("#vzlaSaleUSD_image")).then(canvas => {
                    $('#saleUsdImage').append(canvas);
                });

                $.magnificPopup.open({
                    items: {
                        src: '#saleUsdModal',
                        type: 'inline',
                    },
                    callbacks: {
                        open: function () {
                        },
                        close: function () {
                            $('#saleUsdImage').html('');
                        }
                    }
                });

                vueObject.saveLocalStorage();
            }, 1);
        },
        saveVzlaBuyUSD(object) {
            let objectKeys = Object.keys(this.VnzlaBuyUSD),
                vueObject  = this;
            $.each(objectKeys, function (index, value) {
                let selector = '#' + value;
                if (value === 'vzlaBuyUSD_buyUsdDateTime') {
                    let momentMonth = moment($(selector).val()).locale('es').format("MMMM"),
                        momentDay   = moment($(selector).val()).format("D"),
                        momentYear  = moment($(selector).val()).format("YYYY"),
                        momentHour  = moment($(selector).val()).format("h:mm a");

                    vueObject.VnzlaBuyUSD.vzlaBuyUSD_dateHuman = {
                        month: momentMonth,
                        day: momentDay,
                        year: momentYear,
                        hour: momentHour
                    };
                }

                if (value !== 'vzlaBuyUSD_dateHuman' && value !== 'id') {
                    vueObject.VnzlaBuyUSD[value] = $(selector).val() !== '' ? $(selector).val() : 0;
                }
            });

            setTimeout(function () {
                $.post('/tools/images-generator/save-buyUSDven', {
                    '_token': $('meta[name="csrf-token"]').attr("content"),
                    id: vueObject.VnzlaBuyUSD.id,
                    vzlaBuyUSD_from1: vueObject.VnzlaBuyUSD.vzlaBuyUSD_from1,
                    vzlaBuyUSD_to1: vueObject.VnzlaBuyUSD.vzlaBuyUSD_to1,
                    vzlaBuyUSD_price1: vueObject.VnzlaBuyUSD.vzlaBuyUSD_price1,
                    vzlaBuyUSD_from2: vueObject.VnzlaBuyUSD.vzlaBuyUSD_from2,
                    vzlaBuyUSD_to2: vueObject.VnzlaBuyUSD.vzlaBuyUSD_to2,
                    vzlaBuyUSD_price2: vueObject.VnzlaBuyUSD.vzlaBuyUSD_price2,
                    vzlaBuyUSD_buyUsdDateTime: vueObject.VnzlaBuyUSD.vzlaBuyUSD_buyUsdDateTime,
                    vzlaBuyUSD_dateHuman: vueObject.VnzlaBuyUSD.vzlaBuyUSD_dateHuman,
                    vzlaBuyUSD_tWarning: vueObject.VnzlaBuyUSD.vzlaBuyUSD_tWarning,
                    vzlaBuyUSD_buyValidity: vueObject.VnzlaBuyUSD.vzlaBuyUSD_buyValidity,
                }).done(function ($data) {
                    vueObject.VnzlaBuyUSD.id = $data;
                });

                html2canvas(document.querySelector("#vzlaBuyUSD_image")).then(canvas => {
                    $('#buyUsdImage').append(canvas);
                });

                $.magnificPopup.open({
                    items: {
                        src: '#buyUsdModal',
                        type: 'inline',
                    },
                    callbacks: {
                        open: function () {
                        },
                        close: function () {
                            $('#buyUsdImage').html('');
                        }
                    }
                });

                vueObject.saveLocalStorage();
            }, 1);
        },
        calculatePrices(object) {
            this.VnzlaSaleUSD.vzlaSaleUSD_cost         = $(object.target).val();
            this.VnzlaSaleUSD.vzlaSaleUSD_instantPrice = $(object.target).val() * 0.96;
            this.VnzlaSaleUSD.vzlaSaleUSD_72Price      = $(object.target).val() * 0.94;
            this.VnzlaSaleUSD.vzlaSaleUSD_7dPrice      = $(object.target).val() * 0.90;
            this.VnzlaSaleUSD.vzlaSaleUSD_15dPrice     = $(object.target).val() * 0.85;
        },
        addCountry(object) {
            let activeCountries = this.activeCountries,
                countryInfo     = this.countries[$(object.target).val()],
                newOffer        = new CountryOffersModel(
                    -1,
                    '1',
                    0,
                    0,
                    {},
                    '',
                    countryInfo.name,
                    countryInfo.iso,
                    countryInfo.currency,
                    countryInfo.currency_r
                );

            //Add at the first time
            if (activeCountries === null) {
                activeCountries                            = {};
                activeCountries[countryInfo.iso]           = {};
                activeCountries[countryInfo.iso]['name']   = countryInfo.name;
                activeCountries[countryInfo.iso]['offers'] = [];
                activeCountries[countryInfo.iso]['offers'].push(newOffer);
            } else {
                if (this.activeCountries.hasOwnProperty(countryInfo.iso)) {
                    return false;
                } else {
                    activeCountries                            = this.activeCountries;
                    activeCountries[countryInfo.iso]           = {};
                    activeCountries[countryInfo.iso]['name']   = countryInfo.name;
                    activeCountries[countryInfo.iso]['offers'] = [];
                    activeCountries[countryInfo.iso]['offers'].push(newOffer);
                }
            }

            this.countries[$(object.target).val()].enabled = 0;
            this.activeCountries                           = activeCountries;
            this.saveLocalStorage();
        },
        addNewOffer(object) {
            let vueObject     = this,
                country       = $(object.target).attr("data-country"),
                newOffer      = new CountryOffersModel(
                    -1,
                    '1',
                    0,
                    0,
                    {},
                    '',
                    vueObject.countries[country].name,
                    vueObject.countries[country].iso,
                    vueObject.countries[country].currency,
                    vueObject.countries[country].currency_r
                ),
                countryObject = vueObject.activeCountries[country];

            countryObject['offers'].push(newOffer);
            Vue.set(vueObject.activeCountries, country, countryObject);
            this.saveLocalStorage();
            //Care of this.
        },
        saveForeignOffer(object) {
            let vueObject    = this,
                offerCountry = $(object.target).attr("data-offer_country"),
                offerIndex   = $(object.target).attr("data-offer_index"),
                offerModel   = vueObject.activeCountries[offerCountry].offers[offerIndex],
                offerKeys    = Object.keys(offerModel);

            $.each(offerKeys, function (index, value) {
                if (value !== 'country' && value !== 'countryCurr' && value !== 'humanCurr' && value !== 'countryIso'
                    && value !== 'id') {
                    let selector = '#' + offerCountry + '-' + value + '-' + offerIndex;

                    if (value === 'dateTime') {
                        let momentMonth = moment($(selector).val()).locale('es').format("MMMM"),
                            momentDay   = moment($(selector).val()).format("D"),
                            momentYear  = moment($(selector).val()).format("YYYY"),
                            momentHour  = moment($(selector).val()).format("h:mm a");

                        offerModel.dateHuman = {
                            month: momentMonth,
                            day: momentDay,
                            year: momentYear,
                            hour: momentHour
                        };
                    }

                    if (value !== 'dateHuman') {
                        offerModel[value] = $(selector).val() !== '' ? $(selector).val() : 0;
                    }
                }
            });

            vueObject.activeCountries[offerCountry].offers[offerIndex] = offerModel;

            setTimeout(function () {
                vueObject.$forceUpdate();
                $.post('/tools/images-generator/save-foreign', {
                    '_token': $('meta[name="csrf-token"]').attr("content"),
                    id: offerModel.id,
                    type: offerModel.type,
                    msrp: offerModel.msrp,
                    dateTime: offerModel.dateTime,
                    dateHuman: offerModel.dateHuman,
                    validity: offerModel.validity,
                    country: offerModel.country,
                    countryIso: offerModel.countryIso,
                    countryCurr: offerModel.countryCurr,
                    humanCurr: offerModel.humanCurr
                }).done(function ($data) {
                    offerModel.id                                              = $data;
                    vueObject.activeCountries[offerCountry].offers[offerIndex] = offerModel;
                });

                html2canvas(document.querySelector('#' + offerCountry + '-domImage-' + offerIndex))
                    .then(canvas => {
                        $('#' + offerCountry + '-modalImage-' + offerIndex).append(canvas);
                    });

                $.magnificPopup.open({
                    items: {
                        src: '#' + offerCountry + '-modal-' + offerIndex,
                        type: 'inline',
                    },
                    callbacks: {
                        open: function () {
                        },
                        close: function () {
                            $('#' + offerCountry + '-modalImage-' + offerIndex).html('');
                        }
                    }
                });

                vueObject.saveLocalStorage();
            }, 1);
        },
        removeForeignOffer(object) {
            let vueObject    = this,
                offerCountry = $(object.target).attr("data-offer_country"),
                offerIndex   = $(object.target).attr("data-offer_index"),
                confirmation = confirm('¿Esta seguro de querer borrar esta oferta?');

            if (confirmation === true) {
                vueObject.activeCountries[offerCountry].offers.splice(offerIndex);

                this.saveLocalStorage();
                return true;
            }

            return false;
        },
        removeCountry(object) {
            let vueObject    = this,
                offerCountry = $(object.target).attr("data-country_slug"),
                confirmation = confirm('¿Esta seguro de querer borrar éste país?');

            if (confirmation === true) {
                this.countries[offerCountry].enabled = 1;
                Vue.delete(vueObject.activeCountries, offerCountry);

                this.saveLocalStorage();
                return true;
            }

            return false;
        }
    }
}