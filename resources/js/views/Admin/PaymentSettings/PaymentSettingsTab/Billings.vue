<template>
    <PageTab :is-loading="isLoading">
        <div v-if="billingInformation" class="card shadow-card">
            <FormLabel>
                {{ $t('admin_settings.billings.section_company') }}
            </FormLabel>

            <AppInputText :title="$t('admin_settings.billings.company_name')">
                <input
                    @input="$updateText('/admin/settings', 'billing_name', billingInformation.billing_name)"
                    v-model="billingInformation.billing_name"
                    :placeholder="$t('admin_settings.billings.company_name_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <AppInputText :title="$t('admin_settings.billings.vat')" :is-last="true">
                <input
                    @input="$updateText('/admin/settings', 'billing_vat_number', billingInformation.billing_vat_number)"
                    v-model="billingInformation.billing_vat_number"
                    :placeholder="$t('admin_settings.billings.vat_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>
        </div>
        <div v-if="billingInformation" class="card shadow-card">
            <FormLabel>
                {{ $t('admin_settings.billings.section_billing') }}
            </FormLabel>

            <AppInputText :title="$t('admin_settings.billings.country')">
                <SelectInput
                    @input="$updateText('/admin/settings', 'billing_country', billingInformation.billing_country)"
                    v-model="billingInformation.billing_country"
                    :default="billingInformation.billing_country"
                    :options="countries"
                    :placeholder="$t('admin_settings.billings.country_plac')"
                />
            </AppInputText>

            <AppInputText :title="$t('billing_address')">
                <input
                    @input="$updateText('/admin/settings', 'billing_address', billingInformation.billing_address)"
                    v-model="billingInformation.billing_address"
                    :placeholder="$t('admin_settings.billings.address_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <div class="flex space-x-4">
                <AppInputText :title="$t('billing_city')" class="w-full">
                    <input
                        @input="$updateText('/admin/settings', 'billing_city', billingInformation.billing_city)"
                        v-model="billingInformation.billing_city"
                        :placeholder="$t('admin_settings.billings.city_plac')"
                        type="text"
                        class="focus-border-theme input-dark"
                    />
                </AppInputText>

                <AppInputText :title="$t('admin_settings.billings.postal_code')" class="w-full">
                    <input
                        @input="
                            $updateText(
                                '/admin/settings',
                                'billing_postal_code',
                                billingInformation.billing_postal_code
                            )
                        "
                        v-model="billingInformation.billing_postal_code"
                        :placeholder="$t('admin_settings.billings.postal_code_plac')"
                        type="text"
                        class="focus-border-theme input-dark"
                    />
                </AppInputText>
            </div>

            <AppInputText :title="$t('admin_settings.billings.state')">
                <input
                    @input="$updateText('/admin/settings', 'billing_state', billingInformation.billing_state)"
                    v-model="billingInformation.billing_state"
                    :placeholder="$t('admin_settings.billings.state_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <AppInputText :title="$t('admin_settings.billings.phone_number')" :is-last="true">
                <input
                    @input="
                        $updateText('/admin/settings', 'billing_phone_number', billingInformation.billing_phone_number)
                    "
                    v-model="billingInformation.billing_phone_number"
                    :placeholder="$t('admin_settings.billings.phone_number_plac')"
                    type="text"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>
        </div>
    </PageTab>
</template>

<script>
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PageTabGroup from '../../../../components/Layout/PageTabGroup'
import SelectInput from '../../../../components/Inputs/SelectInput'
import ImageInput from '../../../../components/Inputs/ImageInput'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import PageTab from '../../../../components/Layout/PageTab'
import InfoBox from '../../../../components/UI/Others/InfoBox'
import { required } from 'vee-validate/dist/rules'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
    name: 'AppAppearance',
    components: {
        ValidationObserver,
        ValidationProvider,
        AppInputText,
        PageTabGroup,
        SelectInput,
        ImageInput,
        ButtonBase,
        FormLabel,
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
            billingInformation: undefined,
        }
    },
    mounted() {
        axios
            .get('/api/admin/settings', {
                params: {
                    column: 'billing_phone_number|billing_postal_code|billing_vat_number|billing_address|billing_country|billing_state|billing_city|billing_name',
                },
            })
            .then((response) => {
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
    },
}
</script>
