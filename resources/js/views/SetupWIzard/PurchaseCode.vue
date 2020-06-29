<template>
    <AuthContentWrapper ref="auth">

        <!--Licence Verify-->
        <AuthContent name="licence-verify" :visible="true">

            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>Please set up your application before continue.</h2>
            </div>

            <ValidationObserver @submit.prevent="verifyPurchaseCode" ref="verifyPurchaseCode" v-slot="{ invalid }" tag="form" class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="PurchaseCode" rules="required" v-slot="{ errors }">
                    <input v-model="licence.purchaseCode" placeholder="Paste your purchase code" type="text" :class="{'is-error': errors[0]}"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
                <AuthButton icon="chevron-right" text="Verify" :loading="isLoading" :disabled="isLoading"/>
            </ValidationObserver>

            <p class="additional-link">
                <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">
                    Where I can find purchase code?
                </a>
                Don’t have purchase code?
            </p>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
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
        },
        data() {
            return {
                isLoading: false,

                licence: {
                    purchaseCode: ''
                },
            }
        },
        methods: {
            async verifyPurchaseCode() {

                this.$router.push({name: 'Database'})
                return

                // Validate fields
                const isValid = await this.$refs.verifyPurchaseCode.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get verify account
                axios
                    .post('/api/setup-wizard', {
                        step: this.step,
                        data: this.stepLicence,
                    })
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        // Go to new page
                        this.step++
                    })
                    .catch(error => {

                        // End loading
                        this.isLoading = false
                    })
            },
        },
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_auth-form';
    @import '@assets/vue-file-manager/_auth';
    @import '@assets/vue-file-manager/_setup_wizard';

    .auth-form input {
        min-width: 380px;
    }
</style>
