<template>
    <div id="single-page">
        <div id="page-content">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            
            <div class="wrapper">
                <Spinner v-if="! loadedLanguages"/>
                <div v-if="loadedLanguages" class="side-content">
                    <PageHeader :can-back="true" :title="$router.currentRoute.meta.title"/>

                    <div class="languages-wrapper">
                        <label class="language-label">Langueages</label>
                        <div  class="all-language-wrapper">
                            <div @click="getLanguageStrings(language)"  class="language" v-for="language in languages.allLanguages" :key="language.id">
                                {{language.name}}
                            </div>
                        </div>
                    </div>
                </div>

                <Spinner v-if="! loadedStrings"/>
                <LanguageStrings v-if="loadedStrings"/>
            </div>
        </div>
    </div>
</template>

<script>
import LanguageStrings from '@/views/Admin/Languages/LanguageStrings'
import MobileHeader from '@/components/Mobile/MobileHeader'
import PageHeader from '@/components/Others/PageHeader'
import Spinner from '@/components/FilesView/Spinner'
import { mapGetters } from 'vuex'

export default {
    name: 'Language',
    components: {
        LanguageStrings,
        MobileHeader,
        PageHeader,
        Spinner
    },
    computed: {
        ...mapGetters(['languages'])
    },
    data () {
        return {
            loadedLanguages: false,
            loadedStrings:false
        }
    },
    methods: {
        getLanguageStrings (language) {
            this.loadedStrings = false

           this.$store.dispatch('getLanguageStrings', language).then((loaded) => this.loadedStrings = loaded)
        }
    },
    mounted () {
        this.$store.dispatch('getLanguages').then((loaded) => {

            this.loadedLanguages = loaded

            this.getLanguageStrings(this.languages.allLanguages[0])
        })
    }
}
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

.wrapper {
    display: flex;
}

.side-content{
    width: 225px;

    .languages-wrapper {
        margin-top: 70px;

        .language-label {
            color: $text-muted;
            font-weight: 700;
            @include font-size(12);
        }
        .all-language-wrapper {
            
            .language {
                color: $text;
                font-weight: 700;
                @include font-size(13);
                margin-top: 20px;
            }
        }
    }
}
</style>
