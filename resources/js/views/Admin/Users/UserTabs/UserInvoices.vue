<template>
    <PageTab :is-loading="isLoading">
        <PageTabGroup>
            <DatatableWrapper
                @init="isLoading = false"
                :api="'/api/users/' + this.$route.params.id + '/invoices'"
                :paginator="false"
                :columns="columns"
                class="table"
            >

                <!--Table data content-->
                <template slot-scope="{ row }">
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

                <!--Empty page-->
                <template v-slot:empty-page>
                    <InfoBox class="form-fixed-width">
                        <p>{{ $t('admin_page_user.invoices.empty') }}</p>
                    </InfoBox>
                </template>
            </DatatableWrapper>
        </PageTabGroup>
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
                columns: [
                    {
                        label: this.$t('admin_page_invoices.table.number'),
                        field: 'data.attributes.order',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_invoices.table.total'),
                        field: 'data.attributes.bag.amount',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_invoices.table.plan'),
                        field: 'data.attributes.bag.amount',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_invoices.table.payed'),
                        field: 'data.attributes.created_at',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_user.table.action'),
                        sortable: false
                    },
                ],
            }
        },
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
