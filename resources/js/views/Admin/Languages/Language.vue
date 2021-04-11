<template>
    <div id="single-page">
        <div id="page-content">
            <MobileHeader :title="$t('routes_title.language')" />
            <PageHeader :title="$t('routes_title.language')" />

            <div v-if="languages" class="content-page">

                <!--Sidebar-->
                <div class="side-content">

                    <div class="sticky top-65">
                        <div class="languages-wrapper page-tab from-fixed-width">
                            <div class="language-label-wrapper">
                                <label class="language-label">{{ $t('languages') }}</label>
                            </div>

                            <!-- Languages -->
                            <div class="all-language-wrapper">
                                <div @click="getLanguage(language)" v-for="language in languages" :key="language.data.id" class="language group">
                                    <label class="name" :class="{'active': selectedLanguage && selectedLanguage.data.attributes.locale === language.data.attributes.locale}">
                                        <span class="active-text-theme group-hover-text-theme">{{ language.data.attributes.name }}</span>
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
                            {{ $t('add_language') }}
                        </MobileActionButton>
                    </div>
                </div>

                <!--Content-->
                <div class="form block-form content">

                    <!--Inline Search for mobile-->
                    <div class="block-wrapper sticky search-bar-wrapper">
                        <SearchInput v-model="query" @reset-query="query = ''" />
                    </div>

                    <!--Mobile language navigation-->
                    <div class="menu-list-wrapper horizontal">

                        <!--List of languages-->
                        <div @click="getLanguage(language)" v-for="language in languages" :key="language.data.id" :class="{'router-link-active': selectedLanguage && selectedLanguage.data.attributes.locale === language.data.attributes.locale}" class="menu-list-item link border-bottom-theme">
                            <div class="label text-theme">
                                {{ language.data.attributes.name }}
                            </div>
                        </div>

                        <!--Add new language-->
                        <div @click="createLanguage" class="menu-list-item link border-bottom-theme">
                            <div class="icon text-theme">
                                <plus-icon size="17" />
                            </div>
                        </div>
                    </div>

                    <div class="dynamic-content">

                        <Spinner v-if="! selectedLanguage" class="spinner" />

                        <div v-if="selectedLanguage">

                            <!--Disable content when user is searching translations-->
                            <div v-if="! isSearching">
                                <FormLabel icon="settings">
                                    {{ $t('language_settings') }}
                                </FormLabel>

                                <!--Language name-->
                                <div class="block-wrapper">
                                    <label>{{ $t('language_name') }}:</label>
                                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="App Description" rules="required" v-slot="{ errors }">
                                        <input @input="$updateText(`/admin/languages/${selectedLanguage.data.id}`, 'name', selectedLanguage.data.attributes.name)" v-model="selectedLanguage.data.attributes.name"
                                               :placeholder="$t('admin_settings.appearance.description_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
                                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                    </ValidationProvider>
                                </div>

                                <!--Set default language-->
                                <div class="block-wrapper">
                                    <div class="input-wrapper">
                                        <div class="inline-wrapper">
                                            <div class="switch-label">
                                                <label class="input-label">
                                                    {{ $t('set_as_default_language') }}:
                                                </label>
                                                <small class="input-help">
                                                    If this language is set as default, app will appear in this language for all users.
                                                </small>
                                            </div>
                                            <SwitchInput
                                                @input="setDefaultLanguage"
                                                class="switch"
                                                :class="{'disable-switch': selectedLanguage.data.attributes.locale === this.defaultLanguageLocale }"
                                                :state="selectedLanguage.data.attributes.locale === this.defaultLanguageLocale"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!--Translations-->
                                <FormLabel class="mt-70">
                                    {{ $t('edit_translations') }}
                                </FormLabel>

                                <InfoBox class="info-box">
                                    <p>Please preserve in your translations special string variables defined in format as <b class="text-theme">:variable</b> or <b class="text-theme">{variable}</b>.</p>
                                </InfoBox>
                            </div>

                            <!--Translation-->
                            <div class="block-wrapper" v-for="(translation, key) in translationList" :key="key">
                                <label> {{ referenceTranslations[key] }}:</label>
                                <ValidationProvider tag="div" class="input-wrapper" name="Language string" rules="required" v-slot="{ errors }">

                                    <!--Input-->
                                    <input
                                        v-if="selectedLanguage.data.attributes.translations[key].length < 80"
                                        type="text"
                                        :class="{'is-error': errors[0]}"
                                        class="focus-border-theme"
                                        @input="$updateText(`/admin/languages/${selectedLanguage.data.id}/strings`, key, selectedLanguage.data.attributes.translations[key])"
                                        v-model="selectedLanguage.data.attributes.translations[key]"
                                    />

                                    <!--Textarea-->
                                    <textarea
                                        v-if="selectedLanguage.data.attributes.translations[key].length > 80"
                                        v-model="selectedLanguage.data.attributes.translations[key]"
                                        @input="$updateText(`/admin/languages/${selectedLanguage.data.id}/strings`, key, selectedLanguage.data.attributes.translations[key])"
                                        rows="3"
                                        class="focus-border-theme"
                                        :class="{'is-error': errors[0]}"
                                    ></textarea>

                                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>
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
    import InfoBox from '@/components/Others/Forms/InfoBox'
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
            InfoBox,
            Spinner,
            XIcon
        },
        data() {
            return {
                searchedTranslationResults: undefined,
                referenceTranslations: undefined,

                defaultLanguageLocale: undefined,
                selectedLanguage: undefined,
                languages: undefined,
                query: '',
            }
        },
        watch: {
            query: debounce(function (val) {
                this.searchedTranslationResults = omitBy(this.selectedLanguage.data.attributes.translations, string => {
                    return !string.toLowerCase().includes(val.toLowerCase())
                })

                var container = document.getElementById('single-page')

                container.scrollTop = 0
            }, 300),
        },
        computed: {
            isSearching() {
                return this.searchedTranslationResults && this.query !== ''
            },
            translationList() {
                return this.isSearching
                    ? this.searchedTranslationResults
                    : this.selectedLanguage.data.attributes.translations
            }
        },
        methods: {
            setDefaultLanguage() {
                this.$updateText('/admin/settings', 'language', this.selectedLanguage.data.attributes.locale)
                this.defaultLanguageLocale = this.selectedLanguage.data.attributes.locale

                setTimeout(() => location.reload(), 500)
            },
            getLanguages() {
                axios
                    .get('/api/admin/languages')
                    .then(response => {
                        this.languages = response.data.data
                        this.referenceTranslations = response.data.meta.reference_translations
                        this.selectedLanguage = response.data.meta.current_language
                        this.defaultLanguageLocale = response.data.meta.current_language.data.attributes.locale
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

.dynamic-content {
    position: relative;

    .spinner {
        margin-top: 0 !important;
    }
}

.menu-list-wrapper.horizontal {
    display: none;
}

.search-bar-wrapper {
    background: white;
    padding: 0 10px 0 10px;
    margin: 0 -10px;
    top: 58px;
    z-index: 3;
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

.disable-switch {
    cursor: not-allowed;

    /deep/ .text-right {
        pointer-events: none;
    }
}

.info-box {
    margin-bottom: 32px;
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

@media only screen and (max-width: 790px) {

    .menu-list-wrapper.horizontal {
        position: sticky;
        top: 120px;
        display: flex;

        .menu-list-item {
            margin-top: 0;

            &:last-child {
                padding-left: 0;
            }
        }
    }

    .content-page {
        display: block;

        .side-content,
        .search-bar-wrapper.desktop {
            display: none;
        }

        .search-bar-wrapper {
            top: 60px;
            margin-bottom: 10px !important;
        }

        .info-box {
            margin-bottom: 30px;
        }
    }
}

@media only screen and (max-width: 1024px) {

    .search-bar-wrapper {
        top: 15px;
        z-index: 13;
    }
}


@media only screen and (max-width: 690px) {
    .menu-list-wrapper.horizontal {
        top: 95px;
    }

    .content-page {

        .search-bar-wrapper {
            top: 35px;
        }
    }
}

@media (prefers-color-scheme: dark) {

    .search-bar-wrapper {
        background: $dark_mode_background;
    }

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
