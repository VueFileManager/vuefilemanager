<template>
	<transition name="popup">
		<div class="fixed popup z-20 top-[27px] bottom-[27px] left-20 w-[360px]">

			<!--Triangle-->
			<div class="z-20 absolute left-0 top-[64px] w-4 translate-x-[-15px] overflow-hidden inline-block" :class="{'!top-[102px]': config.subscriptionType === 'metered'}">
				<div class="h-12 -rotate-45 transform origin-top-right dark:bg-2x-dark-foreground bg-white bg-opacity-80 backdrop-blur-2xl"></div>
			</div>

			<div v-click-outside="clickOutside" class="dark:bg-2x-dark-foreground bg-white dark:bg-opacity-80 dark:backdrop-blur-2xl bg-opacity-80 backdrop-blur-2xl shadow-xl rounded-xl text-left p-3 overflow-y-auto max-h-full">

				<!--Title-->
				<b class="dark:text-gray-200 text-xl font-extrabold px-2.5 mb-2.5 block">
					{{ $t('Notification Center') }}
				</b>

				<div class="px-2.5">
					<MobileActionButton v-if="readNotifications.length || unreadNotifications.length" @click.native="deleteAllNotifications" icon="check-square" class="mb-2 dark:!bg-4x-dark-foreground">
						{{ $t('Clear all') }}
					</MobileActionButton>

					<p v-if="!readNotifications.length && !unreadNotifications.length" class="text-sm mt-8">
						{{ $t("There aren't any notifications.") }}
					</p>
				</div>

				<b v-if="unreadNotifications.length" class="dark-text-theme mt-1.5 block px-2.5 mb-2.5 text-xs text-gray-400">
					{{ $t('Unread') }}
				</b>

				<Notification :notification="notification" v-for="notification in unreadNotifications" :key="notification.id" />

				<b v-if="readNotifications.length" class="dark-text-theme mt-2.5 block px-2.5 mb-2.5 text-xs text-gray-400">
					{{ $t('Read') }}
				</b>

				<Notification :notification="notification" v-for="notification in readNotifications" :key="notification.id" />
			</div>
		</div>
	</transition>
</template>

<script>
import MobileActionButton from "../FilesView/MobileActionButton"
import Notification from "./Notification"
import vClickOutside from 'v-click-outside'
import {mapGetters} from "vuex";

export default {
	name: 'NotificationCenter',
	components: {
		MobileActionButton,
		Notification
	},
	directives: {
		clickOutside: vClickOutside.directive
	},
	computed: {
		...mapGetters([
			'user', 'config', 'isVisibleNotificationCenter',
		]),
		readNotifications() {
			return this.user.data.relationships.readNotifications.data
		},
		unreadNotifications() {
			return this.user.data.relationships.unreadNotifications.data
		}
	},
	methods: {
		clickOutside() {
			if (this.isVisibleNotificationCenter)
				this.$store.commit('CLOSE_NOTIFICATION_CENTER')
		},
		deleteAllNotifications() {
			axios.delete('/api/user/notifications')
				.then(() => {
					this.$store.commit('FLUSH_NOTIFICATIONS')
				})
		}
	},
	created() {
		axios.post('/api/user/notifications/read')
			.then(() => {
				this.$store.commit('UPDATE_NOTIFICATION_COUNT', 0)
			})
	}
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
