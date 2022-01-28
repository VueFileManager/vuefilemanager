<template>
	<div v-if="$store.getters.isLimitedUser" class="bg-red-500 text-center py-1">
		<router-link :to="{name: 'Billing'}" class="text-white font-bold text-xs">
			{{ $t('Your functionality is restricted. Please review your billing settings.') }}
		</router-link>
	</div>
</template>
<script>
	export default {
		name: 'RestrictionWarningBar',
	}
</script>