<template>
    <!-- Serach bar -->
    <div v-if="strings" class="language-strings-wrapper form">
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
                    placeholder="Search Language Strings..."
                />
            </div>
        </div>

        <Spinner v-if="!loadedStrings"/>

        <!-- Set Language as default switch -->
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

            <!-- Language name -->
            <div class="block-wrapper">
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

            <!-- Strings -->
            <FormLabel class="mt-70">Language Strings</FormLabel>
            <div v-if="!loadSearch || filteredStrings.length === 0 && !searchInput" class="spinner-wrapper">
                <Spinner class="spinner" />
            </div>

            <div v-if="loadSearch && filteredStrings.length > 0">
                <div class="block-wrapper string" v-for="(string,index) in filteredStrings" :key="index">
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

             <!-- Not Fount -->
            <div class="not-found-wrapper" v-if="loadSearch && filteredStrings.length === 0 && searchInput">
                <span class="not-found">Not Found</span>
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
    props: [ 'activeLanguage', 'setLanguage' ],
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
            return this.language.locale == this.setLanguage ? true : false
        },
    },
    data () {
        return {
            defaultStrings: [],
            filteredStrings: [],
            loadSearch: false,
            loadedStrings: false,
            strings: undefined,
            language: undefined,
            searchInput: '',
        }
    },
    watch: {
        activeLanguage () {
            this.getLanguageStrings(this.activeLanguage)

            this.searchInput = ''
        }
    },
    methods: {
        updateLanguageSetting() {
        
            this.$updateText('/settings', 'language', this.language.locale)

            events.$emit('language:set-as-default', this.language.locale) 

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
                `/languages/${this.language.id}/string`, `${key}`, this.strings[this.getIndex(key)].value,
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
        getLanguageStrings (language) {

            this.loadedStrings = false
            
            this.defaultStrings = []
            this.filteredStrings= []

            axios
				.get(`/api/languages/${language.id}/strings`)
				.then(response => {

					this.strings = response.data.translated_strings.language_strings
                    
                    this.language = {
                        'id': response.data.translated_strings.id,
                        'name': response.data.translated_strings.name,
                        'locale': response.data.translated_strings.locale,
                    }

                    //  Make from JSON object array of objects
                    for (const [key, value] of Object.entries(response.data.default_strings)) {
                        this.defaultStrings.push({
                            'key': key,
                            'value': value,
                        })
                    }

                    this.filterStrings()                    
				})
				.catch(() => Vue.prototype.$isSomethingWrong())
				.finally(() => {
					this.loadedStrings = true
				})
        }
    },
}
</script>

<style lang="scss" scoped>

@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';
@import '@assets/vue-file-manager/_forms';

.spinner-wrapper {
    position: relative;
    height: 50%;
    .spinner {
        top: 60% !important;
    }
}

.not-found-wrapper {
    display: flex;
    margin-top: 20%;

    .not-found {
        margin: auto;
        font-weight: 700;
        padding: 10px;
        border-radius: 8px;
        background: $light_background;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);
    }
}

.string:last-child {
    margin-bottom: 32px !important;
}

.block-form {
    padding: 1px;
    height: 100%;
}
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
    position: relative;

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
        font-weight: 700;
        @include font-size(16);
        width: 100%;
        height: 50px;
        min-width: 175px;
        transition: 0.15s all ease;
        border: 1px solid transparent;
        -webkit-appearance: none;

        &::placeholder {
            color: $light_text;
            @include font-size(14);
            font-weight: 700;
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
                color: $light_text;
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

    // .search-bar {

    //     input {
    //         min-width: initial;
    //         width: 100%;
    //         max-width: initial;
    //         padding: 9px 20px 9px 30px;

    //         &:focus {
    //             box-shadow: none;
    //         }
    //     }

    //     .icon {
    //         padding: 11px 15px 11px 0;
    //     }
    // }

}

@media (prefers-color-scheme: dark) {
    .search-bar {
        input {
            background: $dark_mode_background ;

            &::placeholder {
                color: $dark_mode_text_secondary;
            }
        }

        .icon {
            circle,
            line {
                color: $dark_mode_text_secondary !important;
            }
        }
    }
    .not-found-wrapper {
        .not-found {
            background: $dark_mode_foreground !important;
        }
    }
}

</style>
