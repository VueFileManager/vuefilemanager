<template>
    <div class="language-strings-wrapper">
        <div class="search-bar-wrapper">
            <div class="search-bar">
                <div class="icon" >
                    <search-icon size="19"></search-icon>
                </div>
                <!-- <div class="icon">
                    <x-icon class="pointer" size="19"></x-icon>
                </div> -->
                <input
                    
                        class="query"
                        type="text"
                        name="query"
                        :placeholder="$t('inputs.placeholder_search_files')"
                />
            </div>
        </div>
        <div class="form block-form">
            <div>
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
                                
                                    class="switch"
                            />
                        </div>
                    </div>
                </div>

                <div class="block-wrapper">
                    <label> Language Name:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Language Name" rules="required" v-slot="{ errors }">
                        <input type="text"
                               :class="{'is-error': errors[0]}"
                        />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                   <label> Language Slug:</label>
                   <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Language Slug" rules="required" v-slot="{ errors }">
                        <input type="text"
                               :class="{'is-error': errors[0]}"
                        />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
            </div>

            <div>
                <FormLabel class="mt-70">Language Strings</FormLabel>
                <div class="block-wrapper" v-for="(string, index) in languages.strings.language_strings" :key="string.id">
                    <label> {{string.value}}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Language string" rules="required" v-slot="{ errors }">
                        <input type="text"
                                @input="updateString(string.value)"
                                v-model="languages.strings.language_strings[index].value"
                               :class="{'is-error': errors[0]}"
                        />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
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
import { mapGetters } from 'vuex'

export default {
    name: 'LanguageStrings',
    components: {
        ValidationProvider, 
        ValidationObserver,
        SwitchInput,
        FormLabel,
        SearchIcon,
        XIcon,
    },
    computed: {
       ...mapGetters(['languages'])
    },
    methods: {
        updateString (value) {
            console.log(value)
        }
    }
}
</script>

<style lang="scss" scoped>

@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';
@import '@assets/vue-file-manager/_forms';


.language-strings-wrapper {
    width: 100%;
    display: flex;
    flex-direction: column;    
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
