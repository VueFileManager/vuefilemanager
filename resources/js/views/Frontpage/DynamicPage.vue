<template>
    <div class="landing-page">
        <!--Navigation-->
        <Navigation class="page-wrapper small" />

        <!--Page content-->
        <div class="page-wrapper small">
            <!--Headline-->
            <PageTitle class="headline" :title="page.data.attributes.title"></PageTitle>

            <!--Content-->
            <div class="page-content" v-html="page.data.attributes.content_formatted"></div>
        </div>

        <!--Footer-->
        <PageFooter />
    </div>
</template>

<script>
import PageTitle from '../../components/IndexPage/Components/PageTitle'
import PageFooter from '../../components/IndexPage/IndexPageFooter'
import Navigation from '../../components/IndexPage/IndexNavigation'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'DynamicPage',
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
            page: undefined,
        }
    },
    watch: {
        $route(to, from) {
            this.getPage()
        },
    },
    methods: {
        getPage() {
            axios.get('/api/page/' + this.$route.params.slug).then((response) => {
                this.page = response.data

                this.$scrollTop()
            })
        },
    },
    created() {
        this.getPage()
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/landing-page';
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.headline {
    padding-top: 70px;
    padding-bottom: 50px;
}

.page-content {
    /deep/ p {
        @include font-size(20);
        font-weight: 500;
        line-height: 1.65;
        padding-bottom: 30px;
    }
}

.dark {
}

@media only screen and (max-width: 960px) {
    .headline {
        padding-top: 50px;
        padding-bottom: 30px;
    }
}
</style>
