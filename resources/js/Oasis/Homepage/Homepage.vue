<template>
    <div id="page">
        <OasisHeader v-if="false"/>
        <OasisFeatures v-if="true"/>
    </div>
</template>

<script>
    import OasisHeader from '@/Oasis/Homepage/Components/OasisHeader'
    import OasisFeatures from '@/Oasis/Homepage/Components/OasisFeatures'
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Homepage',
        components: {
            OasisFeatures,
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
