<template>
    <div id="single-page">
        <div id="page-content" class="full-width" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">
                <DatatableWrapper :paginator="true" :columns="columns" :data="users" class="table table-users">
                    <template scope="{ row }">
                        <tr>
                            <td style="width: 320px">
                                <router-link :to="{name: 'UserDetail', params: {id: row.data.id}}" tag="div" class="user-thumbnail">
                                    <div class="avatar">
                                        <img :src="row.data.attributes.avatar" :alt="row.data.attributes.name">
                                    </div>
                                    <div class="info">
                                        <b class="name">{{ row.data.attributes.name }}</b>
                                        <span class="email">{{ row.data.attributes.email }}</span>
                                    </div>
                                </router-link>
                            </td>
                            <td>
                                <ColorLabel :color="getRoleColor(row.data.attributes.role)">
                                    {{ row.data.attributes.role }}
                                </ColorLabel>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.storage.used }}%
                                </span>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.storage.capacity_formatted }}
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
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            DatatableWrapper,
            SectionTitle,
            MobileHeader,
            Trash2Icon,
            PageHeader,
            ColorLabel,
            Edit2Icon,
            Spinner,
        },
        data() {
            return {
                isLoading: true,
                users: [],
                columns: [
                    {
                        label: this.$t('admin_page_user.table.name'),
                        field: 'attributes.name',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.role'),
                        field: 'attributes.role',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.storage_used'),
                        field: 'attributes.storage.used',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.storage_capacity'),
                        field: 'attributes.storage.capacity',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.action'),
                        field: 'action',
                        sortable: false
                    },
                ],
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
            axios.get('/api/users')
                .then(response => {
                    this.users = response.data.data
                    this.isLoading = false
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .action-icons {

        a {
            display: inline-block;
            margin-left: 10px;

            &:first-child {
                margin-left: 0;
            }
        }

        .icon {
            cursor: pointer;

            circle, path, line, polyline {
                stroke: $text;
            }

            &.icon-trash {
                circle, path, line, polyline {
                    stroke: $red;
                }
            }
        }
    }

    .table {

        .cell-item {
            @include font-size(15);
        }
    }

    .user-thumbnail {
        display: flex;
        align-items: center;
        cursor: pointer;

        .avatar {
            margin-right: 20px;
            line-height: 0;

            img {
                line-height: 0;
                width: 48px;
                height: 48px;
                border-radius: 8px;
            }
        }

        .info {

            .name {
                display: block;
                @include font-size(15);
                line-height: 1;
            }

            .email {
                color: $text-muted;
                @include font-size(12);
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .action-icons {

            .icon {
                cursor: pointer;

                circle, path, line, polyline {
                    stroke: $dark_mode_text_primary;
                }
            }
        }

        .user-thumbnail {

            .info {

                .email {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }

</style>
