/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
import $ from 'jquery';

window.$ = window.jQuery = $;
window.Vue           = require('vue');
window.moment        = require('moment');
window.html2canvas   = require('html2canvas');
window.magnificPopup = require('magnific-popup');

import Pusher from 'pusher-js';
import confirm from 'jquery-confirm';

require('jquery-confirm/css/jquery-confirm.css');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Common
import Toastr from "vue-toastr";
import Vue from "vue";
import VCalendar from 'v-calendar';

Vue.use(VCalendar);
require('vue-toastr/src/vue-toastr.scss');
Vue.component('vue-toastr', Toastr);

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
import VueHighcharts from 'vue-highcharts';
Vue.use(VueHighcharts);

//Admin
Vue.component('price-component', require('./components/PriceComponent.vue').default); //Admin
Vue.component('price-component2', require('./components/PriceComponent2.vue').default); //Admin
Vue.component('price-component-v3', require('./components/PriceComponentV3.vue').default); //Admin
Vue.component('advertisements-component', require('./components/AdvertisementsComponent.vue').default);
Vue.component('advertisements-component2', require('./components/AdvertisementsComponent2.vue').default);
Vue.component('advertisements-component-v3', require('./components/AdvertisementsComponentV3.vue').default);
Vue.component('volume-component', require('./components/VolumeComponent.vue').default);
Vue.component('volume-component2', require('./components/VolumeComponent2.vue').default);
Vue.component('search-component', require('./components/SearchComponent.vue').default);
Vue.component('my-ads-component', require('./components/MyAdsComponent.vue').default);
Vue.component('google-places-component', require('./components/GooglePlacesComponent.vue').default);
Vue.component('google-places-component2', require('./components/GooglePlacesComponent2.vue').default);
Vue.component('create-advertisement-component', require('./components/CreateAdvertisementComponent.vue').default);
Vue.component('ads-quick-edit-component', require('./components/AdsQuickEditComponent.vue').default);
Vue.component('order-transaction-chat', require('./components/OrderTransactionChatComponent.vue').default);
Vue.component('transactions-list', require('./components/TransactionsListComponent.vue').default);
Vue.component('transactions-list2', require('./components/TransactionsListComponent2.vue').default);
Vue.component('create-antifraud', require('./components/CreateAntifraudComponent.vue').default);
Vue.component('contact-chat', require('./components/ContactChatComponent.vue').default);
Vue.component('chat', require('./components/ChatComponent.vue').default);
Vue.component('international-transfer-component', require('./components/InternationalTransferComponent.vue').default);
Vue.component('national-transfer-component', require('./components/NationalTransferComponent.vue').default);
Vue.component('cash-deposit-component', require('./components/CashDepositComponent.vue').default);
Vue.component('gift-card-component', require('./components/GiftCardComponent.vue').default);
Vue.component('varo-money-component', require('./components/VaroMoneyComponent.vue').default);
Vue.component('wallets-component', require('./components/WalletsComponent.vue').default);
Vue.component('chat-dashboard', require('./components/ChatDashboardComponent.vue').default);
Vue.component('exchanges-list', require('./components/ExchangesListComponent.vue').default);
Vue.component('remaining-alert', require('./components/RemainingAlertComponent.vue').default);
Vue.component('profile-notification', require('./components/ProfileNotification.vue').default);
Vue.component('btc-wallet', require('./components/BtcWalletComponent.vue').default);
Vue.component('nav-header', require('./components/NavHeaderComponent.vue').default);
Vue.component('tools-generate-images-component', require('./components/ToolsGenerateImages.vue').default);

//Admin Chat Management
Vue.component('admin-chat-dashboard', require('./components/AdminChatDashboardComponent.vue').default);
Vue.component('admin-order-transaction-chat', require('./components/AdminOrderTransactionChatComponent.vue').default);

//Wallets
Vue.component('wallets-chat-dashboard', require('./components/WalletsChatDashboardComponent.vue').default);
Vue.component('wallets-order-transaction-chat', require('./components/WalletsOrderTransactionChatComponent.vue').default);
Vue.component('wallet-transactions-list', require('./components/WalletTransactionsListComponent.vue').default);

//Support
Vue.component('index-support', require('./components/support/IndexComponent.vue').default);
Vue.component('menu-support', require('./components/support/MenuComponent.vue').default);
Vue.component('support-dashboard', require('./components/support/SupportDashboardComponent.vue').default);
Vue.component('tickets-count', require('./components/support/TicketsCountComponent.vue').default);
Vue.component('tickets-chart', require('./components/support/TicketsChartComponent.vue').default);
Vue.component('tickets', require('./components/support/TicketsComponent.vue').default);
Vue.component('ticket-details', require('./components/support/TicketDetailsComponent.vue').default);
Vue.component('support-config', require('./components/support/SupportConfigComponent.vue').default);
//AML-BSA
Vue.component('operaciones', require('./components/AML-BSA/Operaciones.vue').default);
Vue.component('transaction-component', require('./components/AML-BSA/TransactionComponent.vue').default);
Vue.component('modal-transaction-component', require('./components/AML-BSA/ModalTransactionComponent.vue').default);
Vue.component('operaciones-btc', require('./components/AML-BSA/OperacionesBTC.vue').default);
Vue.component('transaction-btc-component', require('./components/AML-BSA/TransactionBTCComponent.vue').default);
Vue.component('tier-settings-component', require('./components/AML-BSA/TierSettingsComponent.vue').default);

//Simultaneous
import AdminUsersStates from './components/AdminUsersStates.vue';

const adminVue = new Vue({
    el: '#app',
    components: {
        'admin-users-states': AdminUsersStates
    }
});

$(function () {
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
});
