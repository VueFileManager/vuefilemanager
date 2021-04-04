<template>
    <div id="page">
        <OasisNavigation v-if="false"/>
        <OasisHeader v-if="false"/>
        <OasisFeatures v-if="false"/>
        <OasisPricing v-if="false"/>
        <OasisAboutUs v-if="false"/>
        <OasisFooter v-if="true"/>
    </div>
</template>

<script>
    import OasisNavigation from '@/Oasis/Homepage/Components/OasisNavigation'
    import OasisFeatures from '@/Oasis/Homepage/Components/OasisFeatures'
    import OasisPricing from '@/Oasis/Homepage/Components/OasisPricing'
    import OasisAboutUs from '@/Oasis/Homepage/Components/OasisAboutUs'
    import OasisHeader from '@/Oasis/Homepage/Components/OasisHeader'
    import OasisFooter from '@/Oasis/Homepage/Components/OasisFooter'
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Homepage',
        components: {
            OasisNavigation,
            OasisFeatures,
            OasisPricing,
            OasisAboutUs,
            OasisHeader,
            OasisFooter,
        },
        computed: {
            ...mapGetters([
                'config'
            ]),
        },
        data() {
            return {

            }
        },
        mounted() {

        },
        created() {
            this.$scrollTop()
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';
</style>
