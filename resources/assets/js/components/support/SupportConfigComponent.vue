<template>
    <div class="row" v-show="showConfig==true">
    <!-- <div class="row"> -->
        <div class="col-12">
            <div class="row">
                <div class="col-6">    <!--Departments-->
                    <b-card>
                        Departamentos
                        <b-button size="sm" v-b-modal.dnew @click="blankFields">
                            <b-icon-plus></b-icon-plus>
                        </b-button>
                        <table class="table b-table table-striped hover">
                            <thead>
                                <td>Nombre</td>
                                <td>Activo</td>
                                <td>Público</td>
                            </thead>
                            <tr v-for="department in departments" :key="department.id" @click="dedit(
                                    department.id,
                                    department.name,
                                    department.is_active,
                                    department.is_public
                                )" v-b-modal.dedit>
                                <td>
                                    {{department.name}}
                                </td>
                                <td v-if="department.is_active==1">
                                    <b-icon-check2></b-icon-check2>
                                </td>
                                <td v-else></td>
                                <td v-if="department.is_public==1">
                                    <b-icon-check2></b-icon-check2>
                                </td>
                                <td v-else></td>
                            </tr>
                        </table>
                        <b-modal id="dedit" ref="dedit" title="Editar Departamento" @ok="deditsubmit">
                            <form ref="dform">
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        v-model="dname"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-checkbox
                                    v-model="dactive"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Activo
                                </b-form-checkbox>
                                <b-form-checkbox
                                    v-model="dpublic"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Público
                                </b-form-checkbox>
                            </form>
                            <b-button @click="confirmDelete('department',did)">Eliminar</b-button>
                        </b-modal>                        
                        <b-modal id="dnew" title="Nuevo Departamento" @ok="dnewsubmit">
                            <form ref="dnewform">
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        v-model="dname"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-checkbox
                                    v-model="dactive"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Activo
                                </b-form-checkbox>
                                <b-form-checkbox
                                    v-model="dpublic"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Público
                                </b-form-checkbox>
                            </form>
                        </b-modal>
                    </b-card>
                </div>
                <div class="col-6">    <!--Priorities-->
                    <b-card>
                        Prioridades
                        <b-button size="sm" v-b-modal.pnew @click="blankFields">
                            <b-icon-plus></b-icon-plus>
                        </b-button>
                        <table class="table b-table table-striped hover">
                            <thead>
                                <td>Nombre</td>
                                <!-- <td>Color</td> -->
                                <td>Activo</td>
                                <td>Público</td>
                            </thead>
                            <tr v-for="priority in priorities" :key="priority.id" @click="pedit(
                                    priority.id,
                                    priority.priority,
                                    priority.status,
                                    priority.priority_desc,
                                    priority.priority_color,
                                    priority.priority_urgency,
                                    priority.is_public,
                                    priority.is_default,
                                    priority.created_by,
                                    priority.created_at,
                                    priority.updated_at
                                )" v-b-modal.pedit>
                                <td>
                                    <span class="badge" :style="'width:15px;height:15px !important;background-color:'+priority.priority_color">&nbsp;</span>
                                    {{priority.priority_desc}}
                                    <span v-if="priority.is_default==1" class="badge badge-info"><b-icon-asterisk></b-icon-asterisk></span>
                                </td>
                                <td v-if="priority.status==1">
                                    <b-icon-check2></b-icon-check2>
                                </td>
                                <td v-else></td>
                                <td v-if="priority.is_public==1">
                                    <b-icon-check2></b-icon-check2>
                                </td>
                                <td v-else></td>
                            </tr>
                        </table>                        
                        <b-modal id="pedit" ref="pedit" title="Editar Prioridad" @ok="peditsubmit">
                            <form ref="pform">
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        v-model="pname"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Descripción">
                                    <b-form-input
                                        v-model="pdesc"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Color">
                                    <b-form-input
                                        type="color"
                                        v-model="pcolor"
                                        required>
                                    </b-form-input>
                                </b-form-group>
                                <b-form-group label="Urgencia">
                                    <b-form-input
                                        v-model="purgency"
                                        type="range" min="1"
                                        max="5">
                                    </b-form-input>
                                </b-form-group>
                                <b-form-checkbox
                                    v-model="pstatus"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Activo
                                </b-form-checkbox>
                                <b-form-checkbox
                                    v-model="ppublic"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Público
                                </b-form-checkbox>
                                <b-form-checkbox
                                    v-model="pdefault"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Por Defecto
                                </b-form-checkbox>
                            </form>
                            <b-button @click="confirmDelete('priority',pid)">Eliminar</b-button>
                        </b-modal>
                        <b-modal id="pnew" title="Nueva Prioridad" @ok="pnewsubmit">
                            <form ref="pnewform">
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        v-model="pname"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Descripción">
                                    <b-form-input
                                        v-model="pdesc"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Color">
                                    <b-form-input
                                        type="color"
                                        v-model="pcolor"
                                        required>
                                    </b-form-input>
                                </b-form-group>
                                <b-form-group label="Urgencia">
                                    <b-form-input
                                        v-model="purgency"
                                        type="range" min="1"
                                        max="5">
                                    </b-form-input>
                                </b-form-group>
                                <b-form-checkbox
                                    v-model="pstatus"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Activo
                                </b-form-checkbox>
                                <b-form-checkbox
                                    v-model="ppublic"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Público
                                </b-form-checkbox>
                                <b-form-checkbox
                                    v-model="pdefault"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Por Defecto
                                </b-form-checkbox>
                            </form>
                        </b-modal>
                    </b-card>
                </div>
                <div class="col-6">    <!--Statuses-->
                    <b-card style="margin-top: 5px">
                        Estados
                        <!-- <b-button size="sm" v-b-modal.snew @click="blankFields">
                            <b-icon-plus></b-icon-plus>
                        </b-button> -->
                        <table class="table b-table table-striped hover">
                            <thead>
                                <td>Nombre</td>
                                <td>Mensaje</td>
                            </thead>
                            <tr v-for="status in statuses" :key="status.id" @click="sedit(
                                    status.id,
                                    status.state,
                                    status.message,
                                    status.created_by,
                                    status.created_at,
                                    status.updated_at
                                )" v-b-modal.sedit>
                                <td>
                                    {{status.state}}
                                </td>
                                <td>
                                    {{status.message}}
                                </td>
                            </tr>
                        </table>
                        <b-modal id="sedit" ref="sedit" title="Editar Estado" @ok="seditsubmit">
                            <form ref="sform">
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        v-model="sstate"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Mensaje">
                                    <b-form-input
                                        v-model="smessage"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                            </form>
                            <!-- <b-button @click="confirmDelete('status',sid)">Eliminar</b-button> -->
                        </b-modal>
                        <!-- <b-button size="sm" v-b-modal.snew @click="blankFields">
                            <b-icon-plus></b-icon-plus>
                        </b-button>
                        <b-modal id="snew" title="Nuevo Departamento" @ok="snewsubmit">
                            <form ref="snewform">
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        v-model="sstate"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Mensaje">
                                    <b-form-input
                                        v-model="smessage"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                            </form>
                        </b-modal> -->
                    </b-card>
                </div>
                <div class="col-6">    <!--Sources-->
                    <b-card style="margin-top: 5px">
                        Canales
                        <b-button size="sm" v-b-modal.srnew @click="blankFields">
                            <b-icon-plus></b-icon-plus>
                        </b-button>
                        <table class="table b-table table-striped hover">
                            <thead>
                                <td>Nombre</td>
                                <td>Activo</td>
                            </thead>
                            <tr v-for="source in sources" :key="source.id" @click="sredit(
                                    source.id,
                                    source.name,
                                    source.created_by,
                                    source.created_at,
                                    source.updated_at,
                                    source.is_active
                                )" v-b-modal.sredit>
                                <td>
                                    {{source.name}}
                                </td>
                                <td v-if="source.is_active==1">
                                    <b-icon-check2></b-icon-check2>
                                </td>
                                <td v-else></td>
                            </tr>
                        </table>
                        <b-modal id="sredit" ref="sredit" title="Editar Canal" @ok="sreditsubmit">
                            <form ref="srform">
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        v-model="srname"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-checkbox
                                    v-model="sractive"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Activo
                                </b-form-checkbox>
                            </form>
                            <b-button @click="confirmDelete('source',srid)">Eliminar</b-button>
                        </b-modal>
                        <b-modal id="srnew" title="Nuevo Canal" @ok="srnewsubmit">
                            <form ref="snewform">
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        v-model="srname"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-checkbox
                                    v-model="sractive"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Activo
                                </b-form-checkbox>
                            </form>
                        </b-modal>
                    </b-card>
                </div>
                <div class="col-6">    <!--Number Codes-->
                    <b-card style="margin-top: 5px">
                        Códigos
                        <b-button size="sm" v-b-modal.ncnew @click="blankFields">
                            <b-icon-plus></b-icon-plus>
                        </b-button>
                        <table class="table b-table table-striped hover">
                            <thead>
                                <td>Departamento</td>
                                <td>Código</td>
                                <td>Activo</td>
                            </thead>
                            <tr v-for="numbercode in numbercodes" :key="numbercode.id" @click="ncedit(
                                    numbercode.id,
                                    numbercode.dept_id,
                                    numbercode.number_code,
                                    numbercode.is_active,
                                    numbercode.created_by,
                                    numbercode.created_at,
                                    numbercode.updated_at
                                )" v-b-modal.ncedit>
                                <td>
                                    {{numbercode.name}}
                                </td>
                                <td>
                                    {{numbercode.number_code}}
                                </td>
                                <td v-if="numbercode.is_active==1">
                                    <b-icon-check2></b-icon-check2>
                                </td>
                                <td v-else></td>
                            </tr>
                        </table>
                        <b-modal id="ncedit" ref="ncedit" title="Editar Código" @ok="nceditsubmit">
                            <form ref="ncform">
                                <b-form-group label="Departamento">
                                    <b-form-select
                                        v-model="ncdept"
                                        :options="departments"
                                        value-field="id"
                                        text-field="name"
                                    ></b-form-select>
                                </b-form-group>
                                <b-form-group label="Código">
                                    <b-form-input
                                        v-model="ncode"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-checkbox
                                    v-model="ncactive"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Activo
                                </b-form-checkbox>
                            </form>
                            <b-button @click="confirmDelete('numbercode',ncid)">Eliminar</b-button>
                        </b-modal>
                        <b-modal id="ncnew" title="Nuevo Código" @ok="ncnewsubmit">
                            <form ref="ncnewform">
                                <b-form-group label="Departamento">
                                    <b-form-select
                                        v-model="ncdept"
                                        :options="departments"
                                        value-field="id"
                                        text-field="name"
                                    ></b-form-select>
                                </b-form-group>
                                <b-form-group label="Código">
                                    <b-form-input
                                        v-model="ncode"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-checkbox
                                    v-model="ncactive"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Activo
                                </b-form-checkbox>
                            </form>
                        </b-modal>
                    </b-card>
                </div>
                <div class="col-6">    <!--Dues-->
                    <b-card style="margin-top: 5px">
                        Vencimientos
                        <b-button size="sm" v-b-modal.dunew @click="blankFields">
                            <b-icon-plus></b-icon-plus>
                        </b-button>
                        <table class="table b-table table-striped hover">
                            <thead>
                                <td>Nombre</td>
                                <td>Activo</td>
                            </thead>
                            <tr v-for="due in dues" :key="due.id" @click="duedit(
                                    due.id,
                                    due.name,
                                    due.time,
                                    due.period,
                                    due.is_active,
                                    due.created_by,
                                    due.created_at,
                                    due.updated_at
                                )" v-b-modal.duedit>
                                <td>
                                    {{due.name}}
                                </td>
                                <td v-if="due.is_active==1">
                                    <b-icon-check2></b-icon-check2>
                                </td>
                                <td v-else></td>
                            </tr>
                        </table>
                        <b-modal id="duedit" ref="duedit" title="Editar Vencimiento" @ok="dueditsubmit">
                            <form ref="duform">
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        v-model="duname"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Tiempo">
                                    <b-form-input
                                        v-model="dutime"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Período">
                                    <b-form-select
                                        v-model="duperiod"
                                        :options="period"
                                        value-field="value"
                                        text-field="text"
                                    ></b-form-select>
                                </b-form-group>
                                <b-form-checkbox
                                    v-model="duactive"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Activo
                                </b-form-checkbox>
                            </form>
                            <b-button @click="confirmDelete('due',duid)">Eliminar</b-button>
                        </b-modal>
                        <b-modal id="dunew" title="Nuevo Vencimiento" @ok="dunewsubmit">
                            <form ref="dunewform">
                                <b-form-group label="Nombre">
                                    <b-form-input
                                        v-model="duname"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Tiempo">
                                    <b-form-input
                                        v-model="dutime"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Período">
                                    <b-form-select
                                        v-model="duperiod"
                                        :options="period"
                                        value-field="value"
                                        text-field="text"
                                    ></b-form-select>
                                </b-form-group>
                                <b-form-checkbox
                                    v-model="duactive"
                                    value="1"
                                    unchecked-value="0"
                                    >
                                    Activo
                                </b-form-checkbox>
                            </form>
                        </b-modal>
                    </b-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import bus from '../../support-event-bus';
