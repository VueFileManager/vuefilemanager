<template>
    <div id="single-page">
        <div id="page-content">
            <MobileHeader :title="$t($router.currentRoute.meta.title)" />
            
            <div class="wrapper">
                <Spinner v-if="! isLoadedLanguages" />
                <div v-if="isLoadedLanguages" class="side-content">
                    <PageHeader :can-back="true" :title="$t($router.currentRoute.meta.title)" />

                    <div class="languages-wrapper page-tab from-fixed-width">
                        <div class="language-label-wrapper">
                            <label class="language-label">Languages</label>
                        </div>

                        <!-- Languages -->
                        <div class="all-language-wrapper">
                            <div @click="openLanguage(language)" v-for="language in languages" :key="language.id">
                                <div class="language">
                                    <label class="name" :class="{'active' :activeLanguage.locale === language.locale}">
                                        {{ language.name }}
                                    </label>
                                    <x-icon v-if="language.locale !== 'en' && language.locale !== current_language"
                                            @click.stop="deleteLanguageConfirm(language)"
                                            class="icon" size="17" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <MobileActionButton @click.native="createLanguage" icon="plus" class="button-add-language">
                        Add Language
                    </MobileActionButton>
                </div>

                <!-- Strings -->
                <!--<LanguageStrings :active-language="activeLanguage" :set-language="current_language" />-->
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
import {PlusIcon, XIcon} from 'vue-feather-icons'
import {events} from '@/bus'

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
    data() {
        return {
            languages: undefined,

            activeLanguage: undefined,
            languagesStrings: undefined,
            current_language: undefined,
            isLoadedLanguages: false,
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
            axios.delete(`/api/admin/languages/${language.id}`)
                .then(() => {
                    this.getLanguages()
                })
        },
        createLanguage() {
            events.$emit('popup:open', {name: 'create-language'})
        },
        getLanguages() {
            axios
                .get('/api/admin/languages')
                .then(response => {
                    this.languages = response.data.data

                    this.activeLanguage = response.data.languages[0]

                    this.current_language = response.data.meta.current_language
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
                .finally(() => {
                    this.isLoadedLanguages = true
                })
        },
        openLanguage(language) {
            this.activeLanguage = language
        }
    },
    mounted() {
        this.getLanguages()

        events.$on('add-language', () => {
            this.getLanguages()
        })

        events.$on('action:confirmed', language => {
            this.deleteLanguage(language)
        })

        events.$on('language-name:update', (language) => {

            let index = _.findIndex(this.languages, function (item) {
                return item.id === language.id
            })

            this.languages[index].name = language.name
        })

        events.$on('language:set-as-default', (locale) => {
            this.current_language = locale
        })
    },
    destroyed() {
        events.$off('action:confirmed')
    },
}
</script>

<style lang="scss" scoped>
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';

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

.side-content {
    flex: 0 0 225px;

    .button-add-language {
        margin-top: 30px;


        /deep/ .content {
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
                color: $light_text;
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
                        color: $theme !important;
                    }
                }

                .name {
                    color: $text;
                    font-weight: 700;
                    @include font-size(13);
                    cursor: pointer;
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

@media only screen and (max-width: 1024px) {
    .wrapper {
        flex-direction: column;

        .side-content {
            margin-bottom: 70px;
        }

        .languages-wrapper {
            margin-top: 0px;
        }
    }
}

@media only screen and (max-width: 690px) {
    .side-content {
        margin-bottom: 35px !important;
        flex: none;
    }
}

@media (prefers-color-scheme: dark) {

    .language {

        .name {
            color: $dark_mode_text_primary !important;
        }
    }
    .language-label {
        color: $dark_mode_text_secondary !important;
    }
}
</style>
