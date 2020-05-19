<template>
	<div class="progress-bar">
		<span :style="{ width: progress + '%' }"></span>
	</div>
</template>

<script>
export default {
	name: 'ProgressBar',
	props: ['progress']
}
</script>

<style scoped lang="scss">
	@import '@assets/vue-file-manager/_variables';
	@import '@assets/vue-file-manager/_mixins';

	.progress-bar {
		width: 100%;
		height: 5px;
		background: $dark_background;
		margin-top: 5px;
		border-radius: 10px;

		span {
			background: $theme;
			display: block;
			height: 100%;
			border-radius: 10px;
			max-width: 100%;
		}
	}

	@media (prefers-color-scheme: dark) {

		.progress-bar {
			background: $dark_mode_foreground;
		}
	}

	@media only screen and (min-width: 680px) and (prefers-color-scheme: dark) {

		.progress-bar {
			background: $dark_mode_foreground;
		}
	}
</style>
