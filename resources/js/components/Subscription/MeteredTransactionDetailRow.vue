<template>
	<tr>
		<td colspan="10" class="rounded-lg overflow-hidden py-2">
			<div class="flex items-center justify-between py-2 border-b dark:border-opacity-5 border-light border-dashed" v-for="(usage, i) in row.data.attributes.metadata" :key="i">
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
		</td>
	</tr>
</template>
<script>
export default {
	name: 'MeteredTransactionDetailRow',
	props: [
		'row'
	]
}
</script>