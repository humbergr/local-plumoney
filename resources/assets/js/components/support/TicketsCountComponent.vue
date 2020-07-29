<template>
    <div class="card rounded-lg shadow-none mb-3">    
        <div class="card-body d-flex justify-content-between border-bottom">
            <div align="center">
                <!-- <a href="{{URL::to('/ticket-list')}}"> -->
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Total</div>
                    <h5 class="text-primary mb-0"> {{ total }} </h5>
                <!-- </a> -->
            </div>
            <div align="center">
                <!-- <a href="{{URL::to('/ticket-list?type=1')}}"> -->
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Vencidos</div>
                    <h5 class="text-primary mb-0"> {{ overdue }} </h5>
                <!-- </a> -->
            </div>
            <div align="center">
                <!-- <a href="{{URL::to('/ticket-list?type=1')}}"> -->
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Abiertos</div>
                    <h5 class="text-primary mb-0"> {{ open }} </h5>
                <!-- </a> -->
            </div>
            <div align="center">
                <!-- <a href="{{URL::to('/ticket-list?type=2')}}"> -->
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Cerrados</div>
                    <h5 class="text-primary mb-0"> {{ closed }} </h5>
                <!-- </a> -->
            </div>
            <div align="center">
                <!-- <a href="{{URL::to('/ticket-list?type=3')}}"> -->
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Resueltos</div>
                    <h5 class="text-primary mb-0"> {{ resolved }} </h5>
                <!-- </a> -->
            </div>
            <div align="center">
                <!-- <a href="{{URL::to('/ticket-list?type=4')}}"> -->
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Eliminados</div>
                    <h5 class="text-primary mb-0"> {{ deleted }} </h5>
                <!-- </a> -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            total: '',
            overdue: '',
            open: '',
            closed: '',
            resolved: '',
            deleted: ''
        }
    },
    mounted() {
        axios.get('/get-tickets-count').then((response) => {
            // console.log(response.data);
            this.total = response.data.total;
            this.overdue = response.data.overdue;
            this.open = response.data.open;
            this.closed = response.data.closed;
            this.resolved = response.data.resolved;
            this.deleted = response.data.deleted;
        });
    },
}
</script>

<style>

</style>