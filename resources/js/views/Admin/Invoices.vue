<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">
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
                                    <router-link :to="{name: 'UserDelete', params: {id: row.relationships.user.data.id}}">
                                        <download-cloud-icon size="15" class="icon"></download-cloud-icon>
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
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import {DownloadCloudIcon} from "vue-feather-icons";
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import axios from 'axios'

    export default {
        name: 'Plans',
        components: {
            DownloadCloudIcon,
            DatatableCellImage,
            MobileActionButton,
            DatatableWrapper,
            SectionTitle,
            MobileHeader,
            SwitchInput,
            PageHeader,
            ButtonBase,
            ColorLabel,
            Spinner,
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
                        relationships: {
                            user: {
                                data: {
                                    id: '1',
                                    type: 'users',
                                    attributes: {
                                        avatar: '/avatars/6osmoXJo-avatar-01.png',
                                        name: 'Jane Doe',
                                        email: 'howdy@hi5ve.digital',
                                    }
                                }
                            }
                        }
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
                        relationships: {
                            user: {
                                data: {
                                    id: '1',
                                    type: 'users',
                                    attributes: {
                                        avatar: '/avatars/dSMRCbwF-69299654_2418248648259454_4545563304688353280_o.jpg',
                                        name: 'Peter Papp',
                                        email: 'peterpapp@makingcg.com',
                                    }
                                }
                            }
                        }
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
                        relationships: {
                            user: {
                                data: {
                                    id: '1',
                                    type: 'users',
                                    attributes: {
                                        avatar: '/assets/images/default-avatar.png',
                                        name: 'Pavel Svintsitskiy',
                                        email: 'pashaUSA@gmail.com',
                                    }
                                }
                            }
                        }
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
                        relationships: {
                            user: {
                                data: {
                                    id: '1',
                                    type: 'users',
                                    attributes: {
                                        avatar: '/avatars/lTksMdJM-6D3529EF-5D8C-4959-BEC2-4BDE80A051C2.jpeg',
                                        name: 'Torsten',
                                        email: 'torsten.hoegel@go-on-net.de',
                                    }
                                }
                            }
                        }
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
                        relationships: {
                            user: {
                                data: {
                                    id: '1',
                                    type: 'users',
                                    attributes: {
                                        avatar: '/avatars/6osmoXJo-avatar-01.png',
                                        name: 'Jane Doe',
                                        email: 'howdy@hi5ve.digital',
                                    }
                                }
                            }
                        }
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
                        relationships: {
                            user: {
                                data: {
                                    id: '1',
                                    type: 'users',
                                    attributes: {
                                        avatar: '/avatars/dSMRCbwF-69299654_2418248648259454_4545563304688353280_o.jpg',
                                        name: 'Peter Papp',
                                        email: 'peterpapp@makingcg.com',
                                    }
                                }
                            }
                        }
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
                        relationships: {
                            user: {
                                data: {
                                    id: '1',
                                    type: 'users',
                                    attributes: {
                                        avatar: '/assets/images/default-avatar.png',
                                        name: 'Pavel Svintsitskiy',
                                        email: 'pashaUSA@gmail.com',
                                    }
                                }
                            }
                        }
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
                        relationships: {
                            user: {
                                data: {
                                    id: '1',
                                    type: 'users',
                                    attributes: {
                                        avatar: '/avatars/lTksMdJM-6D3529EF-5D8C-4959-BEC2-4BDE80A051C2.jpeg',
                                        name: 'Torsten',
                                        email: 'torsten.hoegel@go-on-net.de',
                                    }
                                }
                            }
                        }
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
            /*axios.get('/api/invoices')
                .then(response => {
                    this.invoices = response.data.data
                    this.isLoading = false
                })*/
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

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
        }
    }

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

        .name {
            font-weight: 700;
            cursor: pointer;
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
