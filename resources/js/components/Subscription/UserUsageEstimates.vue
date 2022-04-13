<template>
    <div class="card shadow-card">
        <FormLabel icon="bar-chart">
            {{ $t('usage_estimates') }}
        </FormLabel>

        <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
            {{ user.data.meta.usages.costEstimate }}
        </b>

        <b class="mb-3 mb-5 block text-sm dark:text-gray-500 text-gray-400">
            {{ user.data.relationships.subscription.data.attributes.updated_at }}
            {{ $t('till_now') }}
        </b>

        <div
            class="flex items-center justify-between border-b border-dashed border-light py-2 dark:border-opacity-5"
            v-for="(usage, i) in user.data.meta.usages.featureEstimates"
            :key="i"
        >
            <div class="w-2/4 leading-none">
                <b class="text-sm font-bold leading-none">
                    {{ $t(usage.feature) }}
                </b>
                <small class="hidden pt-2 text-xs leading-none dark:text-gray-500 text-gray-500 sm:block">
                    {{ $t(`feature_usage_desc_${usage.feature}`) }}
                </small>
            </div>
            <div class="w-1/4 text-left">
                <span class="text-gray-560 text-sm font-bold">
                    {{ usage.usage }}
                </span>
            </div>
            <div class="w-1/4 text-right">
                <span class="text-theme text-sm font-bold">
                    {{ usage.cost }}
                </span>
            </div>
        </div>

        <small class="mt-6 block font-bold">
            {{ $t('records_updated_daily_bases') }}
        </small>
    </div>
</template>
<script>
import FormLabel from '../UI/Labels/FormLabel'
import { mapGetters } from 'vuex'

export default {
    name: 'UserUsageEstimates',
    components: {
        FormLabel,
    },
    computed: {
        ...mapGetters(['user']),
    },
}
</script>
