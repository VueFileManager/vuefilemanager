<template>
    <PageTab :is-loading="isLoading">
        <PageTabGroup v-if="subscribers && subscribers.length > 0">
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
        <InfoBox v-else>
            <p>There is no any subscriber yet.</p>
        </InfoBox>
    </PageTab>
</template>

<script>
    import DatatableCellImage from '@/components/Others/Tables/DatatableCellImage'
    import {DownloadCloudIcon, Edit2Icon, Trash2Icon} from "vue-feather-icons"
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import axios from 'axios'

    export default {
        name: 'PlanSubscribers',
        components: {
            DatatableCellImage,
            DownloadCloudIcon,
            DatatableWrapper,
            PageTabGroup,
            Trash2Icon,
            Edit2Icon,
            PageTab,
            InfoBox,
        },
        data() {
            return {
                subscribers: undefined,
                isLoading: false,
                columns: [
                    {
                        label: 'User',
                        field: 'data.attributes.name',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.storage_used'),
                        field: 'data.relationships.storage.data.attributes.used',
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
