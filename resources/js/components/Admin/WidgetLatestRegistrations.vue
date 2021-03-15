<template>
    <WidgetWrapper :icon="icon" :title="title">
        <DatatableWrapper  @init="isLoading = false" api="/api/admin/dashboard/newbies" :paginator="false" :columns="columns" class="table table-users">
            <template slot-scope="{ row }">
                <tr>
                    <td style="width: 300px">
                        <router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
                            <DatatableCellImage
                                    :image="row.data.relationships.settings.data.attributes.avatar"
                                    :title="row.data.relationships.settings.data.attributes.name"
                                    :description="row.data.attributes.email"
                            />
                        </router-link>
                    </td>
                    <td>
                        <ColorLabel :color="getRoleColor(row.data.attributes.role)">
                            {{ row.data.attributes.role }}
                        </ColorLabel>
                    </td>
                    <td>
                        <span class="cell-item">
                            {{ row.data.attributes.storage.used_formatted }}
                        </span>
                    </td>
                    <td>
                        <span class="cell-item">
                            {{ row.data.attributes.created_at_formatted }}
                        </span>
                    </td>
                    <td>
                        <div class="action-icons">
                            <router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
                                <Edit2Icon size="15" class="icon icon-edit" />
                            </router-link>
                            <router-link :to="{name: 'UserDelete', params: {id: row.data.id}}">
                                <Trash2Icon size="15" class="icon icon-trash" />
                            </router-link>
                        </div>
                    </td>
                </tr>
            </template>
        </DatatableWrapper>
    </WidgetWrapper>
</template>

<script>
    import DatatableCellImage from '@/components/Others/Tables/DatatableCellImage'
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import WidgetWrapper from '@/components/Admin/WidgetWrapper'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons"
    import ColorLabel from '@/components/Others/ColorLabel'
    import axios from 'axios'

    export default {
        name: 'WidgetLatestRegistrations',
        props: ['icon', 'title'],
        components: {
            DatatableCellImage,
            DatatableWrapper,
            WidgetWrapper,
            Trash2Icon,
            ColorLabel,
            Edit2Icon,
        },
        data() {
            return {
                isLoading: false,
                columns: [
                    {
                        label: this.$t('admin_page_user.table.name'),
                        field: 'name',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_user.table.role'),
                        field: 'role',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_user.table.storage_used'),
                        field: 'used',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_user.table.created_at'),
                        field: 'created_at',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_user.table.action'),
                        field: 'data.action',
                        sortable: false
                    },
                ]
            }
        },
        methods: {
            getRoleColor(role) {
                switch(role) {
                    case 'admin':
                        return 'purple'
                        break;
                    case 'user':
                        return 'yellow'
                        break;
                }
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    @media (prefers-color-scheme: dark) {

    }
</style>
