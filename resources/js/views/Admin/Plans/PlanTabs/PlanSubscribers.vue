<template>
    <PageTab :is-loading="isLoading">
        <PageTabGroup>
            <DatatableWrapper @init="isLoading = false" :api="'/api/plans/' + this.$route.params.id + '/subscribers'" :paginator="false" :columns="columns" :data="subscribers" class="table">

                <!--Table data content-->
                <template slot-scope="{ row }">
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

                <!--Empty page-->
                <template v-slot:empty-page>
                    <InfoBox>
                        <p>{{ $t('admin_page_plans.subscribers.empty') }}</p>
                    </InfoBox>
                </template>
            </DatatableWrapper>
        </PageTabGroup>
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
                isLoading: true,
                columns: [
                    {
                        label: this.$t('admin_page_user.table.name'),
                        field: 'name',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.storage_used'),
                        field: 'used',
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
