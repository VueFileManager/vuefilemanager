<template>
    <PageTab v-if="subscribers">
        <PageTabGroup>
            <DatatableWrapper :paginator="true" :columns="columns" :data="subscribers" class="table">
                <template scope="{ row }">
                    <tr>
                        <td>
                            <router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
                                <DatatableCellImage
                                        image-size="small"
                                        :image="row.data.attributes.avatar"
                                        :title="row.data.attributes.name"
                                />
                            </router-link>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.relationships.storage.data.attributes.used }}%
                            </span>
                        </td>
                        <td>
                            <span class="cell-item">
                                {{ row.relationships.subscription.data.attributes.ends_at }}
                            </span>
                        </td>
                        <td>
                            <div class="action-icons">
                                <router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
                                    <edit-2-icon size="15" class="icon icon-edit"></edit-2-icon>
                                </router-link>
                                <router-link :to="{name: 'UserDelete', params: {id: row.data.id}}">
                                    <trash2-icon size="15" class="icon icon-trash"></trash2-icon>
                                </router-link>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import DatatableCellImage from '@/components/Others/Tables/DatatableCellImage'
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {DownloadCloudIcon, Edit2Icon, Trash2Icon} from "vue-feather-icons";
    import axios from 'axios'

    export default {
        name: 'PlanSubscribers',
        components: {
            DatatableCellImage,
            DownloadCloudIcon,
            DatatableWrapper,
            PageTabGroup,
            PageTab,
            Edit2Icon,
            Trash2Icon,
        },
        data() {
            return {
                isLoading: false,
                subscribers: undefined,
                columns: [
                    {
                        label: 'User',
                        field: 'data.attributes.plan',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.storage_used'),
                        field: 'data.storage.attributes.storage.used',
                        sortable: true
                    },
                    {
                        label: 'Expire At',
                        field: 'data.subscription.data.attributes.ends_at',
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
            axios.get('/api/plans/' + this.$route.params.id + '/subscribers')
                .then(response => {
                    this.subscribers = response.data.data
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
