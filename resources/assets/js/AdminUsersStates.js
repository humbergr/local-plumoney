import axios from 'axios';
import IdleVue from 'idle-vue';
import Vue from 'vue';
import Vuex from 'vuex';
import Pusher from 'pusher-js';

Vue.use(Vuex);

const eventsHub = new Vue();
const store     = new Vuex.Store();

Vue.use(IdleVue, {
    eventEmitter: eventsHub,
    idleTime: 600000,
    store
});

export default {
    name: 'admin-users-states',
    data() {
        return {
            activeUsers: null,
            clientUser: null,
            alertForIdle: null,
            idleTimeOut: null,
            activePromise: null,
            keepAlivePoll: null
        };
    },
    onIdle() {
        // this.setIdleOnUser();
    },
    onActive() {
        // this.rmIdleOnUser();
    },
    methods: {
        setIdleOnUser() {
            let vueObject = this;

            if (vueObject.alertForIdle === null) {
                this.alertForIdle = $.alert({
                    title: '¡Alerta!',
                    content: 'Tienes 10 minutos de inactividad. <br> En 10 minutos más cerraremos tu sesión.',
                    type: 'red'
                });

                if (vueObject.activePromise === null) {
                    vueObject.activePromise = axios.get(window.location.origin + '/set-idle-user/true').then(re => {
                        vueObject.activePromise = null;
                        this.activeUsers        = re.data.activeUsers;
                        this.clientUser         = re.data.clientUser;
                        vueObject.idleTimeOut   = setTimeout(function () {
                            vueObject.activePromise = axios.get(window.location.origin + '/logout')
                                .then(function (rre) {
                                    vueObject.activePromise = null;
                                    document.cookie         = "laravel_session= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
                                    window.location.href    = window.location.origin + '/login';
                                });
                        }, 600000);
                    });
                }
            }
        },
        rmIdleOnUser() {
            let vueObject = this;

            if (vueObject.alertForIdle) {
                vueObject.alertForIdle.close();
                vueObject.alertForIdle = null;
            }

            clearTimeout(vueObject.idleTimeOut);
            vueObject.idleTimeOut = null;

            if (vueObject.activePromise === null) {
                vueObject.activePromise = axios.get(window.location.origin + '/rm-idle-user/true').then(re => {
                    vueObject.activePromise = null;
                    this.activeUsers        = re.data.activeUsers;
                    this.clientUser         = re.data.clientUser;
                });
            }
        },
        getActiveUsers(onLoad = false) {
            let vueObject = this;

            if (vueObject.activePromise === null) {
                vueObject.activePromise = axios.get(
                    window.location.origin + '/get-active-users/true'
                ).then(re => {
                    if (re.data.clientUser.length === 0) {
                        window.location.replace(window.location.origin + '/login');
                    }

                    vueObject.activePromise = null;
                    if (this.clientUser === null) {
                        this.clientUser = re.data.clientUser;
                    } else {
                        //Verify if is active on this windows or is active on other windows
                        if (re.data.clientUser.is_idle === 1 && this.clientUser.is_idle !== 1 &&
                            this.isAppIdle === false) {
                            console.log('appidel is false');
                            vueObject.rmIdleOnUser();
                        }
                        if (re.data.clientUser.is_idle === 0 && this.clientUser.is_idle === 1) {
                            console.log('appidel is false');
                            vueObject.rmIdleOnUser();
                        }
                        //If are the same
                        if (re.data.clientUser.is_idle === 1 && this.clientUser.is_idle === 1) {
                            this.clientUser = re.data.clientUser;
                        }
                    }

                    this.activeUsers = re.data.activeUsers;

                    if (onLoad) {
                        if (this.clientUser.is_idle === 1) {
                            this.rmIdleOnUser()
                        }
                    }
                })
            }
        },
        subscribe() {
            let pusher = new Pusher('889fce6a69a9c7050bd3', {
                cluster: 'us2',
                forceTLS: true
            });

            pusher.subscribe('admin-states-channel');
            pusher.bind('__admin_logged_in', data => {
                this.getActiveUsers();
            });
            pusher.bind('__admin_logged_out', data => {
                this.getActiveUsers();
            });
            pusher.bind('__admin_is_idle', data => {
                this.getActiveUsers();
            });
            pusher.bind('__admin_is_not_idle', data => {
                this.getActiveUsers();
            });
        },
        closeSite: function handler(event) {
            axios.get(window.location.origin + '/set-idle-user/true').then(re => {
                this.activeUsers = re.data.activeUsers;
                this.clientUser  = re.data.clientUser;
            });
        },
        displayAdminsList() {
            let trigger      = $('.__trigger'),
                floatingInfo = $('.__floating_info');

            if (trigger.hasClass('__active')) {
                trigger.removeClass('__active');
                floatingInfo.removeClass('__active');
            } else {
                trigger.addClass('__active');
                floatingInfo.addClass('__active');
            }
        },
        keepAlivePolling() {
            if (this.keepAlivePoll === null) {
                axios.get(window.location.origin + '/im-alive')
                    .then(function (rre) {
                    });
            }

            this.keepAlivePoll = setInterval(() => {
                axios.get(window.location.origin + '/im-alive')
                    .then(function (rre) {
                    });
            }, 60000)
        }
    },
    watch: {},
    mounted() {
        this.getActiveUsers(true);
    },
    created() {
        this.subscribe();
        this.keepAlivePolling();
        window.addEventListener('beforeunload', this.closeSite)
    },
    beforeDestroy() {
        clearInterval(this.keepAlivePoll);
    }
}
