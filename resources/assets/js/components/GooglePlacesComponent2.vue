<template>
  <div class="row">
    <div class="col-md-2">
      <label for="visible">Location</label>
    </div>
    <div class="col-md-6">
      <input ref="autocomplete" placeholder="Enter a location" :value="location_string" class="search-location form-control" name="ad-place" type="text" required/>
      <input type="hidden" name="lat" value="" v-model="lat">
      <input type="hidden" name="lon" value="" v-model="lon">
      <input type="hidden" name="city" value="" v-model="city">
      <input type="hidden" name="location_string" value="" v-model="location_string">
      <input type="hidden" name="country_code" value="" v-model="country_code">
    </div>
  </div>
</template>

<script>
    export default {
        props: ['def_lat', 'def_lon', 'def_city', 'def_location', 'def_country'],

        data(){
          return {
            lat: this.def_lat,
            lon: this.def_lon,
            city: this.def_city,
            location_string: this.def_location,
            country_code: this.def_country
          }
        },

        mounted(){
          this.autocomplete = new google.maps.places.Autocomplete(
            (this.$refs.autocomplete),
            {types: ['geocode']}
          );

          this.autocomplete.addListener('place_changed', () => {
            let place = this.autocomplete.getPlace();
            let ac = place.address_components;

            this.lat = place.geometry.location.lat();
            this.lon = place.geometry.location.lng();
            this.location_string = place.formatted_address;

            var city = '';
            var country_code = '';

            ac.forEach(function(x){
              if (x['types'][0] == 'locality') {
                city = x['long_name'];
              }
              if (x['types'][0] == 'country') {
                country_code = x['short_name'];
              }
            });

            this.city = city;
            this.country_code = country_code;

            console.log(place);
          });
        },

    }
</script>
