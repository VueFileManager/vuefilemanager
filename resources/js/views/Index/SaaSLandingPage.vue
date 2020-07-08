<template>
    <div class="landing-page">
        <!--Navigation-->
        <Navigation class="page-wrapper medium" />

        <!--Header-->
        <PageHeader />

        <!--VueFileManager ScreenShot-->
        <HeroScreenshot />

        <!--Main Features-->
        <MainFeatures />

        <!--Pricing Tables-->
        <PricingTables />

        <!--Get Started Call To Action-->
        <GetStarted />

        <!--Footer-->
        <PageFooter />
    </div>
</template>

<script>
    import HeroScreenshot from '@/components/Index/IndexHeroScreenshot'
    import PricingTables from '@/components/Index/IndexPricingTables'
    import MainFeatures from '@/components/Index/IndexMainFeatures'
    import Navigation from '@/components/Index/IndexNavigation'
    import PageHeader from '@/components/Index/IndexPageHeader'
    import GetStarted from '@/components/Index/IndexGetStarted'
    import PageFooter from '@/components/Index/IndexPageFooter'
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'SaaSLandingPage',
        components: {
            HeroScreenshot,
            PricingTables,
            MainFeatures,
            GetStarted,
            Navigation,
            PageHeader,
            PageFooter,
        },
        computed: {
            ...mapGetters(['config']),

        },
        data() {
            return {
                isLoading: false,
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_landing-page';
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    @media (prefers-color-scheme: dark) {

    }

    @media only screen and (max-width: 690px) {

    }
</style>
