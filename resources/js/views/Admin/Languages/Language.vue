<template>
    <div id="single-page">
        <div id="page-content">
            <MobileHeader title="Languages" />
            <PageHeader title="Languages" />

            <div v-if="languages" class="content-page">

                <!--Sidebar-->
                <div class="side-content">

                    <div class="sticky top-65">
                        <div class="languages-wrapper page-tab from-fixed-width">
                            <div class="language-label-wrapper">
                                <label class="language-label">Languages</label>
                            </div>

                            <!-- Languages -->
                            <div class="all-language-wrapper">
                                <div @click="getLanguage(language)" v-for="language in languages" :key="language.data.id" class="language">
                                    <label class="name" :class="{'active': selectedLanguage && selectedLanguage.data.attributes.locale === language.data.attributes.locale}">
                                        {{ language.data.attributes.name }}
                                    </label>
                                    <x-icon
                                        v-if="language.data.attributes.locale !== 'en'"
                                        @click.stop="deleteLanguage(language)"
                                        class="icon"
                                        size="17"
                                    />
                                </div>
                            </div>
                        </div>

                        <MobileActionButton @click.native="createLanguage" icon="plus" class="button-add-language">
                            Add Language
                        </MobileActionButton>
                    </div>
                </div>

                <!--Content-->
                <div class="form block-form content">

                    <div v-if="! selectedLanguage">
                        <Spinner />
                    </div>

                    <div v-if="selectedLanguage">
                        <FormLabel icon="settings">
                            Language Settings
                        </FormLabel>

                        <!--Set default language-->
                        <div class="block-wrapper">
                            <div class="input-wrapper">
                                <div class="inline-wrapper">
                                    <div class="switch-label">
                                        <label class="input-label">
                                            Set as Default Language:
                                        </label>
                                    </div>
                                    <SwitchInput
                                        @input="$updateText(`/admin/languages/${selectedLanguage.data.id}`, 'language', selectedLanguage.data.attributes.locale)"
                                        class="switch"
                                        :class="{'disable-switch': selectedLanguage.data.attributes.locale !==  }"
                                    />
                                </div>
                            </div>
                        </div>

                        <!--Language name-->
                        <div class="block-wrapper">
                            <label>Language Name:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                                <input @input="$updateText(`/admin/languages/${selectedLanguage.data.id}`, 'name', selectedLanguage.data.attributes.name)" v-model="selectedLanguage.data.attributes.name"
                                       :placeholder="$t('admin_settings.appearance.description_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <!--Translations-->
                        <FormLabel class="mt-70">
                            Edit Translations
                        </FormLabel>

                        <!--Inline Search-->
                        <div class="block-wrapper sticky top-50 search-bar-wrapper">
                            <SearchInput v-model="query" @reset-query="query = ''" />
                        </div>

                        <div class="block-wrapper" v-for="(translation, key) in translationList" :key="key">
                            <label> {{ defaultTranslations[key] }}:</label>
                            <ValidationProvider tag="div" class="input-wrapper" name="Language string" rules="required" v-slot="{ errors }">
                                <input type="text"
                                       :class="{'is-error': errors[0]}"
                                       class="focus-border-theme"
                                       @input="$updateText(`/admin/languages/${selectedLanguage.data.id}/strings`, key, selectedLanguage.data.attributes.translations[key])"
                                       v-model="selectedLanguage.data.attributes.translations[key]"
                                />
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                </div>
            </div>

            <Spinner v-if="! languages" />
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import SearchInput from '@/components/Others/Forms/SearchInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'
    import {PlusIcon, XIcon} from 'vue-feather-icons'
    import {debounce, omitBy} from 'lodash'
    import {events} from '@/bus'

    export default {
        name: 'Language',
        components: {
            ValidationProvider,
            ValidationObserver,
            MobileActionButton,
            MobileHeader,
            SearchInput,
            SwitchInput,
            ButtonBase,
            PageHeader,
            FormLabel,
            PlusIcon,
            Spinner,
            XIcon
        },
        data() {
            return {
                defaultTranslations: undefined,
                selectedLanguage: undefined,
                occurrences: undefined,
                languages: undefined,
                query: '',
            }
        },
        watch: {
            query: debounce(function (val) {
                this.occurrences = omitBy(this.selectedLanguage.data.attributes.translations, (string, key) => {
                    return !string.toLowerCase().includes(val.toLowerCase())
                })
            }, 300),
        },
        computed: {
            translationList() {
                return this.occurrences && this.query !== ''
                    ? this.occurrences
                    : this.selectedLanguage.data.attributes.translations
            }
        },
        methods: {
            getLanguages() {
                axios
                    .get('/api/admin/languages')
                    .then(response => {
                        this.languages = response.data.data
                        this.defaultTranslations = response.data.meta.default_translations
                        this.selectedLanguage = response.data.meta.current_language
                    })
                    .catch(() => {
                        this.$isSomethingWrong()
                    })
            },
            getLanguage(language) {
                this.selectedLanguage = undefined

                axios
                    .get(`/api/admin/languages/${language.data.id}`)
                    .then(response => {
                        this.selectedLanguage = response.data
                    })
                    .catch(() => {
                        this.$isSomethingWrong()
                    })
            },
            deleteLanguage(language) {
                events.$emit('confirm:open', {
                    title: `Delete "${language.data.attributes.name}" language?`,
                    message: 'Your language will be permanently deleted.',
                    buttonColor: 'danger-solid',
                    action: {
                        id: language.data.id,
                        operation: 'delete-language'
                    }
                })
            },
            createLanguage() {
                events.$emit('popup:open', {name: 'create-language'})
            },
        },
        mounted() {
            this.getLanguages()

            events.$on('reload:languages', () => this.getLanguages())

            events.$on('action:confirmed', data => {

                if (data.operation === 'delete-language')
                    axios.delete(`/api/admin/languages/${data.id}`)
                        .then(() => this.getLanguages())
                        .catch(() => this.$isSomethingWrong())
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
@import '@assets/vuefilemanager/_forms';
@import '@assets/vuefilemanager/_vuewind';

.search-bar-wrapper {
    background: white;
    padding: 10px 10px 0 10px;
    margin: 0 -10px;
}

.content-page {
    display: flex;
    max-width: 1000px;
    margin: 20px auto 0;

    .content {
        width: 100%;
        position: relative;
    }
}

.side-content {
    flex: 0 0 225px;

    .button-add-language {
        margin-top: 30px;
    }

    .languages-wrapper {

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
