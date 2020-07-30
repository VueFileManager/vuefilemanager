<template>
    <div class="user-avatar" :class="size">
        <span v-if="isIncompletePayment || isNearlyFullStorageCapacity" class="notification"></span>
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
            ...mapGetters(['user', 'config']),
            isIncompletePayment() {
                return this.user.data.attributes.incomplete_payment
            },
            isNearlyFullStorageCapacity() {
                return this.config.storageLimit && this.user.relationships.storage.data.attributes.used > 95
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .user-avatar {
        line-height: 0;
        position: relative;
        width: 40px;
        margin: 0 auto;

        .notification {
            width: 12px;
            height: 12px;
            display: block;
            position: absolute;
            bottom: -5px;
            right: -4px;
            border-radius: 10px;
            z-index: 2;
            background: $red;
            border: 2px solid $light_background;
        }

        img {
            border-radius: 6px;
            width: 40px;
            height: 40px;
            object-fit: cover;
        }

        &.large {
            margin: 0;
            width: 54px;

            img {
                border-radius: 9px;
                width: 52px;
                height: 52px;
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .user-avatar {

            .notification {
                border-color: $dark_mode_foreground;
            }
        }
    }
</style>
