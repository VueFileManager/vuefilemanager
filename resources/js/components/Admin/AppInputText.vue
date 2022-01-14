<template>
	<div :class="{'mb-7': !isLast}">

		<!--Label for input-->
		<label v-if="title" class="text-sm font-bold dark:text-gray-200 text-gray-700 mb-1.5 block">
			{{ title }}:
		</label>

		<!--Form element-->
		<slot></slot>

		<!--Input Description-->
		<span v-if="error" class="text-red-800 pt-2 text-xs">
			{{ error }}
		</span>

		<!--Input Description-->
		<small v-if="description" class="text-xs text-gray-500 pt-2 leading-4 block" v-html="description"></small>
	</div>
</template>

<script>
	export default {
		name: 'AppInputText',
		props: [
			'description',
			'isLast',
			'title',
			'error',
		]
	}
</script>