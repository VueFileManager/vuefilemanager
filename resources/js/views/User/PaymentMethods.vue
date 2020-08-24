<template>
    <PageTab :is-loading="isLoading">
        <PageTabGroup>

            <!--Page title-->
            <FormLabel>{{ $t('user_payments.title') }}</FormLabel>

            <!--Add payment method button-->
            <div class="page-actions" v-if="PaymentMethods && PaymentMethods.length > 0">
                <router-link :to="{name: 'CreatePaymentMethod'}">
                    <MobileActionButton icon="credit-card">
                        {{ $t('user_payments.add_card') }}
                    </MobileActionButton>
                </router-link>
            </div>

            <!--Payment methods table-->
            <DatatableWrapper v-if="PaymentMethods" :table-data="{data: PaymentMethods}" :paginator="false" :columns="columns" class="table">

                <!--Table data content-->
                <template slot-scope="{ row }">
                    <tr :class="{'is-deleting': row.data.attributes.card_id === deletingID}">
                        <td style="width: 300px">
                            <span class="cell-item">
                                <div class="credit-card">
                                    <img class="credit-card-icon" :src="$getCreditCardBrand(row.data.attributes.brand)"
                                         :alt="row.data.attributes.brand">
                                    <div class="credit-card-numbers">
                                        •••• {{ row.data.attributes.last4 }}
                                    </div>
                                    <ColorLabel v-if="row.data.id === defaultPaymentCard.data.id" color="purple">{{ $t('global.default') }}</ColorLabel>
                                </div>
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.data.attributes.exp_month }} / {{ row.data.attributes.exp_year }}
                            </span>
                        </td>
                        <td>
                            <div class="action-icons">
                                <label class="icon-wrapper" :title="$t('user_payments.set_as_default')">
                                    <credit-card-icon size="15" class="icon icon-card" @click="setDefaultCard(row.data.attributes)"  v-if="row.data.id !== defaultPaymentCard.data.id"></credit-card-icon>
                                </label>
                                <label class="icon-wrapper" :title="$t('user_payments.delete_card')">
                                    <trash2-icon size="15" class="icon icon-trash" @click="deleteCard(row.data.attributes)"></trash2-icon>
                                </label>
                            </div>
                        </td>
                    </tr>
                </template>

                <!--Empty page-->
                <template v-slot:empty-page>
                    <InfoBox>
                        <p>{{ $t('user_payments.empty') }} <router-link v-if="user.data.attributes.stripe_customer" :to="{name: 'CreatePaymentMethod'}">Add new payment method.</router-link> </p>
                    </InfoBox>
                </template>
            </DatatableWrapper>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import {CreditCardIcon, Trash2Icon} from "vue-feather-icons"
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import PageTab from '@/components/Others/Layout/PageTab'
    import ColorLabel from '@/components/Others/ColorLabel'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import { mapGetters } from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'UserPaymentMethods',
        components: {
            MobileActionButton,
            DatatableWrapper,
            CreditCardIcon,
            PageTabGroup,
            Trash2Icon,
            ColorLabel,
            FormLabel,
            InfoBox,
            PageTab,
        },
        computed: {
            ...mapGetters(['user']),
        },
        data() {
            return {
                defaultPaymentCard: undefined,
                PaymentMethods: undefined,
                deletingID: undefined,
                columns: [
                    {
                        label: this.$t('rows.card.number'),
                        field: 'data.attributes.total',
                        sortable: false
                    },
                    {
                        label: this.$t('rows.card.expiration'),
                        field: 'data.attributes.total',
                        sortable: false
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
            setDefaultCard(card) {
                events.$emit('confirm:open', {
                    title: this.$t('popup_set_card.title'),
                    message: this.$t('popup_set_card.message'),
                    buttonColor: 'theme-solid',
                    action: {
                        id: card.card_id,
                        operation: 'set-as-default-credit-card'
                    }
                })
            },
            deleteCard(card) {
                events.$emit('confirm:open', {
                    title: this.$t('popup_delete_card.title'),
                    message: this.$t('popup_delete_card.message'),
                    action: {
                        id: card.card_id,
                        operation: 'delete-credit-card'
                    }
                })
            },
            fetchPaymentMethods() {
                axios.get('/api/user/payments')
                    .then(response => {

                        if (response.status == 204) {
                            this.PaymentMethods = {}
                        }

                        if (response.status == 200) {
                            this.defaultPaymentCard = response.data.default

                            this.PaymentMethods = response.data.others.data
                            this.PaymentMethods.push(response.data.default)
                        }
                    }).finally(() => {
                        this.isLoading = false
                    }
                )
            }
        },
        created() {

            // Get payments card
            this.fetchPaymentMethods()

            // Delete credit card
            events.$on('action:confirmed', data => {

                if (data.operation === 'delete-credit-card') {

                    this.deletingID = data.id

                    axios.post('/api/user/payment-cards/' + data.id, {
                        _method: 'delete'
                    })
                        .then(() => {

                            // Get payments card
                            this.fetchPaymentMethods()

                            // Show toaster
                            events.$emit('toaster', {
                                type: 'success',
                                message: this.$t('toaster.card_deleted'),
                            })
                        })
                        .catch(() => {
                            events.$emit('alert:open', {
                                title: this.$t('popup_error.title'),
                                message: this.$t('popup_error.message'),
                            })
                        })
                }

                if (data.operation === 'set-as-default-credit-card') {

                    axios.post('/api/user/payment-cards/' + data.id, {
                        default: 1,
                        _method: 'patch'
                    })
                        .then(() => {

                            // Get payments card
                            this.fetchPaymentMethods()

                            // Show toaster
                            events.$emit('toaster', {
                                type: 'success',
                                message: this.$t('toaster.card_set'),
                            })
                        })
                        .catch(() => {
                            events.$emit('alert:open', {
                                title: this.$t('popup_error.title'),
                                message: this.$t('popup_error.message'),
                            })
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

    .page-actions {
        margin-top: 45px;
        margin-bottom: 10px;
    }


    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
