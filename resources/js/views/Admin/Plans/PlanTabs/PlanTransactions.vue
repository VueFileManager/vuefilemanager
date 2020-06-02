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
                            <router-link :to="{name: 'UserInvoices', params: {id: row.relationships.user.data.id}}">
                                <DatatableCellImage
                                        image-size="small"
                                        :image="row.relationships.user.data.attributes.avatar"
                                        :title="row.relationships.user.data.attributes.name"
                                />
                            </router-link>
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
    import DatatableCellImage from '@/components/Others/Tables/DatatableCellImage'
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {DownloadCloudIcon} from "vue-feather-icons";
    import axios from 'axios'

    export default {
        name: 'PlanTransactions',
        components: {
            DatatableCellImage,
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
                        label: 'User',
                        field: 'attributes.plan',
                        sortable: true
                    },
                    {
                        label: 'Payed',
                        field: 'relationships.user.data.attributes.name',
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
