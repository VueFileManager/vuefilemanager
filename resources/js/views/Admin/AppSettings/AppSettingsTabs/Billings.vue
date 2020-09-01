<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width">

        <!--Personal Information-->
        <PageTabGroup v-if="billingInformation">
            <div class="form block-form">
                <FormLabel>{{ $t('admin_settings.billings.section_company') }}</FormLabel>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.billings.company_name') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Billing Name"
                                        rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'billing_name', billingInformation.billing_name)" v-model="billingInformation.billing_name" :placeholder="$t('admin_settings.billings.company_name_plac')"
                               type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.billings.vat') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Billing Vat Number"
                                        rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'billing_vat_number', billingInformation.billing_vat_number)" v-model="billingInformation.billing_vat_number" :placeholder="$t('admin_settings.billings.vat_plac')"
                               type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <FormLabel class="mt-70">{{ $t('admin_settings.billings.section_billing') }}</FormLabel>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.billings.country') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Billing Country"
                                        rules="required" v-slot="{ errors }">
                        <SelectInput @input="$updateText('/settings', 'billing_country', billingInformation.billing_country)" v-model="billingInformation.billing_country" :default="billingInformation.billing_country" :options="countries" :placeholder="$t('admin_settings.billings.country_plac')" :isError="errors[0]"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.billings.address') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Billing Address"
                                        rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'billing_address', billingInformation.billing_address)" v-model="billingInformation.billing_address" :placeholder="$t('admin_settings.billings.address_plac')"
                               type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="wrapper-inline">
                    <div class="block-wrapper">
                        <label>{{ $t('admin_settings.billings.city') }}:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Billing City"
                                            rules="required" v-slot="{ errors }">
                            <input @input="$updateText('/settings', 'billing_city', billingInformation.billing_city)" v-model="billingInformation.billing_city" :placeholder="$t('admin_settings.billings.city_plac')"
                                   type="text" :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                    <div class="block-wrapper">
                        <label>{{ $t('admin_settings.billings.postal_code') }}:</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Billing Postal Code"
                                            rules="required" v-slot="{ errors }">
                            <input @input="$updateText('/settings', 'billing_postal_code', billingInformation.billing_postal_code)" v-model="billingInformation.billing_postal_code"
                                   :placeholder="$t('admin_settings.billings.postal_code_plac')" type="text" :class="{'is-error': errors[0]}"/>
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.billings.state') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Billing State"
                                        rules="required" v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'billing_state', billingInformation.billing_state)" v-model="billingInformation.billing_state" :placeholder="$t('admin_settings.billings.state_plac')"
                               type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('admin_settings.billings.phone_number') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Billing Phone Number"
                                        v-slot="{ errors }">
                        <input @input="$updateText('/settings', 'billing_phone_number', billingInformation.billing_phone_number)" v-model="billingInformation.billing_phone_number" :placeholder="$t('admin_settings.billings.phone_number_plac')"
                               type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
            </div>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import ImageInput from '@/components/Others/Forms/ImageInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import axios from 'axios'
    import { mapGetters } from 'vuex'

    export default {
        name: 'AppAppearance',
        components: {
            ValidationObserver,
            ValidationProvider,
            StorageItemDetail,
            PageTabGroup,
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
            ...mapGetters(['countries']),
        },
        data() {
            return {
                isLoading: true,
                billingInformation: undefined
            }
        },
        mounted() {
            axios.get('/api/settings', {
                params: {
                    column: 'billing_phone_number|billing_postal_code|billing_vat_number|billing_address|billing_country|billing_state|billing_city|billing_name'
                }
            })
                .then(response => {
                    this.isLoading = false

                    this.billingInformation = {
                        billing_phone_number: response.data.billing_phone_number,
                        billing_postal_code: response.data.billing_postal_code,
                        billing_vat_number: response.data.billing_vat_number,
                        billing_address: response.data.billing_address,
                        billing_country: response.data.billing_country,
                        billing_state: response.data.billing_state,
                        billing_city: response.data.billing_city,
                        billing_name: response.data.billing_name,
                    }

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

    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
