<template>
	<div class="whitespace-nowrap overflow-x-auto">
		<router-link
			class="inline-block text-sm font-bold px-4 py-5 border-b-2 border-transparent border-bottom-theme"
			:class="{'text-theme': routeName === page.route, 'text-gray-600': routeName !== page.route}"
			v-for="(page, i) in pages"
			:to="{name: page.route}"
			:key="i"
			replace
		>
			{{ page.title }}
		</router-link>
	</div>
</template>

<script>
export default {
	name: 'CardNavigation',
	props: [
		'pages'
	],
	computed: {
		routeName() {
			return this.$route.name
		}
	},
}
</script>