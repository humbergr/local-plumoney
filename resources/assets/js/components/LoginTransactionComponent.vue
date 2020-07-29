<template lang="html">
  <div class="">
    <input type="hidden" name="sender" v-model="sender" value="">
    <input type="hidden" name="receiver" v-model="receiver" value="">
    <input type="hidden" name="to_send" v-model="to_send" value="">
    <input type="hidden" name="recomended_price" v-model="recomended_price" value="">
  </div>
</template>

<script>
export default {
  data() {
    return {
      sender: localStorage.getItem("sender"),
      receiver: localStorage.getItem("receiver"),
      to_send: localStorage.getItem("to_send"),
      recomended_price: localStorage.getItem("recomended_price"),
    }
  }
}
</script>

<style lang="css">
</style>
