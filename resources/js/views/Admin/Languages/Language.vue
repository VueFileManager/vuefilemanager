<template>
    <div>
        <!--Mobile language navigation-->
        <div v-if="languages" id="card-navigation" style="height: 62px" class="mb-24 block md:hidden">
            <div
                class="z-20 bg-white pt-3 sm:pt-5"
                :class="{
                    'fixed top-0 left-0 right-0 z-10 rounded-none bg-white bg-opacity-70 px-6 backdrop-blur-lg backdrop-filter dark:bg-dark-foreground':
                        fixedNav,
                    'card sticky top-0 z-10 block !pb-0 shadow-card md:hidden': !fixedNav,
                }"
            >
                <SearchInput v-model="query" @reset-query="query = ''" />

                <div class="flex items-center">
                    <!--List of languages-->
                    <div
                        @click="getLanguage(language)"
                        v-for="language in languages"
                        :key="language.data.id"
                        class="border-bottom-theme inline-block border-b-2 border-transparent px-4 py-5 text-sm font-bold text-gray-600"
                        :class="{
                            'text-theme router-link-active':
                                selectedLanguage &&
                                selectedLanguage.data.attributes.locale === language.data.attributes.locale,
                        }"
                    >
                        {{ language.data.attributes.name }}
                    </div>

                    <!--Add new language-->
                    <div @click="createLanguage" class="ml-2 cursor-pointer">
                        <plus-icon size="14" class="vue-feather text-gray-400" />
                    </div>
                </div>
            </div>
        </div>

        <div v-if="languages" class="flex md:space-x-6">
            <!--Sidebar-->
            <div class="hidden md:block">
                <div class="card sticky top-0 shadow-card">
                    <label class="mb-2 text-xs font-bold text-gray-400">
                        {{ $t('languages') }}
                    </label>

                    <!-- Languages -->
                    <div
                        @click="getLanguage(language)"
                        v-for="language in languages"
                        :key="language.data.id"
                        class="group flex cursor-pointer items-center justify-between py-2 pr-4"
                    >
                        <label
                            class="text-base font-bold cursor-pointer"
                            :class="{
                                'text-theme':
                                    selectedLanguage &&
                                    selectedLanguage.data.attributes.locale === language.data.attributes.locale,
                            }"
                        >
                            {{ language.data.attributes.name }}
                        </label>
                        <x-icon
                            v-if="language.data.attributes.locale !== 'en'"
                            @click.stop="deleteLanguage(language)"
                            class="opacity-0 group-hover:opacity-100"
                            size="12"
                        />
                    </div>

                    <!-- Create Language button -->
                    <MobileActionButton @click.native="createLanguage" icon="plus" class="mt-5 whitespace-nowrap">
                        {{ $t('add_language') }}
                    </MobileActionButton>
                </div>
            </div>

            <!--Content-->
            <div class="dynamic-content w-full">
                <Spinner v-if="!selectedLanguage" class="spinner" />

                <div v-if="selectedLanguage">
                    <!--Language Settings-->
                    <div v-if="!isSearching" class="card shadow-card">
                        <FormLabel icon="settings">
                            {{ $t('language_settings') }}
                        </FormLabel>

                        <ValidationProvider
                            tag="div"
                            mode="passive"
                            name="Language name"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <AppInputText :title="$t('language_name')" :error="errors[0]">
                                <input
                                    @input="
                                        $updateText(
                                            `/admin/languages/${selectedLanguage.data.id}`,
                                            'name',
                                            selectedLanguage.data.attributes.name
                                        )
                                    "
                                    v-model="selectedLanguage.data.attributes.name"
                                    :placeholder="$t('admin_settings.appearance.description_plac')"
                                    type="text"
                                    :class="{ '!border-rose-600': errors[0] }"
                                    class="focus-border-theme input-dark"
                                />
                            </AppInputText>
                        </ValidationProvider>

                        <AppInputSwitch
                            :title="$t('set_as_default_language')"
                            :description="
                                $t(
                                    'default_language_disclaimer'
                                )
                            "
                            :is-last="true"
                        >
                            <SwitchInput
                                @input="setDefaultLanguage"
                                class="switch"
                                :class="{
                                    'disable-switch':
                                        selectedLanguage.data.attributes.locale === this.defaultLanguageLocale,
                                }"
                                :state="selectedLanguage.data.attributes.locale === this.defaultLanguageLocale"
                            />
                        </AppInputSwitch>
                    </div>

                    <div v-if="selectedLanguage" class="card shadow-card">
                        <!--Translations-->
                        <FormLabel>
                            {{ $t('edit_translations') }}
                        </FormLabel>

                        <InfoBox>
                            <p>
                                Please preserve in your translations special string variables defined in format as
                                <b class="text-theme">:variable</b> or <b class="text-theme">{variable}</b>.
                            </p>
                        </InfoBox>

                        <!--Inline Search for mobile-->
                        <div class="sticky top-0 z-10 mb-8 hidden md:block">
                            <SearchInput v-model="query" @reset-query="query = ''" />
                        </div>

                        <ValidationProvider tag="div" name="Language string" rules="required" v-slot="{ errors }">
                            <AppInputText
                                :title="referenceTranslations[key]"
                                :error="errors[0]"
                                v-for="(translation, key) in translationList"
                                :key="key"
                            >
                                <textarea
                                    v-model="selectedLanguage.data.attributes.translations[key]"
                                    @input="
                                        $updateText(
                                            `/admin/languages/${selectedLanguage.data.id}/strings`,
                                            key,
                                            selectedLanguage.data.attributes.translations[key]
                                        )
                                    "
                                    :rows="selectedLanguage.data.attributes.translations[key].length >= 80 ? 3 : 1"
                                    class="focus-border-theme input-dark"
                                    :class="{ '!border-rose-600': errors[0] }"
                                ></textarea>
                            </AppInputText>
                        </ValidationProvider>
                    </div>
                </div>
            </div>
        </div>

        <Spinner v-if="!languages" />
    </div>
