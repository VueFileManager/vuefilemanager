<template>
	<div class="typed-avatar" :style="{width: size + 'px', height: size + 'px', background: color ? color : '#f4f5f6', borderRadius: borderRadius + 'px'}">
		<span class="letter" :style="{fontSize: fontSize + 'px'}">
			{{ letter }}
		</span>
	</div>
</template>

<script>
export default {
	name: "TypedAvatar",
	props: [
		'letter',
		'color',
		'size',
	],
	computed: {
		borderRadius() {
			return this.size >= 26 ? 10 : 6
		},
		fontSize() {
			return this.size >= 26 ? 14 : 12
		},
	}
}
</script>

<style scoped lang="scss">
	@import "resources/sass/vuefilemanager/_variables";
	@import "resources/sass/vuefilemanager/_mixins";

	.typed-avatar {
		user-select: none;
		display: flex;
		background: #9ad2bf;
		align-items: center;
		justify-content: center;
		border: 2px solid white;

		.letter {
			font-weight: 800;
			text-transform: uppercase;
		}
	}
</style>