<template>
	<div class="flex items-end justify-between sm:h-28 h-20">
		<!--Data bar-->
		<Bar
			v-for="(item, i) in data"
			:key="i"
			:bar="item"
		/>

		<!--Ghost bar-->
		<span
			v-if="ghostLength > 0"
			class="2xl:w-3 md:w-2 w-1 block rounded-lg lg:mr-2 mr-1.5 dark:bg-gray-800 bg-gray-100"
			v-for="(ghost, i) in ghostLength"
			:style="{height: '7%'}"
			:key="i">
		</span>
	</div>
</template>
<script>
    import Bar from "./Bar";

	export default {
		name: 'BarChart',
		props: [
			'color',
			'data',
		],
		components: {
			Bar,
		},
		computed: {
			ghostLength() {
				return 45 - this.data.length
			}
		},
	}
</script>