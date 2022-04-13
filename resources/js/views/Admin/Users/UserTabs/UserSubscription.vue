<template>
    <PageTab :is-loading="isLoading">
        <UserMeteredSubscription
            v-if="subscription && config.subscriptionType === 'metered'"
            :subscription="subscription"
            :user="user"
        />

        <UserFixedSubscription
            v-if="subscription && config.subscriptionType === 'fixed'"
            :subscription="subscription"
            :user="user"
        />

        <!--Free Plan-->
        <div v-if="!subscription && config.subscriptionType === 'fixed'" class="card shadow-card">
            <FormLabel>
                {{ $t('subscription') }}
            </FormLabel>

            <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                {{ $t('free_plan') }}
            </b>

            <b class="block text-sm text-gray-400">
                {{ $t('free_plan_parameters', {storage: config.storageDefaultSpaceFormatted, members: config.teamsDefaultMembers}) }}
            </b>
        </div>

        <!--Transactions-->
        <div class="card shadow-card">
            <FormLabel icon="file-text">
                {{ $t('transactions') }}
            </FormLabel>

            <DatatableWrapper
                class="overflow-x-auto"
                @init="isLoading = false"
                :api="'/api/admin/users/' + this.$route.params.id + '/transactions'"
                :paginator="true"
                :columns="columns"
            >
                <template slot-scope="{ row }">
                    <!--Transaction rows-->
                    <MeteredTransactionRow
                        v-if="config.subscriptionType === 'metered'"
                        :row="row"
                        @showDetail="showTransactionDetail"
                    />
                    <FixedTransactionRow v-if="config.subscriptionType === 'fixed'" :row="row" />

                    <!--Transaction detail-->
                    <MeteredTransactionDetailRow
                        v-if="row.data.attributes.metadata && showedTransactionDetailById === row.data.id"
                        :row="row"
                    />
                </template>

                <!--Empty page-->
                <template v-slot:empty-page>
                    <InfoBox style="margin-bottom: 0">
                        <p>
                            {{ $t("user_dont_have_transactions") }}
                        </p>
                    </InfoBox>
                </template>
            </DatatableWrapper>
        </div>
    </PageTab>
</template>

<script>
import MeteredTransactionDetailRow from '../../../../components/Subscription/MeteredTransactionDetailRow'
import MeteredTransactionRow from '../../../../components/Subscription/MeteredTransactionRow'
import FixedTransactionRow from '../../../../components/Subscription/FixedTransactionRow'
import DatatableWrapper from '../../../../components/UI/Table/DatatableWrapper'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import PageTab from '../../../../components/Layout/PageTab'
import InfoBox from '../../../../components/UI/Others/InfoBox'
import UserMeteredSubscription from './UserMeteredSubscription'
import UserFixedSubscription from './UserFixedSubscription'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'UserSubscription',
    props: ['user'],
    components: {
        MeteredTransactionDetailRow,
        UserMeteredSubscription,
        MeteredTransactionRow,
        UserFixedSubscription,
        FixedTransactionRow,
        DatatableWrapper,
        FormLabel,
        InfoBox,
        PageTab,
    },
    computed: {
        ...mapGetters(['config']),
        columns() {
            let filter = {
                metered: ['user_id'],
                fixed: ['type', 'user_id'],
            }

            return this.$store.getters.transactionColumns.filter(
                (column) => !filter[config.subscriptionType].includes(column.field)
            )
        },
    },
    data() {
        return {
            showedTransactionDetailById: undefined,
            subscription: undefined,
            isLoading: true,
        }
    },
    methods: {
        showTransactionDetail(id) {
            if (this.showedTransactionDetailById === id) this.showedTransactionDetailById = undefined
            else this.showedTransactionDetailById = id
        },
    },
    created() {
        axios
            .get(`/api/subscriptions/admin/users/${this.$route.params.id}/subscription`)
            .then((response) => {
                this.subscription = response.data.data

                this.isLoading = false
            })
            .catch((error) => {
                if (error.response.status === 404) this.isLoading = false
            })
    },
}
</script>
