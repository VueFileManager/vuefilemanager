<template>
    <div v-if="$store.getters.userLimitationReason" class="bg-gradient-to-r from-red-600 to-red-500 py-2.5 px-1 text-center leading-none">
        <router-link :to="{ name: 'Billing' }" class="text-xs font-bold text-white">
            {{ $t('restricted_account_warning') + ' ' + $store.getters.userLimitationReason }}
        </router-link>
    </div>
</template>
<script>
export default {
    name: 'RestrictionWarningBar',
}
</script>
