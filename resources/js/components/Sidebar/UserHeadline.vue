<template>
    <div class="user-meta" v-if="app">
        <b class="name">{{ app.user.name }}</b>
        <span class="email">{{ app.user.email }}</span>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'UserHeadline',
        computed: {
            ...mapGetters(['app']),
        },
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .user-meta {
        padding-left: 20px;

        .name {
            display: block;
            @include font-size(18);
        }

        .email {
            display: block;
            @include font-size(12);
            color: $theme;
            font-weight: 600;
        }
    }

    @media only screen and (max-width: 690px) {

    }

    @media (prefers-color-scheme: dark) {

    }
</style>
