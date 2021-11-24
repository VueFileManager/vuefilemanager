<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading && data">

            <div class="dashboard-headline">
                <div class="logo">
                    <a href="https://vuefilemanager.com" target="_blank">
                        <img src="/assets/images/vuefilemanager-horizontal-logo.svg" alt="VueFileManager" class="light-mode">
                    </a>
                </div>
                <div class="metadata">
                    <a href="https://gist.github.com/MakingCG/9c07f8af392081ae5d5290d920a79b5d" target="_blank" class="meta">
                        <span class="meta-title">{{ $t('admin_page_dashboard.version') }}:</span>
                        <ColorLabel color="purple">
                            {{ data.app_version }}
                        </ColorLabel>
                    </a>
                    <a v-if="data.license" href="https://codecanyon.net/item/vue-file-manager-with-laravel-backend/25815986" target="_blank" class="meta">
                        <span class="meta-title">{{ $t('admin_page_dashboard.license') }}:</span>
                        <ColorLabel color="purple">
                            {{ data.license }}
                        </ColorLabel>
                    </a>
                    <a href="https://bit.ly/VueFileManager-survey" target="_blank" class="became-backer bg-theme-100">
                        <div class="icon">
                            <credit-card-icon size="15" class="text-theme dark-text-theme"/>
                        </div>
                        <span class="content text-theme dark-text-theme">
                            {{ $t('admin_page_dashboard.backer_button') }}
                        </span>
                    </a>
                </div>
            </div>

            <!--Stripe notice-->
<!--            <InfoBox v-if="config.isSaaS && ! config.stripe_public_key" class="dashboard-notice">
                <i18n path="notice.stripe_activation">
                    <router-link :to="{name: 'AppPayments'}">{{ $t('notice.stripe_activation_button') }}</router-link>
                </i18n>
            </InfoBox>-->

            <div class="widgets-total" :class="{'widgets-coll-3': config.isSaaS, 'widgets-coll-2': ! config.isSaaS}">
                <WidgetTotals
                        class="widget"
                        icon="users"
                        :title="$t('admin_page_dashboard.w_total_users.title')"
                        :value="data.total_users"
                        link-route="Users"
                        :link-name="$t('admin_page_dashboard.w_total_users.link')"
                />
                <WidgetTotals
                        class="widget"
                        icon="hard-drive"
                        :title="$t('admin_page_dashboard.w_total_space.title')"
                        :value="data.total_used_space"
                        link-route="Users"
                        :link-name="$t('admin_page_dashboard.w_total_space.link')"
                />
                <WidgetTotals
                        v-if="config.isSaaS"
                        class="widget"
                        icon="star"
                        :title="$t('admin_page_dashboard.w_total_premium.title')"
                        :value="data.total_premium_users"
                        link-route="Plans"
                        :link-name="$t('admin_page_dashboard.w_total_premium.link')"
                />
            </div>
<!--            <div class="widget-users">
                <WidgetLatestRegistrations
                        class="widget"
                        icon="users"
                        :title="$t('admin_page_dashboard.w_latest_users.title')"
                />
            </div>-->
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import WidgetLatestRegistrations from '/resources/js/components/Admin/WidgetLatestRegistrations'
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
    import EmptyPageContent from '/resources/js/components/Others/EmptyPageContent'
    import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
    import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
    import SectionTitle from '/resources/js/components/Others/SectionTitle'
    import WidgetTotals from '/resources/js/components/Admin/WidgetTotals'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import PageHeader from '/resources/js/components/Others/PageHeader'
    import ColorLabel from '/resources/js/components/Others/ColorLabel'
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import {CreditCardIcon} from "vue-feather-icons"
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Dashboard',
        components: {
            WidgetLatestRegistrations,
            MobileActionButton,
            EmptyPageContent,
            DatatableWrapper,
            WidgetTotals,
            CreditCardIcon,
            SectionTitle,
            MobileHeader,
            SwitchInput,
            PageHeader,
            ButtonBase,
            ColorLabel,
            InfoBox,
            Spinner,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: false,
                data: undefined,
            }
        },
        methods: {
            changeStatus(val, id) {
                this.$updateText('/admin/plans/' + id + '/update', 'is_active', val)
            }
        },
        created() {
            axios.get('/api/admin/dashboard')
                .then(response => {
                    this.data = response.data
                })
                .finally(() => {
                    this.isLoading = false
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .dashboard-notice {
        margin-bottom: 20px;
    }

    .widgets-total {
        display: grid;
        margin: 0 -20px 20px;

        &.widgets-coll-2 {
            grid-template-columns: repeat(auto-fill, 50%);
        }

        &.widgets-coll-3 {
            grid-template-columns: repeat(auto-fill, 33.3%);
        }

        .widget {
            width: 100%;
            padding: 20px;
        }
    }

    .dashboard-headline {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .became-backer {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 6px;
        margin-left: 40px;
        cursor: pointer;

        .icon, .content {
            display: inline-block;
            vertical-align: middle;
        }

        .icon {
            margin-right: 10px;
            line-height: 0;

            rect, line {
                color: inherit;
            }
        }

        .content {
            font-weight: 700;
            @include font-size(14);
        }
    }

    .metadata {

        .meta {
            display: inline-block;
            margin-left: 20px;
        }

        .meta-title {
            @include font-size(14);
            font-weight: 700;
        }
    }

    .logo {
        .dark {
            display: none;
        }
    }

    @media only screen and (max-width: 1190px) {
        .widgets-total {
            margin: 0 -10px 10px;

            .widget {
                padding: 10px;
            }
        }
    }

    @media only screen and (max-width: 1024px) {
        .widgets-total {

            &.widgets-coll-2, &.widgets-coll-3 {
                grid-template-columns: repeat(auto-fill, 50%);
            }
        }
    }

    @media only screen and (max-width: 960px) {

        .widgets-total {

            &.widgets-coll-2, &.widgets-coll-3 {
                grid-template-columns: repeat(auto-fill, 100%);
            }
        }

        .became-backer {
            display: none;
        }

        .dashboard-headline {
            display: block;
            text-align: left;

            .metadata {

                .meta:first-child {
                    margin-left: 0;
                }
            }

            .logo {
                margin-bottom: 10px;
            }
        }
    }

    .dark {

        .metadata {

            .meta-title {
                color: $dark_mode_text_primary;
            }
        }
    }

</style>
