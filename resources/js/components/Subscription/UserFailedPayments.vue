<template>
    <div
        v-if="user.data.relationships.failedPayments && user.data.relationships.failedPayments.data.length > 0"
        class="card shadow-card"
    >
        <FormLabel icon="frown">
            {{ $t('failed_payments') }}
        </FormLabel>

        <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
            -{{ user.data.meta.totalDebt.formatted }}
        </b>

        <b class="mb-3 mb-5 block text-sm text-gray-400">
            {{
                $t(
                    "unable_to_charge"
                )
            }}
        </b>

        <!--Failed Payments-->
        <div
            v-for="payment in user.data.relationships.failedPayments.data"
            :key="payment.data.id"
            class="flex items-center justify-between border-b border-dashed border-light py-2 dark:border-opacity-5"
        >
            <div class="w-2/4 leading-none">
                <b class="text-sm font-bold leading-none">
                    {{ payment.data.attributes.note }}
                </b>
            </div>
            <div class="w-1/4 text-left">
                <span class="text-gray-560 text-sm font-bold capitalize">
                    {{ $t(payment.data.attributes.source) }}
                </span>
            </div>
            <div class="w-1/4 text-right">
                <span class="text-sm font-bold">
                    {{ payment.data.attributes.created_at }}
                </span>
            </div>
            <div class="w-1/4 text-right">
                <span class="text-red text-sm font-bold">
                    {{ payment.data.attributes.amount }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import FormLabel from '../UI/Labels/FormLabel'
import InfoBox from '../UI/Others/InfoBox'
import { mapGetters } from 'vuex'

export default {
    name: 'UserFailedPayments',
    components: {
        FormLabel,
        InfoBox,
    },
    computed: {
        ...mapGetters(['user']),
    },
}
</script>
