<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-md-offset-1">
                <div class="card card-default">
                    <div class="card-header">Total Volume</div>

                    <div class="card-body loader--wrapper" :class="{ '--loading': loader }">
                        <div class="row">
                          <div class="col-md-6 text-center">
                              <div v-if="loader" class="loader"></div>
                              <div v-else class="">
                                Buy {{selectedVolumeB}}
                                <br>
                                Sell {{selectedVolumeS}}
                                <br>
                                Volume {{volumeT}}
                              </div>
                          </div>
                          <div class="col-md-6 text-center">
                            <select v-model="selectedCountry" v-on:change="getVolume(selectedCountry)" class="form-control" name="">
                              <option  value="" selected disabled>Select Country</option>
                              <option v-for="country in countries" v-bind:value="country.value">{{ country.text.replace(new RegExp('-', 'g'), " ") }}</option>
                            </select>
                          </div>
                        </div>

                        <div class="loader">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="sr-only">Loading...</span>
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
      props: ['countries'],
       data() {
         return {
           selectedCountry: '',
           selectedVolumeB: '-',
           selectedVolumeS: '-',
           volumeT: '-',
           loader: false
         }
       },
       mounted() {
         console.log(this.counties);
       },
       methods: {
         getVolume(country){
           this.loader = true;
           axios.get(window.location.origin+'/get-volume-buy/'+country, {
           })
           .then(
             re => {
              //  this.loader = false;
               this.selectedVolumeB = re.data + ' BTC';
             }
           )
           axios.get(window.location.origin+'/get-volume-sell/'+country, {
           })
           .then(
             re => {
               this.selectedVolumeS = re.data + ' BTC';
               this.loader = false;
             }
           )
           axios.get(window.location.origin+'/get-volume-total/'+country, {
           })
           .then(
             re => {
               this.volumeT = re.data + ' BTC';
              //  this.loader = false;
             }
           )
         }
       }
    }
</script>
