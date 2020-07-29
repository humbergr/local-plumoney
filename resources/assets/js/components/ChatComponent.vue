<template lang="html">
    <div class="chat__tab--wrapper">
        <div class="chat__tab mx-2 collapsed">
            <!-- chat header -->
            <header class="chat__header" :class="{'new-message' : incoming_msg}">
                <div class="flex-grow-1">
                    <a href="#!" class="chat__headerAvatar mr-2" title="Sara Connor">
                        <img src="img/cb-img/avatar.png" alt="Profile avatar" class="object-cover rounded-circle">
                    </a>
                    <a href="#!" class="chat__headerName text-truncate">{{ad.ad_username}}</a>
                </div>
                <!--  <button type="button" class="close" title="Cerrar pestaña">
                      <span aria-hidden="true">&times;</span>
                  </button> -->
            </header>

            <!-- chat body content -->
            <div class="chat__body loader--wrapper" :class="{ '--loading': loader }">
                <!-- chat messages -->
                <div class="chat__msgs py-3 px-2" ref="scroller">

                    <div v-for="msg in messages" class="chat__item mr-2">
                        <div v-if="ad.ad_username != msg.sender.username && msg.msg != ''" class="media">
                            <div class="media-body">
                                <div class="chat__item__message"
                                     :class="{'--sent' : (ad.ad_username != msg.sender.username), '--received' : (ad.ad_username == msg.sender.username)}">
                                    {{msg.msg}}
                                </div>
                            </div>
                        </div>
                        <div v-if="ad.ad_username == msg.sender.username && msg.msg != ''" class="media">
                            <a href="#!" class="chat__item__avatar mt-auto mr-2" title="Sara Connor">
                                <img src="img/cb-img/avatar.png" alt="Profile avatar"
                                     class="object-cover rounded-circle">
                            </a>
                            <div class="media-body">
                                <div class="chat__item__message --received">{{msg.msg}}</div>
                            </div>
                        </div>
                        <div v-if="ad.ad_username != msg.sender.username && msg.attachment_name" class="media">
                            <div class="media-body">
                                <div class="chat__item__message --file --sent"><a
                                        :href="getCBAttachmentUrl(msg.attachment_name)" target="_blank"
                                        class="font-weight-bold" style="font-size:12px !important">{{msg.attachment_name}}
                                    <i class="fa fa-paperclip va-middle"></i></a></div>
                            </div>
                        </div>
                        <div v-if="ad.ad_username == msg.sender.username && msg.attachment_name" class="media">
                            <a href="#!" class="chat__item__avatar mt-auto mr-2" :title="msg.sender.username">
                                <img src="img/cb-img/avatar.png" alt="Profile avatar"
                                     class="object-cover rounded-circle">
                            </a>
                            <div class="media-body">
                                <div class="chat__item__message --file --received"><a
                                        :href="getAttachmentUrl(msg.attachment_name)" target="_blank"
                                        class="font-weight-bold" style="font-size:12px !important">{{msg.attachment_name}}
                                    <i class="fa fa-paperclip va-middle"></i></a></div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- chat tools -->
                <div class="chat__tools border-top">
                    <!-- <textarea name="name" rows="8" cols="80"></textarea> -->
                    <textarea @keyup.enter="sendMessage" v-model="message" @focus="textFocus"
                              class="form-control chat__input mb-1" rows="1" placeholder="Escribir un mensaje..."
                              style="resize: none;"></textarea>
                    <ul class="list-inline px-2 py-1 mb-0">
                        <li class="list-inline-item">
                            <button class="btn-transparent">
                                <i class="fa fa-bank"></i>
                            </button>
                        </li>
                        <li class="list-inline-item">
                            <label for="file-window" class="mb-0">
                                <!-- <button type="button" class="btn-transparent"> -->
                                <div class="btn-transparent px-1">
                                    <i class="fa fa-paperclip"></i>
                                </div>
                                <!-- </button> -->
                            </label>
                            <input type="file" name="file-window" v-on:change="fileHandler" ref="file" hidden>
                        </li>
                        <li class="list-inline-item">
                            <p v-if="file != ''" style="font-size:12px">{{file.name}}</p>
                        </li>
                    </ul>
                </div>

                <div class="loader">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['ad'],


        data() {
            return {
                messages: {},
                loader: true,
                message: '',
                file: '',
                incoming_msg: false,
                count: 0,
            }
        },

        methods: {
            textFocus() {
                this.incoming_msg = false;
            },

            getAttachmentUrl(name) {
                return window.location.origin + '/img/from-lcb-files/' + name;
            },

            getCBAttachmentUrl(name) {
                return window.location.origin + '/img/contact-imgs/' + name;
            },

            getDate(date) {
                var newDate = new Date(date);
                return (newDate.getMonth() + 1) + '/' + newDate.getDate() + '/' + newDate.getFullYear();
            },

            fileHandler() {
                this.file = this.$refs.file.files[0];
            },

            sendMessage() {
                if (this.message != '' || this.file != '') {
                    this.loader  = true;
                    let formData = new FormData();
                    formData.append('file', this.file);
                    axios.post(window.location.origin + '/api/send-message/' + this.ad.contact_id, formData, {
                        params: {
                            message: this.message,
                            _token: $('meta[name="csrf-token"]').attr("content")
                        }
                    })
                        .then(re => {
                            this.count++;
                            this.getMessages();
                        })
                }
            },

            getMessages() {
                this.loader = true;
                axios.get(window.location.origin + '/api/contact-messages/' + this.ad.contact_id + '/' + this.count)
                    .then(re => {
                        if (this.count < re.data.message_count) {
                            this.incoming_msg = true;
                        }
                        this.messages       = re.data.message_list;
                        this.count          = re.data.message_count;
                        this.message        = '';
                        this.file           = '';
                        this.loader         = false;
                        var container       = this.$refs.scroller;
                        container.scrollTop = container.scrollHeight;
                    })
            },

            getMessagesCron() {
                axios.get(window.location.origin + '/api/contact-messages/' + this.ad.contact_id + '/' + this.count)
                    .then(re => {
                        if (this.count < re.data.message_count) {
                            this.incoming_msg = true;
                        }
                        this.messages       = re.data.message_list;
                        this.count          = re.data.message_count;
                        var container       = this.$refs.scroller;
                        container.scrollTop = container.scrollHeight;
                    })
            },

            getInitMessages() {
                this.loader = true;
                axios.get(window.location.origin + '/api/contact-messages-not/' + this.ad.contact_id)
                    .then(re => {
                        this.messages       = re.data.message_list;
                        this.count          = re.data.message_count;
                        this.message        = '';
                        this.file           = '';
                        this.loader         = false;
                        var container       = this.$refs.scroller;
                        container.scrollTop = container.scrollHeight;
                    })
            }
        },

        mounted() {
            this.getInitMessages();
            this.$nextTick(function () {
                window.setInterval(() => {
                    this.getMessagesCron();
                }, 10000);
            })
        }
    }
</script>

<style lang="css">
    .new-message {
        background-color: #c3e6cb;
    }
</style>