<template>
    <div class="user-avatar" :class="size">
        <img :src="user.data.attributes.avatar" :alt="user.data.attributes.name">
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        name: 'UserAvatar',
        props: [
            'size'
        ],
        computed: {
            ...mapGetters(['user']),
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .user-avatar {
        line-height: 0;

        img {
            border-radius: 6px;
            width: 40px;
            height: 40px;
        }

        &.large {

            img {
                border-radius: 9px;
                width: 52px;
                height: 52px;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

    }
</style>
