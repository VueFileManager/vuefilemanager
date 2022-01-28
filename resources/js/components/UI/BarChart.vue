<template>
	<div class="lg:flex lg:items-end lg:justify-between grid grid-flow-col lg:gap-0 sm:gap-2 gap-1 items-end sm:h-28 h-20">
		<!--Data bar-->
		<Bar
			v-for="(item, i) in data"
			:key="i"
			:bar="item"
		/>

		<!--Ghost bar-->
		<span
			v-if="ghostLength > 0"
			class="2xl:w-3 lg:w-2 block lg:mr-2 relative cursor-pointer dark:bg-gray-800 bg-gray-100"
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