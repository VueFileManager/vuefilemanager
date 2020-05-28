<template>
    <div id="single-page">
        <div id="page-content" class="medium-width" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">
                <div class="table-tools">
                    <div class="buttons">
                        <router-link :to="{name: 'UserCreate'}">
                            <MobileActionButton icon="user-plus">
                                {{ $t('admin_page_user.create_user.submit') }}
                            </MobileActionButton>
                        </router-link>
                    </div>
                    <div class="searching">

                    </div>
                </div>
                <DatatableWrapper :paginator="true" :columns="columns" :data="users" class="table table-users">
                    <template scope="{ row }">
                        <tr>
                            <td style="width: 300px">
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
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
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
        data() {
            return {
                isLoading: true,
                users: [],
                columns: [
                    {
                        label: this.$t('admin_page_user.table.name'),
                        field: 'data.attributes.name',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.role'),
                        field: 'data.attributes.role',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.storage_used'),
                        field: 'data.attributes.storage.used',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.storage_capacity'),
                        field: 'data.attributes.storage.capacity',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.created_at'),
                        field: 'data.attributes.created_at_formatted',
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

    .table-tools {
        background: white;
        display: flex;
        justify-content: space-between;
        padding: 15px 0 10px;
        position: sticky;
        top: 40px;
        z-index: 9;
    }

    .action-icons {
        white-space: nowrap;

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
            white-space: nowrap;
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

            .name, .email {
                max-width: 150px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
            }

            .name {
                @include font-size(15);
                line-height: 1;
            }

            .email {
                color: $text-muted;
                @include font-size(12);
            }
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
