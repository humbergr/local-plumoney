<template>
    <b-overlay :show="ticketsOverlay" rounded="lg">
    <div class="row" v-show="showTickets==true">
        <div class="col-9">
            <div class="card" style="height:60px">
                <div class="card-body">
                    <div class="row">
                        <div class="col-1">
                            <input type="checkbox" class="form-check-input" style="margin-left:10px">
                        </div>
                        <div class="col-3">
                            <h5>Tickets</h5>
                        </div>
                        <div class="col-8">
                            <b-button variant="outline-primary" size="sm">Nuevo</b-button>
                        </div>
                    </div>
                </div>
            </div>
            <ticket-details
                v-for="ticket in tickets"
                :key="ticket.id"
                :ticket="ticket"
                :priorities="priorities"
                :statuses="statuses"
                @ticket_id="replyid=$event"
                @ticket_number="replyticketnumber=$event"
                @name="replyname=$event"
                @email="replyemail=$event"
                @body="replybody=$event"
                @tstatus="tstatus=$event"
                @tpriority="tpriority=$event"
                @tdepartment="tdepartment=$event"
                @tagent="tagent=$event"
                @tgroup="tgroup=$event"
                @tsource="tsource=$event"
                @tdue="tdue=$event"
                @tid="tid=$event"
                @userid="userid=$event"
                @title="title=$event"
                @tname="tname=$event"
                @tnumber="tnumber=$event"
                @temail="temail=$event"
                @threads="threads=$event"
            >
            </ticket-details>
            <!-- ticket details sidebar -->
            <b-sidebar
                    id="sidebar-ticket"
                    backdrop-variant="dark"
                    backdrop
                    shadow
                    left
                    width="100%"
                    :title="'Ticket #'+tnumber+' - '+title"
                >
                <b-container fluid>
                    <div class="row">
                        <div class="col-12">
                            <b-button variant="outline-primary" size="sm" @click="ticketFavorite" :class="{active:activeFavorite}">
                                <svg class="bi bi-star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                                </svg>
                            </b-button>
                            <b-button-group size="sm">
                                <b-button variant="outline-primary" @click="replydiv = true;internal = 0;$nextTick(() => $refs.message.focus());activeReply=true;activeInternal=false;" :class="{active:activeReply}">
                                    <svg class="bi bi-arrow-90deg-left" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6.104 2.396a.5.5 0 0 1 0 .708L3.457 5.75l2.647 2.646a.5.5 0 1 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0z"/>
                                        <path fill-rule="evenodd" d="M2.75 5.75a.5.5 0 0 1 .5-.5h6.5a2.5 2.5 0 0 1 2.5 2.5v5.5a.5.5 0 0 1-1 0v-5.5a1.5 1.5 0 0 0-1.5-1.5h-6.5a.5.5 0 0 1-.5-.5z"/>
                                    </svg>
                                    Responder
                                </b-button>
                                <b-button variant="outline-primary" @click="replydiv = true;internal = 1;$nextTick(() => $refs.message.focus());activeReply=false;activeInternal=true;" :class="{active:activeInternal}">
                                    <svg class="bi bi-file-text" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4z"/>
                                        <path fill-rule="evenodd" d="M4.5 10.5A.5.5 0 0 1 5 10h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 8h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 4h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                                    </svg>
                                    Nota Interna
                                </b-button>
                                <b-button variant="outline-primary">
                                    <svg class="bi bi-reply" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M9.502 5.013a.144.144 0 0 0-.202.134V6.3a.5.5 0 0 1-.5.5c-.667 0-2.013.005-3.3.822-.984.624-1.99 1.76-2.595 3.876C3.925 10.515 5.09 9.982 6.11 9.7a8.741 8.741 0 0 1 1.921-.306 7.403 7.403 0 0 1 .798.008h.013l.005.001h.001L8.8 9.9l.05-.498a.5.5 0 0 1 .45.498v1.153c0 .108.11.176.202.134l3.984-2.933a.494.494 0 0 1 .042-.028.147.147 0 0 0 0-.252.494.494 0 0 1-.042-.028L9.502 5.013zM8.3 10.386a7.745 7.745 0 0 0-1.923.277c-1.326.368-2.896 1.201-3.94 3.08a.5.5 0 0 1-.933-.305c.464-3.71 1.886-5.662 3.46-6.66 1.245-.79 2.527-.942 3.336-.971v-.66a1.144 1.144 0 0 1 1.767-.96l3.994 2.94a1.147 1.147 0 0 1 0 1.946l-3.994 2.94a1.144 1.144 0 0 1-1.767-.96v-.667z"/>
                                    </svg>
                                    Reenviar
                                </b-button>
                            </b-button-group>
                            <b-button variant="outline-primary" size="sm" v-show="tstatus==1" v-b-modal.modal-close>
                                <svg class="bi bi-check2-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                    <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                                </svg>
                                Cerrar
                            </b-button>
                            <b-modal id="modal-close" centered title="Cerrar Ticket" hide-footer>
                                <p>¿Está seguro que desea cerrar el ticket # {{tnumber}}?</p>
                                <b-button class="mt-3" block @click="closeTicket();$bvModal.hide('modal-close');">Cerrar</b-button>
                            </b-modal>
                            <b-button variant="outline-primary" size="sm">
                                <svg class="bi bi-arrow-down-left-square" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                    <path fill-rule="evenodd" d="M5.5 11h4a.5.5 0 0 0 0-1H6.707l4.147-4.146a.5.5 0 0 0-.708-.708L6 9.293V6.5a.5.5 0 0 0-1 0v4a.5.5 0 0 0 .5.5z"/>
                                </svg>
                                Unir
                            </b-button>
                            <b-button variant="outline-primary" size="sm">
                                <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                                Eliminar
                            </b-button>
                            <b-button variant="outline-primary" size="sm">
                                <svg class="bi bi-three-dots-vertical" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                </svg>
                            </b-button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <!-- threads -->
                        <div class="col-6">
                            <b-form-rating></b-form-rating>
                            <div class="card" v-for="thread in threads" :key="thread.id" style="margin-top: 5px">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-9 text-left">
                                            <span v-show="thread.is_internal==1">Nota Interna - </span>
                                            <a :href="'user-profile/'+thread.user_id" target="_blank">{{thread.name}}</a> ({{thread.email}})
                                        </div>
                                        <div class="col-3 text-right">
                                            {{thread.created_at}} {{thread.ip_address}}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    {{thread.body}}
                                </div>
                                <div class="card-footer" v-if="thread.file != null">
                                    <div v-for="file in thread.file.split(',')" :key="file">
                                        <a :href="file" target="_blank">{{file.replace('/support_attachments/'+tnumber+'/','')}}</a>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div v-if="replydiv == true">
                            <!-- <b-form action="/reply-ticket" method="post" enctype="multipart/form-data"> -->
                            <!-- <b-form @submit="onSubmit" @reset="onReset" v-if="show"> -->
                                <b-form-group :label="'Para: '+tname+' ('+temail+')'"></b-form-group>
                                <b-form-checkbox
                                    v-model="internal"
                                    value="1"
                                    unchecked-value="0"
                                >
                                    Nota Interna
                                </b-form-checkbox>
                                <br>
                                <b-form-textarea
                                    ref="message"
                                    placeholder="Escriba aqui su respuesta..."
                                    rows="10"
                                    max-rows="10"
                                    v-model="message"
                                ></b-form-textarea>
                                <b-form-file class="mt-3" plain multiple v-model="files"></b-form-file>
                                <br>
                                <b-button type="button" variant="outline-primary" @click="replyTicket">Enviar</b-button>
                                <b-button type="reset" variant="outline-danger" @click="replydiv = false;activeReply = false;activeInternal = false;">Cancelar</b-button>
                            <!-- </b-form> -->
                            </div>
                        </div>
                        <div class="col-3">
                            <h5>
                                <!-- {{tstatus}} -->
                            </h5>
                            <h5>Propiedades</h5> 
                            <b-form-group
                                label="Vencimiento"
                            >
                                <span v-if="tdue!=null">{{formatDate(tdue)}}</span>
                                <b-form-select
                                    v-else
                                    v-model="tdue"
                                    :options="dues"
                                    class="mb-3"
                                    value-field="id"
                                    text-field="name"
                                ></b-form-select>
                            </b-form-group>
                            <b-form-group
                                label="Estado"
                            >
                                <b-form-select
                                    v-model="tstatus"
                                    :options="statuses"
                                    class="mb-3"
                                    value-field="id"
                                    text-field="state"
                                    @change="changeStatus"
                                ></b-form-select>
                            </b-form-group>
                            <b-form-group
                                label="Prioridad"
                            >
                                <b-form-select
                                    v-model="tpriority"
                                    :options="priorities"
                                    class="mb-3"
                                    value-field="id"
                                    text-field="priority_desc"
                                    @change="changePriority"
                                ></b-form-select>
                            </b-form-group>
                            <b-form-group
                                label="Departamento"
                            >
                                <b-form-select
                                    v-model="tdepartment"
                                    :options="departments"
                                    class="mb-3"
                                    value-field="id"
                                    text-field="name"
                                    @change="changeDepartment"
                                ></b-form-select>
                            </b-form-group>
                            <b-form-group
                                label="Agente"
                            >
                                <b-form-select
                                    v-model="tagent"
                                    :options="agents"
                                    class="mb-3"
                                    value-field="id"
                                    text-field="name"
                                ></b-form-select>
                            </b-form-group>
                            <b-form-group
                                label="Grupo"
                            >
                                <b-form-select
                                    v-model="tgroup"
                                    :options="groups"
                                    class="mb-3"
                                    value-field="id"
                                    text-field="name"
                                ></b-form-select>
                            </b-form-group>
                            <b-form-group
                                label="Canal"
                            >
                                <b-form-select
                                    v-model="tsource"
                                    :options="sources"
                                    class="mb-3"
                                    value-field="id"
                                    text-field="name"
                                ></b-form-select>
                            </b-form-group>
                        </div>
                        <div class="col-3">
                            <h5>
                                Detalles de Contacto
                                <!-- <a href="#">Editar</a> -->
                            </h5>
                            <b-avatar rounded="sm"></b-avatar>
                            <h5>
                                <a :href="'user-profile/'+userid" target="_blank">{{tname}}</a>
                            </h5>
                            <a :href="'mailto:'+temail">{{temail}}</a>
                        </div>
                    </div>
                </b-container>
            </b-sidebar>
            <!-- sidebar reply -->
            <b-sidebar
                    id="sidebar-reply"
                    backdrop-variant="dark"
                    backdrop
                    shadow
                    right
                    width="500px"
                    :title="'#'+replyticketnumber+' - Respuesta Rápida'"
            >
                <b-container fluid>
                    <b-form>
                    <!-- <b-form @submit="onSubmit" @reset="onReset" v-if="show"> -->
                        <b-form-rating></b-form-rating>
                        <br>
                        <b-form-group :label="'Para: '+replyname+' ('+replyemail+')'"></b-form-group>
                        <b-form-textarea
                            id="textarea"
                            placeholder="Escriba aqui su respuesta rápida..."
                            rows="10"
                            max-rows="10"
                        ></b-form-textarea>
                        <b-form-file class="mt-3" plain></b-form-file>
                        <br>
                        <b-button type="submit" variant="primary">Enviar</b-button>
                        <b-button type="reset" variant="danger">Cancelar</b-button>
                        </b-form>
                </b-container>
            </b-sidebar>
            <b-toast id="priority-change-toast" title="Notificación">¡Prioridad de Ticket Actualizada!</b-toast>
            <b-toast id="status-change-toast" title="Notificación">¡Status de Ticket Actualizado!</b-toast>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    Filtros
                </div>
                <div class="card-body">
                    <b-form-group
                        label="Estado"
                    >
                        <b-form-select
                            v-model="status"
                            class="mb-3"
                            @change="getTickets"
                            value="0"
                        >
                            <option value="0">Todos</option>
                            <option v-for="status in statuses" :value="status.id" :key="status.id">{{status.state}}</option>
                        </b-form-select>
                    </b-form-group>
                    <b-form-group
                        label="Prioridad"
                    >
                        <b-form-select
                            v-model="priority"
                            class="mb-3"
                            @change="getTickets"
                            value="0"
                        >
                            <option value="0">Todos</option>
                            <option v-for="priority in priorities" :value="priority.id" :key="priority.id">{{priority.priority_desc}}</option>
                        </b-form-select>
                    </b-form-group>                            
                    <b-form-group
                        label="Departamento"
                    >
                        <b-form-select
                            v-model="department"
                            class="mb-3"
                            @change="getTickets"
                            value="0"
                        >
                            <option value="0">Todos</option>
                            <option v-for="department in departments" :value="department.id" :key="department.id">{{department.name}}</option>
                        </b-form-select>
                    </b-form-group>
                    <b-form-group
                        label="Agentes"
                    >
                        <b-form-select
                            v-model="agent"
                            class="mb-3"
                            @change="getTickets"
                            value="0"
                        >
                            <option value="0">Todos</option>
                            <option v-for="agent in agents" :value="agent.id" :key="agent.id">{{agent.name}}
                                <span v-if="agent.department!=null">(Departamento: {{agent.department}})</span>
                                <span v-if="agent.group!=null">(Grupo: {{agent.group}})</span>
                            </option>
                        </b-form-select>
                    </b-form-group>
                    <b-form-group
                        label="Grupos"
                    >
                        <b-form-select
                            v-model="group"
                            class="mb-3"
                            @change="getTickets"
                            value="0"
                        >
                            <option value="0">Todos</option>
                            <option v-for="group in groups" :value="group.id" :key="group.id">{{group.name}}
                            </option>
                        </b-form-select>
                    </b-form-group>
                    <b-form-group
                        label="Canal"
                    >
                        <b-form-select
                            v-model="source"
                            class="mb-3"
                            @change="getTickets"
                            value="0"
                        >
                            <option value="0">Todos</option>
                            <option v-for="source in sources" :value="source.id" :key="source.id">{{source.name}}</option>
                        </b-form-select>
                    </b-form-group>
                    <b-form-group
                        label="Vencimiento"
                    >
                        <b-form-select
                            v-model="due"
                            class="mb-3"
                            @change="getTickets"
                            value="0"
                        >
                            <option value="0">Todos</option>
                            <option v-for="due in dues" :value="due.id" :key="due.id">{{due.name}}</option>
                        </b-form-select>
                    </b-form-group>
                </div>
            </div>
        </div>
    </div>
    </b-overlay>
