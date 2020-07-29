import axios from 'axios';
import Pusher from 'pusher-js';
import moment from 'moment';
import countdown from 'moment-countdown';

export default {
    props: ['order', 'user'],

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
            send_bank: false,
            transfer_chat: false,
            bank_name: '',
            fiat_amount: '',
            account_type: '',
            account_number: '',
            account_owner: '',
            id_number: '',
            available_minutes: '',
            attended: 0,
            attended_by: '',
            activeUsers: null
        }
    },

    methods: {
        collapsedFunc(event) {
            let container  = $(event.target).parent('header').siblings('.chat__body').find('.chat__msgs');
            this.collapsed = !this.collapsed;
            container.stop();
            container.animate(
                {
                    scrollTop: container[0].scrollHeight + 2000
                },
                1000
            );
        },

        sendBankAccount() {
            if (this.bank_name === '' || this.account_number === '') {
                this.$refs.toastr.e('Por favor completa los campos requeridos.');
                return;
            }
            this.loader = true;
            axios.post(window.location.origin + '/send-bank-account/' + this.order.id, {
                bankname: this.bank_name,
                account_type: this.account_type,
                account_number: this.account_number,
                account_owner: this.account_owner,
                id_number: this.id_number,
                fiat_amount: this.fiat_amount,
                available_minutes: this.available_minutes,
                _token: $('meta[name="csrf-token"]').attr("content")
            }).then(re => {
                this.showBankForm();
                this.$refs.toastr.s('Account info has been sent');
                this.messages          = re.data[1];
                this.bank_name         = '';
                this.account_type      = '';
                this.account_number    = '';
                this.account_owner     = '';
                this.id_number         = '';
                this.available_minutes = '';
                this.loader            = false;
            })
        },

        requestTransferChat() {
            this.loader  = true;
            let destUser = $('#__transfer_chat_select').val();
            //Axios Transfer Chat
            axios.get(window.location.origin + '/transfer-chat-user/' + this.order.id + '/' + destUser)
                .then(re => {
                    this.showTransferChatForm();
                    this.loader  = false;
                })
        },

        textFocus() {
            this.incoming_msg = false;
        },

        getAttachmentUrl(name) {
            return window.location.origin + '/img/orders-imgs/' + this.order.id + '/' + name;
        },

        getCBAttachmentUrl(name) {
            return window.location.origin + '/img/contact-imgs/' + name;
        },

        showBankForm() {
            this.send_bank = !this.send_bank;
        },

        showTransferChatForm() {
            this.transfer_chat = !this.transfer_chat;
        },

        getDate(date) {
            let newDate = new Date(date);
            return (newDate.getMonth() + 1) + '/' + newDate.getDate() + '/' + newDate.getFullYear();
        },

        fileHandler() {
            this.file = this.$refs.file.files[0];
        },

        sendMessage(event) {
            let textarea = $(event.target);

            if (!event.shiftKey) {
                event.preventDefault();
                let controlMessage = this.message.replace(/[\n\r\s]+/gi, '');

                if (controlMessage.length > 0 || this.file !== '') {
                    textarea.css('height', 35);

                    this.loader  = true;
                    let formData = new FormData();
                    formData.append('file', this.file);
                    axios.post(
                        window.location.origin + '/api/create-order-message/' + this.order.id,
                        formData,
                        {
                            params: {
                                message: this.message,
                                _token: $('meta[name="csrf-token"]').attr("content")
                            }
                        }
                    ).then(re => {
                        this.messages = re.data[1];
                        this.loader   = false;
                        this.message  = '';
                        this.file     = '';
                        let container = this.$refs.scroller;
                        $(container).stop();
                        $(container).animate(
                            {
                                scrollTop: container.scrollHeight + 2000
                            },
                            1000
                        );
                    });
                }
            }
        },

        getMessages() {
            this.loader = true;
            axios.get(window.location.origin + '/api/order-messages/' + this.order.id)
                .then(re => {
                    this.messages = re.data.messages;
                    this.merchant = re.data.user;
                    this.loader   = false;
                    let container = this.$refs.scroller;

                    $(container).stop();
                    $(container).animate(
                        {
                            scrollTop: container.scrollHeight + 2000
                        },
                        1000
                    );
                })
        },
        createNotification(body) {
            if (Notification.permission === "granted") {
                // If it's okay let's create a notification
                let notification     = new Notification('AmericanKryptosBank', {
                    body: body,
                    icon: "/img/cb-img/favicon.png",
                });
                notification.onclick = function (event) {
                    event.preventDefault(); // prevent the browser from focusing the Notification's tab
                    notification.close();
                }
            }
        },

        subscribe() {
            let my_id  = this.user.id,
                pusher = new Pusher(
                    '889fce6a69a9c7050bd3',
                    {
                        cluster: 'us2'
                    }
                );

            pusher.subscribe('admins-super-channel');
            pusher.bind(
                'notification-chat-' + this.order.id,
                data => {
                    if (data.sender_id !== my_id && typeof data.message !== 'undefined' && data.message.length) {
                        this.getMessages();
                        this.createNotification(data.message);
                        this.incoming_msg = true;
                        this.showChatWindow();
                        let audio = new Audio('../sounds/new-message.wav');
                        audio.play();
                    }

                    if (data.sender_id !== my_id && typeof data.attended_by !== 'undefined' && data.attended_by.length) {
                        this.attended    = data.attended;
                        this.attended_by = data.attended_by;
                    }
                }
            );
        },
        getAccountStatus() {
            let vueObject      = this;
            let accountsStatus = null;
            axios.get(window.location.origin + '/api/bank-accounts-status/' + this.order.id)
                .then(re => {
                    accountsStatus = re.data;
                    $.each(vueObject.messages, function (index, value) {
                        if (value.type === 2) {

                            let bankAccountDbID = vueObject.messages[index]['json_data']['bank_account_db_id'];

                            if (accountsStatus[bankAccountDbID] === 1) {
                                Vue.set(vueObject.messages[index], 'payed', 1);
                            }

                            if (accountsStatus[bankAccountDbID] === 2) {
                                Vue.set(vueObject.messages[index], 'failed', 1);
                            }
                        }
                    });
                });
        },
        momentCountdown() {
            let vueObject = this;
            setInterval(() => {
                let accountsStatus = null;
                axios.get(window.location.origin + '/api/bank-accounts-status/' + this.order.id)
                    .then(re => {
                        accountsStatus = re.data;
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
                                Vue.set(vueObject.messages[index], 'hours', ('0' + countDown.hours).slice(-2));
                                Vue.set(vueObject.messages[index], 'minutes', ('0' + countDown.minutes).slice(-2));
                                Vue.set(vueObject.messages[index], 'seconds', ('0' + countDown.seconds).slice(-2));
                            }
                        });
                    });
            }, 1000)
        },
        momentCountdown2() {
            let vueObject      = this;
            let accountsStatus = null;
            axios.get(window.location.origin + '/api/bank-accounts-status/' + this.order.id)
                .then(re => {
                    accountsStatus = re.data;
                });
            setInterval(() => {
                $.each(vueObject.messages, function (index, value) {
                    if (value.type === 2) {
                        let utcNow    = moment.utc().format('YYYY-MM-DD HH:mm:ss'),
                            countDown = moment(utcNow).countdown(value.json_data.available_minutes);

                        let seconds = vueObject.messages[index].seconds;

                        Vue.set(vueObject.messages[index], 'hours', ('0' + countDown.hours).slice(-2));
                        Vue.set(vueObject.messages[index], 'minutes', ('0' + countDown.minutes).slice(-2));
                        Vue.set(vueObject.messages[index], 'seconds', ('0' + countDown.seconds).slice(-2));

                        if (parseInt(seconds) !== 0 && parseInt(seconds) < countDown.seconds) {
                            Vue.set(vueObject.messages[index], 'failed', 1);
                        }
                    }
                });
            }, 1000)
        },
        textareaSize(event) {
            let textarea = $(event.target);

            textarea.css('height', 0);
            let height = Math.min(150, textarea[0].scrollHeight);
            textarea.css('height', height);
        },
        cancelBankAccount(id) {
            //console.log(id);
            this.loader = true;
            axios.post(window.location.origin + '/cancel-bank-account/', {
                account_id: id,
                _token: $('meta[name="csrf-token"]').attr("content")
            }).then(re => {
                this.$refs.toastr.s('La cuenta ha sido cancelada');
                this.messages = re.data[1];
                this.loader   = false;
            })
        },
        reclaim() {
            axios.get(window.location.origin + '/api/reclaim/' + this.order.id)
                .then(re => {
                    this.attended    = 1;
                    this.attended_by = re.data;
                });
            return true;
        },
        hideChatWindow() {
            $('#chat-' + this.order.tracking_id + '-window').hide();
        },
        showChatWindow() {
            let selector   = '#chat-' + this.order.tracking_id + '-window';
            this.collapsed = false;
            $(selector).show();
        },
        clearVisualHelpers() {
            $('#chat-' + this.order.tracking_id + '-window').removeClass('__recent_open');
        },
        getActiveUsers() {
            let vueObject = this;

            axios.get(
                window.location.origin + '/get-active-users/true'
            ).then(re => {
                vueObject.activeUsers = re.data.activeUsers;
            })
        },
    },

    created() {
        this.subscribe();
    },

    mounted() {
        if (this.order.attended_by !== null) {
            this.attended    = 1;
            this.attended_by = this.order.master_account.name;
        }
        this.getMessages();
        this.getActiveUsers();
        this.momentCountdown2();
        Notification.requestPermission().then(function (result) {
            console.log(result);
        });
    }

}