<template>
	<transition name="popup">
		<div v-if="remoteUploadQueue" class="fixed left-0 right-0 bottom-8 text-center select-none pointer-events-none z-10">
			<div class="relative inline-block rounded-lg overflow-hidden bg-theme shadow-lg px-3 py-2">
				<div class="flex items-center">
					<RefreshCwIcon size="14" class="vue-feather text-white animate-[spin_2s_linear_infinite] z-10 relative" />
					<span class="text-xs font-bold text-white z-10 relative ml-2 dark:text-black">
						{{ this.$t('remote_upload_progress', {processed: remoteUploadQueue.processed, total: remoteUploadQueue.total}) }}{{ remoteUploadQueue.failed > 0 ? ", " + this.$t('remote_upload_failed_count', {count: remoteUploadQueue.failed}) : '' }}
					</span>
				</div>
				<span class="absolute w-full h-full top-0 bottom-0 left-0 right-0 block bg-theme brightness-125 animate-[pulse_3s_ease-in-out_infinite] z-[5]"></span>
				<span class="absolute w-full h-full top-0 bottom-0 left-0 right-0 block bg-theme z-0"></span>
			</div>
		</div>
	</transition>
</template>
<script>
	import {RefreshCwIcon} from "vue-feather-icons";
	import {mapGetters} from "vuex";

	export default {
		name: 'RemoteUploadProgress',
		components: {
			RefreshCwIcon,
		},
		computed: {
			...mapGetters([
				'remoteUploadQueue'
			]),
		},
	}
</script>

<style lang="scss">
	.popup-leave-active {
		animation: popup-slide-in 0.15s ease reverse;
	}

	.popup-enter-active {
		animation: popup-slide-in 0.25s 0.1s ease both;
	}

	@keyframes popup-slide-in {
		0% {
			opacity: 0;
			transform: translateY(100px);
		}

		100% {
			opacity: 1;
			transform: translateY(0);
		}
	}
</style>