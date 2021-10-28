<template>
	<div v-if="isVisible" class="popover-item z-20" :class="side">
		<slot></slot>
	</div>
</template>

<script>
	import {events} from '/resources/js/bus'

	export default {
		name: 'PopoverItem',
		props: [
			'side',
			'name',
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

			events.$on('popover:close', name => {
				if (this.name === name) this.isVisible = false
			})

			// todo: events.$on('unClick', () => this.isVisible = false)
		}
	}
</script>

<style scoped lang="scss">
	@import "resources/sass/vuefilemanager/_variables";
	@import "resources/sass/vuefilemanager/_mixins";

	.popover-item {
		min-width: 250px;
		position: absolute;
		box-shadow: $shadow;
		background: white;
		border-radius: 8px;
		overflow: hidden;
		top: 50px;

		&.left {
			right: 0;
		}

		&.right {
			left: 0;
		}
	}

	.dark {
		.popover-item {
			background: $dark_mode_foreground;
		}
	}
</style>
