<script src="../AdminUsersStates.js"></script>

<template lang="html">
    <div class="__admin_users_states">
        <div class="__trigger"
             @click="displayAdminsList">
            Active Admins
            <img src="/img/icons/admin.svg" alt="Online Admins">
        </div>
        <div class="__floating_info">
            <ul v-if="activeUsers">
                <li class="__li_counter">Active Users: {{activeUsers.length}}</li>
                <li class="__user_item"
                    v-for="user in activeUsers" v-if="user.id !== clientUser.id">
                    <span class="__status_circle" :class="[user.is_idle === 1 ? '__idle' : '']"></span>
                    {{user.name}}: {{user.email}}
                </li>
            </ul>
        </div>
    </div>
</template>

<style lang="scss">
    .jconfirm-title-c {
        text-align: center;
    }

    .jconfirm-content {
        font-size: 13px;
        text-align: center;
    }

    .jconfirm-buttons {
        width: 100%;
        text-align: center;
    }
</style>
