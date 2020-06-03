<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">
                <DatatableWrapper :paginator="false" :columns="columns" :data="gateways" class="table table-users">
                    <template scope="{ row }">
                        <tr>
                            <td style="min-width: 200px;">
                                <router-link :to="{name: 'GatewaySettings', params: {slug: row.data.attributes.slug}}">
                                    <DatatableCellImage
                                            :image="row.data.attributes.logo"
                                            :title="row.data.attributes.name"
                                    />
                                </router-link>
                            </td>
                            <td>
                                <span class="cell-item">
                                    <SwitchInput
                                            @input="changeStatus($event, row.data.attributes.slug)"
                                            :state="row.data.attributes.status"
                                            class="switch"
                                    />
                                </span>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.payments_processed }}
                                </span>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.active_subscribers }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <router-link :to="{name: 'GatewaySettings', params: {slug: row.data.attributes.slug}}">
                                        <edit-2-icon size="15" class="icon icon-edit"></edit-2-icon>
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
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import axios from 'axios'

    export default {
        name: 'Plans',
        components: {
            DatatableCellImage,
            MobileActionButton,
            DatatableWrapper,
            SectionTitle,
            MobileHeader,
            SwitchInput,
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
                gateways: undefined,
                columns: [
                    {
                        label: 'Payment Gateway',
                        field: 'data.attributes.gateway',
                        sortable: true
                    },
                    {
                        label: 'Status',
                        field: 'data.attributes.status',
                        sortable: true
                    },
                    {
                        label: 'Payments Processed',
                        field: 'data.attributes.payments_processed',
                        sortable: true
                    },
                    {
                        label: 'Active Subscribers',
                        field: 'data.attributes.active_subscribers',
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
            changeStatus(val, type) {
                this.$updateText('/gateways/' + type, 'status', val)
            }
        },
        created() {
            axios.get('/api/gateways')
                .then(response => {
                    this.gateways = response.data.data
                    this.isLoading = false
                })
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
