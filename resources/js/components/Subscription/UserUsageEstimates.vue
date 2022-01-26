<template>
	<div class="card shadow-card">
		<FormLabel icon="bar-chart">
			{{ $t('Usage Estimates') }}
		</FormLabel>

		<b class="sm:text-3xl text-2xl font-extrabold -mt-3 block mb-0.5">
			{{ user.data.meta.usages.costEstimate }}
		</b>

		<b class="mb-3 block text-sm text-gray-400 mb-5">
			{{ user.data.relationships.subscription.data.attributes.updated_at }} {{ $t('till now') }}
		</b>

		<div class="flex items-center justify-between py-2 border-b dark:border-opacity-5 border-light border-dashed" v-for="(usage, i) in user.data.meta.usages.featureEstimates" :key="i">
			<div class="w-2/4 leading-none">
				<b class="text-sm font-bold leading-none">
					{{ $t(usage.feature) }}
				</b>
				<small class="text-xs text-gray-500 pt-2 leading-none sm:block hidden">
					{{ $t(`feature_usage_desc_${usage.feature}`) }}
				</small>
			</div>
			<div class="text-left w-1/4">
				<span class="text-sm font-bold text-gray-560">
					{{ usage.usage }}
				</span>
			</div>
			<div class="text-right w-1/4">
				<span class="text-sm font-bold text-theme">
					{{ usage.cost }}
				</span>
			</div>
		</div>

		<small class="mt-6 block font-bold">
			{{ $t('Records are updated on daily bases.') }}
		</small>
	</div>
</template>
<script>
import FormLabel from "../Others/Forms/FormLabel"
import {mapGetters} from "vuex";

export default {
	name: 'UserUsageEstimates',
	components: {
		FormLabel
	},
	computed: {
		...mapGetters([
			'user',
		])
	},
}
</script>