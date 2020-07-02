<template>
    <PageTab :is-loading="isLoading">
        <PageTabGroup v-if="invoices && invoices.length > 0">
            <FormLabel>Invoices</FormLabel>
            <DatatableWrapper :paginator="true" :columns="columns" :data="invoices" class="table">
                <template scope="{ row }">
                    <tr>
                        <td>
                            <a :href="'/invoice/' + row.data.attributes.customer + '/' + row.data.id" target="_blank" class="cell-item">
                                {{ row.data.attributes.order }}
                            </a>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.data.attributes.total }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.data.attributes.bag.description }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.data.attributes.created_at_formatted }}
                            </span>
                        </td>
                        <td>
                            <div class="action-icons">
                                <a :href="'/invoice/' + row.data.attributes.customer + '/' + row.data.id" target="_blank">
                                    <external-link-icon size="15" class="icon"></external-link-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
        </PageTabGroup>
        <PageTabGroup v-else>
            You don't have any invoices yet.
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {ExternalLinkIcon} from "vue-feather-icons";
    import axios from 'axios'

    export default {
        name: 'UserInvoices',
        components: {
            DatatableWrapper,
            ExternalLinkIcon,
            PageTabGroup,
            FormLabel,
            PageTab,
        },
        data() {
            return {
                isLoading: true,
                invoices: undefined,
                columns: [
                    {
                        label: 'Invoice Number',
                        field: 'data.attributes.total',
                        sortable: true
                    },
                    {
                        label: 'Total',
                        field: 'data.attributes.total',
                        sortable: true
                    },
                    {
                        label: 'Plan',
                        field: 'data.attributes.plan',
                        sortable: true
                    },
                    {
                        label: 'Payed',
                        field: 'data.attributes.created_at',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.action'),
                        field: 'data.action',
                        sortable: false
                    },
                ],
            }
        },
        created() {
            axios.get('/api/user/invoices')
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


    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
