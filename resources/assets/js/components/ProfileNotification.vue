<template lang="html">

</template>

<script>
    export default {

        methods: {
            createNotification(body) {
                if (Notification.permission === "granted") {
                    // If it's okay let's create a notification
                    let notification     = new Notification('AmericanKryptosBank', {
                        body: body,
                        icon: "img/cb-img/favicon.png",
                    });
                    notification.onclick = function (event) {
                        event.preventDefault(); // prevent the browser from focusing the Notification's tab
                        notification.close();
                    }
                }
            },

            subscribe() {
                let pusher = new Pusher('889fce6a69a9c7050bd3', {cluster: 'us2'});

                pusher.subscribe('my-channel');
                pusher.bind('new-profile', data => {
                    let audio = new Audio('../sounds/new-profile.wav');
                    audio.play();
                    this.createNotification(data.message);
                    location.reload();
                });
            }
        },

        created() {
            this.subscribe();
        },

        mounted() {

        }
    }
</script>

<style lang="css">
</style>
