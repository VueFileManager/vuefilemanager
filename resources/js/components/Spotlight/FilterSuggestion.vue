<template>
	<div class="flex items-center py-2 cursor-pointer">
		<span class="text-sm dark:bg-4x-dark-foreground bg-light-background font-bold py-1 px-2 rounded-lg dark-text-theme">
			{{ keyword }} + {{ $t('space') }}
		</span>
		<p class="ml-3 text-sm font-semibold text-gray-500">
			{{ description }}
		</p>
	</div>
</template>
<script>
	export default {
		name: 'FilterSuggestion',
		props: [
			'keyword',
			'description',
		]
	}
</script>