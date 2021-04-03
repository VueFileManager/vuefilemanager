<template>
    <h1>hello oasis</h1>
</template>

<script>
    //import HeroScreenshot from '@/components/Index/IndexHeroScreenshot'
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Homepage',
        components: {

        },
        computed: {
            ...mapGetters([
                'config'
            ]),
        },
        data() {
            return {
                isLoading: false,
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

</style>
