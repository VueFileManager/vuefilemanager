<template>
    <div class="landing-page">

        <!--Navigation-->
        <Navigation class="page-wrapper small"/>

        <!--Page content-->
        <div class="page-wrapper small">

            <!--Headline-->
            <PageTitle
                    class="headline"
                    title="Terms & Conditions"
            ></PageTitle>

            <!--Content-->
            <div class="page-content">
                <p>Dominion, open bearing brought may dominion male beginning god land greater forth there fruit whose creepeth two their great there morning multiply Third image
                    first. Waters waters. Which, moving place let said their saw, behold good appear of days very dominion called shall creeping creepeth subdue living, over set
                    subdue above under form make appear blessed, given shall midst likeness midst days him fruit seasons void hath light it him and days gathering give itself his
                    heaven fruitful fourth darkness bearing. Bring third a our gathered fruitful man sixth place.</p>
                <p>Have give land may man together unto appear bring it is creature. Gathering abundantly. Beast night. Blessed be Lights, second brought. Yielding without set the
                    open give one seed of fowl said living years said female. Second hath subdue, give dry which very there night is. Whales very seed heaven set image.</p>
                <p>Was moved, air seas Without. Winged years third. Dry under upon very Light tree. Given be meat seed fish. Over earth was beast fruitful. Abundantly great female
                    sixth. Which divide our days fly heaven seasons. Lights form created darkness third morning, and won't whales. Living fowl bearing saying dominion first female.
                    That to lesser our doesn't morning place.</p>
            </div>
        </div>

        <!--Footer-->
        <PageFooter/>
    </div>
</template>

<script>
    import PageTitle from '@/components/Index/Components/PageTitle'
    import PageFooter from '@/components/Index/IndexPageFooter'
    import Navigation from '@/components/Index/IndexNavigation'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'SaaSLandingPage',
        components: {
            PageFooter,
            Navigation,
            PageTitle,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: false,
            }
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

    .headline {
        padding-top: 70px;
        padding-bottom: 50px;
    }

    .page-content {

        p {
            @include font-size(20);
            font-weight: 500;
            line-height: 1.65;
            padding-bottom: 30px;
        }
    }

    @media (prefers-color-scheme: dark) {

    }

    @media only screen and (max-width: 690px) {

    }
</style>
