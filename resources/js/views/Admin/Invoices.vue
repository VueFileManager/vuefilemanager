<template>
    <div id="single-page">

        <!--Page Content-->
        <div id="page-content" v-show="! isLoading && config.stripe_public_key">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page" v-if="config.stripe_public_key">
                <DatatableWrapper @data="invoices = $event" @init="isLoading = false" api="/api/invoices" :paginator="false" :columns="columns" class="table">
                    <template slot-scope="{ row }">
                        <tr>
                            <td>
                                <a :href="$getInvoiceLink(row.data.attributes.customer, row.data.id)" target="_blank" class="cell-item">
                                    {{ row.data.attributes.order }}
                                </a>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.total }}
                                </span>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.bag.description }}
                                </span>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.created_at_formatted }}
                                </span>
                            </td>
                            <td>
                                <router-link v-if="row.relationships" :to="{name: 'UserInvoices', params: {id: row.relationships.user.data.id}}">
                                    <DatatableCellImage
                                            image-size="small"
                                            :image="row.relationships.user.data.attributes.avatar"
                                            :title="row.relationships.user.data.attributes.name"
                                    />
                                </router-link>
                                <span v-else class="cell-item">
                                    -
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a :href="$getInvoiceLink(row.data.attributes.customer, row.data.id)" target="_blank">
                                        <external-link-icon size="15" class="icon"></external-link-icon>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </template>
                </DatatableWrapper>
            </div>
        </div>

        <!--Empty invoices-->
        <EmptyPageContent
                v-if="! isLoading && invoices.length === 0 && config.stripe_public_key"
                icon="file-text"
                :title="$t('admin_page_invoices.empty.title')"
                :description="$t('admin_page_invoices.empty.description')"
        >
        </EmptyPageContent>

        <!--Stripe Not Configured-->
        <EmptyPageContent
                v-if="! config.stripe_public_key"
                icon="settings"
                :title="$t('activation.stripe.title')"
                :description="$t('activation.stripe.description')"
        >
            <router-link :to="{name: 'AppPayments'}">
                <ButtonBase button-style="theme">{{ $t('activation.stripe.button') }}</ButtonBase>
            </router-link>
        </EmptyPageContent>

        <!--Spinner-->
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import DatatableCellImage from '@/components/Others/Tables/DatatableCellImage'
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import EmptyPageContent from '@/components/Others/EmptyPageContent'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import {ExternalLinkIcon} from "vue-feather-icons";
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Invoices',
        components: {
            DatatableCellImage,
            MobileActionButton,
            ExternalLinkIcon,
            EmptyPageContent,
            DatatableWrapper,
            SectionTitle,
            MobileHeader,
            SwitchInput,
            PageHeader,
            ButtonBase,
            ColorLabel,
            Spinner,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: true,
                invoices: [],
                columns: [
                    {
                        label: this.$t('admin_page_invoices.table.number'),
                        field: 'data.attributes.order',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_invoices.table.total'),
                        field: 'data.attributes.bag.amount',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_invoices.table.plan'),
                        field: 'data.attributes.bag.amount',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_invoices.table.payed'),
                        field: 'data.attributes.created_at',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_invoices.table.user'),
                        field: 'relationships.user.data.attributes.name',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_user.table.action'),
                        sortable: false
                    },
                ],
            }
        },
        created() {
            if (! this.config.stripe_public_key)
                this.isLoading = false
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
