<template lang="html">
    <div class="__master_chat_dashboard">
        <!-- Chat contact list -->
        <aside class="asidebar border-left">
            <div class="d-flex flex-column h-100">
                <a href="javascript:void(0);"
                   class="__btn_close_bar"
                   @click="closeBar">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
                <a href="javascript:void(0);"
                   class="__btn_open_bar"
                   @click="openBar">
                    <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>
                </a>
                <!--<div class="h-50">
                    <div class="asidebar__header lb-primary">Localbitcoins</div>

                    <ul id="localBitcoins-contacts" class="list-unstyled contact__list">
                        <li class="nav-item">
                            <a class="contact__item text-truncate &#45;&#45;lb &#45;&#45;active" href="#">
                            <span class="contact__avatar">
                                <img src="img/landing/localbitcoins-avatar.png" alt="Karla Garcia" class="object-cover">
                            </span>Karla Garcia
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="contact__item text-truncate &#45;&#45;lb &#45;&#45;active" href="#">
                            <span class="contact__avatar">
                                <img src="img/landing/localbitcoins-avatar.png" alt="Karla Garcia" class="object-cover">
                            </span>Juan Carlos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="contact__item text-truncate &#45;&#45;lb &#45;&#45;active" href="#">
                            <span class="contact__avatar">
                                <img src="img/landing/localbitcoins-avatar.png" alt="Karla Garcia" class="object-cover">
                            </span>José González
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="contact__item text-truncate &#45;&#45;lb &#45;&#45;active" href="#">
                            <span class="contact__avatar">
                                <img src="img/landing/localbitcoins-avatar.png" alt="Karla Garcia" class="object-cover">
                            </span>Micaela Jiménez Guierrez Leon
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="contact__item text-truncate &#45;&#45;lb &#45;&#45;active" href="#">
                            <span class="contact__avatar">
                                <img src="img/landing/localbitcoins-avatar.png" alt="Karla Garcia" class="object-cover">
                            </span>Karla Garcia
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="contact__item text-truncate &#45;&#45;lb &#45;&#45;active" href="#">
                            <span class="contact__avatar">
                                <img src="img/landing/localbitcoins-avatar.png" alt="Karla Garcia" class="object-cover">
                            </span>Juan Carlos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="contact__item text-truncate &#45;&#45;lb &#45;&#45;active" href="#">
                            <span class="contact__avatar">
                                <img src="img/landing/localbitcoins-avatar.png" alt="Karla Garcia" class="object-cover">
                            </span>José González
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="contact__item text-truncate &#45;&#45;lb &#45;&#45;active" href="#">
                            <span class="contact__avatar">
                                <img src="img/landing/localbitcoins-avatar.png" alt="Karla Garcia" class="object-cover">
                            </span>Micaela Jiménez
                            </a>
                        </li>
                    </ul>
                </div>-->
                <div class="h-100 border-top">
                    <div class="asidebar__header text-primary">Chat - Customers <br><small>Exchange Transactions</small></div>
                    <ul id="wallets-contacts" class="list-unstyled contact__list" v-if="orders.length">
                        <li class="nav-item"
                            v-for="order in orders"
                            :key="order.id"
                            :order="order"
                            :user="user">
                            <a class="contact__item text-truncate --active"
                               href="javascript:void(0)"
                               role="button"
                               @click="showChatWindow(order.tracking_id)">
                                <span class="contact__avatar">
                                    <img :src="'/' + order.merchant.person_profile.selfie"
                                         :alt="order.merchant.name"
                                         class="object-cover">
                                </span>
                                {{order.merchant.name}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        <!-- END Chat contact list -->
        <section id="chat" class="chat--container">
            <order-transaction-chat
                    v-for="order in orders"
                    :key="order.id"
                    :order="order"
                    :user="user">
            </order-transaction-chat>
        </section>
    </div>
</template>

<script>
    import axios from 'axios';
    import Pusher from 'pusher-js';

    export default {
        props: ['user'],
        data() {
            return {
                orders: {},
            }
        },
        methods: {
            getOrders() {
                axios.get(window.location.origin + '/api/get-orders/')
                    .then(re => {
                        this.orders = re.data;
                    })
            },
            closeBar() {
                $('.asidebar').addClass('__hidden');
                $('body').removeClass('asidebar--open');
            },
            openBar() {
                $('.asidebar').removeClass('__hidden');
                $('body').addClass('asidebar--open');
            },
            createNotification(body) {
                if (Notification.permission === "granted") {
                    // If it's okay let's create a notification
                    let notification     = new Notification('AmericanKryptosBank', {
                        body: body,
                        icon: "img/cb-img/favicon.png",
                    });
                    notification.onclick = function (event) {
                        event.preventDefault(); // prevent the browser from focusing the Notification's tab
                        notification.close();
                    }
                }
            },
            subscribe() {
                let pusher = new Pusher(
                    '889fce6a69a9c7050bd3',
                    {
                        cluster: 'us2'
                    }
                );

                pusher.subscribe('operator-' + this.user.id + '-channel');
                pusher.bind(
                    'transaction-order',
                    data => {
                        let audio = new Audio('../sounds/dinding.wav');
                        audio.play();
                        this.getOrders();
                        this.createNotification(data.message);
                    }
                );
                pusher.bind(
                    'queue-event',
                    data => {
                        this.getOrders();
                    }
                );
                pusher.bind(
                    'assignations',
                    data => {
                        let audio = new Audio('../sounds/dinding.wav');
                        audio.play();
                        this.createNotification(data.message);
                    }
                )
            },
            showChatWindow(trackingID) {
                let selector = '#chat-' + trackingID + '-window';
                $(selector + ' .chat__tab').removeClass('collapsed');
                $(selector).addClass('__recent_open').show();

                setTimeout(function () {
                    $(selector).removeClass('__recent_open');
                }, 2000);
            }
        },
        created() {
            this.subscribe();
        },
        mounted() {
            this.getOrders();
        }
    }
</script>

<style lang="scss">
    .__btn_close_bar {
        color: red;
        text-align: right;
        padding: 0 10px;
    }

    .__btn_open_bar {
        color: green;
        font-size: 30px;
        font-weight: bold;
        background: #fff;
        padding: 8px;
        border-radius: 8px 0 0 8px;
        position: absolute;
        margin-top: 40vh;
        left: -41px;
        line-height: 30px;
        display:none;
    }

    .asidebar {
        &.__hidden {
            right: -220px;

            .__btn_open_bar {
                display: block;
            }
        }
    }
</style>
