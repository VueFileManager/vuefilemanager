<template>
	<div class="mb-2 text-right">
		<label
			:class="{ 'text-gray-400': !isSelectedYearlyPlans }"
			class="cursor-pointer text-xs font-bold"
		>
			{{ $t('billed_annually') }}
		</label>
		<div class="relative inline-block w-12 select-none align-middle">
			<SwitchInput
				class="scale-75 transform"
				v-model="isSelectedYearlyPlans"
				:state="isSelectedYearlyPlans"
			/>
		</div>
	</div>
</template>
<script>
import SwitchInput from '../Inputs/SwitchInput'

export default {
	name: 'PlanPeriodSwitcher',
	components: {
		SwitchInput
	},
	watch: {
		'isSelectedYearlyPlans': function () {
			this.$emit('input', this.isSelectedYearlyPlans)
		}
	},
	data() {
		return {
			isSelectedYearlyPlans: false
		}
	}
}
</script>