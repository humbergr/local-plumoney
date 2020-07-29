require('./bootstrap');

import Toastr from "vue-toastr";
import Vue from "vue";
import $ from 'jquery';
import confirm from 'jquery-confirm';
import router from './routes';
import VueProgressBar from 'vue-progressbar'
import anime from 'animejs';

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px'
})

window.$ = window.jQuery = $;
window.Vue = require('vue');
require('select2');
require('@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css');
require('vue-toastr/src/vue-toastr.scss');
Vue.component('vue-toastr', Toastr);
Vue.component('home-component', require('./components/HomeComponent.vue').default);

const app = new Vue({
                    el: '#app',
                    router
                });
