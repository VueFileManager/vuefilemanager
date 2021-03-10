<template>
    <div id="single-page">
        <div id="page-content">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            
            <div class="wrapper">
                <Spinner v-if="! loadedLanguages"/>
                <div v-if="loadedLanguages" class="side-content">
                    <PageHeader :can-back="true" :title="$router.currentRoute.meta.title"/>

                    <div class="languages-wrapper page-tab from-fixed-width">
                        <div class="language-label-wrapper">
                            <label class="language-label">Languages</label>
                        </div>
                        <div  class="all-language-wrapper">
                            <div @click="getLanguageStrings(language)" v-for="language in languages" :key="language.id">
                                <div class="language" >
                                    <label class="name" :class="{'active' :active === language.locale}">{{language.name}}</label>
                                    <x-icon v-if="language.locale !== 'en'" @click.stop="deleteLanguageConfirm(language)" class="icon" size="17"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <MobileActionButton @click.native="createLanguage" icon="plus" class="button-add-language">
                        Add Language
                    </MobileActionButton>
                </div>

                <Spinner v-if="! loadedStrings"/>
                <LanguageStrings v-if="loadedStrings" :language-strings="languagesStrings" />
            </div>
        </div>
    </div>
</template>

<script>
import LanguageStrings from '@/views/Admin/Languages/LanguageStrings'
import MobileHeader from '@/components/Mobile/MobileHeader'
import ButtonBase from '@/components/FilesView/ButtonBase'
import MobileActionButton from '@/components/FilesView/MobileActionButton'
import PageHeader from '@/components/Others/PageHeader'
import Spinner from '@/components/FilesView/Spinner'
import { PlusIcon, XIcon } from 'vue-feather-icons'
import { events } from '@/bus'


export default {
    name: 'Language',
    components: {
        MobileActionButton,
        LanguageStrings,
        MobileHeader,
        ButtonBase,
        PageHeader,
        PlusIcon,
        Spinner,
        XIcon
    },
    data () {
        return {
            active: undefined,
            languages:undefined,
            languagesStrings:undefined,
            loadedLanguages: false,
            loadedStrings:false
        }
    },
    methods: {
        deleteLanguageConfirm(language) {
              events.$emit('confirm:open', {
                    title: `Delete ${language.name} language?`,
                    message: 'Your language will be permanently deleted.',
                    buttonColor: 'danger-solid',
                    action: language
                })
        },
        deleteLanguage(language) {
            axios.delete(`/api/languages/${language.id}`)
                .then(() => { this.getLanguages() })
        },
        createLanguage() {
             events.$emit('popup:open', {name: 'create-language'})
        },
        getLanguages() {

            this.loadedStrings = false

            axios
				.get('/api/languages')
				.then((response) => {
                    this.languages = response.data
                    this.active = response.data[0].locale
				})
				.catch(() => Vue.prototype.$isSomethingWrong())
				.finally(() => {
					this.loadedLanguages = true
                    this.getLanguageStrings(this.languages[0])
				})
        },
        getLanguageStrings (language) {

            this.active = language.locale

            this.loadedStrings = false

            axios
				.get(`/api/languages/${language.id}/strings`)
				.then(response => {
					this.languagesStrings = response.data
				})
				.catch(() => Vue.prototype.$isSomethingWrong())
				.finally(() => {
					this.loadedStrings = true
				})
        }
    },
    mounted () {
        this.getLanguages()

        events.$on('add-language', () => {
            this.getLanguages()
        })

        events.$on('action:confirmed', language => {
            this.deleteLanguage(language)
        })

        events.$on('language-name:update', (language) => {

            let index = _.findIndex(this.languages, function(item) { return item.id === language.id })
            
            this.languages[index].name = language.name
        })
    },
    destroyed () {
        events.$off('action:confirmed')
    },
}
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

#single-page {
    height: 100%;

    #page-content {
        height: 100%;
    }
}

.wrapper {
    display: flex;
    height: 100%;
}

.side-content{
    flex: 0 0 225px;

    .button-add-language {
        margin-top: 30px;


        /deep/.content {
            display: flex;
            align-items: center;
            @include font-size(14);
            font-weight: 700;

            .icon {
                margin-right: 10px;
            }
        }
    }

    .languages-wrapper {
        margin-top: 70px;

        .language-label-wrapper {
            margin-bottom: 5px;

            .language-label {
                color: $text-muted;
                font-weight: 700;
                @include font-size(12);
                margin-top: 20px;
            }
        }
       
        .all-language-wrapper {
            
            .language {
                display: flex;
                align-items: center;
                padding: 12px 25px 12px 0px;
                cursor: pointer;

                &:hover {
                    .icon {
                        display: block;
                    }

                    .name {
                        color: $theme;
                    }
                }

                .name {
                    color: $text;
                    font-weight: 700;
                    @include font-size(13);
                }
                .icon {
                    display: none;
                    margin-left: auto;
                    cursor: pointer;
                }
                .active {
                    color: $theme !important;
                }
               
            }
            
        }
    }
}

//  @media only screen and (max-width: 1024px) {
//         .side-content {
//             flex: 0 0 205px;
//         }
//     }

//     @media only screen and (max-width: 690px) {
//         .side-content {
//             display: none;
//         }
//     }
</style>
