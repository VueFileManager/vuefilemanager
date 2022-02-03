<template>
	<div>
		<b v-if="title" class="py-0.5 px-4 mt-2 block text-xs text-gray-500 dark-text-theme">
			{{ title }}
		</b>
		<ul class="option-group py-1">
			<slot></slot>
		</ul>
	</div>
</template>

<script>
export default {
    name: 'OptionGroup',
	props: [
		'title'
	]
}
</script>

<style scoped lang="scss">
	.option-group {
		&:first-child {
			padding-top: 0 !important;
		}

		&:last-child {
			padding-bottom: 0 !important;
		}
	}
</style>
