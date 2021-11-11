<template>
	<label class="py-3 px-4 cursor-pointer border-b border-light rounded-lg block select-none" :class="{'bg-light-background': isSelected}">
		<div class="flex items-center mb-1.5">
			<CheckBox :is-clicked="isSelected" />
			<b class="pl-4 text-lg">
				{{ plan.data.attributes.name }}
			</b>
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
		<span class="ml-9 inline-block py-1 px-2 text-theme font-extrabold text-sm rounded-xl bg-theme-100">
			{{ currency }} / {{ $t(`interval.${plan.data.attributes.interval}`) }}
		</span>
	</label>
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
	computed: {
		currency() {
			let formatter = new Intl.NumberFormat('en-US', {
				style: 'currency',
				currency: 'USD',
			});

			return formatter.format(this.plan.data.attributes.amount);
		}
	}
}
</script>
