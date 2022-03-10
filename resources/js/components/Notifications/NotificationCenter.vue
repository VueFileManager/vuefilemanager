<template>
	<transition name="popup">
		<div class="fixed popup z-20 top-[27px] bottom-[27px] left-20 w-[360px]">

			<!--Triangle-->
			<div class="z-20 absolute left-0 top-[102px] w-4 translate-x-[-15px] overflow-hidden inline-block">
				<div class="h-12 -rotate-45 transform origin-top-right dark:bg-2x-dark-foreground bg-white bg-opacity-80 backdrop-blur-2xl"></div>
			</div>

			<div class="dark:bg-2x-dark-foreground bg-white dark:bg-opacity-80 dark:backdrop-blur-2xl bg-opacity-80 backdrop-blur-2xl shadow-xl rounded-xl text-left p-3 overflow-y-auto max-h-full">

				<!--Title-->
				<b class="dark:text-gray-200 text-xl font-extrabold px-2.5 mb-2.5 block">
					{{ $t('Notification Center') }}
				</b>

				<div class="px-2.5">
					<MobileActionButton icon="check-square" class="mb-2 dark:!bg-4x-dark-foreground">
						{{ $t('Clear all') }}
					</MobileActionButton>
				</div>

				<b class="dark-text-theme mt-1.5 block px-2.5 mb-2.5 text-xs text-gray-400">
					Today
				</b>

				<Notification :notification="notification" v-for="notification in todayNotifications" :key="notification.id" />

				<b class="dark-text-theme mt-2.5 block px-2.5 mb-2.5 text-xs text-gray-400">
					Later
				</b>

				<Notification :notification="notification" v-for="notification in laterNotifications" :key="notification.id" />
			</div>
		</div>
	</transition>
</template>

<script>
import Notification from "./Notification";
import MobileActionButton from "../FilesView/MobileActionButton";

export default {
	name: 'NotificationCenter',
	components: {
		MobileActionButton,
		Notification
	},
	data() {
		return {
			laterNotifications: [
				{
					id: 1,
					seen: 1,
					type: 'remote-upload-done',
					title: 'Remote Upload Finished',
					description: 'Your remote uploads has been done.',
					time: '09. Mar. 2022, 08:27',
					action: {
						id: '12-abc'
					}
				},
			],
			todayNotifications: [
				{
					id: 2,
					seen: 0,
					type: 'team-invitation',
					title: 'New Team Invitation',
					description: 'Jane Doe invite you to join into “Work Documents” Team Folder.',
					time: '09. Mar. 2022, 08:27',
					action: {
						id: '12-abc'
					}
				},
				{
					id: 3,
					seen: 0,
					type: 'file-request',
					title: 'File Request Filled',
					description: 'Your file request for “Videohive” folder was filled successfully.',
					time: '09. Mar. 2022, 07:12',
					action: {
						id: '12-abc'
					}
				},
			]
		}
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
