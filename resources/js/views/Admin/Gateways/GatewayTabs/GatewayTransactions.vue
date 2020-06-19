<template>
    <PageTab>
        <PageTabGroup v-if="transactions.length > 0">
            <DatatableWrapper :paginator="true" :columns="columns" :data="transactions" class="table">
                <template scope="{ row }">
                    <tr>
                        <td>
                            <a :href="'/invoice/' + row.data.attributes.token" target="_blank" class="cell-item">
                                {{ row.data.attributes.order }}
                            </a>
                        </td>
                        <td>
                                <span class="cell-item">
                                    ${{ row.data.attributes.total }}
                                </span>
                        </td>
                        <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.bag[0].description }}
                                </span>
                        </td>
                        <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.created_at_formatted }}
                                </span>
                        </td>
                        <td>
                            <router-link :to="{name: 'UserInvoices', params: {id: row.relationships.user.data.id}}">
                                <DatatableCellImage
                                        image-size="small"
                                        :image="row.relationships.user.data.attributes.avatar"
                                        :title="row.relationships.user.data.attributes.name"
                                />
                            </router-link>
                        </td>
                        <td>
                            <div class="action-icons">
                                <a :href="'/invoice/' + row.data.attributes.token" target="_blank">
                                    <external-link-icon size="15" class="icon"></external-link-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
        </PageTabGroup>
        <PageTabGroup v-else>
            You don't have any transactions yet.
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import DatatableCellImage from '@/components/Others/Tables/DatatableCellImage'
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {ExternalLinkIcon} from "vue-feather-icons";
    import axios from 'axios'

    export default {
        name: 'GatewayTransactions',
        components: {
            DatatableCellImage,
            ExternalLinkIcon,
            DatatableWrapper,
            PageTabGroup,
            PageTab,
        },
        data() {
            return {
                isLoading: false,
                transactions: [],
                columns: [
                    {
                        label: 'Invoice Number',
                        field: 'attributes.total',
                        sortable: true
                    },
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
                        label: 'User',
                        field: 'relationships.user.data.id',
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
            axios.get('/api/gateways/' + this.$route.params.slug + '/transactions')
                .then(response => {
                    this.transactions = response.data.data
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
