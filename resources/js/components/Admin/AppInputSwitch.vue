<template>
	<div :class="{'mb-7': !isLast}" class="flex justify-between w-full">

		<!--Label for input-->
		<div class="leading-5">
			<label class="text-sm font-bold dark:text-gray-400 text-gray-700 mb-1.5 block">
				{{ title }}:
			</label>

			<!--Input Description-->
			<span v-if="description" class="text-xs text-gray-500 leading-4 block" v-html="description"></span>

			<!--Input Description-->
			<span v-if="error" class="error-message">
				{{ error }}
			</span>
		</div>

		<!--Form element-->
		<div>
			<slot></slot>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'AppInputSwitch',
		props: [
			'description',
			'isLast',
			'title',
			'error',
		]
	}
</script>