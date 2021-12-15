<template>
	<div class="flex items-end justify-between h-28">
		<span
			class="w-2.5 block rounded-lg lg:mr-2 mr-1.5"
			:style="{height: height + '%', backgroundColor: color}"
			v-for="(height, i) in data"
			:key="i">
		</span>
	</div>
</template>
<script>
export default {
	name: 'BarChart',
	props: {
		data: {},
		color: {},
	}
}
</script>