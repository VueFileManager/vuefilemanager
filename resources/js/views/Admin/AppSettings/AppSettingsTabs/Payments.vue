<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width">

        <!--Stripe Information-->
        <PageTabGroup v-if="config.stripe_public_key && payments">
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

                <InfoBox v-if="isError" type="error" >
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
            ...mapGetters(['config', 'currencyList']),
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
                payments: undefined,
                stripeCredentials: {
                    key: '',
                    secret: '',
                    webhookSecret: '',
                    currency: '',
                },
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
                    })
                    .finally(() => {

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

                    this.payments = {
                        configured: parseInt(response.data.payments_configured),
                        status: parseInt(response.data.payments_active),
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
</style>
