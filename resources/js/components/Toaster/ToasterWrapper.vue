<template>
	<transition v-if="isActive" appear name="fade">
		<div class="relative">
			<div @click="isActive = false" class="absolute z-[20] right-0 top-0 cursor-pointer p-2">
				<x-icon size="16" class="vue-feather text-black opacity-10 dark:text-white" />
			</div>

			<slot />

			<!--Progress bar-->
			<div class="absolute bottom-0 left-0 right-0 z-20">
				<span class="bar-animation block h-1 w-0" :class="barColor"></span>
			</div>
		</div>
	</transition>
</template>

<script>
	import { XIcon } from 'vue-feather-icons'

	export default {
		name: 'Toast',
		props: [
			'barColor'
		],
		components: {
			XIcon,
		},
		data() {
			return {
				isActive: 1,
			}
		},
		created() {
			setTimeout(() => (this.isActive = 0), 6000)
		},
	}
</script>

<style lang="scss" scoped>
.bar-animation {
	animation: progressbar 6s linear both;
}

@keyframes progressbar {
	0% {
		width: 0;
	}
	100% {
		width: 100%;
	}
}

.fade-enter-active,
.fade-leave-active {
	transition: 0.3s ease;
}

.fade-enter,
.fade-leave-to {
	opacity: 0;
	transform: translateX(100%);
}
</style>