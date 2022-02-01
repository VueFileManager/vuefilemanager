<template>
    <tr>
        <td colspan="10" class="overflow-hidden rounded-lg py-2">
            <div
                class="flex items-center justify-between border-b border-dashed border-light py-2 dark:border-opacity-5"
                v-for="(usage, i) in row.data.attributes.metadata"
                :key="i"
            >
                <div class="w-2/4 leading-none">
                    <b class="text-sm font-bold leading-none">
                        {{ $t(usage.feature) }}
                    </b>
                    <small class="hidden pt-2 text-xs leading-none text-gray-500 sm:block">
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
        </td>
    </tr>
</template>
<script>
export default {
    name: 'MeteredTransactionDetailRow',
    props: ['row'],
}
</script>
