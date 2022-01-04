<template>
	<div
		class="py-3 px-4 cursor-pointer rounded-lg block select-none"
		:class="{'dark:bg-2x-dark-foreground bg-light-background': isSelected}"
	>
		<div class="flex items-center justify-between mb-1.5">
			<CheckBox :is-clicked="isSelected" />
			<b class="pl-4 text-lg text-left flex-1">
				{{ plan.data.attributes.name }}
			</b>
			<span class="ml-9 inline-block py-1 px-2 text-theme font-extrabold text-sm rounded-xl bg-theme-100 whitespace-nowrap">
				{{ plan.data.attributes.price }} / {{ $t(`interval.${plan.data.attributes.interval}`) }}
			</span>
		</div>
		<ul class="ml-9 mb-3">
			<li class="flex items-center mb-1.5" v-for="(value, key, i) in plan.data.attributes.features" :key="i">
				<CheckIcon size="12" class="svg-stroke-theme" />
				<small class="pl-1.5 text-xs text-gray-600 font-bold" v-if="value !== -1">
					{{ $t(key, {value: value}) }}
				</small>
				<small class="pl-1.5 text-xs text-gray-600 font-bold" v-if="value === -1">
					{{ $t(`${key}.unlimited`) }}
				</small>
			</li>
		</ul>
	</div>
</template>
<script>
import {CheckIcon} from 'vue-feather-icons'
import CheckBox from "../FilesView/CheckBox"

export default {
	name: 'PlanDetail',
	components: {
		CheckIcon,
		CheckBox,
	},
	props: [
		'isSelected',
		'plan',
	],
}
</script>
