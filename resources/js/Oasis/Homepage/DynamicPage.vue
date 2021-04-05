<template>
    <div class="landing-page">

        <!--Navigation-->
        <OasisNavigation class="page-wrapper"/>

        <!--Page content-->
        <div v-if="page" class="container small">

            <!--Headline-->
            <PageTitle
                    class="headline"
                    :title="page.data.attributes.title"
            />

            <!--Content-->
            <div class="page-content" v-html="page.data.attributes.content_formatted"></div>
        </div>

        <!--Footer-->
        <OasisFooter/>
    </div>
</template>

<script>
    import OasisFooter from '@/Oasis/Homepage/Components/OasisFooter'
    import PageTitle from '@/components/Index/Components/PageTitle'
    import OasisNavigation from '@/Oasis/Homepage/Components/OasisNavigation'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'DynamicPage',
        components: {
            OasisNavigation,
            OasisFooter,
            PageTitle,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: false,
                page: undefined,
            }
        },
        watch: {
            $route(to, from) {
                this.getPage()
            }
        },
        methods: {
            getPage() {
                axios.get('/api/page/' + this.$route.params.slug)
                    .then(response => {
                        this.page = response.data

                        this.$scrollTop()
                    })
            }
        },
        created() {
            this.getPage()
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';

    .headline {
        padding-top: 70px;
        padding-bottom: 50px;
    }

    .small {
        margin-top: 50px;
        max-width: 960px;
    }

    .page-content {

        /deep/ p {
            @include font-size(20);
            font-weight: 500;
            line-height: 1.65;
            padding-bottom: 30px;
        }
    }

    @media (prefers-color-scheme: dark) {

    }

    @media only screen and (max-width: 960px) {
        .headline {
            padding-top: 50px;
            padding-bottom: 30px;
        }
    }
</style>
