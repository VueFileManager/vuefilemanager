<template>
	<div class="toolbar-tools">
		<slot></slot>
	</div>
</template>

<script>
	export default {
		name: 'ToolbarWrapper',
	}
</script>

<style scoped lang="scss">
	@import "@assets/vuefilemanager/_variables";
	@import "@assets/vuefilemanager/_mixins";

	.toolbar-tools {
		text-align: right;
		display: flex;;
	}
</style>
