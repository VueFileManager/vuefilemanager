<template>
	<div id="card-navigation" style="height: 62px" class="mb-7">
		<div :class="{'fixed top-0 left-0 right-0 px-6 rounded-none backdrop-filter backdrop-blur-lg dark:bg-dark-foreground bg-white bg-opacity-50 z-10': fixedNav}">
			<div class="whitespace-nowrap overflow-x-auto">
				<router-link
					class="inline-block text-sm font-bold px-4 py-5 border-b-2 border-transparent border-bottom-theme"
					:class="{'text-theme': routeName === page.route, 'dark:text-gray-100 text-gray-600': routeName !== page.route}"
					v-for="(page, i) in pages"
					:to="{name: page.route}"
					:key="i"
					replace
				>
					{{ page.title }}
				</router-link>
			</div>
		</div>
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
	data() {
		return {
			fixedNav: false,
		}
	},
	created() {
		// Handle fixed mobile navigation
		window.addEventListener("scroll", () => {
			let card = document.getElementById('card-navigation')

			this.fixedNav = card.getBoundingClientRect().top < 0;
		});
	}
}
</script>