<template>
	<div class="mb-14">
		<!--Custom content-->
		<slot></slot>

		<!--Default application logo-->
		<div v-if="! $slots.default">

			<!--Image logo-->
			<img v-if="config.app_logo" class="md:w-32 w-28 mb-6 mx-auto" :src="$getImage(config.app_logo)" :alt="config.app_name">

			<!--Text logo if image isn't available-->
			<b v-if="! config.app_logo" class="font-bold text-xl mb-10 block">
				{{ config.app_name }}
			</b>
		</div>

		<h1 class="md:text-4xl text-3xl font-extrabold mb-0.5">
			{{ title }}
		</h1>

		<h2 class="md:text-2xl text-xl font-normal">
			{{ description }}
		</h2>
	</div>
</template>
<script>
	import {mapGetters} from "vuex";

	export default {
		name: 'Headline',
		props: [
			'description',
			'title',
		],
		computed: {
			...mapGetters([
				'config',
			])
		}
	}
</script>