<template>
    <div v-if="strings" class="language-strings-wrapper">
        <div class="search-bar-wrapper">
            <div class="search-bar">
                <div v-if="!searchInput" class="icon" >
                    <search-icon size="19"></search-icon>
                </div>
                <div @click="resetInput" v-if="searchInput" class="icon">
                    <x-icon class="pointer" size="19"></x-icon>
                </div>
                <input
                    v-model="searchInput"
                    @input="searchStrings"
                    class="query"
                    type="text"
                    name="searchInput"
                    placeholder="Search Language Strings"
                />
            </div>
        </div>

        <Spinner v-if="!loadedStrings"/>
        <div v-if="loadedStrings" class="form block-form">
            <FormLabel class="mt-70" icon="settings">Language Settings</FormLabel>
            <div class="block-wrapper">
                <div class="input-wrapper">
                    <div class="inline-wrapper">
                        <div class="switch-label">
                            <label class="input-label">
                                Set as Default Language:
                            </label>
                        </div>
                        <SwitchInput
                            @input="updateLanguageSetting"
                            class="switch"
                            :class="{'disable-switch': languageSettingHandle }"
                            :state="languageSettingHandle"
                        />
                    </div>
                </div>
            </div>

            <div v-if="language.name" class="block-wrapper">
                <label> Language Name:</label>
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Language Name" rules="required" v-slot="{ errors }">
                    <input  type="text"
                            v-model="language.name"
                            @input="updateName"
                            :class="{'is-error': errors[0]}"
                    />
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>

            <FormLabel class="mt-70">Language Strings</FormLabel>
            <div class="block-wrapper">
                <Spinner v-if="!loadSearch"/>
                <div v-if="loadSearch">
                    <div  class="block-wrapper" v-for="(string,index) in filteredStrings" :key="index">
                        <label> {{string.value}}:</label>
                        <ValidationProvider tag="div" class="input-wrapper" name="Language string" rules="required" v-slot="{ errors }">
                            <input  type="text"
                                    :class="{'is-error': errors[0]}"
                                    @input="updateString(string.key)"
                                    v-model="strings[getIndex(string.key)].value"
                            />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import SwitchInput from '@/components/Others/Forms/SwitchInput'
import FormLabel from '@/components/Others/Forms/FormLabel'
import { SearchIcon, XIcon } from 'vue-feather-icons'
import Spinner from '@/components/FilesView/Spinner'
import { events } from '@/bus'
import lodash from 'lodash'


export default {
    name: 'LanguageStrings',
    props: [ 'languageStrings', 'loadedStrings'],
    components: {
        ValidationProvider, 
        ValidationObserver,
        SwitchInput,
        SearchIcon,
        FormLabel,
        Spinner,
        XIcon,
    },
    computed: {
        languageSettingHandle() {
            return this.language.locale == this.languageSetting ? true : false
        },
    },
    data () {
        return {
            defaultStrings: [],
            filteredStrings: [],
            loadSearch: false,
            strings: undefined,
            language: undefined,
            searchInput: '',
            languageSetting: undefined
        }
    },
    watch: {
        loadedStrings () {
            if(this.loadedStrings)
                this.loadData()
        }
    },
    methods: {
        updateLanguageSetting() {
        
            this.$updateText('/settings', 'language', this.language.locale)

            this.languageSetting = this.language.locale

            this.$loadLanguage(this.language.locale)
        },
        resetInput(){

            this.searchInput = ''

            this.searchStrings()
        },
        getIndex(key){

            if(this.strings)
               return _.findIndex(this.strings, function(string) { return string.key === key })
        },
        updateName() {

            this.$updateText(`/languages/${this.language.id}`, 'name', this.language.name)

            events.$emit('language-name:update', this.language)
        },
        updateString (key) {

             // Return if the input is empty
             if(! this.strings[this.getIndex(key)].value) return

             this.$updateText(
                `/languages/${this.languageStrings.translated_strings.id}/string`, `${key}`, this.strings[this.getIndex(key)].value,
            )
        },
        searchStrings() {

            this.loadSearch = false

            this.filteredStrings = []

            this.filterStrings()

        },
        filterStrings: _.debounce(function ()  {

            this.filteredStrings = this.defaultStrings.filter(string => string.value.toLowerCase().includes( this.searchInput.toLowerCase() ))

            this.loadSearch = true

        }, 200),
        loadData() {

            if(this.languageStrings.translated_strings) {

                this.strings = this.languageStrings.translated_strings.language_strings

                // Make from JSON object array of objects
                for (const [key, value] of Object.entries(this.languageStrings.default_strings)) {
                    this.defaultStrings.push({
                        'key': key,
                        'value': value,
                    })
                }

                this.language = {
                    'id': this.languageStrings.translated_strings.id,
                    'name': this.languageStrings.translated_strings.name,
                    'locale': this.languageStrings.translated_strings.locale,
                }

                this.languageSetting = this.languageStrings.language_setting

                this.filterStrings()
            }
        }
    },
}
</script>

<style lang="scss" scoped>

@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';
@import '@assets/vue-file-manager/_forms';

.disable-switch {
   cursor: not-allowed;
   
   /deep/.text-right {
       pointer-events: none;
   }
}

.language-strings-wrapper {
    width: 100%;
    height: 100%;
    margin: 0 auto;
    display: flex;
    flex-direction: column;

    .block-form{
        overflow-y: scroll;
        overflow-x: hidden;
    }
}

.search-bar-wrapper {
    padding: 7px 0px;
}

.search-bar {
    position: relative;
    width: 100%;
   
    border-radius: 8px;

    input {
        background: $light-background;
        border-radius: 8px;
        outline: 0;
        padding: 9px 20px 9px 43px;
        font-weight: 400;
        @include font-size(16);
        width: 100%;
        height: 50px;
        min-width: 175px;
        transition: 0.15s all ease;
        border: 1px solid transparent;
        -webkit-appearance: none;

        &::placeholder {
            color: $text-muted;
            @include font-size(14);
            font-weight: 500;
        }

        &:focus {
            border: 1px solid $theme;
            box-shadow: 0 0 7px rgba($theme, 0.3);
        }

        &:focus + .icon {
            path {
                fill: $theme;
            }
        }
    }

    .icon {
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        padding: 11px 15px;
        display: flex;
        align-items: center;

            circle,
            line {
                color: $text-muted;
            }

        .pointer {
            cursor: pointer;
        }
    }
}

// @media only screen and (max-width: 1024px) {

//     .search-bar input {
//         max-width: 190px;
//         padding-right: 0;
//     }
// }

@media only screen and (max-width: 690px) {

    .search-bar {

        input {
            min-width: initial;
            width: 100%;
            max-width: initial;
            padding: 9px 20px 9px 30px;

            &:focus {
                border: 1px solid transparent;
                box-shadow: none;
            }
        }

        .icon {
            padding: 11px 15px 11px 0;
        }
    }

}

@media (prefers-color-scheme: dark) {
    .search-bar {
        input {
            border-color: transparent;
            color: $dark_mode_text_primary;

            &::placeholder {
                color: $dark_mode_text_secondary;
            }
        }

        .icon svg path {
            fill: $dark_mode_text_secondary;
        }
    }
}

</style>
