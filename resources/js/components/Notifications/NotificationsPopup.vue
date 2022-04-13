<template>
    <PopupWrapper name="notifications-mobile">
        <!--Title-->
        <PopupHeader :title="$t('notifications')" icon="bell" />

        <!--Content-->
        <PopupContent v-if="readNotifications && unreadNotifications">
			<MobileActionButton
				v-if="readNotifications.length || unreadNotifications.length"
				@click.native="$store.dispatch('deleteAllNotifications')"
				icon="check-square"
				class="mb-2 dark:!bg-4x-dark-foreground"
			>
				{{ $t('clear_all') }}
			</MobileActionButton>

			<p v-if="!readNotifications.length && !unreadNotifications.length" class="text-sm text-gray-500">
				{{ $t("not_any_notifications") }}
			</p>

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

            <b v-if="readNotifications.length" class="dark-text-theme mt-2.5 mb-2.5 block px-2.5 text-xs text-gray-400">
                {{ $t('read') }}
            </b>

            <Notification
                :notification="notification"
                v-for="notification in readNotifications"
                :key="notification.id"
            />
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary">
                {{ $t('close') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import MobileActionButton from '../UI/Buttons/MobileActionButton'
import Notification from './Components/Notification'
import ButtonBase from '../UI/Buttons/ButtonBase'
import PopupWrapper from '../Popups/Components/PopupWrapper'
import PopupActions from '../Popups/Components/PopupActions'
import PopupContent from '../Popups/Components/PopupContent'
import PopupHeader from '../Popups/Components/PopupHeader'
import vClickOutside from 'v-click-outside'
import { mapGetters } from 'vuex'

export default {
    name: 'NotificationsPopup',
    components: {
        MobileActionButton,
        Notification,
        PopupWrapper,
        PopupActions,
        PopupContent,
        PopupHeader,
        ButtonBase,
    },
    directives: {
        clickOutside: vClickOutside.directive,
    },
    computed: {
        ...mapGetters(['user', 'config']),
        readNotifications() {
            return this.user?.data.relationships.readNotifications.data
        },
        unreadNotifications() {
            return this.user?.data.relationships.unreadNotifications.data
        },
    },
    methods: {
        clickOutside() {
            if (this.isVisibleNotificationCenter) this.$store.commit('CLOSE_NOTIFICATION_CENTER')
        },
    },
}
</script>
