<template>
	<div v-if="isVisible" class="popover-item">
		<slot></slot>
	</div>
</template>

<script>
	import {events} from '@/bus'

	export default {
		name: 'PopoverItem',
		props: [
			'name'
		],
		data() {
			return {
				isVisible: false,
			}
		},
		mounted() {
			events.$on('popover:open', name => {
				if (this.name === name) this.isVisible = !this.isVisible
			})

			events.$on('unClick', () => this.isVisible = false)
		}
	}
</script>

<style scoped lang="scss">
	@import "@assets/vuefilemanager/_variables";
	@import "@assets/vuefilemanager/_mixins";

	.popover-item {
		min-width: 250px;
		position: absolute;
		z-index: 9;
		box-shadow: $shadow;
		background: white;
		border-radius: 8px;
		overflow: hidden;
		right: 0;
		top: 50px;
	}

	@media (prefers-color-scheme: dark) {
		.popover-item {
			background: $dark_mode_foreground;
		}
	}
</style>
