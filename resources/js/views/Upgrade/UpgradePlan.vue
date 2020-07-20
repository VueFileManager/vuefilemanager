<template>
    <div id="single-page">
        <div id="page-content" class="large-width center-page" v-show="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <div class="content-page">

                <!--Page Title-->
                <div class="plan-title">
                    <cloud-icon size="42" class="title-icon"></cloud-icon>
                    <h1>{{ $t('page_pricing_tables.title') }}</h1>
                    <h2>{{ $t('page_pricing_tables.description') }}</h2>
                </div>

                <!--Pricing Tables-->
                <PlanPricingTables @load="onLoadPricingTables" @selected-plan="onSelectTable"/>
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import PlanPricingTables from '@/components/Others/PlanPricingTables'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'
    import { CloudIcon } from 'vue-feather-icons'
    import axios from 'axios'

    export default {
        name: 'UpgradePlan',
        components: {
            PlanPricingTables,
            MobileHeader,
            PageHeader,
            CloudIcon,
            Spinner,
        },
        data() {
            return {
                isLoading: true,
            }
        },
        methods: {
            onLoadPricingTables(state) {
                this.isLoading = state
            },
            onSelectTable(plan) {
                this.$store.commit('STORE_REQUESTED_PLAN', plan)
            }
        },
        beforeMount() {
            let StripeElementsScript = document.createElement('script')

            StripeElementsScript.setAttribute('src', 'https://js.stripe.com/v3/')
            document.head.appendChild(StripeElementsScript)
        },
        mounted() {
            // Reload user data
            this.$store.dispatch('getAppData')
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .plan-title {
        text-align: center;
        max-width: 600px;
        margin: 0 auto 80px;

        path, line, polyline, rect, circle {
            color: $theme;
        }

        h1 {
            @include font-size(38);
            font-weight: 800;
            margin-bottom: 5px;
        }

        h2 {
            @include font-size(20);
            font-weight: 500;
        }
    }

    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {
        .plan-title {

            h1 {
                color: $dark_mode_text_primary;
            }

            h2 {
                color: $dark_mode_text_secondary;
            }
        }
    }

</style>
