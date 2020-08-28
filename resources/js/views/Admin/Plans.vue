<template>
    <div id="single-page">

        <!--Stripe plans-->
        <div id="page-content" v-show="stripeConfiguredWithPlans">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page" v-if="config.stripe_public_key">
                <div class="table-tools">
                    <div class="buttons">
                        <router-link :to="{name: 'PlanCreate'}">
                            <MobileActionButton icon="plus">
                                {{ $t('admin_page_plans.create_plan_button') }}
                            </MobileActionButton>
                        </router-link>
                    </div>
                    <div class="searching">

                    </div>
                </div>
                <DatatableWrapper @data="plans = $event" @init="isLoading = false" api="/api/plans" :paginator="false" :columns="columns" class="table table-users">
                    <template slot-scope="{ row }">
                        <tr>
                            <td style="max-width: 80px">
                                <span class="cell-item">
                                    <SwitchInput @input="changeStatus($event, row.data.id)" class="switch" :state="row.data.attributes.status"/>
                                </span>
                            </td>
                            <td class="name" style="min-width: 120px">
                                <router-link :to="{name: 'PlanSettings', params: {id: row.data.id}}" class="cell-item" tag="div">
                                    <span>{{ row.data.attributes.name }}</span>
                                </router-link>
                            </td>

                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.subscribers }}
                                </span>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.price_formatted }}
                                </span>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.capacity_formatted }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <router-link :to="{name: 'PlanSettings', params: {id: row.data.id}}">
                                        <edit-2-icon size="15" class="icon icon-edit"></edit-2-icon>
                                    </router-link>
                                    <router-link :to="{name: 'PlanDelete', params: {id: row.data.id}}">
                                        <trash2-icon size="15" class="icon icon-trash"></trash2-icon>
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                    </template>
                </DatatableWrapper>
            </div>
        </div>

        <!--Stripe configured but has empty plans-->
        <EmptyPageContent
                v-if="isEmptyPlans"
                icon="file"
                :title="$t('admin_page_plans.empty.title')"
                :description="$t('admin_page_plans.empty.description')"
        >
            <router-link :to="{name: 'PlanCreate'}" tag="p">
                <ButtonBase button-style="theme">{{ $t('admin_page_plans.empty.button') }}</ButtonBase>
            </router-link>
        </EmptyPageContent>

        <!--Stripe is Not Configured-->
        <EmptyPageContent
                v-if="stripeIsNotConfigured"
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
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import EmptyPageContent from '@/components/Others/EmptyPageContent'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Plans',
        components: {
            MobileActionButton,
            EmptyPageContent,
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
        computed: {
            ...mapGetters(['config']),
            isEmptyPlans() {
                return ! this.isLoading && this.plans.length === 0 && this.config.stripe_public_key
            },
            stripeIsNotConfigured() {
                return ! this.config.stripe_public_key
            },
            stripeConfiguredWithPlans() {
                return ! this.isLoading && this.config.stripe_public_key
            }
        },
        data() {
            return {
                isLoading: true,
                plans: [],
                columns: [
                    {
                        label: this.$t('admin_page_plans.table.status'),
                        field: 'data.attributes.status',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_plans.table.name'),
                        field: 'data.attributes.name',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_plans.table.subscribers'),
                        field: 'data.attributes.subscribers',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_plans.table.price'),
                        field: 'data.attributes.price',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_plans.table.storage_capacity'),
                        field: 'data.attributes.capacity',
                        sortable: false
                    },
                    {
                        label: this.$t('admin_page_user.table.action'),
                        sortable: false
                    },
                ],
            }
        },
        methods: {
            changeStatus(val, id) {
                this.$updateText('/plans/' + id + '/update', 'is_active', val)
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
