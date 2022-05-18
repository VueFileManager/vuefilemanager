<template>
    <transition name="popup">
        <div class="popup fixed top-[27px] bottom-[27px] left-20 z-20 w-[360px]">
            <!--Triangle-->
            <div
				v-if="! isEmpty"
                class="absolute left-0 z-20 inline-block w-4 translate-x-[-15px] overflow-hidden"
                :class="{
                    'top-[64px]': config.subscriptionType !== 'metered',
                    'top-[102px]': config.subscriptionType === 'metered',
                }"
            >
                <div
                    class="h-12 origin-top-right -rotate-45 transform bg-white bg-opacity-80 backdrop-blur-2xl dark:bg-2x-dark-foreground"
                ></div>
            </div>

            <div
                v-click-outside="clickOutside"
                class="max-h-full overflow-y-auto rounded-xl bg-white bg-opacity-80 p-3 text-left shadow-xl backdrop-blur-2xl dark:bg-2x-dark-foreground dark:bg-opacity-80 dark:backdrop-blur-2xl"
            >
                <!--Title-->
                <b class="mb-2.5 block px-2.5 text-xl font-extrabold dark:text-gray-200">
                    {{ $t('notification_center') }}
                </b>

                <div class="px-2.5">
                    <MobileActionButton
                        v-if="readNotifications.length || unreadNotifications.length"
                        @click.native="$store.dispatch('deleteAllNotifications')"
                        icon="check-square"
                        class="mb-2 dark:!bg-4x-dark-foreground"
                    >
                        {{ $t('clear_all') }}
                    </MobileActionButton>

                    <p v-if="isEmpty" class="mt-1 text-sm">
                        {{ $t('not_any_notifications') }}
                    </p>
                </div>

                <b
                    v-if="unreadNotifications.length"
                    class="dark-text-theme mt-1.5 mb-2.5 block px-2.5 text-xs text-gray-400"
                >
                    {{ $t('unread') }}
                </b>

                <Notification
                    :notification="notification"
                    v-for="notification in unreadNotifications"
                    :key="notification.id"
                />

                <b
                    v-if="readNotifications.length"
                    class="dark-text-theme mt-2.5 mb-2.5 block px-2.5 text-xs text-gray-400"
                >
                    {{ $t('read') }}
                </b>

                <Notification
                    :notification="notification"
                    v-for="notification in readNotifications"
                    :key="notification.id"
                />
            </div>
        </div>
    </transition>
</template>

<script>
import MobileActionButton from '../UI/Buttons/MobileActionButton'
import Notification from './Components/Notification'
import vClickOutside from 'v-click-outside'
import { mapGetters } from 'vuex'

export default {
    name: 'NotificationCenter',
    components: {
        MobileActionButton,
        Notification,
    },
    directives: {
        clickOutside: vClickOutside.directive,
    },
    computed: {
        ...mapGetters(['user', 'config', 'isVisibleNotificationCenter']),
        isEmpty() {
            return (
                !this.user.data.relationships.readNotifications.data.length &&
                !this.user.data.relationships.unreadNotifications.data.length
            )
        },
        readNotifications() {
            return this.user.data.relationships.readNotifications.data
        },
        unreadNotifications() {
            return this.user.data.relationships.unreadNotifications.data
        },
    },
    methods: {
        clickOutside() {
            if (this.isVisibleNotificationCenter) this.$store.commit('CLOSE_NOTIFICATION_CENTER')
        },
    },
    created() {
        this.$store.dispatch('readAllNotifications')
    },
}
</script>

<style lang="scss" scoped>
.popup-leave-active {
    animation: popup-slide-in 0.15s ease reverse;
}

.popup-enter-active {
    animation: popup-slide-in 0.25s 0.1s ease both;
}

@keyframes popup-slide-in {
    0% {
        opacity: 0;
        transform: translateY(50px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
