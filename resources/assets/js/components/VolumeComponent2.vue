<template lang="html">
  <div class="grid-x">
            <div class="cell international-vol">
                <div class="grid-x">
                    <div class="cell text-center">
                        <h3>International Volume</h3>
                        <select name="" class="form-control" v-model="selectedCountry" v-on:change="getVolume(selectedCountry)">
                            <option value="" selected="selected" disabled="disabled">Select Country</option>
                            <option v-for="country in countries" v-bind:value="country.code">{{ country.name.replace(new RegExp('-', 'g'), " ") }}</option>
                        </select>
                    </div>
                    <div class="cell text-center">
                        <div v-if="loader_2" class="loader"></div>
                        <div v-else class="btc-vol">
                            {{selectedVolume}}
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
           selectedVolume: '-',
           loader_2: false
         }
       },
       methods: {
         getVolume(country){
           this.loader_2 = true;
           axios.get(window.location.origin+'/get-volume/'+country, {
           })
           .then(
             re => {
               this.loader_2 = false;
               this.selectedVolume = re.data + ' BTC';
             }
           )
         }
       }
    }
</script>

<style media="screen">
.loader {
  border: 4px solid #f3f3f3; /* Light grey */
  border-top: 4px solid #46953f; /* Blue */
  border-radius: 50%;
  width: 36px;
  height: 36px;
  animation: spin 2s linear infinite;
  margin: 0 auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
