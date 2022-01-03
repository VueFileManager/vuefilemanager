<template>
	<div class="flex items-center justify-between py-4 border-b dark:border-opacity-5 border-light border-dashed">
		<div>
			<img :src="$getPaymentLogo(driver)" :alt="driver" class="h-6">
			<small class="text-xs text-gray-500 pt-2 leading-4 block">
				{{ description }}
			</small>
		</div>
		<div v-if="$slots.default" class="bg-theme-200 inline-block px-3 py-1 rounded-lg">
			<slot></slot>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'PaymentMethod',
		props: [
			'description',
			'driver',
		]
	}
</script>
