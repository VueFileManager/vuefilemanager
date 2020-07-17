<template>
    <PageTab :is-loading="isLoading" :class="{'form-fixed-width': ! isLoading && invoices.length === 0}">
        <PageTabGroup v-if="invoices && invoices.length > 0">
            <DatatableWrapper :paginator="true" :columns="columns" :data="invoices" class="table">
                <template scope="{ row }">
                    <tr>
                        <td>
                            <a :href="$getInvoiceLink(row.data.attributes.customer, row.data.id)" target="_blank" class="cell-item">
                                {{ row.data.attributes.order }}
                            </a>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.data.attributes.total }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-item" v-if="row.data.attributes.invoice_subscriptions[0].description">
                                {{ row.data.attributes.invoice_subscriptions[0].description }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.data.attributes.created_at_formatted }}
                            </span>
                        </td>
                        <td>
                            <div class="action-icons">
                                <a :href="$getInvoiceLink(row.data.attributes.customer, row.data.id)" target="_blank">
                                    <external-link-icon size="15" class="icon"></external-link-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
        </PageTabGroup>
        <InfoBox v-else>
            <p>{{ $t('admin_page_user.invoices.empty') }}</p>
        </InfoBox>
    </PageTab>
</template>

<script>
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import EmptyPageContent from '@/components/Others/EmptyPageContent'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {ExternalLinkIcon} from "vue-feather-icons";
    import axios from 'axios'

    export default {
        name: 'UserInvoices',
        components: {
            EmptyPageContent,
            DatatableWrapper,
            ExternalLinkIcon,
            PageTabGroup,
            InfoBox,
            PageTab,
        },
        data() {
            return {
                isLoading: true,
                invoices: undefined,
                columns: [
                    {
                        label: this.$t('admin_page_invoices.table.number'),
                        field: 'data.attributes.order',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_invoices.table.total'),
                        field: 'data.attributes.bag.amount',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_invoices.table.plan'),
                        field: 'data.attributes.bag.amount',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_invoices.table.payed'),
                        field: 'data.attributes.created_at',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.action'),
                        sortable: false
                    },
                ],
            }
        },
        created() {
            axios.get('/api/users/' + this.$route.params.id + '/invoices')
                .then(response => {
                    this.invoices = response.data.data
                    this.isLoading = false
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .block-form {
        max-width: 100%;
    }
</style>