export default {
    data(){
        return{
            departments: [],            
            did: '',
            dname: '',
            dactive: '',
            dpublic: '',
            
            priorities: [],
            pid: '',
            pname: '',
            pstatus: '',
            pdesc: '',
            pcolor: '',
            purgency: '',
            ppublic: '',
            pdefault: '',
            pcreated_by: '',
            pcreated_at: '',
            pupdated_at: '',
            
            statuses: [],
            sid: '',
            sname: '',
            sstate: '',
            smessage: '',
            screated_by: '',
            screated_at: '',
            supdated_at: '',
            
            sources: [],
            srid: '',
            srname: '',
            screated_by: '',
            screated_at: '',
            supdated_at: '',
            sractive: '',

            numbercodes: [],
            ncid: '',
            ncdept: '',
            ncode: '',
            ncactive: '',
            nccreated_by: '',
            nccreated_at: '',
            ncupdated_at: '',
            
            dues: [],
            duid: '',
            duname: '',
            dutime: '',
            duperiod: '',
            duactive: '',
            ducreated_by: '',
            ducreated_at: '',
            duupdated_at: '',
            period: [
                { value: 'minutes', text: 'Minutos'},
                { value: 'hours', text: 'Horas'},
                { value: 'days', text: 'Días'},
            ],
            showConfig: false
        }
    },
    methods:{
        blankFields(){
            this.did = '';
            this.dname = '';
            this.dactive = '';
            this.dpublic = '';
            
            this.pid = '';
            this.pname = '';
            this.pstatus = '';
            this.pdesc = '';
            this.pcolor = '';
            this.purgency = '';
            this.ppublic = '';
            this.pdefault = '';
            this.pcreated_by = '';
            this.pcreated_at = '';
            this.pupdated_at = '';
            
            this.sid = '';
            this.sstate = '';
            this.smessage = '';
            this.screated_by = '';
            this.screated_at = '';
            this.supdated_at = '';
            
            this.srid = '';
            this.srname = '';
            this.srcreated_by = '';
            this.srcreated_at = '';
            this.srupdated_at = '';
            this.sractive = '';
            
            this.ncid = '';
            this.ncdept = '';
            this.ncode = '';
            this.ncactive = '';
            this.nccreated_by = '';
            this.nccreated_at = '';
            this.ncupdated_at = '';

            this.duid = '';
            this.duname = '';
            this.dutime = '';
            this.duperiod = '';
            this.duis_active = '';
            this.ducreated_by = '';
            this.ducreated_at = '';
            this.duupdated_at = '';
        },
        confirmDelete(type,id) {
            this.$bvModal.msgBoxConfirm('Está Seguro de Eliminar?')
            .then(value => {
                if (value==true) {
                    axios.post('/delete-config',{
                        'type': type,
                        'id': id
                    }).then((response) => {
                        // console.log(response.data);
                        if (response.data == 'ok') {
                            switch (type) {
                                case 'department':
                                    this.getDepartments();
                                    this.$refs['dedit'].hide();
                                break;
                                case 'priority':
                                    this.getPriorities();
                                    this.$refs['pedit'].hide();
                                break;
                                // case 'status':
                                //     this.getStatuses();
                                //     this.$refs['sedit'].hide();
                                // break;
                                case 'source':
                                    this.getSources();
                                    this.$refs['sredit'].hide();
                                break;
                                case 'numbercode':
                                    this.getNumberCodes();
                                    this.$refs['ncedit'].hide();
                                break;
                                case 'due':
                                    this.getDues();
                                    this.$refs['duedit'].hide();
                                break;
                            }
                        }
                    }).catch(error => {console.log(error)});
                }
            })
            .catch(error => {console.log(error)});
        },
        getSupportApp(){
            axios.get('/get-support-app')
            .then((response) => {
                // console.log(response.data);
                this.departments = response.data.departments;
                this.statuses = response.data.statuses;
                this.priorities = response.data.priorities;
                this.sources = response.data.sources;
                this.numbercodes = response.data.numbercodes;
                this.dues = response.data.dues;
            }).catch(error => {console.log(error)});
        },
        getDepartments(){
            this.departments = [];
            axios.get('/get-departments')
            .then((response) => {
                // console.log(response.data);
                this.departments = response.data;
            }).catch(error => {console.log(error)});
        },
        dedit(did,dname,dactive,dpublic){
            this.blankFields();
            this.did = did;
            this.dname = dname;
            this.dactive = dactive;
            this.dpublic = dpublic;
        },
        deditsubmit() {
            axios.post('/edit-department',{
                'id':this.did,
                'name':this.dname,
                'is_active':this.dactive,
                'is_public':this.dpublic
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getDepartments();
                }
            }).catch(error => {console.log(error)});
        },
        dnewsubmit() {
            axios.post('/new-department',{
                'name':this.dname,
                'is_active':this.dactive,
                'is_public':this.dpublic
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getDepartments();
                }
            }).catch(error => {console.log(error)});
        },
        getPriorities(){
            this.priorities = [];
            axios.get('/get-priorities')
            .then((response) => {
                // console.log(response.data);
                this.priorities = response.data;
            }).catch(error => {console.log(error)});
        },
        pedit(pid,pname,pstatus,pdesc,pcolor,purgency,ppublic,pdefault,pcreated_by,pcreated_at,pupdated_at){
            this.blankFields();
            this.pid = pid;
            this.pname = pname;
            this.pstatus = pstatus;
            this.pdesc = pdesc;
            this.pcolor = pcolor;
            this.purgency = purgency;
            this.ppublic = ppublic;
            this.pdefault = pdefault;
            this.pcreated_by = pcreated_by;
            this.pcreated_at = pcreated_at;
            this.pupdated_at = pupdated_at;
        },
        peditsubmit() {
            axios.post('/edit-priority',{
                'id': this.pid,
                'name': this.pname,
                'status': this.pstatus,
                'desc': this.pdesc,
                'color': this.pcolor,
                'urgency': this.purgency,
                'public': this.ppublic,
                'default': this.pdefault
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getPriorities();
                }
            }).catch(error => {console.log(error)});
        },
        pnewsubmit() {
            axios.post('/new-priority',{
                'name': this.pname,
                'status': this.pstatus,
                'desc': this.pdesc,
                'color': this.pcolor,
                'urgency': this.purgency,
                'public': this.ppublic,
                'default': this.pdefault
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getPriorities();
                }
            }).catch(error => {console.log(error)});
        },
        getStatuses(){
            this.statuses = [];
            axios.get('/get-statuses')
            .then((response) => {
                this.statuses = response.data;
            }).catch(error => {console.log(error)});
        },
        sedit(sid,sstate,smessage,screated_by,screated_at,supdated_at){
            this.blankFields();
            this.sid = sid;
            this.sstate = sstate;
            this.smessage = smessage;
            this.screated_by = screated_by;
            this.screated_at = screated_at;
            this.supdated_at = supdated_at;
        },
        seditsubmit() {
            axios.post('/edit-status',{
                'id' : this.sid,
                'name' : this.sname,
                'state' : this.sstate,
                'message' : this.smessage
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getStatuses();
                }
            }).catch(error => {console.log(error)});
        },
        // snewsubmit() {
        //     console.log(this.pcolor);
        //     axios.post('/new-status',{
        //         'name' : this.sname,
        //         'state' : this.sstate,
        //         'message' : this.smessage,
        //         'created_by' : this.screated_by,
        //         'created_at' : this.screated_at,
        //         'updated_at' : this.supdated_at
        //     }).then((response) => {
        //         console.log(response.data);
        //         if (response.data == 'ok') {
        //             this.getStatuses();
        //         }
        //     }).catch(error => {console.log(error)});
        // },
        getSources(){
            this.sources = [];
            axios.get('/get-sources')
            .then((response) => {
                this.sources = response.data;
            }).catch(error => {console.log(error)});
        },
        sredit(srid,srname,srcreated_by,srcreated_at,srupdated_at,sractive){
            this.blankFields();
            this.srid = srid;
            this.srname = srname;
            this.srcreated_by = srcreated_by;
            this.srcreated_at = srcreated_at;
            this.srupdated_at = srupdated_at;
            this.sractive = sractive;
        },
        sreditsubmit() {
            axios.post('/edit-source',{
                'id' : this.srid,
                'name' : this.srname,
                'active' : this.sractive,
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getSources();
                }
            }).catch(error => {console.log(error)});
        },
        srnewsubmit() {
            axios.post('/new-source',{
                'name' : this.srname,
                'active' : this.sractive
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getSources();
                }
            }).catch(error => {console.log(error)});
        },
        getNumberCodes(){
            this.numbercodes = [];
            axios.get('/get-number-codes')
            .then((response) => {
                this.numbercodes = response.data;
            }).catch(error => {console.log(error)});
        },
        ncedit(ncid,ncdept,ncode,ncactive,nccreated_by,nccreated_at,ncupdated_at){
            this.blankFields();
            this.ncid = ncid;
            this.ncdept = ncdept;
            this.ncode = ncode;
            this.ncactive = ncactive;
            this.nccreated_by = nccreated_by;
            this.nccreated_at = nccreated_at;
            this.ncupdated_at = ncupdated_at;
        },
        nceditsubmit() {
            axios.post('/edit-number-code',{
                'id' : this.ncid,
                'dept' : this.ncdept,
                'ncode' : this.ncode,
                'is_active' : this.ncactive
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getNumberCodes();
                }
            }).catch(error => {console.log(error)});
        },
        ncnewsubmit() {
            axios.post('/new-number-code',{
                'dept' : this.ncdept,
                'ncode' : this.ncode,
                'is_active' : this.ncactive
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getNumberCodes();
                }
            }).catch(error => {console.log(error)});
        },
        getDues(){
            this.dues = [];
            axios.get('/get-dues')
            .then((response) => {
                this.dues = response.data;
            }).catch(error => {console.log(error)});
        },
        duedit(duid,duname,dutime,duperiod,duactive,ducreated_by,ducreated_at,duupdated_at){
            this.blankFields();
            this.duid = duid;
            this.duname = duname;
            this.dutime = dutime;
            this.duperiod = duperiod;
            this.duactive = duactive;
            this.ducreated_by = ducreated_by;
            this.ducreated_at = ducreated_at;
            this.duupdated_at = duupdated_at;
        },
        dueditsubmit() {
            axios.post('/edit-due',{
                'id' : this.duid,
                'name' : this.duname,
                'time' : this.dutime,
                'period' : this.duperiod,
                'active' : this.duactive
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getDues();
                }
            }).catch(error => {console.log(error)});
        },
        dunewsubmit() {
            axios.post('/new-due',{
                'name' : this.duname,
                'time' : this.dutime,
                'period' : this.duperiod,
                'active' : this.duactive
            }).then((response) => {
                if (response.data == 'ok') {
                    this.getDues();
                }
            }).catch(error => {console.log(error)});
        },
        active(value){
            if (value==1) {
                return 'Activo';
            }
            else{
                return 'Inactivo'
            }
        },
        public(value){
            if (value==1) {
                return 'Público';
            }
            else{
                return 'Privado'
            }
        }
    },
    mounted(){
        // this.getDepartments();
        // this.getPriorities();
        // this.getStatuses();
        // this.getSources();
        // this.getNumberCodes();
        // this.getDues();
        this.getSupportApp();
    },
    created(){
        bus.$on('showConfig', data =>{
            this.showConfig = true;
        });
        bus.$on('showDashboard', data =>{
            this.showConfig = false;
        });
        bus.$on('showTickets', data =>{
            this.showConfig = false;
        });
    }
}
</script>

<style>

</style>