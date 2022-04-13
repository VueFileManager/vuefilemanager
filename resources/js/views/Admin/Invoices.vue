<template>
    <div>
        <!--Datatable-->
        <DatatableWrapper
            v-if="!config.isEmptyTransactions"
            class="card overflow-x-auto shadow-card"
            api="/api/admin/transactions"
            :paginator="true"
            :columns="columns"
        >
            <template slot-scope="{ row }">
                <!--Transaction rows-->
                <MeteredTransactionRow
                    v-if="config.subscriptionType === 'metered'"
                    :row="row"
                    :user="true"
                    @showDetail="showTransactionDetail"
                />

                <FixedTransactionRow v-if="config.subscriptionType === 'fixed'" :row="row" :user="true" />

                <!--Transaction detail-->
                <MeteredTransactionDetailRow
                    v-if="row.data.attributes.metadata && showedTransactionDetailById === row.data.id"
                    :row="row"
                />
            </template>
        </DatatableWrapper>

        <!--Empty State-->
        <div v-if="config.isEmptyTransactions" class="flex items-center justify-center fixed top-0 bottom-0 left-0 right-0">
            <div class="text-center">
                <img
                    class="mb-6 inline-block w-28"
                    src="https://twemoji.maxcdn.com/v/13.1.0/svg/1f9ee.svg"
                    alt="transaction"
                />
                <h1 class="mb-1 text-2xl font-bold">
                    {{ $t('there_is_nothing') }}
                </h1>
                <p class="text-sm text-gray-600">
                    {{ $t('transaction_will_be_here') }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import FixedTransactionRow from '../../components/Subscription/FixedTransactionRow'
import MeteredTransactionDetailRow from '../../components/Subscription/MeteredTransactionDetailRow'
import MeteredTransactionRow from '../../components/Subscription/MeteredTransactionRow'
import MemberAvatar from '../../components/UI/Others/MemberAvatar'
import DatatableWrapper from '../../components/UI/Table/DatatableWrapper'
import ColorLabel from '../../components/UI/Labels/ColorLabel'
import { mapGetters } from 'vuex'

export default {
    name: 'Invoices',
    components: {
        MeteredTransactionDetailRow,
        MeteredTransactionRow,
        FixedTransactionRow,
        DatatableWrapper,
        MemberAvatar,
        ColorLabel,
    },
    computed: {
        ...mapGetters(['config']),
        columns() {
            if (config.subscriptionType === 'fixed') {
                return this.$store.getters.transactionColumns.filter((column) => !['type'].includes(column.field))
            }

            return this.$store.getters.transactionColumns
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
