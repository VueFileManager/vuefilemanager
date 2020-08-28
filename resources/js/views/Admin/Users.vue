<template>
    <div id="single-page">
        <div id="page-content">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">

                <!--Table tools-->
                <div class="table-tools">
                    <div class="buttons">
                        <router-link :to="{name: 'UserCreate'}">
                            <MobileActionButton icon="user-plus">
                                {{ $t('admin_page_user.create_user.submit') }}
                            </MobileActionButton>
                        </router-link>
                    </div>
                </div>

                <!--Datatable-->
                <DatatableWrapper @init="isLoading = false" api="/api/users" :paginator="true" :columns="columns" class="table table-users">
                    <template slot-scope="{ row }">
                        <tr>
                            <td style="min-width: 320px">
                                <router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
                                    <DatatableCellImage
                                            :image="row.data.attributes.avatar"
                                            :title="row.data.attributes.name"
                                            :description="row.data.attributes.email"
                                    />
                                </router-link>
                            </td>
                            <td>
                                <ColorLabel :color="getRoleColor(row.data.attributes.role)">
                                    {{ row.data.attributes.role }}
                                </ColorLabel>
                            </td>
                            <td v-if="config.isSaaS">
                                <span class="cell-item" v-if="row.data.attributes.subscription">
                                    {{ $t('global.premium') }}
                                </span>
                                <span class="cell-item" v-else>
                                    {{ $t('global.free') }}
                                </span>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.relationships.storage.data.attributes.used_formatted }}
                                </span>
                            </td>
                            <td v-if="config.storageLimit">
                                <span class="cell-item">
                                    {{ row.relationships.storage.data.attributes.capacity_formatted }}
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
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import DatatableCellImage from '@/components/Others/Tables/DatatableCellImage'
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import {mapGetters} from "vuex"
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            DatatableCellImage,
            MobileActionButton,
            DatatableWrapper,
            SectionTitle,
            MobileHeader,
            Trash2Icon,
            PageHeader,
            ButtonBase,
            ColorLabel,
            Edit2Icon,
            Spinner,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: true,
                columns: undefined,
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
        created() {
            this.columns = [
                {
                    label: this.$t('admin_page_user.table.name'),
                    field: 'name',
                    sortable: true
                },
                {
                    label: this.$t('admin_page_user.table.role'),
                    field: 'role',
                    sortable: true
                },
                {
                    label: this.$t('admin_page_user.table.plan'),
                    field: 'subscription',
                    sortable: false,
                    hidden: ! this.config.isSaaS,
                },
                {
                    label: this.$t('admin_page_user.table.storage_used'),
                    field: 'used',
                    sortable: true
                },
                {
                    label: this.$t('admin_page_user.table.storage_capacity'),
                    field: 'settings.storage_capacity',
                    sortable: true,
                    hidden: ! this.config.storageLimit,
                },
                {
                    label: this.$t('admin_page_user.table.created_at'),
                    field: 'created_at',
                    sortable: true
                },
                {
                    label: this.$t('admin_page_user.table.action'),
                    field: 'data.action',
                    sortable: false
                },
            ]
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .table-tools {
        background: white;
        display: flex;
        justify-content: space-between;
        padding: 15px 0 10px;
        position: sticky;
        top: 40px;
        z-index: 9;
    }

    .table {

        .cell-item {
            @include font-size(15);
            white-space: nowrap;
        }
    }

    @media only screen and (max-width: 690px) {
        .table-tools {
            padding: 0 0 5px;
        }
    }

    @media (prefers-color-scheme: dark) {

        .table-tools {
            background: $dark_mode_background;
        }
    }

</style>
