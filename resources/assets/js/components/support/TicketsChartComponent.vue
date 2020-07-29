<template>
    <div class="row">
        <div class="col-12">
            <highcharts :options="chartOptions"></highcharts>
        </div>
    </div>
</template>

<script>
export default {
    beforeCreate(){
        fetch('/get-tickets-today-yesterday')
            .then(response => response.json())
            .then(data => {
                this.chartOptions.xAxis.categories = data.categorias;
                this.chartOptions.series[0].data = data.hoy;
                this.chartOptions.series[0].name = 'Hoy';
                this.chartOptions.series[1].data = data.ayer;
                this.chartOptions.series[1].name = 'Ayer';
            });
    },
    data() {
        return {
            chartOptions:{
                xAxis:{
                    type: 'datetime'
                },
                legend:{
                    align: 'right'
                },
                series:[{
                    data:[{}],
                    name:'Hoy'
                },{
                    data:[{}],
                    name:'Ayer'
                }]
            }
        }
    }
}
</script>

<style>

</style>
