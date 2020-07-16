<template>
    <div class="landing-page">
        <div v-if="! isLoading">
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
        <div v-if="isLoading">
            <Spinner></Spinner>
        </div>
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
    import Spinner from '@/components/FilesView/Spinner'
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
            Spinner,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: true,
            }
        },
        beforeMount() {
            if (! this.config.isSaaS) {
                this.$router.push({name: 'SignIn'})
            }
        },
        mounted() {
            if (! this.config.isSaaS) return

            // Get page content
            axios.get('/api/content', {
                params: {
                    column: 'footer_content|get_started_description|get_started_title|pricing_description|pricing_title|feature_description_3|feature_title_3|feature_description_2|feature_title_2|feature_description_1|feature_title_1|features_description|features_title|header_description|header_title|section_get_started|section_pricing_content|section_feature_boxes|section_features'
                }
            })
                .then(response => {
                    this.$store.commit('SET_INDEX_CONTENT', response.data)
                })
                .finally(() => {
                    this.isLoading = false
                })
        },
        created() {
            this.$scrollTop()
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_landing-page';
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
</style>
