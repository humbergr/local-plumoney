<script>
    import * as Vue from "vue";

    export default {
        props: ['stockholdersList'],
        data() {
            return {
                stockholders: [],
                lastID: 0
            }
        },
        mounted: function () {
            this.$nextTick(function () {
                if (this.stockholdersList.length > 0) {
                    $('.dynamicInput').attr('readonly', 'true');
                }
                this.lastID = this.stockholders.length;
            })
        },
        watch: {
            stockholders: {
                immediate: true,
                handler: function () {
                    this.stockholders = this.stockholdersList;
                }
            },
        },
        methods: {
            addStockholder() {
                let stockholder = '';

                Vue.set(this.stockholders, this.lastID, stockholder);
                // this.stockholders.push(stockholder);
                this.lastID = this.lastID + 1;
            },
            removeStockholder(index) {
                //console.log(index);
                Vue.delete(this.stockholders, index);
                this.lastID = this.lastID - 1;

                if (isNaN(this.lastID)) {
                    this.lastID = 0;
                }
            },
            saveName(event, index) {
                this.stockholders[index] = $(event.target).val();
            }
        }
    }
</script>
<style lang="scss">
</style>
<template>
    <div class="__vue_stockholders">
        <h6 class="text-primary font-weight-bold mb-0 mt-4">Accionistas</h6>
        <div class="text-muted font-14 mb-3">Esta información es privada y será usada
            únicamente para efectos de seguridad
        </div>
        <div id="stockholders" class="row">
            <div class="col-md-6 dynamicInput__col" v-for="(stockholder, index) in stockholders">
                <div class="dynamicInput--wrapper form-group">
                    <input type="text"
                           class="form-control dynamicInput"
                           :name="'UserCompanyProfile[shareholders]['+index+']'"
                           @keyup="saveName($event, index)"
                           placeholder="Nombre de accionista"
                           :value="stockholder">
                    <button type="button"
                            class="dynamicInput__delete"
                            @click="removeStockholder(index)"
                    >&times;
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button id="addDynamicInput"
                        type="button"
                        @click="addStockholder()"
                        class="btn btn-light btn-block font-14 text-left">
                    + Añadir nombre de accionista
                </button>
            </div>
        </div>
    </div>
</template>