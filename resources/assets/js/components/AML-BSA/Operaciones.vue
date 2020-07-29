<template lang="html">
    <div>
        <vue-toastr ref="toastr"></vue-toastr>

        <section id="analytics" class="pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 v-if="type == 1" class="sections-titles">Recarga de Billetera</h3>
                        <h3 v-if="type == 2" class="sections-titles">Operaciones de Envio</h3>
                        <h3 v-if="type == 4" class="sections-titles">Operaciones Entre Billetera</h3>
                        <h3 v-if="type == 5" class="sections-titles">Operaciones de Recepcion</h3>
                    </div>
                </div>
            </div>
        </section>

        <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
            <form v-on:submit.prevent="onSubmit" method="get" class="form-inline flex-md-nowrap ml-md-3">
                <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" v-model="userName" placeholder="First Name">
                </div>

                <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" v-model="userLastname" placeholder="Last Name">
                </div>

                <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" v-model="userEmail" placeholder="Email">
                </div>

                <div  class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white text-muted">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                    <input type="text"
                           id="creation-date-filter"
                           class="form-control"
                           aria-label="Creation date filter"
                           aria-describedby="creation-date-filter">
                    

                </div>

                <div class="input-group mb-3 mb-md-0">
                    <input type="submit" class="ml-3 btn btn-primary" value="Search">
                </div>
            
                
            </form>
        </div>

        <section id="transactions" class="py-section-1">
            <div class="container-fluid">

                <div class="card" id="list">
                    <div class="loader--wrapper" :class="{ '--loading': loader }">
                        <table class="table table-striped table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            User Red Flag
                                        </h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Name
                                        </h5>
                                    </th>
                                    <th class="text-center" @click="sort('day')">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Dialy Amount
                                        </h5>
                                        <p style="margin: 0;" class="text-muted">{{dateList.getDayFormat}}</p>
                                    </th>
                                    <th class="text-center" @click="sort('week')">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Weekly Amount
                                        </h5>
                                        <p style="margin: 0;" class="text-muted">{{dateList.weekDate}}</p>
                                    </th>
                                    <th class="text-center" @click="sort('month')">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Monthly Amount
                                        </h5>
                                        <p style="margin: 0;" class="text-muted">{{dateList.monthDate}}</p>
                                    </th>
                                    <th class="text-center" @click="sort('trimester')">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Quarterly Amount
                                        </h5>
                                        <p style="margin: 0;" class="text-muted">{{dateList.trimesterDate}}</p>
                                    </th>
                                    <th class="text-center" @click="sort('year')">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Annual Amount
                                        </h5>
                                        <p style="margin: 0;" class="text-muted">{{dateList.yearDate}}</p>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Tier Level
                                        </h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Details
                                        </h5>
                                    </th>
                                </tr>
                            </thead>
                            <tbody v-if="transactions">
                                <transaction-component
                                    v-for="(transaction, index) in transactions.data"
                                    @showModal="openModal"
                                    @showModalConfirmationUserTier="openModalConfirmationUser"
                                    @showModalUnlockConfirmation="openModalUnlockConfirmation"
                                    @notification="showNotificacion"
                                    :key="transaction.id"
                                    :transaction="transaction"
                                    :type="type"
                                    ref="childComponent">
                                </transaction-component>
                                
                            </tbody>
                        </table>
                        <div class="loader">
                            <div class="spinner-border text-primary"
                                style="width: 3rem; height: 3rem;"
                                role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="transactions">
                    <div v-if="transactions.current_page" class="d-flex justify-content-center mt-3">
                        <a v-if="transactions.current_page != 1" class="page-link px-3" href="javascript:void(0);"
                        aria-label="Load more"
                        style="width: unset; height: unset; border-radius: 10rem !important;"
                        v-on:click="transactionsPagination(transactions.current_page - 1)">
                            <span aria-hidden="true">Prev</span>
                        </a>
                        <a v-if="transactions.current_page != transactions.last_page" class="page-link px-3"
                        href="javascript:void(0);" aria-label="Load more"
                        style="width: unset; height: unset; border-radius: 10rem !important;"
                        v-on:click="transactionsPagination(transactions.current_page + 1)">
                            <span aria-hidden="true">Next</span>
                        </a>
                    </div>
                </div>

                
            </div>
        </section>
             

        <!-- Modal -->
        <div v-if="modal_visibility">
            <div class="modal fade" id="transactions-details-modal" tabindex="-1" role="dialog"
                aria-labelledby="transactionsDetailsModal" aria-hidden="true" style="overflow-y: scroll;">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content loader--wrapper" :class="{ '--loading': loaderModal }">
                        <div class="modal-body p-md-4">
                            <div class="row">
                                <div class="col-md-12 mb-12">
                                    <h5 class="text-primary font-weight-bold mb-0">Historial del Usuario:</h5>
                                    <h5 class="text-secondary font-weight-bold">
                                        <a :href="getEndpoint('/user-profile/' + dataModal.user_account.id)" class="text-secondary font-weight-bold" target="_blank">
                                            {{ dataModal.user_account.name }}
                                        </a>
                                    </h5>
                                </div>
                                <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
                                    <form v-on:submit.prevent="getDataFilterModal" method="get" class="form-inline flex-md-nowrap ml-md-3">

                                        <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-search text-muted"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" v-model="searchTraking" placeholder="Tracking">
                                        </div>

                                        <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-search text-muted"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" v-model="searchIP" placeholder="Buscar IP">
                                        </div>

                                        <div  class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white text-muted">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text"
                                                id="creation-date-filter"
                                                class="form-control"
                                                aria-label="Creation date filter"
                                                aria-describedby="creation-date-filter">
                                        </div>

                                        <div class="input-group mb-3 mb-md-0">
                                            <input type="submit" class="ml-3 btn btn-primary" value="Search">
                                        </div>
                                    
                                        
                                    </form>
                                </div>
                                <table class="table table-striped table-borderless mb-0">
                                    <thead>
                                            
                                            <th class="text-center">
                                                <h5 class="text-muted font-weight-bold mb-0">
                                                    Approval Date
                                                </h5>
                                            </th>
                                            <th class="text-center">
                                                <h5 class="text-muted font-weight-bold mb-0">
                                                    Tracking
                                                </h5>
                                            </th>
                                            <th class="text-center">
                                                <h5 class="text-muted font-weight-bold mb-0">
                                                    Amount
                                                </h5>
                                            </th>
                                            <th class="text-center">
                                                <h5 class="text-muted font-weight-bold mb-0">
                                                    Details
                                                </h5>
                                            </th>
                                            <th v-if="type == 4" class="text-center">
                                                <h5 class="text-muted font-weight-bold mb-0">
                                                    Email to / Email from
                                                </h5>
                                            </th>
                                            <th class="text-center">
                                                <h5 class="text-muted font-weight-bold mb-0">
                                                    IP
                                                </h5>
                                            </th>
                                            <th class="text-center">
                                                <h5 class="text-muted font-weight-bold mb-0">
                                                    Red Flag
                                                </h5>
                                            </th>
                                    </thead>
                                    <tbody>
                                        <modal-transaction-component
                                            v-for="dataTransaction in dataModalTranasactions" 
                                            :key="dataTransaction.id"
                                            :dataTransaction="dataTransaction"
                                            :type="type"
                                            @notification="showNotificacion"
                                            @getPagination="transactionsPagination"
                                            @showModalConfirmation="openModalConfirmation"
                                            @showModalUnlockConfirmation="openModalUnlockConfirmation"
                                            >
                                        </modal-transaction-component>
                                    </tbody>
                                        
                                </table>
                               
                            </div>                    
                        </div>
                        <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div v-if="modal_visibility_confirmation">
            <div class="modal fade" id="transactions-details-modaaaaal" tabindex="-1" role="dialog"
                aria-labelledby="transactionsDetailsModaaaaal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content" >
                        <div class="modal-body p-md-4">
                            <div class="row">
                                <div class="col-md-12 mb-12">
                                    <h5 class="text-secondary text-center font-weight-bold">
                                        ¿{{user.name}} seguro que quieres marcar la operación con el tracking {{suspicious_transaction.tracking_id}}?
                                    </h5>
                                </div>

                                <form v-on:submit.prevent="sendToCheckTier" method="post" class="form-inline flex-md-nowrap ml-md-3">
                                    <div class="col-md-12 input-group mb- mb-md-0 mr-3" style="width: 180px">
                                        <select ref="tierLevel">
                                            <option selected disabled>Seleccione nivel tier a verificar</option>
                                            <option v-for="tier in tiers" v-bind:value="tier.id">
                                                {{tier.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-group mb-3 mb-md-0">
                                        <input type="button" class="ml-3 btn btn-danger" data-dismiss="modal" aria-label="Close" value="Cancelar">
                                    </div>
                                    <div class="input-group mb-3 mb-md-0">
                                        <input type="submit" class="ml-3 btn btn-primary" value="Guardar Cambios">
                                    </div>
                                    
                                </form>
                                        
                                
                               
                            </div>                      
                        </div>
                        <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="modal_visibility_confirmation_user">
            <div class="modal fade" id="transactions-details-modaaaaal" tabindex="-1" role="dialog"
                aria-labelledby="transactionsDetailsModaaaaal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content" >
                        <div class="modal-body p-md-4">
                            <div class="row">
                                <div class="col-md-12 mb-12">
                                    <h5 class="text-secondary text-center font-weight-bold">
                                        ¿{{user.name}} seguro que quieres maracar a {{user_to_review.user_account.name}}?
                                    </h5>
                                </div>

                                <form v-on:submit.prevent="sendUserToCheckTier" method="get" class="form-inline flex-md-nowrap ml-md-3">
                                    <div class="col-md-12 input-group mb- mb-md-0 mr-3" style="width: 180px">
                                        <select ref="tierLevel">
                                            <option selected disabled>Seleccione nivel tier a verificar</option>
                                            <option v-for="tier in tiers" v-bind:value="tier.id">
                                                {{tier.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="input-group mb-3 mb-md-0">
                                        <input type="button" class="ml-3 btn btn-danger" data-dismiss="modal" aria-label="Close" value="Cancelar">
                                    </div>
                                    <div class="input-group mb-3 mb-md-0">
                                        <input type="submit" class="ml-3 btn btn-primary" value="Guardar Cambios">
                                    </div>
                                    
                                </form>
                                        
                                
                               
                            </div>                      
                        </div>
                        <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="modal_visibility_unlock_confirmation">
            <div class="modal fade" id="transactions-unlock-details-modaaaaal" tabindex="-1" role="dialog"
                aria-labelledby="transactionsDetailsModaaaaal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content" >
                        <div class="modal-body p-md-4">
                            <div class="row">
                                <div class="col-md-12 mb-12">
                                    <h5 class="text-secondary text-center font-weight-bold">
                                        ¿{{user.name}} seguro que quieres desmarcar la operación con el tracking {{suspicious_transaction.tracking_id}}?
                                    </h5>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" @click="unlockTier" class="btn btn-primary">Save changes</button>
                                
                                </div>
                               
                            </div>                      
                        </div>
                        <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: ['type', 'user'],
    data() {
        return {
            cont: 1,
            currentSort:'name',
            currentSortDir:'asc',
            type: this.type,
            tiers: this.tiers,
            transactions: null,
            modal_visibility:false,
            modal_visibility_confirmation: false,
            modal_visibility_confirmation_user: false,
            modal_visibility_unlock_confirmation: false,
            loader: false,
            loaderModal: false,
            dataModal: null,
            dataModalTranasactions: null,
            userName: null,
            userLastname: null,
            userEmail: null,
            status: '',
            dateFilter: null,
            startDate: null,
            endDate: null,
            dateList: {
                dayDate: null,
                getDayFormat: null,
                lastDayOfWeek: null,
                weekDate: null,
                monthDate: null,
                trimesterDate: null,
                yearDate: null,
                getMont: null,
                trimesterStar: null
            },
            ascending: false,
            sortColumn: '',
            dataTransactions: null,
            searchTraking: null,
            searchIP: null,
            tiers: null,
            suspicious_transaction: null,
            user_to_review: null,
        };
    },
    created() {
        axios.get(window.location.origin + '/operaciones/get-tiers')
                    .then(re => {
                        this.tiers = re.data;
                    });
    },
    mounted: function () {
        this.$nextTick(function () {
            //Graphics
            let vueObject = this;
            let currentYear                     = new Date().getFullYear();
            let lastYear                        = currentYear - 1;
            let startDate                       = moment();
            let endDate                         = moment();
            let yearStartDate                   = moment(currentYear + '-01-01');
            let yearEndDate                     = moment(currentYear + '-12-01');
            this.startDate                      = startDate.format('YYYY-MM-D HH:mm:ss');
            this.endDate                        = endDate.format('YYYY-MM-D HH:mm:ss');
            

            function cb(start, end) {
                if (start !== vueObject.startDate || end !== vueObject.endDate) {
                    vueObject.changeDate(start, end);
                }
            }

            $('#creation-date-filter').daterangepicker({
                opens: 'center',
                startDate: startDate,
                endDate: endDate,
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                    'Este mes': [moment().startOf('month'), moment().endOf('month')],
                    'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);
            cb(startDate, endDate);
        })
        console.log(this.tiers);
        
    },
    methods: {
        onSubmit(){            
            this.dateList.dayDate       = moment(this.endDate).format('YYYY-MM-DD');
            this.dateList.getDayFormat  = moment(this.endDate).format('MM-DD-YYYY');
            this.dateList.getMont       = moment(this.endDate).format('YYYY-MM');
            this.dateList.weekDate      = moment(this.endDate).subtract(6, 'days').format('MM-DD-YYYY') + ' - ' + moment(this.dateList.dayDate).format('MM-DD-YYYY');
            this.dateList.monthDate     = moment(this.endDate).format('MM-YYYY');
            this.dateList.trimesterDate = moment(this.endDate).subtract(3, 'month').format('MM-YYYY') + ' - ' + moment(this.endDate).format('MM-YYYY');
            this.dateList.trimesterStar = moment(this.endDate).subtract(3, 'month').format('YYYY-MM');
            this.dateList.yearDate      = moment(this.endDate).format('YYYY');
            this.dateList.lastDayOfWeek = moment(this.endDate).subtract(6, 'days').format('MM-DD-YYYY')
            this.loader = true;
            this.transactions = null;
            let order   = (this.status == '') ? 'desc' : this.status;
            axios.get(window.location.origin + '/aml/get-data', {
                params: {
                    type: this.type,
                    userName: this.userName,
                    userLastname: this.userLastname,
                    userEmail: this.userEmail,
                    starDate: this.startDate,
                    dayDate: this.dateList.dayDate,
                    lastDayOfWeek: this.dateList.lastDayOfWeek,
                    trimesterStar: this.dateList.trimesterStar,
                    year: this.dateList.yearDate,
                    month: this.dateList.getMont,
                    order: order,
                }
            }).then(re => {
                console.log(re.data);
                
                this.transactions = re.data;
                this.loader = false;
            });
        },
        sort(sortBy){
            if (this.sortColumn === sortBy) {
                this.ascending = !this.ascending;
            } else {
                this.ascending = true;
                this.sortColumn = sortBy;
            }
            this.loader = true;
            this.transactions = null;
            let order   = (this.ascending == true) ? 'asc' : 'desc'
            axios.get(window.location.origin + '/aml/get-data', {
                params: {
                    type: this.type,
                    userName: this.userName,
                    userLastname: this.userLastname,
                    userEmail: this.userEmail,
                    starDate: this.startDate,
                    dayDate: this.dateList.dayDate,
                    lastDayOfWeek: this.dateList.lastDayOfWeek,
                    trimesterStar: this.dateList.trimesterStar,
                    year: this.dateList.yearDate,
                    month: this.dateList.getMont,
                    order: order,
                    sort: sortBy
                }
            }).then(re => {
                this.transactions = re.data;
                this.loader = false;
            });
        },
        changeDate(start, end) {
            this.startDate = start.format('YYYY-MM-D HH:mm:ss');
            this.endDate   = end.format('YYYY-MM-D HH:mm:ss');
        },
        showNotificacion(id = null, check = null){
            if(check == null && id == null){
                this.$refs.toastr.e('Ha ocurrido un error!!!!');
            }else{
                if(check == 1 || check == true) {
                    this.$refs.toastr.s('Se marco la operación: ' + id);
                }else{
                    this.$refs.toastr.w('Se desmarco la operación: ' + id);
                }  
            }
            this.transactionsPagination(this.transactions.current_page);
        },
        transactionsPagination(page) {
            this.loader = true;
            axios.get(window.location.origin + '/pagination', {
                params: {
                    page: page,
                    type: this.type
                }
            }).then(re => {
                //Vue.set(this, transactions, re.data);
                this.transactions = re.data;
                this.loader = false;
            });
        },
        getEndpoint(endpoint) {
            return window.location.origin + endpoint;
        },
        openModal(data, dataTransactions) {
            this.loaderModal             = true;
            this.dataModalTranasactions  = dataTransactions;
            this.dataModal               = data;
            this.check                   = data.tier_review,
            this.modal_visibility        = true;
        },
        openModalConfirmation(transacation){
            this.loaderModal             = true;
            this.suspicious_transaction  = transacation;
            this.modal_visibility_confirmation  = true;
        },
        openModalConfirmationUser(user){
            this.loaderModal             = true;
            this.user_to_review          = user
            this.modal_visibility_confirmation_user  = true;
        },
        openModalUnlockConfirmation(transacation){
            this.loaderModal             = true;
            this.unsuspicious_transaction  = transacation;
            this.modal_visibility_unlock_confirmation  = true;
        },
        getDataFilterModal(){
            let tracking = this.tracking;
            let ip = this.searchTraking;
            axios.get(window.location.origin+'/get-data-to-user-review',  {
                    params: {
                        transaction: this.dataModal,
                        tier_review: true,
                        traking: this.searchTraking,
                        type: this.type
                    }
                }).then(re => {
                    console.log(re.data);
                    this.dataTransactions = re.data
                    this.openModal(this.dataModal, this.dataTransactions);
                    });
        },
        sendToCheckTier(){
            axios.post(window.location.origin+'/send-to-revision', {
                        transaction: this.suspicious_transaction.id,
                        tier_id: this.$refs.tierLevel.value,
                        tier_review: true
                    }).then(re => {
                    this.$refs.toastr.s('Se ha marcado la operación');
                    this.getDataFilterModal();
                    this.onSubmit();
                    $('#transactions-details-modaaaaal').modal('hide');
                    console.log(re.data);
                    }).catch((error) => {
                        // Error 😨
                        if (error.response) {
                            this.check = !this.check;
                            this.$emit('notification');
                        } 
                    });
            
        },
        sendUserToCheckTier(){
                axios.get(window.location.origin+'/send-user-to-revision', {
                        user: this.user_to_review.user_account.id,
                        tier_id: this.$refs.tierLevel.value,
                        tier_review: true
                    }).then(re => {
                    this.$refs.toastr.s('Se ha marcado al usuario para revision');
                    this.onSubmit();
                    console.log(re.data);
                    }).catch((error) => {
                        // Error 😨
                        if (error.response) {
                            this.check = !this.check;
                            this.$emit('notification');
                        } 
                    });
            
        },
        unlockTier(){
            axios.post(window.location.origin+'/send-to-revision', {
                        transaction: this.suspicious_transaction.id,
                        tier_review: false
                    }).then(re => {
                    this.$refs.toastr.w('Se ha desmarcado la operación');
                    this.getDataFilterModal();
                    this.onSubmit();
                    $('#transactions-unlock-details-modaaaaal').modal('hide');
                    console.log(re.data);
                    }).catch((error) => {
                        // Error 😨
                        if (error.response) {
                            this.check = !this.check;
                            this.$emit('notification');
                        } 
                    });
        }
    }
};
</script>

<style lang="scss">

    th {
        cursor:pointer;
    }
    .graph-ranges {
        font-size: 14px;
        color: #777;
        border-color: #303392;
        padding: 6px;
    }

    .sections-titles {
        font-size: 45px;
        text-align: center;
        color: #f7941d;
        font-weight: 700;
    }

    .calculator-btc-sale {
        background: #fff;
        padding: 1rem 12px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 1rem;

        input,
        select {
            margin: 0 10px;
        }

        label {
            margin: 0 1rem;
        }
    }

    .calculator-results {
        .row {
            margin: 0;
            background: #fff;
            padding: 1rem 12px;
            border-radius: 8px;
        }

        .alert-no-btc {
            display: none;
        }

        .suggestions {
            display: flex;
            align-items: center;
            justify-content: center;

            .suggested-rate {
                text-align: center;

                span {
                    display: block;
                    font-weight: 700;
                }
            }
        }

        h6 {
            text-align: center;
            margin-bottom: 1rem;
        }
    }
        

</style>
