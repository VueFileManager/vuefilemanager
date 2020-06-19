<template>
    <PageTab :is-loading="isLoading">
        <PageTabGroup v-if="paymentCards && paymentCards.length > 0">
            <DatatableWrapper :paginator="true" :columns="columns" :data="paymentCards" class="table">
                <template scope="{ row }">
                    <tr :class="{'is-deleting': row.data.attributes.card_id === deletingID}">
                        <td style="width: 300px">
                            <span class="cell-item">
                                <div class="credit-card">
                                    <img class="credit-card-icon" :src="$getCreditCardBrand(row.data.attributes.brand)"
                                         :alt="row.data.attributes.brand">
                                    <div class="credit-card-numbers">
                                        •••• {{ row.data.attributes.last4 }}
                                    </div>
                                    <ColorLabel v-if="row.data.id === defaultPaymentCard.data.id" color="purple">Default</ColorLabel>
                                </div>
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                <ColorLabel :color="getCardStatusColor(row.data.attributes.status)">{{ getCardStatus(row.data.attributes.status) }}</ColorLabel>
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.data.attributes.exp_month }} / {{ row.data.attributes.exp_year }}
                            </span>
                        </td>
                        <td>
                            <div class="action-icons">
                                <credit-card-icon size="15" class="icon icon-card" title="Set as default card" @click="setDefaultCard(row.data.attributes)"  v-if="row.data.id !== defaultPaymentCard.data.id"></credit-card-icon>
                                <trash2-icon size="15" class="icon icon-trash" title="Delete card" @click="deleteCard(row.data.attributes)"></trash2-icon>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
        </PageTabGroup>
        <PageTabGroup v-else>
            You don't have any payment cards yet.
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import ColorLabel from '@/components/Others/ColorLabel'
    import {CreditCardIcon, Trash2Icon} from "vue-feather-icons"
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'UserPaymentCards',
        components: {
            DatatableWrapper,
            PageTabGroup,
            Trash2Icon,
            ColorLabel,
            CreditCardIcon,
            PageTab,
        },
        data() {
            return {
                defaultPaymentCard: undefined,
                paymentCards: undefined,
                deletingID: undefined,
                columns: [
                    {
                        label: 'Card Number',
                        field: 'data.attributes.total',
                        sortable: true
                    },
                    {
                        label: 'Status',
                        field: 'data.attributes.status',
                        sortable: true
                    },
                    {
                        label: 'Expiration Date',
                        field: 'data.attributes.total',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.action'),
                        field: 'data.action',
                        sortable: false
                    },
                ],
                isLoading: true,
            }
        },
        methods: {
            getCardStatusColor(status) {
                switch (status) {
                    case 'active':
                        return 'green'
                    break
                    case 'card_declined':
                        return 'yellow'
                    break
                    case 'expired':
                        return 'red'
                    break
                }
            },
            getCardStatus(status) {
                switch (status) {
                    case 'active':
                        return 'Active'
                    break
                    case 'card_declined':
                        return 'Rejected'
                    break
                    case 'expired':
                        return 'Expired'
                    break
                }
            },
            setDefaultCard(card) {
                events.$emit('confirm:open', {
                    title: 'Set as default card?',
                    message: 'Your card will be set as default and will be always charged for the next billings.',
                    buttonColor: 'theme-solid',
                    action: {
                        id: card.card_id,
                        operation: 'set-as-default-credit-card'
                    }
                })
            },
            deleteCard(card) {
                events.$emit('confirm:open', {
                    title: 'Are you sure?',
                    message: 'This event is irreversible and your payment card will be delete forever',
                    action: {
                        id: card.card_id,
                        operation: 'delete-credit-card'
                    }
                })
            },
            fetchPaymentCards() {
                axios.get('/api/user/payments')
                    .then(response => {

                        this.defaultPaymentCard = response.data.default

                        this.paymentCards = response.data.others.data
                        this.paymentCards.push(response.data.default)

                        this.isLoading = false
                    })
            }
        },
        created() {

            // Get payments card
            this.fetchPaymentCards()

            // Delete credit card
            events.$on('action:confirmed', data => {

                if (data.operation === 'delete-credit-card') {

                    this.deletingID = data.id

                    axios.delete('/api/user/payment-cards/' + data.id)
                        .then(() => {

                            // Get payments card
                            this.fetchPaymentCards()

                            // Show toaster
                            events.$emit('toaster', {
                                type: 'success',
                                message: 'Your card was successfully deleted.',
                            })
                        })
                        .catch(error => {
                            console.error(error);
                        })
                }

                if (data.operation === 'set-as-default-credit-card') {

                    axios.patch('/api/user/payment-cards/' + data.id, {
                        default: 1
                    })
                        .then(() => {

                            // Get payments card
                            this.fetchPaymentCards()

                            // Show toaster
                            events.$emit('toaster', {
                                type: 'success',
                                message: 'Your card was successfully set as default.',
                            })
                        })
                        .catch(error => {
                            console.error(error);
                        })
                }
            })
        },
        destroyed() {
            events.$off('action:confirmed')
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .is-deleting {
        opacity: 0.35;
    }

    .credit-card {
        display: flex;
        align-items: center;

        .credit-card-numbers {
            margin-right: 10px;
        }

        .credit-card-icon {
            max-height: 20px;
            margin-right: 8px;
        }
    }


    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
