<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width">

        <!--Stripe Information-->
        <PageTabGroup v-if="config.stripe_public_key">
            <div class="form block-form">
                <FormLabel>{{ $t('admin_settings.payments.section_payments') }}</FormLabel>
                <InfoBox>
                    <p v-html="$t('admin_settings.payments.credentials_disclaimer')"></p>
                </InfoBox>
                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">{{ $t('admin_settings.payments.allow_payments') }}:</label>
                            </div>
                            <SwitchInput @input="$updateText('/settings', 'payments_active', payments.status)" v-model="payments.status" class="switch" :state="payments.status"/>
                        </div>
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.payments.webhook_url') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Webhook URL" rules="required" v-slot="{ errors }">
                        <input :value="stripeWebhookEndpoint" type="text" disabled/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
            </div>
        </PageTabGroup>

        <!--Stripe Set up-->
        <PageTabGroup v-if="! config.stripe_public_key">
            <ValidationObserver @submit.prevent="stripeCredentialsSubmit" ref="stripeCredentials" v-slot="{ invalid }" tag="form" class="form block-form">

                <FormLabel>{{ $t('admin_settings.payments.stripe_setup') }}</FormLabel>

                <InfoBox>
                    <p v-html="$t('admin_settings.payments.stripe_create_acc')"></p>
                </InfoBox>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.payments.stripe_currency') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Currency" rules="required" v-slot="{ errors }">
                        <SelectInput v-model="stripeCredentials.currency" :options="currencyList" :placeholder="$t('admin_settings.payments.stripe_currency_plac')" :isError="errors[0]"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.payments.stripe_pub_key') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Publishable Key" rules="required" v-slot="{ errors }">
                        <input v-model="stripeCredentials.key" :placeholder="$t('admin_settings.payments.stripe_pub_key_plac')" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.payments.stripe_sec_key') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Secret Key" rules="required" v-slot="{ errors }">
                        <input v-model="stripeCredentials.secret" :placeholder="$t('admin_settings.payments.stripe_sec_key_plac')" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Webhook URL:</label>
                    <InfoBox>
                        <p v-html="$t('admin_settings.payments.stripe_create_webhook')"></p>
                    </InfoBox>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Webhook URL" rules="required" v-slot="{ errors }">
                        <input :value="stripeWebhookEndpoint" type="text" disabled/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Webhook Secret:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Webhook Secret" rules="required" v-slot="{ errors }">
                        <input v-model="stripeCredentials.webhookSecret" :placeholder="$t('admin_settings.payments.stripe_webhook_key_plac')" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <InfoBox v-if="isError" type="error" style="margin-bottom: -20px">
                    <p>{{ errorMessage }}</p>
                </InfoBox>

                <ButtonBase :loading="isLoading" :disabled="isLoading" type="submit"
                            button-style="theme" class="submit-button">
                    {{ submitButtonText }}
                </ButtonBase>

            </ValidationObserver>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import ImageInput from '@/components/Others/Forms/ImageInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'AppPayments',
        components: {
            ValidationObserver,
            ValidationProvider,
            StorageItemDetail,
            PageTabGroup,
            SwitchInput,
            SelectInput,
            ImageInput,
            ButtonBase,
            FormLabel,
            SetupBox,
            required,
            PageTab,
            InfoBox,
        },
        computed: {
            ...mapGetters(['config']),
            stripeWebhookEndpoint() {
                return this.config.host + '/stripe/webhook'
            },
            submitButtonText() {
                return this.isLoading ? this.$t('admin_settings.payments.button_testing') : this.$t('admin_settings.payments.button_submit')
            }
        },
        data() {
            return {
                isLoading: true,
                isError: false,
                errorMessage: '',
                payments: {
                    status: 1,
                    configured: undefined,
                },
                stripeCredentials: {
                    key: '',
                    secret: '',
                    webhookSecret: '',
                    currency: '',
                },
                currencyList: [
                    {
                        label: 'USD - United States Dollar',
                        value: 'USD',
                    },
                    {
                        label: 'EUR - Euro',
                        value: 'EUR',
                    },
                    {
                        label: 'GBP - British Pound',
                        value: 'GBP',
                    },
                    {
                        label: 'AFN - Afghan Afghani',
                        value: 'AFN',
                    },
                    {
                        label: 'ALL - Albanian Lek',
                        value: 'ALL',
                    },
                    {
                        label: 'DZD - Algerian Dinar',
                        value: 'DZD',
                    },
                    {
                        label: 'AOA - Angolan Kwanza',
                        value: 'AOA',
                    },
                    {
                        label: 'ARS - Argentine Peso',
                        value: 'ARS',
                    },
                    {
                        label: 'AMD - Armenian Dram',
                        value: 'AMD',
                    },
                    {
                        label: 'AWG - Aruban Florin',
                        value: 'AWG',
                    },
                    {
                        label: 'AUD - Australian Dollar',
                        value: 'AUD',
                    },
                    {
                        label: 'AZN - Azerbaijani Manat',
                        value: 'AZN',
                    },
                    {
                        label: 'BDT - Bangladeshi Taka',
                        value: 'BDT',
                    },
                    {
                        label: 'BBD - Barbadian Dollar',
                        value: 'BBD',
                    },
                    {
                        label: 'BZD - Belize Dollar',
                        value: 'BZD',
                    },
                    {
                        label: 'BMD - Bermudian Dollar',
                        value: 'BMD',
                    },
                    {
                        label: 'BOB - Bolivian Boliviano',
                        value: 'BOB',
                    },
                    {
                        label: 'BAM - Bosnia & Herzegovina Convertible Mark',
                        value: 'BAM',
                    },
                    {
                        label: 'BWP - Botswana Pula',
                        value: 'BWP',
                    },
                    {
                        label: 'BRL - Brazilian Real',
                        value: 'BRL',
                    },
                    {
                        label: 'BND - Brunei Dollar',
                        value: 'BND',
                    },
                    {
                        label: 'BGN - Bulgarian Lev',
                        value: 'BGN',
                    },
                    {
                        label: 'BIF - Burundian Franc',
                        value: 'BIF',
                    },
                    {
                        label: 'KHR - Cambodian Riel',
                        value: 'KHR',
                    },
                    {
                        label: 'CAD - Canadian Dollar',
                        value: 'CAD',
                    },
                    {
                        label: 'CVE - Cape Verdean Escudo',
                        value: 'CVE',
                    },
                    {
                        label: 'KYD - Cayman Islands Dollar',
                        value: 'KYD',
                    },
                    {
                        label: 'XAF - Central African Cfa Franc',
                        value: 'XAF',
                    },
                    {
                        label: 'XPF - Cfp Franc',
                        value: 'XPF',
                    },
                    {
                        label: 'CLP - Chilean Peso',
                        value: 'CLP',
                    },
                    {
                        label: 'CNY - Chinese Renminbi Yuan',
                        value: 'CNY',
                    },
                    {
                        label: 'COP - Colombian Peso',
                        value: 'COP',
                    },
                    {
                        label: 'KMF - Comorian Franc',
                        value: 'KMF',
                    },
                    {
                        label: 'CDF - Congolese Franc',
                        value: 'CDF',
                    },
                    {
                        label: 'CRC - Costa Rican Colón',
                        value: 'CRC',
                    },
                    {
                        label: 'HRK - Croatian Kuna',
                        value: 'HRK',
                    },
                    {
                        label: 'CZK - Czech Koruna',
                        value: 'CZK',
                    },
                    {
                        label: 'DKK - Danish Krone',
                        value: 'DKK',
                    },
                    {
                        label: 'DJF - Djiboutian Franc',
                        value: 'DJF',
                    },
                    {
                        label: 'DOP - Dominican Peso',
                        value: 'DOP',
                    },
                    {
                        label: 'XCD - East Caribbean Dollar',
                        value: 'XCD',
                    },
                    {
                        label: 'EGP - Egyptian Pound',
                        value: 'EGP',
                    },
                    {
                        label: 'ETB - Ethiopian Birr',
                        value: 'ETB',
                    },
                    {
                        label: 'FKP - Falkland Islands Pound',
                        value: 'FKP',
                    },
                    {
                        label: 'FJD - Fijian Dollar',
                        value: 'FJD',
                    },
                    {
                        label: 'GMD - Gambian Dalasi',
                        value: 'GMD',
                    },
                    {
                        label: 'GEL - Georgian Lari',
                        value: 'GEL',
                    },
                    {
                        label: 'GIP - Gibraltar Pound',
                        value: 'GIP',
                    },
                    {
                        label: 'GTQ - Guatemalan Quetzal',
                        value: 'GTQ',
                    },
                    {
                        label: 'GNF - Guinean Franc',
                        value: 'GNF',
                    },
                    {
                        label: 'GYD - Guyanese Dollar',
                        value: 'GYD',
                    },
                    {
                        label: 'HTG - Haitian Gourde',
                        value: 'HTG',
                    },
                    {
                        label: 'HNL - Honduran Lempira',
                        value: 'HNL',
                    },
                    {
                        label: 'HKD - Hong Kong Dollar',
                        value: 'HKD',
                    },
                    {
                        label: 'HUF - Hungarian Forint',
                        value: 'HUF',
                    },
                    {
                        label: 'ISK - Icelandic Króna',
                        value: 'ISK',
                    },
                    {
                        label: 'INR - Indian Rupee',
                        value: 'INR',
                    },
                    {
                        label: 'IDR - Indonesian Rupiah',
                        value: 'IDR',
                    },
                    {
                        label: 'ILS - Israeli New Sheqel',
                        value: 'ILS',
                    },
                    {
                        label: 'JMD - Jamaican Dollar',
                        value: 'JMD',
                    },
                    {
                        label: 'JPY - Japanese Yen',
                        value: 'JPY',
                    },
                    {
                        label: 'KZT - Kazakhstani Tenge',
                        value: 'KZT',
                    },
                    {
                        label: 'KES - Kenyan Shilling',
                        value: 'KES',
                    },
                    {
                        label: 'KGS - Kyrgyzstani Som',
                        value: 'KGS',
                    },
                    {
                        label: 'LAK - Lao Kip',
                        value: 'LAK',
                    },
                    {
                        label: 'LBP - Lebanese Pound',
                        value: 'LBP',
                    },
                    {
                        label: 'LSL - Lesotho Loti',
                        value: 'LSL',
                    },
                    {
                        label: 'LRD - Liberian Dollar',
                        value: 'LRD',
                    },
                    {
                        label: 'MOP - Macanese Pataca',
                        value: 'MOP',
                    },
                    {
                        label: 'MKD - Macedonian Denar',
                        value: 'MKD',
                    },
                    {
                        label: 'MGA - Malagasy Ariary',
                        value: 'MGA',
                    },
                    {
                        label: 'MWK - Malawian Kwacha',
                        value: 'MWK',
                    },
                    {
                        label: 'MYR - Malaysian Ringgit',
                        value: 'MYR',
                    },
                    {
                        label: 'MVR - Maldivian Rufiyaa',
                        value: 'MVR',
                    },
                    {
                        label: 'MRO - Mauritanian Ouguiya',
                        value: 'MRO',
                    },
                    {
                        label: 'MUR - Mauritian Rupee',
                        value: 'MUR',
                    },
                    {
                        label: 'MXN - Mexican Peso',
                        value: 'MXN',
                    },
                    {
                        label: 'MDL - Moldovan Leu',
                        value: 'MDL',
                    },
                    {
                        label: 'MNT - Mongolian Tögrög',
                        value: 'MNT',
                    },
                    {
                        label: 'MAD - Moroccan Dirham',
                        value: 'MAD',
                    },
                    {
                        label: 'MZN - Mozambican Metical',
                        value: 'MZN',
                    },
                    {
                        label: 'MMK - Myanmar Kyat',
                        value: 'MMK',
                    },
                    {
                        label: 'NAD - Namibian Dollar',
                        value: 'NAD',
                    },
                    {
                        label: 'NPR - Nepalese Rupee',
                        value: 'NPR',
                    },
                    {
                        label: 'ANG - Netherlands Antillean Gulden',
                        value: 'ANG',
                    },
                    {
                        label: 'TWD - New Taiwan Dollar',
                        value: 'TWD',
                    },
                    {
                        label: 'NZD - New Zealand Dollar',
                        value: 'NZD',
                    },
                    {
                        label: 'NIO - Nicaraguan Córdoba',
                        value: 'NIO',
                    },
                    {
                        label: 'NGN - Nigerian Naira',
                        value: 'NGN',
                    },
                    {
                        label: 'NOK - Norwegian Krone',
                        value: 'NOK',
                    },
                    {
                        label: 'PKR - Pakistani Rupee',
                        value: 'PKR',
                    },
                    {
                        label: 'PAB - Panamanian Balboa',
                        value: 'PAB',
                    },
                    {
                        label: 'PGK - Papua New Guinean Kina',
                        value: 'PGK',
                    },
                    {
                        label: 'PYG - Paraguayan Guaraní',
                        value: 'PYG',
                    },
                    {
                        label: 'PEN - Peruvian Nuevo Sol',
                        value: 'PEN',
                    },
                    {
                        label: 'PHP - Philippine Peso',
                        value: 'PHP',
                    },
                    {
                        label: 'PLN - Polish Złoty',
                        value: 'PLN',
                    },
                    {
                        label: 'QAR - Qatari Riyal',
                        value: 'QAR',
                    },
                    {
                        label: 'RON - Romanian Leu',
                        value: 'RON',
                    },
                    {
                        label: 'RUB - Russian Ruble',
                        value: 'RUB',
                    },
                    {
                        label: 'RWF - Rwandan Franc',
                        value: 'RWF',
                    },
                    {
                        label: 'STD - São Tomé and Príncipe Dobra',
                        value: 'STD',
                    },
                    {
                        label: 'SHP - Saint Helenian Pound',
                        value: 'SHP',
                    },
                    {
                        label: 'SVC - Salvadoran Colón',
                        value: 'SVC',
                    },
                    {
                        label: 'WST - Samoan Tala',
                        value: 'WST',
                    },
                    {
                        label: 'SAR - Saudi Riyal',
                        value: 'SAR',
                    },
                    {
                        label: 'RSD - Serbian Dinar',
                        value: 'RSD',
                    },
                    {
                        label: 'SCR - Seychellois Rupee',
                        value: 'SCR',
                    },
                    {
                        label: 'SLL - Sierra Leonean Leone',
                        value: 'SLL',
                    },
                    {
                        label: 'SGD - Singapore Dollar',
                        value: 'SGD',
                    },
                    {
                        label: 'SBD - Solomon Islands Dollar',
                        value: 'SBD',
                    },
                    {
                        label: 'SOS - Somali Shilling',
                        value: 'SOS',
                    },
                    {
                        label: 'ZAR - South African Rand',
                        value: 'ZAR',
                    },
                    {
                        label: 'KRW - South Korean Won',
                        value: 'KRW',
                    },
                    {
                        label: 'LKR - Sri Lankan Rupee',
                        value: 'LKR',
                    },
                    {
                        label: 'SRD - Surinamese Dollar',
                        value: 'SRD',
                    },
                    {
                        label: 'SZL - Swazi Lilangeni',
                        value: 'SZL',
                    },
                    {
                        label: 'SEK - Swedish Krona',
                        value: 'SEK',
                    },
                    {
                        label: 'CHF - Swiss Franc',
                        value: 'CHF',
                    },
                    {
                        label: 'TJS - Tajikistani Somoni',
                        value: 'TJS',
                    },
                    {
                        label: 'TZS - Tanzanian Shilling',
                        value: 'TZS',
                    },
                    {
                        label: 'THB - Thai Baht',
                        value: 'THB',
                    },
                    {
                        label: 'TOP - Tongan Paʻanga',
                        value: 'TOP',
                    },
                    {
                        label: 'TTD - Trinidad and Tobago Dollar',
                        value: 'TTD',
                    },
                    {
                        label: 'TRY - Turkish Lira',
                        value: 'TRY',
                    },
                    {
                        label: 'UGX - Ugandan Shilling',
                        value: 'UGX',
                    },
                    {
                        label: 'UAH - Ukrainian Hryvnia',
                        value: 'UAH',
                    },
                    {
                        label: 'AED - United Arab Emirates Dirham',
                        value: 'AED',
                    },
                    {
                        label: 'UYU - Uruguayan Peso',
                        value: 'UYU',
                    },
                    {
                        label: 'UZS - Uzbekistani Som',
                        value: 'UZS',
                    },
                    {
                        label: 'VUV - Vanuatu Vatu',
                        value: 'VUV',
                    },
                    {
                        label: 'VND - Vietnamese Đồng',
                        value: 'VND',
                    },
                    {
                        label: 'XOF - West African Cfa Franc',
                        value: 'XOF',
                    },
                    {
                        label: 'YER - Yemeni Rial',
                        value: 'YER',
                    },
                    {
                        label: 'ZMW - Zambian Kwacha',
                        value: 'ZMW',
                    },
                ],
            }
        },
        methods: {
            async stripeCredentialsSubmit() {

                // Validate fields
                const isValid = await this.$refs.stripeCredentials.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get verify account
                axios
                    .post('/api/settings/stripe', this.stripeCredentials)
                    .then(() => {

                        // End loading
                        this.isLoading = false

                        // Store Stripe Public
                        this.$store.commit('SET_STRIPE_PUBLIC_KEY', this.stripeCredentials.key)

                        // Show toaster
                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('toaster.stripe_set'),
                        })
                    })
                    .catch(error => {

                        if (error.response.status = 401) {
                            this.isError = true
                            this.errorMessage = error.response.data.message
                        }

                        // End loading
                        this.isLoading = false
                    })
            },
        },
        mounted() {
            axios.get('/api/settings', {
                params: {
                    column: 'payments_active|payments_configured'
                }
            })
                .then(response => {
                    this.isLoading = false

                    this.payments.configured = parseInt(response.data.payments_configured)
                    this.payments.status = parseInt(response.data.payments_active)
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .block-form {
        max-width: 100%;
    }
</style>