</template>

<script>
import AppInputSwitch from '../../../components/Forms/Layouts/AppInputSwitch'
import AppInputText from '../../../components/Forms/Layouts/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import MobileActionButton from '../../../components/UI/Buttons/MobileActionButton'
import SwitchInput from '../../../components/Inputs/SwitchInput'
import SearchInput from '../../../components/Inputs/SearchInput'
import FormLabel from '../../../components/UI/Labels/FormLabel'
import MobileHeader from '../../../components/Mobile/MobileHeader'
import ButtonBase from '../../../components/UI/Buttons/ButtonBase'
import InfoBox from '../../../components/UI/Others/InfoBox'
import Spinner from '../../../components/UI/Others/Spinner'
import { PlusIcon, XIcon } from 'vue-feather-icons'
import { debounce, omitBy } from 'lodash'
import { events } from '../../../bus'

export default {
    name: 'Language',
    components: {
        ValidationProvider,
        ValidationObserver,
        MobileActionButton,
        AppInputSwitch,
        AppInputText,
        MobileHeader,
        SearchInput,
        SwitchInput,
        ButtonBase,
        FormLabel,
        PlusIcon,
        InfoBox,
        Spinner,
        XIcon,
    },
    data() {
        return {
            searchedTranslationResults: undefined,
            referenceTranslations: undefined,

            defaultLanguageLocale: undefined,
            selectedLanguage: undefined,
            languages: undefined,
            query: '',

            fixedNav: false,
        }
    },
    watch: {
        query: debounce(function (val) {
            this.searchedTranslationResults = omitBy(this.selectedLanguage.data.attributes.translations, (string) => {
                return !string.toLowerCase().includes(val.toLowerCase())
            })

			// todo: fix container
            let container = document.getElementById('single-page')

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
        },
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
                .then((response) => {
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
                .then((response) => {
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
                    operation: 'delete-language',
                },
            })
        },
        createLanguage() {
            events.$emit('popup:open', { name: 'create-language' })
        },
    },
    mounted() {
        this.getLanguages()

        events.$on('reload:languages', () => this.getLanguages())

        events.$on('action:confirmed', (data) => {
            if (data.operation === 'delete-language')
                axios
                    .delete(`/api/admin/languages/${data.id}`)
                    .then(() => this.getLanguages())
                    .catch(() => this.$isSomethingWrong())
        })

        // Handle fixed mobile navigation
        window.addEventListener('scroll', () => {
            let card = document.getElementById('card-navigation')

            this.fixedNav = card.getBoundingClientRect().top < 0
        })
    },
    destroyed() {
        events.$off('action:confirmed')
    },
}
</script>
