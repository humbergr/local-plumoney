<template lang="html">
    <div>
        <vue-toastr ref="toastr"></vue-toastr>

        <div class="container">
            <a data-toggle="modal" data-target="#create-tier" href="#" class="btn btn btn-primary mb-3">+ Add Tier</a>
            <div id="new" class="card d-none">

                <div class="card-header text-primary font-weight-bold">
                    <h4>Cajas & Bancos</h4>
                </div>

                <div class="card-body">
                    <form method="POST" accept-charset="utf-8">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div
                                    class="form-group">
                                    <label class="form-control-label text-primary"
                                        for="input-moneda"></label>
                                    <br>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Crear Banco u/o Caja</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div> 

            <div class="card mt-3">

                <div class="card-header text-primary font-weight-bold">
                    <h4>Tiers</h4>
                </div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="text-primary">
                            <tr>
                                <th>Name</th>
                                <th>requirements</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="tier in tiers">
                                <td>{{tier.name}}</td>
                                <td>
                                    <ul v-for="test in tier.requirements.split(new RegExp(' , ', 'g')).slice(0, -1)">
                                        <li>
                                            {{test}}
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <a data-toggle="modal" data-target="#edit-tier" @click="openModal(tier)" href="#">
                                        <i class="fa fa-edit text-muted"></i>
                                    </a>
                                    |
                                    <a @click="deleteTier(tier.id)" href="#">
                                        <i class="fa fa-trash text-muted"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div> 
            </div> 
        </div>

        <div class="modal fade" id="create-tier" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title text-primary font-weight-bold">Add Tier</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="row">

                            <div class="col-md-12">
                                <form v-on:submit.prevent="">
                                    <div class="container">
                                        <div class="form-group">
                                            <label for="name" class="col-form-label" required>Name:</label>
                                            <input type="text" v-model="tier_name" class="form-control" id="name">
                                        </div>
                                    </div>

                                    <div class="list-group">
                                        <div class="list-group-item" v-for="(re, index) in requirements">
                                            <div class="form-group">
                                                <label for="name" class="col-form-label">Documento de validación: {{index + 1}}</label>
                                                <div class="row justify-content-md-center">
                                                    <div class="col col-lg-9">
                                                        <input type="text" class="form-control" v-model="re.requirement" placeholder="Answer" required>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <button tabIndex="-1" class="btn btn-danger p-2 btn-sm" type="button" @click="deleteRequirement(index)">
                                                            Remove
                                                        </button> 
                                                    </div>
                                                </div>
                                                
                                               
                                                
                                            </div>
                                        </div>
                                        <div style="display:none"></div>
                                    </div>
                                    

                                    <div class="col-md-6 m-2">
                                        <button class="btn btn-secondary btn-sm">Add Answer</button>
                                    </div>

                                     <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" @click="createTier" class="btn btn-primary">Save changes</button>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-tier" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title text-primary font-weight-bold">Add Tier</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="row">

                            <div class="col-md-12">
                                <form v-on:submit.prevent="">
                                    <div class="container">
                                        <div class="form-group">
                                            <label for="name" class="col-form-label" required>Name:</label>
                                            <input type="hidden" v-model="tier_id" class="form-control" id="name">

                                            <input type="text" v-model="tier_name_modal" class="form-control" id="name">
                                        </div>
                                    </div>

                                    <div class="list-group">
                                        <div class="list-group-item" v-for="(re, index) in requirements_modal">
                                            <div class="form-group">
                                                <label for="name" class="col-form-label">Documento de validación: {{index + 1}}</label>
                                                <div class="row justify-content-md-center">
                                                    <div class="col col-lg-9">
                                                        <input type="text" class="form-control" v-model="requirements_modal[index]" placeholder="Answer" required>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <button tabIndex="-1" class="btn btn-danger p-2 btn-sm" type="button" @click="deleteRequerimentModal(index)">
                                                            Remove
                                                        </button> 
                                                    </div>
                                                </div>
                                                
                                               
                                                
                                            </div>
                                        </div>
                                        <div style="display:none"></div>
                                    </div>
                                    

                                    <div class="col-md-6 m-2">
                                        <button class="btn btn-secondary btn-sm">Add Answer</button>
                                    </div>

                                     <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" @click="updateTier" class="btn btn-primary">Save changes</button>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {
    data() {
        return {
            tiers: null,
            tier_name: null,
            requirements: [{requirement: ''}],
            tier_name_modal: null,
            requirements_modal: null,
            tier_id: null
        }
    },
    created() {
        axios.get(window.location.origin + '/operaciones/get-tiers')
                    .then(re => {
                        this.tiers = re.data;
                    });
    },
    mounted() {
        console.log(this.tiers);
    },
    methods: {
    openModal(tier){
        this.tier_id = tier.id;
        this.tier_name_modal = tier.name;
        this.requirements_modal =  tier.requirements.split(' , ');
        var popped = this.requirements_modal.pop();        
    },
    addRequeriment: function(){
      this.requirements.push({requirement: ''});
    },
    deleteRequirement: function(answer_id){
      this.requirements.splice(answer_id, 1);
    },
    addRequerimentModal: function(){
      this.requirements_modal.push({requirement: ''});
    },
    deleteRequerimentModal: function(answer_id){
      this.requirements_modal.splice(answer_id, 1);
    },
    createTier(){
        let data = this.requirements;
        let json = JSON.stringify(data);
        axios.get(window.location.origin + '/operaciones/new-tier-level', {
                        params: {
                            name: this.tier_name,
                            requirements: json
                        }
                    }).then(re => {
                        this.tiers = re.data;
                        $('#create-tier').modal('hide')
                        this.$refs.toastr.s('Tier creado satifactoriamente');
                    });
    },
    updateTier(){
        let data = this.requirements_modal;
        let json = JSON.stringify(data);
        axios.get(window.location.origin + '/operaciones/update-tier-level', {
                        params: {
                            id: this.tier_id,
                            name: this.tier_name_modal,
                            requirements: json
                        }
                    }).then(re => {
                        this.tiers = re.data;
                        $('#edit-tier').modal('hide')
                        this.$refs.toastr.s('Tier actualizado satifactoriamente');
                    });
        
    },
    deleteTier(id){
        axios.get(window.location.origin + '/operaciones/delete-tier-level', {
                        params: {
                            id: id,
                        }
                    }).then(re => {
                        this.tiers = re.data;
                        this.$refs.toastr.w('Tier eliminado');
                    });
        
    }
  }
}
</script>