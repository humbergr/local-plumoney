<template lang="html">
    <nav class="nav nav-underline align-items-center flex-nowrap">
        <a class="nav-link ws-nowrap" :class="{'active': (getPathName() === 'home')}" :href="getUrl('/app')">Home</a>
        <div class="dropdown">
            <a class="nav-link dropdown-toggle ws-nowrap" :class="{'active': (getPathName() === 'users')}" href="#usersMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Users</a>
            <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="usersMenu">
                <a class="dropdown-item font-14" :href="getUrl('/profiles-to-approve')">Profiles</a>
                <a class="dropdown-item font-14" :href="getUrl('/user-profiles')">Users</a>
                <a class="dropdown-item font-14" :href="getUrl('/traders')">Traders</a>
                <a class="dropdown-item font-14" :href="getUrl('/create-user')">Invite Users</a>
            </div>
        </div>


        <div class="dropdown">
            <a class="nav-link dropdown-toggle ws-nowrap" :class="{'active': (getPathName() === 'users')}" href="#usersMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Banks</a>
            <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="usersMenu">
 
                <a class="dropdown-item font-14" :href="getUrl('/bancos')">Banks</a>
                <a class="dropdown-item font-14" :href="getUrl('/cuentas')">Accounts</a>
                <a class="dropdown-item font-14" :href="getUrl('/movimientos')">Movements</a>
                 <a class="dropdown-item font-14" :href="getUrl('/transaction-all')">Transactions</a>
 
            </div>
        </div>
        <div class="dropdown">
            <a class="nav-link dropdown-toggle ws-nowrap" :class="{'active': (getPathName() === 'transactions')}" href="#transactionsMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Transactions</a>
            <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="transactionsMenu">
                 <a class="dropdown-item font-14" :href="getUrl('/confirm-card-admin')">Tarjetas x Confirmar</a>
                <a class="dropdown-item font-14" :href="getUrl('/exchange-transactions-list')">Customer Transactions</a>
                <a class="dropdown-item font-14" :href="getUrl('/transactions')">Transactions</a>
                <a class="dropdown-item font-14" :href="getUrl('/error-transactions')">Error Transactions</a>
            </div>
        </div>
        <div class="dropdown">
            <a class="nav-link dropdown-toggle ws-nowrap" :class="{'active': (getPathName() === 'wallets')}" href="#transactionsMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Wallets</a>
            <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="transactionsMenu">
                <a class="dropdown-item font-14" :href="getUrl('/wallets/outgoing')">Outgoing Transactions</a>
                <a class="dropdown-item font-14" :href="getUrl('/wallets/incoming')">Incoming Transactions</a>
                <a class="dropdown-item font-14" :href="getUrl('/wallets/dashboard')">Wallets Dashboard</a>
                <a class="dropdown-item font-14" :href="getUrl('/wallets/users')">Users Transactions Wallet</a>
            </div>
        </div>

        <div class="dropdown">
            <a class="nav-link dropdown-toggle ws-nowrap" :class="{'active': (getPathName() === 'wallets')}" href="#transactionsMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">AML-BSA</a>
            <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="transactionsMenu">
                <a class="dropdown-item font-14" :href="getUrl('/operaciones/envio')">Operaciones de Envio</a>
                <a class="dropdown-item font-14" :href="getUrl('/operaciones/recepcion')">Operaciones de Recepción</a>
                <a class="dropdown-item font-14" :href="getUrl('/operaciones/recarga-de-billetera')">Operaciones de Recarga de Billetera</a>
                <a class="dropdown-item font-14" :href="getUrl('/operaciones/entre-billetera')">Operaciones Entre Billeteras</a>
                <a class="dropdown-item font-14" href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon">Operaciones de Compra de BTC</a>
                <a class="dropdown-item font-14" href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon">Operaciones de Venta de BTC</a>
                <a class="dropdown-item font-14" :href="getUrl('/operaciones/tier-settings')">Configuración Tier</a>

            </div>
        </div>

        <a class="nav-link ws-nowrap" :class="{'active': (getPathName() === 'adv-list')}" :href="getUrl('/app') + '/#adv-list'">Advertisements</a>
        <a class="nav-link ws-nowrap" :class="{'active': (getPathName() === 'publicists')}" :href="getUrl('/admin/publicists')">Brokers</a>
        <a class="nav-link ws-nowrap" :class="{'active': (getPathName() === 'support')}" :href="getUrl('/support-admin')">Support</a>
        <div class="dropdown">
            <a class="nav-link ws-nowrap nav-link-rounded dashboard__navigation__more align-self-center mx-0" href="#menuMore" role="button" title="Mostrar más" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg width="14" height="14" viewBox="0 0 13 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="1.5" cy="1.5" r="1.5" fill="white"/>
                    <circle cx="6.5" cy="1.5" r="1.5" fill="white"/>
                    <circle cx="11.5" cy="1.5" r="1.5" fill="white"/>
                </svg>
            </a>
            <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="menuMore">
                <a class="dropdown-item font-14" :href="getUrl('/dashboard')">Dashboard</a>
                <a class="dropdown-item font-14" :href="getUrl('/user-settings')">Settings</a>
                <!-- <a class="dropdown-item font-14" :href="getUrl('/support-admin')">Support</a> -->
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        data() {
            return {
                path: window.location.pathname,
            }
        },

        methods: {
            getPathName(){
                if (window.location.pathname === '/app'){
                    return 'home';
                } else if (
                    window.location.pathname === '/user-profiles' ||
                    window.location.pathname === '/profiles-to-approve' ||
                    window.location.pathname === '/traders' ||
                    window.location.pathname === '/create-user'
                ) {
                    return 'users';
                } else if (
                    window.location.pathname === '/exchange-transactions-list' ||
                    window.location.pathname === '/transactions' ||
                    window.location.pathname === '/error-transactions'
                ) {
                    return 'transactions';
                } else if (window.location.pathname === '/app/') {
                    return 'adv-list';
                } else if (window.location.pathname.substring(0, 8) === '/wallets') {
                    return 'wallets';
                } else if (
                    window.location.pathname.substring(0, 17) === '/admin/publicists'
                ) {
                    return 'publicists';
                } else if (
                    window.location.pathname === '/support-admin'
                ) {
                    return 'support';
                }
            },

            getUrl(endpoint) {
                return window.location.origin + endpoint;
            },
        },

        mounted() {

        }
    }
</script>

<style lang="css">
</style>
