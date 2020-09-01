<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">
            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>Set up your database credentials.</h2>
            </div>

            <ValidationObserver @submit.prevent="stripeCredentialsSubmit" ref="stripeCredentials" v-slot="{ invalid }" tag="form" class="form block-form">
                <InfoBox>
                    <p>If you don’t have stripe account, please <a href="https://dashboard.stripe.com/register" target="_blank">register here</a> and get your Publishable Key, Secret Key and create your webhook.</p>
                </InfoBox>

                <FormLabel>Stripe Setup</FormLabel>

                <div class="block-wrapper">
                    <label>Stripe Currency:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Currency" rules="required" v-slot="{ errors }">
                        <SelectInput v-model="stripeCredentials.currency" :options="currencyList" placeholder="Select your Stripe currency" :isError="errors[0]"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <FormLabel class="mt-70">Stripe Credentials</FormLabel>

                <div class="block-wrapper">
                    <label>Publishable Key:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Publishable Key" rules="required" v-slot="{ errors }">
                        <input v-model="stripeCredentials.key" placeholder="Paste your publishable key" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Secret Key:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Secret Key" rules="required" v-slot="{ errors }">
                        <input v-model="stripeCredentials.secret" placeholder="Paste your secret key" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <FormLabel class="mt-70">Stripe Webhook</FormLabel>
                <InfoBox>
                    <p>You have to create webhook endpoint in your Stripe Dashboard. You can find it in <b>Dashboard -> Developers -> Webhooks -> Add Endpoint</b>. In Endpoint URL
                        please copy and paste url bellow. Make sure, this url is your public domain, not localhost. In events section, please click on <b>receive all events</b>.
                        That's all.</p>
                </InfoBox>

                <div class="block-wrapper">
                    <label>Endpoint URL:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Webhook URL" rules="required" v-slot="{ errors }">
                        <input :value="stripeWebhookEndpoint" type="text" disabled/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Webhook Secret:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Webhook Secret" rules="required" v-slot="{ errors }">
                        <input v-model="stripeCredentials.webhookSecret" placeholder="Type your stripe webhook secret" type="text" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <InfoBox v-if="isError" type="error" style="margin-bottom: -20px">
                    <p>{{ errorMessage }}</p>
                </InfoBox>

                <div class="submit-wrapper">
                    <AuthButton icon="chevron-right" :text="submitButtonText" :loading="isLoading" :disabled="isLoading"/>
                </div>

            </ValidationObserver>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import AuthContent from '@/components/Auth/AuthContent'
    import AuthButton from '@/components/Auth/AuthButton'
    import {SettingsIcon} from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'StripeCredentials',
        components: {
            AuthContentWrapper,
            ValidationProvider,
            ValidationObserver,
            SettingsIcon,
            SelectInput,
            AuthContent,
            AuthButton,
            FormLabel,
            required,
            InfoBox,
        },
        computed: {
            ...mapGetters(['config', 'currencyList']),
            stripeWebhookEndpoint() {
                return this.config.host + '/stripe/webhook'
            },
            submitButtonText() {
                return this.isLoading ? 'Testing Stripe Connection' : 'Save and Set Billings'
            }
        },
        data() {
            return {
                isLoading: false,
                isError: false,
                errorMessage: '',
                stripeCredentials: {
                    key: '',
                    secret: '',
                    webhookSecret: '',
                    currency: '',
                }
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
                    .post('/api/setup/stripe-credentials', this.stripeCredentials)
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        // Store Stripe Public
                        this.$store.commit('SET_STRIPE_PUBLIC_KEY', this.stripeCredentials.key)

                        // Redirect to next step
                        this.$router.push({name: 'BillingsDetail'})
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
        created() {
            this.$scrollTop()
        }
    }
</script>

<style scoped lang="scss">
    //@import '@assets/vue-file-manager/_auth-form';
    @import '@assets/vue-file-manager/_forms';
    @import '@assets/vue-file-manager/_auth';
    @import '@assets/vue-file-manager/_setup_wizard';
</style>
