<template>
    <AuthContentWrapper ref="auth" class="h-screen bg-white dark:bg-dark-background">
        <!--Licence Verify-->
        <AuthContent name="licence-verify" :visible="true">
            <Headline
                title="Setup Wizard"
                description="Please set your purchase code before continue to set up your application."
            >
                <settings-icon
                    size="40"
                    class="vue-feather text-theme mx-auto mb-3 animate-[spin_5s_linear_infinite]"
                />
            </Headline>

            <ValidationObserver
                @submit.prevent="verifyPurchaseCode"
                ref="verifyPurchaseCode"
                v-slot="{ invalid }"
                tag="form"
                class="mb-12 items-start space-y-4 md:flex md:space-x-4 md:space-y-0"
            >
                <ValidationProvider
                    tag="div"
                    mode="passive"
                    class="w-full text-left"
                    name="Purchase Code"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <input
                        v-model="purchaseCode"
                        placeholder="Paste your purchase code"
                        type="text"
                        class="dark:placeholder:text-gray-600 focus-border-theme w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 font-bold dark:bg-2x-dark-foreground"
                        :class="{ 'border-rose-600': errors[0] }"
                    />
                    <span class="text-left text-xs dark:text-rose-600 text-rose-600" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
                <AuthButton
                    icon="chevron-right"
                    text="Verify"
                    class="w-full justify-center md:w-min"
                    :loading="isLoading"
                    :disabled="isLoading"
                />
            </ValidationObserver>

            <p class="block">
                <a
                    href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"
                    target="_blank"
                    class="text-theme font-bold"
                    >Where I can find purchase code?
                </a>
                <a
                    class="black-link"
                    href="https://codecanyon.net/item/vue-file-manager-with-laravel-backend/25815986"
                    target="_blank"
                    >Don’t have purchase code?
                </a>
            </p>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import AuthContentWrapper from '../../components/Layout/AuthPages/AuthContentWrapper'
import InfoBox from '../../components/UI/Others/InfoBox'
import AuthContent from '../../components/Layout/AuthPages/AuthContent'
import AuthButton from '../../components/UI/Buttons/AuthButton'
import { SettingsIcon } from 'vue-feather-icons'
import { required } from 'vee-validate/dist/rules'
import Headline from '../../components/UI/Labels/LogoHeadline'
import axios from 'axios'

export default {
    name: 'PurchaseCode',
    components: {
        AuthContentWrapper,
        ValidationProvider,
        ValidationObserver,
        SettingsIcon,
        AuthContent,
        AuthButton,
        required,
        InfoBox,
        Headline,
    },
    data() {
        return {
            isLoading: false,
            isExtended: undefined,
            purchaseCode: '',
        }
    },
    methods: {
        async verifyPurchaseCode() {
            if (this.$root.$data.config.isSetupWizardDemo) {
                this.$router.push({ name: 'Database' })
            }

            // Validate fields
            const isValid = await this.$refs.verifyPurchaseCode.validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true

            // Send request to get verify account
            axios
                .post('/api/setup/purchase-code', {
                    purchaseCode: this.purchaseCode,
                })
                .then((response) => {
                    // End loading
                    this.isLoading = false

                    if (response.data === 'b6896a44017217c36f4a6fdc56699728') {
                        this.isExtended = true
                        localStorage.setItem('license', 'Extended')
                    } else {
                        this.isExtended = false
                        localStorage.setItem('license', 'Regular')
                    }

                    localStorage.setItem('purchase_code', this.purchaseCode)

                    // Redirect to next step
                    this.$router.push({ name: 'Database' })
                })
                .catch((error) => {
                    // End loading
                    this.isLoading = false

                    if (error.response.status === 400) {
                        this.$refs.verifyPurchaseCode.setErrors({
                            'Purchase Code': ['Purchase code is invalid.'],
                        })
                    } else if (error.response.status === 404) {
                        this.$refs.verifyPurchaseCode.setErrors({
                            'Purchase Code': [
                                'You may have misconfigured the app, please read the readme file and try it again.',
                            ],
                        })
                    } else {
                        this.$refs.verifyPurchaseCode.setErrors({
                            'Purchase Code': ['Something is wrong. Please try again.'],
                        })
                    }
                })
        },
    },
}
</script>