</template>

<script>
import bus from '../../support-event-bus';
export default {
    data() {
        return {
            tickets: [],
            agents: [],
            groups: [],
            priorities: [],
            statuses: [],
            departments: [],
            sources: [],
            dues: [],
            threads: [],

            replyid: '',
            replyticketnumber: '',
            replyname: '',
            replyemail: '',
            replybody: '',

            showTickets: false,
            ticketsOverlay: false,
            
            status: 0,
            priority: 0,
            department: 0,
            agent: 0,
            group: 0,
            source: 0,
            due: 0,

            activeReply: false,
            activeInternal: false,

            activeFavorite: '',
            replydiv: false,
            tstatus: '',
            tpriority: '',
            tdepartment: '',
            tagent: '',
            tgroup: '',
            tsource: '',
            tdue: '',
            userid: '',
            tid: '',
            ip: '',
            title: '',
            tnumber: '',
            tname: '',
            temail: '',
            attachments: [],
            message: '',
            internal: '',
            files: []
        }
    },
    created(){
        bus.$on('showTickets', data =>{
            this.showTickets = true;
            this.ticketsOverlay = true;

            axios.get('/get-tickets-app')
            .then((response) => {
                // console.log(response.data);
                this.statuses = response.data.statuses;
                this.priorities = response.data.priorities;
                this.departments = response.data.departments;
                this.agents = response.data.agents;
                this.groups = response.data.groups;
                this.sources = response.data.sources;
                this.dues = response.data.dues;
            }).catch(error => {console.log(error)});
            this.getTickets();
        });
        bus.$on('showDashboard', data =>{
            this.showTickets = false;
        });
        bus.$on('showConfig', data =>{
            this.showTickets = false;
        });
    },
    methods:{
        getTickets(){
            this.ticketsOverlay = true;
            this.tickets = [];
            
            axios.get('/ticket-list?agent='+this.agent+'&group='+this.group+'&status='+this.status+'&priority='+this.priority+'&department='+this.department+'&source='+this.source)
            .then((response) => {//Falta rango
                this.tickets = response.data;
                this.ticketsOverlay = false;
            }).catch(error => {console.log(error)});
        },
        formatDate(date){//INCOMPLETO
            return date;
        },
        // ticketAction(action,value){//INCOMPLETO, Actualizar para nueva version
        //     //action 1 change status 2 change priority
        //     axios.post('/ticket-action',{'id':this.tid,'action':action,'value':value}).then((response) => {
        //         console.log(response.data);
        //         // if (response.data=='ok') {
        //         //     console.log('Ticket Favorite!');
        //         // }
        //     });
        // },
        ticketFavorite(){
            if (this.activeFavorite==false) {
                isFavorite=1;
            }
            else{
                isFavorite=0;
            }
            axios.post('/set-favorite',{'id':this.tid,'favorite':isFavorite}
            ).then((response) => {
                console.log(response.data);
                // if (response.data=='ok') {
                //     console.log('Ticket Favorite!');
                // }
            }).catch(error => {console.log(error)});
        },
        changeDepartment(){
            axios.post('/change-department',{'id':this.tid,'department':this.tdepartment})
            .then((response) => {
                console.log(response.data);
                if (response.data=='ok') {
                    
                    }
            }).catch(error => {console.log(error)});
        },
        changePriority(){
            axios.post('/change-priority',{'id':this.tid,'priority':this.tpriority})
            .then((response) => {
                console.log(response.data);
                if (response.data=='ok') {
                    
                    }
            }).catch(error => {console.log(error)});
        },
        changeStatus(){
            axios.post('/change-status',{'id':this.tid,'status':this.tstatus}
            ).then((response) => {
                console.log(response.data);
                if (response.data=='ok') {

                }
            }).catch(error => {console.log(error)});
        },
        closeTicket(){
            axios.post('/change-status',{'id':this.tid,'status':2}
            ).then((response) => {
                if (response.data=='ok') {
                    console.log('Ticket Closed!');
                }
            }).catch(error => {console.log(error)});
        },
        replyTicket(){
            if (this.message!='') {
                const formData = new FormData();
                formData.append('id', this.tid);
                
                fetch('https://api.ipify.org?format=json')
                .then(x => x.json())
                .then(({ ip }) => {
                    formData.append('ip', ip);
                    formData.append('title', this.title);
                    formData.append('message', this.message);
                    formData.append('number', this.tnumber);
                    formData.append('internal', this.internal);
                    for( var i = 0; i < this.files.length; i++ ){
                        let file = this.files[i];
                        formData.append('file[' + i + ']', file);
                    }
                    axios.post('/reply-ticket', formData,
                        {headers: {'Content-Type': 'multipart/form-data'}})
                    .then(response => {
                        console.log(response.data);
                        if (response.data == 'OK') {
                            console.log('Guardado!');
                            this.message = '';
                            this.files = [];
                            this.replydiv = false;
                            this.activeReply = false;
                            this.activeInternal = false;

                            axios.get('/get-ticket-details?id='+this.tid
                            ).then((response) => {
                                this.threads = [];
                                this.threads = response.data;
                            }).catch(error => {console.log(error)});
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
                });
            }
        }
    }
}
</script>

<style>

</style>