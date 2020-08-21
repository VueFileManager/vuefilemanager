<template>
    <AuthContentWrapper ref="auth">

        <!--Licence Verify-->
        <AuthContent name="licence-verify" :visible="true">

            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>Please set your purchase code before continue to set up your application.</h2>
            </div>

            <ValidationObserver @submit.prevent="verifyPurchaseCode" ref="verifyPurchaseCode" v-slot="{ invalid }" tag="form" class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Purchase Code" rules="required" v-slot="{ errors }">
                    <input v-model="purchaseCode" placeholder="Paste your purchase code" type="text" :class="{'is-error': errors[0]}"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
                <AuthButton icon="chevron-right" text="Verify" :loading="isLoading" :disabled="isLoading"/>
            </ValidationObserver>

            <p class="additional-link">
                <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">
                    Where I can find purchase code?
                </a>
                <a class="black-link" href="https://codecanyon.net/item/vue-file-manager-with-laravel-backend/25815986" target="_blank">
                    Don’t have purchase code?
                </a>
            </p>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import AuthContent from '@/components/Auth/AuthContent'
    import AuthButton from '@/components/Auth/AuthButton'
    import { SettingsIcon } from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
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
        },
        data() {
            return {
                isLoading: false,
                purchaseCode: '',
            }
        },
        methods: {
            async verifyPurchaseCode() {

                // Validate fields
                const isValid = await this.$refs.verifyPurchaseCode.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get verify account
                axios
                    .post('/api/setup/purchase-code', {
                        purchaseCode: this.purchaseCode,
                    })
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        localStorage.setItem('purchase_code', this.purchaseCode)

                        // Redirect to next step
                        this.$router.push({name: 'Database'})
                    })
                    .catch(error => {

                        // End loading
                        this.isLoading = false

                        if (error.response.status == 400) {
                            this.$refs.verifyPurchaseCode.setErrors({
                                'Purchase Code': ['Purchase code is invalid.']
                            });
                        } else if (error.response.status == 404) {
                            this.$refs.verifyPurchaseCode.setErrors({
                                'Purchase Code': ['You may have misconfigured the app, please read the readme file and try it again.']
                            });
                        } else {
                            this.$refs.verifyPurchaseCode.setErrors({
                                'Purchase Code': ['Something is wrong. Please try again.']
                            });
                        }
                    })
            },
        },
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_auth-form';
    @import '@assets/vue-file-manager/_auth';
    @import '@assets/vue-file-manager/_setup_wizard';

    .additional-link {

        .black-link {
            color: $text;
        }
    }

    .auth-form input {
        min-width: 380px;
    }

    @media (prefers-color-scheme: dark) {
        .additional-link {

            .black-link {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
