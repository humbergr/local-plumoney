<template lang="html">
  <vue-toastr ref="toastr"></vue-toastr>
</template>

<script>
export default {
  methods: {
    createNotification(body) {
        if (Notification.permission === "granted") {
            // If it's okay let's create a notification
            let notification     = new Notification('AmericanKryptosBank', {
                body: body,
                icon: "/img/cb-img/favicon.png",
            });
            notification.onclick = function (event) {
                event.preventDefault(); // prevent the browser from focusing the Notification's tab
                notification.close();
            }
            this.$refs.toastr.w(body);
        }
    },

    subscribe() {
        let pusher = new Pusher('889fce6a69a9c7050bd3', {cluster: 'us2'});
        pusher.subscribe('my-channel');
        pusher.bind('remaining-alert', data => {
                this.createNotification(data.message);
        })
    },
  },

  created() {
      this.subscribe();
  },

  mounted() {
      Notification.requestPermission().then(function (result) {
          console.log(result);
      });
  }
}
</script>

<style lang="css">
</style>
