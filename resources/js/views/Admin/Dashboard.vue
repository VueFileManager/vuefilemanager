<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading">
            <div class="dashboard-headline">
                <div class="logo">
                    <a href="https://vuefilemanager.com" target="_blank">
                        <img src="/assets/images/vuefilemanager-horizontal-logo.svg" alt="VueFileManager">
                    </a>
                </div>
                <div class="metadata">
                    <a href="https://vuefilemanager.com/changelog" target="_blank" class="meta">
                        <span class="meta-title">Version:</span>
                        <ColorLabel color="purple">
                            {{ data.app_version }}
                        </ColorLabel>
                    </a>
                    <a href="https://codecanyon.net/item/vue-file-manager-with-laravel-backend/25815986" target="_blank" class="meta">
                        <span class="meta-title">License:</span>
                        <ColorLabel color="purple">
                            Extended
                        </ColorLabel>
                    </a>
                    <a href="https://vuefilemanager.com" target="_blank" class="became-backer">
                        <div class="icon">
                            <credit-card-icon size="15"></credit-card-icon>
                        </div>
                        <span class="content">
                            Become a Backer
                        </span>
                    </a>
                </div>
            </div>
            <div class="widgets-total">
                <WidgetTotals
                        class="widget"
                        icon="users"
                        title="Total Users"
                        :value="data.total_users"
                        link-route="Users"
                        link-name="Show All Users"
                ></WidgetTotals>
                <WidgetTotals
                        class="widget"
                        icon="hard-drive"
                        title="Total Space Used"
                        :value="data.total_used_space"
                        link-route="Users"
                        link-name="Show All Users"
                ></WidgetTotals>
                <WidgetTotals
                        v-if="config.isSaaS"
                        class="widget"
                        icon="star"
                        title="Total Premium Users"
                        :value="data.total_premium_users"
                        link-route="Plans"
                        link-name="Show All Plans"
                ></WidgetTotals>
            </div>
            <div class="widget-users">
                <WidgetLatestRegistrations
                        class="widget"
                        icon="users"
                        title="Latest Registrations"
                />
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import WidgetLatestRegistrations from '@/components/Admin/WidgetLatestRegistrations'
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import EmptyPageContent from '@/components/Others/EmptyPageContent'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import WidgetTotals from '@/components/Admin/WidgetTotals'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
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
                this.$updateText('/plans/' + id + '/update', 'is_active', val)
            }
        },
        created() {
            axios.get('/api/dashboard')
                .then(response => {
                    this.data = response.data
                    this.isLoading = false
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .widgets-total {
        display: flex;
        flex: 0 0 33%;
        margin: 0 -20px 20px;

        .widget {
            width: 100%;
            padding: 20px;
        }
    }

    .dashboard-headline {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .became-backer {
        background: rgba($yellow, 0.1);
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
                stroke: $yellow;
            }
        }

        .content {
            color: $yellow;
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

    @media only screen and (max-width: 690px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
