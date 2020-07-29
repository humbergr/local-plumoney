<template>
   <div class="card" style="margin-top: 5px">
        <b-overlay :show="ticketOverlay" rounded="sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-7">
                        <!-- badges -->
                        <!-- <span v-if="ticket.status_id==1" class="badge badge-info">{{ticket.status}}</span>
                        <span v-else-if="ticket.status_id==2" class="badge badge-primary">{{ticket.status}}</span>
                        <span v-else-if="ticket.status_id==3" class="badge badge-success">{{ticket.status}}</span>
                        <span v-else>{{ticket.status}}</span> -->
                        <span v-if="ticket.is_answered==0" class="badge badge-secondary">Pendiente</span>
                        <span v-if="ticket.is_deleted==1" class="badge badge-danger">Eliminado</span>
                        <span v-if="ticket.is_registered==0" class="badge badge-info">No Registrado</span>
                    </div>
                    <div class="col-4">
                        <!-- priority -->
                        <span :id="'priority'+ticket.id" class="pointer">
                            <span class="badge" :style="'width:15px;height:15px !important;background-color:'+ticket.color">&nbsp;</span> {{ticket.priority}}<svg class="bi bi-chevron-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>
                        </span>
                        <b-popover
                            :target="'priority'+ticket.id"
                            triggers="hover"
                            placement="bottom"
                        >
                            <b-list-group flush>
                                <b-list-group-item button v-for="priority in priorities" :key="priority.id" @click="changePriority(ticket.id,priority.id,priority.priority_color,priority.priority_desc);$bvToast.show('priority-change-toast')">
                                    <span class="badge" :style="'width:15px;height:15px !important;background-color:'+priority.priority_color">&nbsp;</span> {{priority.priority_desc}}
                                </b-list-group-item>
                            </b-list-group>
                        </b-popover>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                        <!-- checkbox -->
                        <input type="checkbox" class="form-check-input" :id="ticket.id" :name="ticket.id" style="margin-left:10px">
                    </div>
                    <div class="col-7">
                        <!-- title -->
                        <span :id="'title'+ticket.id" class="pointer" v-b-toggle.sidebar-ticket @click="ticketDetails">{{ticket.title}} #{{ticket.ticket_number}}</span>
                        <b-popover
                            :target="'title'+ticket.id"
                            triggers="hover"
                            placement="bottom"
                            @show="getTicketBody"
                        >
                            <b-overlay :show="messageOverlay" rounded="sm">
                                <div class="row">
                                    <div class="col-12">
                                        <!-- Agent details -->
                                        <b-avatar rounded="sm"></b-avatar>
                                        Detalles De Respuesta
                                        <p>{{body}}</p>
                                    </div>
                                    <div class="col-12">
                                        <b-button @click="reply" size="sm" v-b-toggle.sidebar-reply variant="outline-primary">Respoder</b-button>
                                        <b-button @click="internalnote" size="sm"  variant="outline-primary">Nota Interna</b-button>
                                    </div>
                                </div>
                            </b-overlay>
                        </b-popover>
                    </div>
                    <div class="col-4">
                        <!-- grupo -->
                        <span v-if="ticket.group!=null">{{ticket.group}}</span>
                        <span v-else>---</span>/
                        <!-- agente -->
                        <span v-if="agent!=null">{{agent}}</span>
                        <span v-else>---</span>
                        <svg class="bi bi-chevron-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-7">
                        <!-- Nombre -->
                        <span :id="'name'+ticket.id" class="default">{{ticket.name}}</span>
                            <b-popover
                                :target="'name'+ticket.id"
                                triggers="hover"
                                placement="bottom"
                            >
                                <div class="row">
                                    <div class="col-4">
                                        <b-avatar rounded="sm"></b-avatar>
                                    </div>
                                    <div class="col-8">
                                        <a :href="'user-profile/'+ticket.user_id" target="_blank">{{ticket.name}}</a>
                                        <br>
                                        <a :href="'user-profile/'+ticket.user_id+'#support'" target="_blank">Ver Tickets</a>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-12">
                                        <a :href="'mailto:'+ticket.email">{{ticket.email}}</a>
                                    </div>
                                </div>
                            </b-popover>
                        · {{ticket.department}} · {{ticket.duedate}}
                    </div>
                    <div class="col-4">
                        <!-- status -->
                        <span :id="'status'+ticket.id" class="pointer">
                            {{ticket.status}}
                            <svg class="bi bi-chevron-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>
                        </span>
                        <b-popover
                            :target="'status'+ticket.id"
                            triggers="hover"
                            placement="bottom"
                        >
                            <b-list-group flush>
                                <b-list-group-item button v-for="status in statuses" :key="status.id" @click="changeStatus(ticket.id,status.id,status.state,status.message);$bvToast.show('status-change-toast')">
                                    {{status.state}}
                                </b-list-group-item>
                            </b-list-group>
                        </b-popover>
                    </div>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    props: ['ticket','priorities','statuses'],
    data() {
        return {
            body:'',
            agent:'',
            messageOverlay: false,
            ticketOverlay: false
        }
    },
    mounted() {
        this.ticketOverlay = true;
        // axios.get('/ticket-agent?id='+this.ticket.id).then((response) => {
        axios.get('/get-user-by-id?id='+this.ticket.agent).then((response) => {
            this.agent = response.data;
        });
        this.ticketOverlay = false;
    },
    methods: {
        changeStatus(id,status,name,message){
            this.ticketOverlay = true;
            axios.post('/change-status',{'id':id,'status':status}).then((response) => {
                if (response.data=='ok') {
                    this.ticket.status = name;
                    this.ticketOverlay = false;
                }
            });
        },
        changePriority(id,priority,color,name){
            this.ticketOverlay = true;
            axios.post('/change-priority',{'id':id,'priority':priority}).then((response) => {
                if (response.data=='ok') {
                    this.ticket.color = color;
                    this.ticket.priority = name;
                    this.ticketOverlay = false;
                }
            });
        },
        reply(){
            this.$emit('ticket_id',this.ticket.id);
            this.$emit('ticket_number',this.ticket.ticket_number);
            this.$emit('name',this.ticket.name);
            this.$emit('email',this.ticket.email);
            this.$emit('title',this.ticket.title);
            this.$emit('body',this.body);
            axios.get('/ticket-reply-details?id='+this.ticket.id).then((response) => {
                console.log(response.data);
            });
        },
        internalnote(){
            console.log('onInternalNote');
        },
        ticketDetails(){
            axios.get('/get-ticket-details?id='+this.ticket.id).then((response) => {
                this.$emit('threads',response.data);
            });
            // console.log(this.ticket.agent);
            // console.log(this.agent);
            // console.log(this.ticket.group);
            this.$emit('tid',this.ticket.id);
            this.$emit('tnumber',this.ticket.ticket_number);
            this.$emit('userid',this.ticket.user_id);
            this.$emit('tname',this.ticket.name);
            this.$emit('temail',this.ticket.email);
            this.$emit('title',this.ticket.title);
            this.$emit('tstatus',this.ticket.status_id);
            this.$emit('tpriority',this.ticket.priority_id);
            this.$emit('tdepartment',this.ticket.department_id);
            this.$emit('tagent',this.agent);
            this.$emit('tgroup',this.ticket.group);
            this.$emit('tsource',this.ticket.source);
            this.$emit('tdue',this.ticket.duedate);
        },
        getTicketBody(){
            this.messageOverlay = true;
            axios.get('/ticket-body?id='+this.ticket.id).then((response) => {
                this.messageOverlay = false;
                this.body = response.data;
            });
        }
    }
}
</script>

<style>
    .pointer{
        cursor:pointer;
    }
    .default{
        cursor:default;
    }
</style>