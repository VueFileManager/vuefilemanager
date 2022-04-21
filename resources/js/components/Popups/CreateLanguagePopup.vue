<template>
    <PopupWrapper name="create-language">
        <!--Title-->
        <PopupHeader :title="$t('create_language')" icon="edit" />

        <!--Content-->
        <PopupContent class="!overflow-initial">
            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent="createLanguage" ref="createForm" v-slot="{ invalid }" tag="form">
                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="Language Locale"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <AppInputText :title="$t('select_locale')" :error="errors[0]">
                        <SelectInput
                            v-model="form.locale"
                            :options="locales"
                            :placeholder="$t('select_language_locale')"
                            :isError="errors[0]"
                        />
                    </AppInputText>
                </ValidationProvider>
                <ValidationProvider tag="div" mode="passive" name="Language Name" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('locale_name')" :error="errors[0]" :is-last="true">
                        <input
                            v-model="form.name"
                            :class="{ '!border-rose-600': errors[0] }"
                            type="text"
                            ref="input"
                            class="focus-border-theme input-dark"
                            :placeholder="$t('type_language_name')"
                        />
                    </AppInputText>
                </ValidationProvider>
            </ValidationObserver>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary">
                {{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase
                class="w-full"
                @click.native="createLanguage"
                button-style="theme"
                :loading="isLoading"
                :disabled="isLoading"
            >
                {{ $t('create_language') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import AppInputText from '../Forms/Layouts/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from './Components/PopupWrapper'
import PopupActions from './Components/PopupActions'
import PopupContent from './Components/PopupContent'
import PopupHeader from './Components/PopupHeader'
import SelectInput from '../Inputs/SelectInput'
import ButtonBase from '../UI/Buttons/ButtonBase'
import { required } from 'vee-validate/dist/rules'
import { events } from '../../bus'
import axios from 'axios'

export default {
    name: 'CreateLanguagePopup',
    components: {
        ValidationProvider,
        ValidationObserver,
        AppInputText,
        PopupWrapper,
        PopupActions,
        PopupContent,
        PopupHeader,
        SelectInput,
        ButtonBase,
        required,
    },
    data() {
        return {
            form: {
                name: undefined,
                locale: undefined,
            },
            isLoading: false,
            locales: [
                {
                    value: 'ab',
                    label: 'Abkhaz',
                },
                {
                    value: 'aa',
                    label: 'Afar',
                },
                {
                    value: 'af',
                    label: 'Afrikaans',
                },
                {
                    value: 'ak',
                    label: 'Akan',
                },
                {
                    value: 'sq',
                    label: 'Albanian',
                },
                {
                    value: 'am',
                    label: 'Amharic',
                },
                {
                    value: 'ar',
                    label: 'Arabic',
                },
                {
                    value: 'an',
                    label: 'Aragonese',
                },
                {
                    value: 'hy',
                    label: 'Armenian',
                },
                {
                    value: 'as',
                    label: 'Assamese',
                },
                {
                    value: 'av',
                    label: 'Avaric',
                },
                {
                    value: 'ae',
                    label: 'Avestan',
                },
                {
                    value: 'ay',
                    label: 'Aymara',
                },
                {
                    value: 'az',
                    label: 'Azerbaijani',
                },
                {
                    value: 'bm',
                    label: 'Bambara',
                },
                {
                    value: 'ba',
                    label: 'Bashkir',
                },
                {
                    value: 'eu',
                    label: 'Basque',
                },
                {
                    value: 'be',
                    label: 'Belarusian',
                },
                {
                    value: 'bn',
                    label: 'Bengali; Bangla',
                },
                {
                    value: 'bh',
                    label: 'Bihari',
                },
                {
                    value: 'bi',
                    label: 'Bislama',
                },
                {
                    value: 'bs',
                    label: 'Bosnian',
                },
                {
                    value: 'br',
                    label: 'Breton',
                },
                {
                    value: 'bg',
                    label: 'Bulgarian',
                },
                {
                    value: 'my',
                    label: 'Burmese',
                },
                {
                    value: 'ca',
                    label: 'Catalan; Valencian',
                },
                {
                    value: 'ch',
                    label: 'Chamorro',
                },
                {
                    value: 'ce',
                    label: 'Chechen',
                },
                {
                    value: 'ny',
                    label: 'Chichewa; Chewa; Nyanja',
                },
                {
                    value: 'zh',
                    label: 'Chinese',
                },
                {
                    value: 'cv',
                    label: 'Chuvash',
                },
                {
                    value: 'kw',
                    label: 'Cornish',
                },
                {
                    value: 'co',
                    label: 'Corsican',
                },
                {
                    value: 'cr',
                    label: 'Cree',
                },
                {
                    value: 'hr',
                    label: 'Croatian',
                },
                {
                    value: 'cs',
                    label: 'Czech',
                },
                {
                    value: 'da',
                    label: 'Danish',
                },
                {
                    value: 'dv',
                    label: 'Divehi; Dhivehi; Maldivian;',
                },
                {
                    value: 'nl',
                    label: 'Dutch',
                },
                {
                    value: 'dz',
                    label: 'Dzongkha',
                },
                {
                    value: 'en',
                    label: 'English',
                },
                {
                    value: 'eo',
                    label: 'Esperanto',
                },
                {
                    value: 'et',
                    label: 'Estonian',
                },
                {
                    value: 'ee',
                    label: 'Ewe',
                },
                {
                    value: 'fo',
                    label: 'Faroese',
                },
                {
                    value: 'fj',
                    label: 'Fijian',
                },
                {
                    value: 'fi',
                    label: 'Finnish',
                },
                {
                    value: 'fr',
                    label: 'French',
                },
                {
                    value: 'ff',
                    label: 'Fula; Fulah; Pulaar; Pular',
                },
                {
                    value: 'gl',
                    label: 'Galician',
                },
                {
                    value: 'lg',
                    label: 'Ganda',
                },
                {
                    value: 'ka',
                    label: 'Georgian',
                },
                {
                    value: 'de',
                    label: 'German',
                },
                {
                    value: 'el',
                    label: 'Greek, Modern',
                },
                {
                    value: 'gn',
                    label: 'GuaranÃ­',
                },
                {
                    value: 'gu',
                    label: 'Gujarati',
                },
                {
                    value: 'ht',
                    label: 'Haitian; Haitian Creole',
                },
                {
                    value: 'ha',
                    label: 'Hausa',
                },
                {
                    value: 'he',
                    label: 'Hebrew (modern)',
                },
                {
                    value: 'hz',
                    label: 'Herero',
                },
                {
                    value: 'hi',
                    label: 'Hindi',
                },
                {
                    value: 'ho',
                    label: 'Hiri Motu',
                },
                {
                    value: 'hu',
                    label: 'Hungarian',
                },
                {
                    value: 'ia',
                    label: 'Interlingua',
                },
                {
                    value: 'id',
                    label: 'Indonesian',
                },
                {
                    value: 'ie',
                    label: 'Interlingue',
                },
                {
                    value: 'ga',
                    label: 'Irish',
                },
                {
                    value: 'ig',
                    label: 'Igbo',
                },
                {
                    value: 'ik',
                    label: 'Inupiaq',
                },
                {
                    value: 'io',
                    label: 'Ido',
                },
                {
                    value: 'is',
                    label: 'Icelandic',
                },
                {
                    value: 'it',
                    label: 'Italian',
                },
                {
                    value: 'iu',
                    label: 'Inuktitut',
                },
                {
                    value: 'ja',
                    label: 'Japanese',
                },
                {
                    value: 'jv',
                    label: 'Javanese',
                },
                {
                    value: 'kl',
                    label: 'Kalaallisut, Greenlandic',
                },
                {
                    value: 'kn',
                    label: 'Kannada',
                },
                {
                    value: 'kr',
                    label: 'Kanuri',
                },
                {
                    value: 'ks',
                    label: 'Kashmiri',
                },
                {
                    value: 'kk',
                    label: 'Kazakh',
                },
                {
                    value: 'km',
                    label: 'Khmer',
                },
                {
                    value: 'ki',
                    label: 'Kikuyu, Gikuyu',
                },
                {
                    value: 'rw',
                    label: 'Kinyarwanda',
                },
                {
                    value: 'rn',
                    label: 'Kirundi',
                },
                {
                    value: 'ky',
                    label: 'Kyrgyz',
                },
                {
                    value: 'kv',
                    label: 'Komi',
                },
                {
                    value: 'kg',
                    label: 'Kongo',
                },
                {
                    value: 'ko',
                    label: 'Korean',
                },
                {
                    value: 'ku',
                    label: 'Kurdish',
                },
                {
                    value: 'kj',
                    label: 'Kwanyama, Kuanyama',
                },
                {
                    value: 'la',
                    label: 'Latin',
                },
                {
                    value: 'lb',
                    label: 'Luxembourgish, Letzeburgesch',
                },
                {
                    value: 'li',
                    label: 'Limburgish, Limburgan, Limburger',
                },
                {
                    value: 'ln',
                    label: 'Lingala',
                },
                {
                    value: 'lo',
                    label: 'Lao',
                },
                {
                    value: 'lt',
                    label: 'Lithuanian',
                },
                {
                    value: 'lu',
                    label: 'Luba-Katanga',
                },
                {
                    value: 'lv',
                    label: 'Latvian',
                },
                {
                    value: 'gv',
                    label: 'Manx',
                },
                {
                    value: 'mk',
                    label: 'Macedonian',
                },
                {
                    value: 'mg',
                    label: 'Malagasy',
                },
                {
                    value: 'ms',
                    label: 'Malay',
                },
                {
                    value: 'ml',
                    label: 'Malayalam',
                },
                {
                    value: 'mt',
                    label: 'Maltese',
                },
                {
                    value: 'mi',
                    label: 'MÄori',
                },
                {
                    value: 'mr',
                    label: 'Marathi',
                },
                {
                    value: 'mh',
                    label: 'Marshallese',
                },
                {
                    value: 'mn',
                    label: 'Mongolian',
                },
                {
                    value: 'na',
                    label: 'Nauru',
                },
                {
                    value: 'nv',
                    label: 'Navajo, Navaho',
                },
                {
                    value: 'nb',
                    label: 'Norwegian',
                },
                {
                    value: 'nd',
                    label: 'North Ndebele',
                },
                {
                    value: 'ne',
                    label: 'Nepali',
                },
                {
                    value: 'ng',
                    label: 'Ndonga',
                },
                {
                    value: 'nn',
                    label: 'Norwegian Nynorsk',
                },
                {
                    value: 'no',
                    label: 'Norwegian',
                },
                {
                    value: 'ii',
                    label: 'Nuosu',
                },
                {
                    value: 'oc',
                    label: 'Occitan',
                },
                {
                    value: 'oj',
                    label: 'Ojibwe, Ojibwa',
                },
                {
                    value: 'cu',
                    label: 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic',
                },
                {
                    value: 'om',
                    label: 'Oromo',
                },
                {
                    value: 'or',
                    label: 'Oriya',
                },
                {
                    value: 'os',
                    label: 'Ossetian, Ossetic',
                },
                {
                    value: 'pa',
                    label: 'Panjabi, Punjabi',
                },
                {
                    value: 'pi',
                    label: 'Pali',
                },
                {
                    value: 'fa',
                    label: 'Persian (Farsi)',
                },
                {
                    value: 'pl',
                    label: 'Polish',
                },
                {
                    value: 'ps',
                    label: 'Pashto, Pushto',
                },
                {
                    value: 'pt',
                    label: 'Portuguese',
                },
                {
                    value: 'qu',
                    label: 'Quechua',
                },
                {
                    value: 'rm',
                    label: 'Romansh',
                },
                {
                    value: 'ro',
                    label: 'Romanian',
                },
                {
                    value: 'ru',
                    label: 'Russian',
                },
                {
                    value: 'sa',
                    label: 'Sanskrit',
                },
                {
                    value: 'sc',
                    label: 'Sardinian',
                },
                {
                    value: 'sd',
                    label: 'Sindhi',
                },
                {
                    value: 'se',
                    label: 'Northern Sami',
                },
                {
                    value: 'sm',
                    label: 'Samoan',
                },
                {
                    value: 'sg',
                    label: 'Sango',
                },
                {
                    value: 'sr',
                    label: 'Serbian',
                },
                {
                    value: 'gd',
                    label: 'Scottish Gaelic',
                },
                {
                    value: 'sn',
                    label: 'Shona',
                },
                {
                    value: 'si',
                    label: 'Sinhala, Sinhalese',
                },
                {
                    value: 'sk',
                    label: 'Slovak',
                },
                {
                    value: 'sl',
                    label: 'Slovene',
                },
                {
                    value: 'so',
                    label: 'Somali',
                },
                {
                    value: 'st',
                    label: 'Southern Sotho',
                },
                {
                    value: 'az',
                    label: 'South Azerbaijani',
                },
                {
                    value: 'nr',
                    label: 'South Ndebele',
                },
                {
                    value: 'es',
                    label: 'Spanish; Castilian',
                },
                {
                    value: 'su',
                    label: 'Sundanese',
                },
                {
                    value: 'sw',
                    label: 'Swahili',
                },
                {
                    value: 'ss',
                    label: 'Swati',
                },
                {
                    value: 'sv',
                    label: 'Swedish',
                },
                {
                    value: 'ta',
                    label: 'Tamil',
                },
                {
                    value: 'te',
                    label: 'Telugu',
                },
                {
                    value: 'tg',
                    label: 'Tajik',
                },
                {
                    value: 'th',
                    label: 'Thai',
                },
                {
                    value: 'ti',
                    label: 'Tigrinya',
                },
                {
                    value: 'bo',
                    label: 'Tibetan Standard, Tibetan, Central',
                },
                {
                    value: 'tk',
                    label: 'Turkmen',
                },
                {
                    value: 'tl',
                    label: 'Tagalog',
                },
                {
                    value: 'tn',
                    label: 'Tswana',
                },
                {
                    value: 'to',
                    label: 'Tonga (Tonga Islands)',
                },
                {
                    value: 'tr',
                    label: 'Turkish',
                },
                {
                    value: 'ts',
                    label: 'Tsonga',
                },
                {
                    value: 'tt',
                    label: 'Tatar',
                },
                {
                    value: 'tw',
                    label: 'Twi',
                },
                {
                    value: 'ty',
                    label: 'Tahitian',
                },
                {
                    value: 'ug',
                    label: 'Uyghur, Uighur',
                },
                {
                    value: 'uk',
                    label: 'Ukrainian',
                },
                {
                    value: 'ur',
                    label: 'Urdu',
                },
                {
                    value: 'uz',
                    label: 'Uzbek',
                },
                {
                    value: 've',
                    label: 'Venda',
                },
                {
                    value: 'vi',
                    label: 'Vielabele',
                },
				{
					value: 'vn',
					label: 'Viet Nam',
				},
				{
                    value: 'vo',
                    label: 'Volapük',
                },
                {
                    value: 'wa',
                    label: 'Walloon',
                },
                {
                    value: 'cy',
                    label: 'Welsh',
                },
                {
                    value: 'wo',
                    label: 'Wolof',
                },
                {
                    value: 'fy',
                    label: 'Western Frisian',
                },
                {
                    value: 'xh',
                    label: 'Xhosa',
                },
                {
                    value: 'yi',
                    label: 'Yiddish',
                },
                {
                    value: 'yo',
                    label: 'Yoruba',
                },
                {
                    value: 'za',
                    label: 'Zhuang, Chuang',
                },
                {
                    value: 'zu',
                    label: 'Zulu',
                },
            ],
        }
    },
    methods: {
        async createLanguage() {
            // Validate fields
            const isValid = await this.$refs.createForm.validate()

            if (isValid) {
                this.isLoading = true

                axios
                    .post('/api/admin/languages', this.form)
                    .then((response) => {
                        events.$emit('reload:languages', response.data)
                    })
                    .catch(() => {
                        this.$isSomethingWrong()
                    })
                    .finally(() => {
                        this.form = {
                            name: undefined,
                            locale: undefined,
                        }

                        this.isLoading = false
                        this.$closePopup()
                    })
            }
        },
    },
}
</script>
