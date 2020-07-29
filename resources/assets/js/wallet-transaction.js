import axios from 'axios';
import Pusher from 'pusher-js';
import * as numeral from "numeral";

require('moment-countdown');
let moment            = require('moment');
let bodyScrollLock    = require('body-scroll-lock');
let disableBodyScroll = bodyScrollLock.disableBodyScroll;
let enableBodyScroll  = bodyScrollLock.enableBodyScroll;

export default {
    props: ['order', 'user', 'profile', 'accounts'],

    data() {
        return {
            messages: {},
            loader: true,
            message: '',
            file: '',
            incoming_msg: false,
            count: 0,
            merchant: {},
            collapsed: true,
            accounts_to_pay: this.accounts,
            new_messages: 0,
            recomended_price: 0,
            queue_number: 0,
            sendNoHoursMessage: 0,
            operatorID: this.order.attended_by
        }
    },

    methods: {
        getAttachmentUrl(name) {
            return window.location.origin + '/img/orders-imgs/' + this.order.id + '/' + name;
        },

        getMessages() {
            let currentMessages = this.messages.length,
                audio           = new Audio('../../sounds/dinding.wav');
            this.loader         = true;
            axios.get(window.location.origin + '/api/wallets/order-messages/' + this.order.id)
                .then(re => {
                    this.messages     = re.data.messages;
                    this.new_messages = this.messages.length - (currentMessages - this.new_messages);
                    this.loader       = false;
                    this.makingScroll();
                    audio.pause();
                    audio.play();
                })
        },

        getCountryImage(fiat) {
            if (fiat === 'VES') {
                return '/img/landing/flags/ve.svg';
            } else if (fiat === 'COP') {
                return '/img/landing/flags/co.svg';
            } else if (fiat === 'USD') {
                return '/img/landing/flags/us.svg';
            } else if (fiat === 'ARS') {
                return '/img/landing/flags/ar.svg';
            } else if (fiat === 'CLP') {
                return '/img/landing/flags/cl.svg';
            } else if (fiat === 'EUR') {
                return '/img/landing/flags/es.svg';
            }
        },

        getCountryName(fiat) {
            if (fiat === 'VES') {
                return 'Venezuela';
            } else if (fiat === 'COP') {
                return 'Colombia';
            } else if (fiat === 'USD') {
                return 'United States';
            } else if (fiat === 'ARS') {
                return 'Argentina';
            } else if (fiat === 'CLP') {
                return 'Chile';
            } else if (fiat === 'EUR') {
                return 'España';
            }
        },

        capitalizeString(s) {
            return s.charAt(0).toUpperCase() + s.slice(1);
        },

        sendMessage() {
            if (this.message !== '') {
                this.loader = true;

                axios.post(window.location.origin + '/api/wallets/create-order-message/' + this.order.id, {
                    message: this.message,
                    operatorID: this.operatorID,
                    _token: $('meta[name="csrf-token"]').attr("content")
                })
                    .then(re => {
                        this.messages = re.data[1];
                        this.loader   = false;
                        this.message  = '';
                        this.makingScroll();
                    })
            }
        },

        fileHandler(ID, index) {
            let activeInputFile = null,
                vueObject       = this;

            $.each(this.$refs.file, function (index, value) {
                if (value.id === 'photo-voucher-input-' + ID) {
                    activeInputFile = vueObject.$refs.file[index].files[0]
                }
            });
            $('#upload-voucher-' + ID).text('Archivo Seleccionado');
            Vue.set(this.accounts_to_pay[index], 'file', activeInputFile);
        },

        sendFile(ID, index) {
            let fileToUpload = this.accounts_to_pay[index].file,
                vueObject    = this;

            if (fileToUpload) {
                this.loader  = true;
                let formData = new FormData();
                formData.append('file', fileToUpload);
                axios.post(window.location.origin + '/api/wallets/create-order-message/' + this.order.id, formData, {
                    params: {
                        bankAccountID: ID,
                        message: '',
                        _token: $('meta[name="csrf-token"]').attr("content")
                    }
                }).then(re => {
                    if (re.data[0] === 'success') {
                        Vue.set(this.accounts_to_pay[index], 'payed', 1);
                    }
                    this.messages       = re.data[1];
                    this.loader         = false;
                    this.message        = '';
                    this.file           = '';
                    let container       = this.$refs.scroller;
                    container.scrollTop = container.scrollHeight;
                })
            }
        },
        getQueue() {
            axios.get(window.location.origin + '/api/wallets/get-queue/' + this.order.id)
                .then(re => {
                    this.queue_number = re.data;
                })
        },
        getBankAccounts() {
            axios.get(window.location.origin + '/api/wallets/bank-accounts/' + this.order.id)
                .then(re => {
                    this.accounts_to_pay = re.data
                })
        },
        makingScroll() {
            let selector = '';
            if (window.matchMedia("(max-width: 640px)").matches) {
                selector = '#oChat__mobile';
            } else {
                selector = '.oChat__body';
            }
            setTimeout(
                function () {
                    $(selector).stop();
                    $(selector).animate(
                        {
                            scrollTop: $(selector)[0].scrollHeight + 2000
                        },
                        1000
                    );
                },
                1000
            );
        },
        updateTransactionData() {
            axios.get(window.location.origin + '/api/get-transaction-data/' + this.order.id)
                .then(re => {
                    this.order = re.data
                })
        },
        subscribe() {
            let my_id  = this.user.id;
            let pusher = new Pusher('889fce6a69a9c7050bd3', {cluster: 'us2'});
            pusher.subscribe('wallets-channel');
            //message notification
            pusher.bind('notification-chat-' + this.order.id, data => {
                if (data.sender_id != my_id) {
                    this.getMessages();
                    this.createNotification(data.message);
                }
            });

            //new account notification
            pusher.bind('bank-account-' + this.order.id, data => {
                this.getBankAccounts();
            });

            //connected event
            let vueObject = this;
            pusher.connection.bind('connected', function () {
                vueObject.getMessages();
                vueObject.getBankAccounts();
            });

            //cancelled accounts
            pusher.bind('cancel-account-' + this.order.id, data => {
                vueObject.getBankAccounts();
            });

            //no operators
            pusher.bind('no-operators', data => {
                vueObject.getQueue();
            });

            //no operators
            pusher.bind('operator-change', data => {
                vueObject.operatorID = data.operator_id;
            });

            //queue_number
            pusher.bind('queue-event', data => {
                if (vueObject.queue_number > 0) {
                    vueObject.queue_number - data.many_out < 0 ? vueObject.queue_number = 0 :
                        vueObject.queue_number = vueObject.queue_number - data.many_out;
                }

                if (data.order_id === vueObject.order.id && data.order_finished === true) {
                    window.location.replace(window.location.origin + '/wallets/details/' + vueObject.order.id);
                }

                if (data.order_id === vueObject.order.id) {
                    vueObject.operatorID = data.operator_id;
                }
            });
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
        /*  momentCountdown() {
              let vueObject = this;
              setInterval(() => {
                  //Verify Account Status
                  let accountsStatus = null;
                  axios.get(window.location.origin + '/api/bank-accounts-status/' + this.order.id)
                      .then(re => {
                          accountsStatus = re.data[0];
                          $.each(vueObject.accounts_to_pay, function (index, value) {
                              let utcNow    = moment.utc().format('YYYY-MM-DD HH:mm:ss'),
                                  countDown = moment(utcNow).countdown(value.limit_date);

                              if (accountsStatus[vueObject.accounts_to_pay[index]['id']] === 1) {
                                  Vue.set(vueObject.accounts_to_pay[index], 'payed', 1);
                              }

                              if (accountsStatus[vueObject.accounts_to_pay[index]['id']] === 2) {
                                  Vue.set(vueObject.accounts_to_pay[index], 'failed', 1);
                              }

                              if (accountsStatus[vueObject.accounts_to_pay[index]['id']] === 3) {
                                  Vue.set(vueObject.accounts_to_pay[index], 'canceled', 1);
                              }

                              Vue.set(vueObject.accounts_to_pay[index], 'hours', ('0' + countDown.hours).slice(-2));
                              Vue.set(vueObject.accounts_to_pay[index], 'minutes', ('0' + countDown.minutes).slice(-2));
                              Vue.set(vueObject.accounts_to_pay[index], 'seconds', ('0' + countDown.seconds).slice(-2));
                          });

                          $.each(vueObject.messages, function (index, value) {
                              if (value.type === 2) {
                                  let utcNow    = moment.utc().format('YYYY-MM-DD HH:mm:ss'),
                                      countDown = moment(utcNow).countdown(value.json_data.available_minutes);

                                  let bankAccountDbID = vueObject.messages[index]['json_data']['bank_account_db_id'];

                                  if (accountsStatus[bankAccountDbID] === 1) {
                                      Vue.set(vueObject.messages[index], 'payed', 1);
                                  }

                                  if (accountsStatus[bankAccountDbID] === 2) {
                                      Vue.set(vueObject.messages[index], 'failed', 1);
                                  }

                                  if (accountsStatus[bankAccountDbID] === 3) {
                                      Vue.set(vueObject.messages[index], 'canceled', 1);
                                  }

                                  Vue.set(vueObject.messages[index], 'hours', ('0' + countDown.hours).slice(-2));
                                  Vue.set(vueObject.messages[index], 'minutes', ('0' + countDown.minutes).slice(-2));
                                  Vue.set(vueObject.messages[index], 'seconds', ('0' + countDown.seconds).slice(-2));
                              }
                          });

                          let transactionStatus = re.data[1];
                          if (transactionStatus !== 0) {
                              window.location.replace(window.location.origin + '/transaction-success/' + vueObject.order.id);
                          }
                      });
              }, 5000)
          }, */
        fixAccountsData() {
            let vueObject = this;
            $.each(vueObject.accounts_to_pay, function (index, value) {
                vueObject.accounts_to_pay[index].hours   = null;
                vueObject.accounts_to_pay[index].minutes = null;
                vueObject.accounts_to_pay[index].seconds = null;
            });
        },
        switchChat() {
            this.new_messages = 0;
            if (window.matchMedia("(max-width: 640px)").matches) {
                $('#__chat_switch_button').toggleClass("__active");
                $('#transaction-chat-mobile').toggleClass("__active").slideToggle();

                let targetElement = $('#oChat__mobile')[0];

                if ($('body').hasClass('__no_scroll')) {
                    enableBodyScroll(targetElement);
                } else {
                    disableBodyScroll(targetElement);
                }

                $('body').toggleClass('__no_scroll');

                $('.__blackout').fadeToggle();
                this.makingScroll();
            }
        },
        checkHoursCash() {
            let timeNow = new Date().getUTCHours();
            if (timeNow < 12 || timeNow > 23) {
                this.sendNoHoursMessage = 1;
            }
        },
        noBack() {
            history.pushState(null, document.title, location.href);
            window.addEventListener('popstate', function (event) {
                history.pushState(null, document.title, location.href);
            });
        },
        recommendedUtility() {
            if (this.recomended_price < 1) {
                return numeral(1 / this.recomended_price).format('0,0.[00]');
            }

            return numeral(this.recomended_price).format('0,0.[00]');
        }
    },
    mounted() {
        this.getQueue();
        //this.momentCountdown();
        Notification.requestPermission().then(function (result) {
            console.log(result);
        });
        this.switchChat();
        this.recomended_price = parseFloat(this.order.receiver_fiat_amount) / parseFloat(this.order.sender_fiat_amount);
        this.checkHoursCash();
    },
    created() {
        this.subscribe();
        this.noBack();
    },
}
