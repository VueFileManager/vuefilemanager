<template>
    <PageTab>
        <PageTabGroup>
            <DatatableWrapper
                @init="isLoading = false"
                :api="`/api/oasis/clients/${$route.params.id}/invoices`"
                :paginator="false"
                :columns="columns"
                class="table"
            >

                <!--Table data content-->
                <template slot-scope="{ row }">
                    <tr>
                        <td>
                            <a target="_blank" class="cell-item">
                                {{ row.invoiceNumber }}
                            </a>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.total }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.created_at }}
                            </span>
                        </td>
                        <td>
                            <div class="action-icons">
                                <a target="_blank">
                                    <external-link-icon size="15" class="icon" />
                                </a>
                            </div>
                        </td>
                    </tr>
                </template>

                <!--Empty page-->
                <template v-slot:empty-page>
                    <InfoBox class="form-fixed-width">
                        <p>Client doesn't have any invoices yet.</p>
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
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';
    @import '@assets/vuefilemanager/_forms';

    .block-form {
        max-width: 100%;
    }
</style>
