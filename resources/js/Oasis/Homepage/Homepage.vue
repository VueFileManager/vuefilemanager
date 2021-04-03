<template>
    <div id="page">
        <OasisNavigation v-if="true"/>
        <OasisHeader v-if="true"/>
        <OasisFeatures v-if="true"/>
        <OasisPricing v-if="true"/>
    </div>
</template>

<script>
    import OasisNavigation from '@/Oasis/Homepage/Components/OasisNavigation'
    import OasisFeatures from '@/Oasis/Homepage/Components/OasisFeatures'
    import OasisPricing from '@/Oasis/Homepage/Components/OasisPricing'
    import OasisHeader from '@/Oasis/Homepage/Components/OasisHeader'
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Homepage',
        components: {
            OasisNavigation,
            OasisFeatures,
            OasisPricing,
            OasisHeader,
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
