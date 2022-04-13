<template>
    <div class="card shadow-card">
        <FormLabel icon="file-text">
            {{ $t('transactions') }}
        </FormLabel>

        <DatatableWrapper class="overflow-x-auto" api="/api/user/transactions" :paginator="true" :columns="columns">
            <template slot-scope="{ row }">
                <!--Transaction rows-->
                <MeteredTransactionRow :row="row" @showDetail="showTransactionDetail" />

                <!--Transaction detail-->
                <MeteredTransactionDetailRow
                    v-if="row.data.attributes.metadata && showedTransactionDetailById === row.data.id"
                    :row="row"
                />
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
import { EyeIcon, FileTextIcon } from 'vue-feather-icons'
import ColorLabel from '../UI/Labels/ColorLabel'
import DatatableWrapper from '../UI/Table/DatatableWrapper'
import FormLabel from '../UI/Labels/FormLabel'
import InfoBox from '../UI/Others/InfoBox'
import { mapGetters } from 'vuex'
import MeteredTransactionDetailRow from './MeteredTransactionDetailRow'
import MeteredTransactionRow from './MeteredTransactionRow'

export default {
    name: 'UserTransactionsForMeteredBilling',
    components: {
        MeteredTransactionDetailRow,
        MeteredTransactionRow,
        DatatableWrapper,
        ColorLabel,
        FormLabel,
        InfoBox,
        FileTextIcon,
        EyeIcon,
    },
    computed: {
        ...mapGetters(['user']),
        columns() {
            return this.$store.getters.transactionColumns.filter((column) => column.field !== 'user_id')
        },
    },
    data() {
        return {
            showedTransactionDetailById: undefined,
        }
    },
    methods: {
        showTransactionDetail(id) {
            if (this.showedTransactionDetailById === id) this.showedTransactionDetailById = undefined
            else this.showedTransactionDetailById = id
        },
    },
}
</script>
