<template>
    <div class="user-headline">
        <UserAvatar size="large"/>
        <div class="user-meta">
            <b class="name">{{ user.data.relationships.settings.data.attributes.name }}</b>
            <span class="email text-theme">{{ user.data.attributes.email }}</span>
        </div>
    </div>
</template>

<script>
    import UserAvatar from '@/components/Others/UserAvatar'
    import { mapGetters } from 'vuex'

    export default {
        name: 'UserHeadline',
        components: {
            UserAvatar,
        },
        computed: {
            ...mapGetters([
                'user'
            ]),
        },
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .user-headline {
        display: flex;
        align-items: center;
    }

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
</style>
