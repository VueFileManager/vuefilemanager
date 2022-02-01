<template>
    <div v-if="$store.getters.isLimitedUser" class="bg-red-500 py-1 text-center">
        <router-link :to="{ name: 'Billing' }" class="text-xs font-bold text-white">
            {{ $t('Your functionality is restricted. Please review your billing settings.') }}
        </router-link>
    </div>
</template>
<script>
export default {
    name: 'RestrictionWarningBar',
}
</script>
