<template>
    <div class="card shadow-card">
        <FormLabel icon="file-text">
            {{ $t('transactions') }}
        </FormLabel>

        <DatatableWrapper
            class="overflow-x-auto"
            api="/api/subscriptions/transactions"
            :paginator="true"
            :columns="columns"
        >
            <template slot-scope="{ row }">
                <FixedTransactionRow :row="row" />
            </template>

            <!--Empty page-->
            <template v-slot:empty-page>
                <InfoBox style="margin-bottom: 0">
                    <p>{{ $t('user_invoices.empty') }}</p>
                </InfoBox>
            </template>
        </DatatableWrapper>
    </div>
</template>
<script>
import InfoBox from '../UI/Others/InfoBox.vue'
import DatatableWrapper from '../UI/Table/DatatableWrapper.vue'
import FixedTransactionRow from './FixedTransactionRow.vue'
import FormLabel from '../UI/Labels/FormLabel.vue'
import ColorLabel from '../UI/Labels/ColorLabel.vue'

export default {
    name: 'UserTransactionsForFixedBilling',
    components: {
        FixedTransactionRow,
        DatatableWrapper,
        ColorLabel,
        FormLabel,
        InfoBox,
    },
    computed: {
        columns() {
            return this.$store.getters.transactionColumns.filter(
                (column) => !['type', 'user_id'].includes(column.field)
            )
        },
    },
}
</script>
