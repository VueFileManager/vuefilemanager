<template>
	<div class="flex items-end justify-between sm:h-28 h-20">
		<!--Data bar-->
		<span
			class="2xl:w-3 md:w-2 w-1 block rounded-lg lg:mr-2 mr-1.5 bg-theme"
			v-for="(height, i) in data"
			:style="{height: height.amount + '%'}"
			:key="i">
		</span>

		<!--Ghost bar-->
		<div v-if="ghostLength > 0">
			<span
				class="2xl:w-3 md:w-2 w-1 block rounded-lg lg:mr-2 mr-1.5 bg-gray-100"
				v-for="(ghost, i) in ghostLength"
				:style="{height: '5%'}"
				:key="i">
			</span>
		</div>
	</div>
</template>
<script>
export default {
	name: 'BarChart',
	props: [
		'color',
		'data',
	],
	computed: {
		ghostLength() {
			return 45 - this.data.length
		}
	}
}
</script>