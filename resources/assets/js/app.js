/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import $ from 'jquery';
import confirm from 'jquery-confirm';

import router from './routes';
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px'
  })

window.$ = window.jQuery = $;

window.Vue = require('vue');
window.moment = require('moment');
window.html2canvas = require('html2canvas');
window.magnificPopup = require('magnific-popup');
window.intlTelInput = require('intl-tel-input');
require('select2');
require('@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Common
import Toastr from "vue-toastr";
import Vue from "vue";

require('vue-toastr/src/vue-toastr.scss');
Vue.component('vue-toastr', Toastr);
//END Common

//Frontend
Vue.component('login-transaction', require('./components/LoginTransactionComponent.vue').default);
Vue.component('merchant-wallets-component', require('./components/MerchantWalletsComponent.vue').default);
Vue.component('home-transaction', require('./components/HomeTransactionComponent.vue').default);
Vue.component('add-card', require('./components/AddCardComponent.vue').default);
Vue.component('add-card-v2', require('./components/AddCardComponent2.vue').default);
Vue.component('send-money', require('./components/SendMoneyComponent.vue').default);
Vue.component('send-cash', require('./components/SendCashComponent.vue').default);
Vue.component('home-order', require('./components/HomeOrderComponent.vue').default);
Vue.component('stockholders', require('./components/StockHoldersComponent.vue').default);
Vue.component('home-transaction-mvp', require('./components/HomeTransactionComponent2.vue').default);
Vue.component('btc-price', require('./components/BtcPriceComponent.vue').default);
Vue.component('signin-component', require('./components/SigninComponent.vue').default);
Vue.component('layout-signin', require('./components/LayoutSigninComponent.vue').default);
Vue.component('wallet-transactions', require('./components/WalletTransactionsComponent.vue').default);
Vue.component('wallet-charges', require('./components/ChargeComponent.vue').default);
Vue.component('wallet-send', require('./components/SendComponent.vue').default);
Vue.component('wallet-transfer', require('./components/TransferComponent.vue').default);
Vue.component('wallet-withdraw', require('./components/WithdrawComponent.vue').default);
Vue.component('countdown', require('./components/CountdownComponent.vue').default);
Vue.component('wallets-countdown', require('./components/WalletsCountdownComponent.vue').default);
Vue.component('convert-money', require('./components/ConvertMoneyComponent.vue').default);
Vue.component('wallet-transaction', require('./components/WalletTransactionComponent.vue').default);


Vue.component('home-component', require('./components/HomeComponent.vue').default);


const app = new Vue({
                        el: '#app',
                        router
                    });

$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    if ($('#merchant-select-country').length) {
        getStates();
        getCompanyStates();
    }

    function getStates() {
        let countryISO  = $('#merchant-select-country').val(),
            actualState = $('.__dynamic_states').attr('data-user-state');
        $.get('/get-countries?countryISO=' + countryISO, function (data) {
            if ($.type(data) === 'object') {
                $('#merchant-select-state').remove();
                $('.__dynamic_states').append('<select class="custom-select" name="UserPersonProfile[state]" id="merchant-select-state"></select>');
                $('#merchant-select-state').html(generateOptions(data, actualState));
            } else {
                $('#merchant-select-state').remove();
                $('.__dynamic_states').append('<input class="form-control" type="text" value="' + actualState + '" name="UserPersonProfile[state]" id="merchant-select-state">')
            }
        });
    }

    function getCompanyStates() {
        let countryISO  = $('#company-select-country').val(),
            actualState = $('.__company_dynamic_states').attr('data-user-state');
        $.get('/get-countries?countryISO=' + countryISO, function (data) {
            if ($.type(data) === 'object') {
                $('#company-select-state').remove();
                $('.__company_dynamic_states').append('<select class="custom-select" name="UserCompanyProfile[state]" id="company-select-state"></select>');
                $('#company-select-state').html(generateOptions(data, actualState));
            } else {
                $('#company-select-state').remove();
                $('.__company_dynamic_states').append('<input class="form-control" type="text" value="' + actualState + '" name="UserCompanyProfile[state]" id="company-select-state">')
            }
        });
    }

    function generateOptions(data, actualState) {
        let html = '';
        $.each(data, function (index, value) {
            let selected = '';
            if (actualState === index) {
                selected = 'selected';
            }
            html += '<option ' + selected + ' value="' + index + '">' + value + '</option>';
        });

        return html;
    }

    $('#merchant-select-country').change(function () {
        getStates();
    });

    $('#company-select-country').change(function () {
        getCompanyStates();
    });

    $('#merchant-settings-change-pass').click(function () {
        document.getElementById('merchant-username').toggleAttribute('disabled');
        document.getElementById('merchant-password').toggleAttribute('disabled');
    });

    $('#active-natural-form').click(function () {
        $('#active-natural-form').addClass('--active');
        $('#active-company-form').removeClass('--active');
        $('#merchant-company').slideUp(function () {
            $('#merchant-natural').slideDown();
        });
    });

    $('#active-company-form').click(function () {
        $('#active-natural-form').removeClass('--active');
        $('#active-company-form').addClass('--active');
        $('#merchant-natural').slideUp(function () {
            $('#merchant-company').slideDown();
        });
    });

    function changePreview(input, errorClass, imageClass) {
        if (input && input[0]) {
            let reader = new FileReader();
            if (input[0].size > 4000000) {
                $(this).val('');
                $(errorClass).fadeIn();
                return false;
            }

            $(errorClass).fadeOut();

            reader.onload = function (e) {
                $(imageClass).attr('src', e.target.result);
            };

            reader.readAsDataURL(input[0]);
        }
    }

    $('#__no_second_last_name').click(function () {
        $('#__u_second_last_name').val('-- No Posee --').attr('readonly', true);
    });

    $('#__no_second_name').click(function () {
        $('#__u_second_name').val('-- No Posee --').attr('readonly', true);
    });

    $('#__no_main_local').click(function () {
        $('#__u_local_main').val('--No_Posee--').attr('readonly', true);
    });

    $("#__selfie_input").change(function () {
        changePreview(this.files, '.__selfie_photo_error', '#__selfie_img');
        changePreview(this.files, '.__selfie_photo_error', '#__selfie_img_too');
    });

    $("#__selfie_id_input").change(function () {
        changePreview(this.files, '.__selfie_id_photo_error', '#__selfie_id_img');
        changePreview(this.files, '.__selfie_id_photo_error', '#__selfie_id_img_too');
    });

    $("#__id_input").change(function () {
        changePreview(this.files, '.__id_photo_error', '#__id_img');
        changePreview(this.files, '.__id_photo_error', '#__id_img_too');
    });


    $("#__id_input2").change(function () {
        changePreview(this.files, '.__id_photo_error2', '#__id_img2');
        changePreview(this.files, '.__id_photo_error2', '#__id_img_too2');
    });

    $("#__logo_input").change(function () {
        changePreview(this.files, '.__logo_photo_error', '#__logo_img');
    });

    $("#__public_service_input").change(function () {
        changePreview(this.files, '.__public_service_photo_error', '#__public_service_img');
        changePreview(this.files, '.__public_service_photo_error', '#__public_service_img_too');
    });

    $("#__id_comp_input").change(function () {
        changePreview(this.files, '.__id_comp_photo_error', '#__id_comp_img');
        changePreview(this.files, '.__id_comp_photo_error', '#__id_comp_img_too');
    });

    $("#__tax_id_input").change(function () {
        changePreview(this.files, '.__tax_id_photo_error', '#__tax_id_img');
        changePreview(this.files, '.__tax_id_photo_error', '#__tax_id_img_too');
    });

    $('#__btn_personal_data').click(function () {
        $(this).addClass('active');
        $('#__btn_personal_id_data').removeClass('active');
        $('#__personal_id_data_tab').fadeOut(function () {
            $('#__personal_data_tab').fadeIn();
        });
    });

    $('#__btn_personal_id_data').click(function () {
        $(this).addClass('active');
        $('#__btn_personal_data').removeClass('active');
        $('#__personal_data_tab').fadeOut(function () {
            $('#__personal_id_data_tab').fadeIn();
        });
    });

    $('#__btn_company_data_tab').click(function () {
        $(this).addClass('active');
        $('#__btn_company_id_data_tab').removeClass('active');
        $('#__company_id_data_tab').fadeOut(function () {
            $('#__company_data_tab').fadeIn();
        });
    });

    $('#__btn_company_id_data_tab').click(function () {
        $(this).addClass('active');
        $('#__btn_company_data_tab').removeClass('active');
        $('#__company_data_tab').fadeOut(function () {
            $('#__company_id_data_tab').fadeIn();
        });
    });

    $('#__verify_phone_button').click(function () {
        let encodedPrefix = $('#__u_mobile_prefix').val().replace('+', '%2B');
        axios.get(window.location.origin + '/user/send-sms-confirmation?phone_number=' +
                  encodedPrefix + encodeURI(' ' + $('#__u_mobile_main').val()) + '&user_id=' +
                  $('#__u_user_id').val()
        )
             .then(re => {
                 if (re.data.error) {
                     $('#verify-phone').modal('hide');
                     toastr.error('Hey un error en el número de teléfono. Por favor, verifique.');
                 }
             })
    });

    $('#__verify_code').click(function () {
        let encodedPrefix = $('#__u_mobile_prefix').val().replace('+', '%2B');
        axios.get(window.location.origin + '/user/confirm-sms-code?phone_number=' +
                  encodedPrefix + encodeURI(' ' + $('#__u_mobile_main').val()) + '&user_id=' +
                  $('#__u_user_id').val() + '&sms_code=' + $('#__verification_code_input').val()
        )
             .then(re => {
                 if (re.data.success) {
                     $('#__verify_phone_button').remove();
                     $('#__verified_phone').show();
                     $('#verify-phone').modal('hide');
                     $('#__u_mobile_prefix').attr('readonly', true);
                     $('#__u_mobile_prefix').addClass('__disabled_select');
                     $('#__u_mobile_main').attr('readonly', true);
                 }
             })
    });

    let cellphoneInput = document.querySelector("#__u_mobile_main");
    let uLocalPhoneInput = document.querySelector("#__u_local_main");

    if (cellphoneInput) {
        let iti   = intlTelInput(cellphoneInput, {
                initialCountry    : "us",
                preferredCountries: ["us", "ve", /*"ar", "co"*/],
                separateDialCode  : true,
                utilsScript       : "/js/intlTelInput-utils.js" // just for formatting/placeholders etc
            }),
            itiul = intlTelInput(uLocalPhoneInput, {
                initialCountry    : "us",
                preferredCountries: ["us", "ve", /*"ar", "co"*/],
                separateDialCode  : true,
                utilsScript       : "/js/intlTelInput-utils.js" // just for formatting/placeholders etc
            });

        if ($('#__u_mobile_main').val() !== '') {
            iti.setNumber($('#__u_mobile_prefix').val() + $('#__u_mobile_main').val())
        }

        if ($('#__u_local_main').val() !== '') {
            itiul.setNumber($('#__u_local_prefix').val() + $('#__u_local_main').val())
        }

        cellphoneInput.addEventListener("countrychange", function () {
            let countryData = iti.getSelectedCountryData();
            $('#__u_mobile_prefix').val('+' + countryData.dialCode);
        });

        uLocalPhoneInput.addEventListener("countrychange", function () {
            let countryData = itiul.getSelectedCountryData();
            $('#__u_local_prefix').val('+' + countryData.dialCode);
        });
    }

    let cCellphoneInput = document.querySelector("#__c_mobile_main");
    let cLocalPhoneInput = document.querySelector("#__c_office_main");

    if (cCellphoneInput) {
        let iti   = intlTelInput(cCellphoneInput, {
                initialCountry    : "us",
                preferredCountries: ["us", "ve", /*"ar", "co"*/],
                separateDialCode  : true,
                utilsScript       : "/js/intlTelInput-utils.js" // just for formatting/placeholders etc
            }),
            itiul = intlTelInput(cLocalPhoneInput, {
                initialCountry    : "us",
                preferredCountries: ["us", "ve", /*"ar", "co"*/],
                separateDialCode  : true,
                utilsScript       : "/js/intlTelInput-utils.js" // just for formatting/placeholders etc
            });

        if ($('#__c_mobile_prefix').val() !== '') {
            iti.setNumber($('#__c_mobile_prefix').val() + $('#__c_mobile_main').val())
        }

        if ($('#__c_office_prefix').val() !== '') {
            itiul.setNumber($('#__c_office_prefix').val() + $('#__c_office_main').val())
        }

        cCellphoneInput.addEventListener("countrychange", function () {
            let countryData = iti.getSelectedCountryData();
            $('#__c_mobile_prefix').val('+' + countryData.dialCode);
        });

        cLocalPhoneInput.addEventListener("countrychange", function () {
            let countryData = itiul.getSelectedCountryData();
            $('#__c_office_prefix').val('+' + countryData.dialCode);
        });
    }

    $("#person-select-country").select2(
        {
            theme         : 'bootstrap4',
            width         : '100%',
            placeholder   : 'Selecciona tu país',
            templateResult: function (item) {
                return format(item, false);
            }
        }
    );
});

function format(item, state) {

    if (!item.id) {
        return item.text;
    }

    let countryUrl = "https://lipis.github.io/flag-icon-css/flags/4x3/";
    let stateUrl = "https://oxguy3.github.io/flags/svg/us/";
    let url = state ? stateUrl : countryUrl;
    let img = $("<img>", {
        class: "img-flag mx-2",
        width: 22,
        src  : url + item.element.value.toLowerCase() + ".svg"
    });

    let span = $("<span>", {
        text: " " + item.text
    });

    span.prepend(img);
    return span;
}

require('./transaction-success');
