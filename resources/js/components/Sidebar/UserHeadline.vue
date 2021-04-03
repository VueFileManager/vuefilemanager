<template>
    <div class="user-meta" v-if="user">
        <b class="name">{{ user.data.relationships.settings.data.attributes.name }}</b>
        <span class="email text-theme">{{ user.data.attributes.email }}</span>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'UserHeadline',
        computed: {
            ...mapGetters(['user']),
        },
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .user-meta {
        padding-left: 20px;

        .name {
            display: block;
            @include font-size(18);
        }

        .email {
            display: block;
            @include font-size(12);
            font-weight: 600;
        }
    }

    @media only screen and (max-width: 690px) {

    }

    @media (prefers-color-scheme: dark) {

    }
</style>
