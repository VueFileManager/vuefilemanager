<template>
    <PageTab>
        <PageTabGroup>
            <DatatableWrapper :paginator="true" :columns="columns" :data="invoices" class="table">
                <template scope="{ row }">
                    <tr>
                        <td>
                            <span class="cell-item">
                                ${{ row.attributes.total }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.attributes.plan }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.attributes.created_at_formatted }}
                            </span>
                        </td>
                        <td>
                            <div class="action-icons">
                                <download-cloud-icon size="15" class="icon"></download-cloud-icon>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {DownloadCloudIcon} from "vue-feather-icons";
    import axios from 'axios'

    export default {
        name: 'GatewayTransactions',
        components: {
            DownloadCloudIcon,
            DatatableWrapper,
            PageTabGroup,
            PageTab,
        },
        data() {
            return {
                isLoading: false,
                invoices: [
                    {
                        id: '1',
                        type: 'invoices',
                        attributes: {
                            total: 9.99,
                            plan: 'Starter Plan',
                            created_at: '30. April. 2020',
                            created_at_formatted: '30. April. 2020',
                            download: 'https://vuefilemanager.com/',
                        },
                    },
                    {
                        id: '2',
                        type: 'invoices',
                        attributes: {
                            total: 9.99,
                            plan: 'Starter Plan',
                            created_at: '30. April. 2020',
                            created_at_formatted: '30. April. 2020',
                            download: 'https://vuefilemanager.com/',
                        },
                    },
                    {
                        id: '3',
                        type: 'invoices',
                        attributes: {
                            total: 49.99,
                            plan: 'Business Plan',
                            created_at: '31. April. 2020',
                            created_at_formatted: '31. April. 2020',
                            download: 'https://vuefilemanager.com/',
                        },
                    },
                    {
                        id: '4',
                        type: 'invoices',
                        attributes: {
                            total: 29.99,
                            plan: 'Professional Plan',
                            created_at: '31. April. 2020',
                            created_at_formatted: '31. April. 2020',
                            download: 'https://vuefilemanager.com/',
                        },
                    },
                    {
                        id: '5',
                        type: 'invoices',
                        attributes: {
                            total: 9.99,
                            plan: 'Starter Plan',
                            created_at: '30. April. 2020',
                            created_at_formatted: '30. April. 2020',
                            download: 'https://vuefilemanager.com/',
                        },
                    },
                    {
                        id: '6',
                        type: 'invoices',
                        attributes: {
                            total: 9.99,
                            plan: 'Starter Plan',
                            created_at: '30. April. 2020',
                            created_at_formatted: '30. April. 2020',
                            download: 'https://vuefilemanager.com/',
                        },
                    },
                    {
                        id: '7',
                        type: 'invoices',
                        attributes: {
                            total: 49.99,
                            plan: 'Business Plan',
                            created_at: '31. April. 2020',
                            created_at_formatted: '31. April. 2020',
                            download: 'https://vuefilemanager.com/',
                        },
                    },
                    {
                        id: '8',
                        type: 'invoices',
                        attributes: {
                            total: 29.99,
                            plan: 'Professional Plan',
                            created_at: '31. April. 2020',
                            created_at_formatted: '31. April. 2020',
                            download: 'https://vuefilemanager.com/',
                        },
                    },
                ],
                columns: [
                    {
                        label: 'Total',
                        field: 'attributes.total',
                        sortable: true
                    },
                    {
                        label: 'Plan',
                        field: 'attributes.plan',
                        sortable: true
                    },
                    {
                        label: 'Payed',
                        field: 'attributes.created_at',
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
            /*axios.get('/api/users/' + this.$route.params.id + '/storage')
                .then(response => {
                    this.storage = response.data.data
                    this.isLoading = false
                })*/
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
