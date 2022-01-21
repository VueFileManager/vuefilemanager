<template>
	<div
		class="2xl:w-3 md:w-2 w-1 block lg:mr-2 mr-1.5 relative cursor-pointer"
		:style="{height: bar.percentage + '%'}"
		@mouseover="isVisible = true"
		@mouseleave="isVisible = false"
	>
		<div
			v-if="isVisible"
			class="absolute transform -translate-y-full -translate-x-1/2 -top-4 ml-1.5 dark:bg-white bg-gray-800 rounded-lg shadow-lg py-2 px-3 z-10"
		>
			<b class="dark:text-gray-800 text-white text-xs whitespace-nowrap block mb-2">
				{{ bar.created_at }}
			</b>
			<div class="flex items-center pb-1">
				<span class="w-3 h-3 block bg-theme mr-2 rounded"></span>
				<b class="dark:text-gray-800 text-white text-xs whitespace-nowrap">
					{{ bar.amount }}
				</b>
			</div>
			<div class="w-5 overflow-hidden inline-block absolute -bottom-2.5 left-0 right-0 mx-auto">
				<div class="h-3 w-3 dark:bg-white bg-gray-800 -rotate-45 transform origin-top-left"></div>
			</div>
		</div>
		<span class="bg-theme w-full h-full block rounded-lg"></span>
	</div>
</template>
<script>
	export default {
		name: 'Bar',
		props: [
			'bar',
		],
		data() {
			return {
				isVisible: false,
			}
		},
	}
</script>