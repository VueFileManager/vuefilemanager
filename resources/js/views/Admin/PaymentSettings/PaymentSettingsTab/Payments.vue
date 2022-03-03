<template>
    <PageTab>
        <!--Global payment settings-->
        <div class="card shadow-card">
            <FormLabel icon="dollar">
                {{ $t('Subscription Payments') }}
            </FormLabel>

            <AppInputSwitch
                :title="$t('Allow Subscription Payments')"
                :description="$t('User can subscribe to fixed or metered plan')"
                :is-last="!allowedPayments"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'allowed_payments', allowedPayments)"
                    v-model="allowedPayments"
                    :state="allowedPayments"
                />
            </AppInputSwitch>

            <AppInputText v-if="allowedPayments" :title="$t('Subscription Type')" :is-last="true">
                <SelectInput
                    @change="subscriptionTypeChange"
                    :default="config.subscriptionType"
                    :options="subscriptionTypes"
                    :placeholder="$t('Select your subscription type')"
                />
            </AppInputText>
        </div>

        <!--Metered settings-->
        <div v-if="config.subscriptionType === 'metered' && allowedPayments" class="card shadow-card">
            <FormLabel icon="bar-chart">
                {{ $t('Metered Billing Settings') }}
            </FormLabel>

            <AppInputSwitch
                :title="$t('Allow Registration Bonus')"
                :description="$t('Credit user automatically bonus to his balance after registration.')"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'allowed_registration_bonus', allowedRegistrationBonus)"
                    v-model="allowedRegistrationBonus"
                    :state="allowedRegistrationBonus"
                />
            </AppInputSwitch>

            <AppInputText
                v-if="allowedRegistrationBonus"
                :title="$t('The Amount of Registration Bonus')"
                :description="$t('This bonus will be automatically added when user successfully register his account.')"
            >
                <input
                    @input="$updateText('/admin/settings', 'registration_bonus_amount', registrationBonusAmount)"
                    v-model="registrationBonusAmount"
                    :placeholder="$t('Type registration bonus amount...')"
                    type="number"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <AppInputButton
                :title="$t('Metered Plan')"
                :description="$t('Your price set up for billing multiple features by user usage.')"
                :is-last="true"
            >
                <router-link
                    v-if="config.isCreatedMeteredPlan"
                    :to="{
                        name: 'PlanMeteredSettings',
                        params: { id: config.meteredPlanId },
                    }"
                >
                    <ButtonBase v-if="config.isCreatedMeteredPlan" class="w-full sm:w-auto" button-style="theme">
                        {{ $t('Plan Details') }}
                    </ButtonBase>
                </router-link>

                <router-link v-if="!config.isCreatedMeteredPlan" :to="{ name: 'CreateMeteredPlan' }">
                    <ButtonBase class="w-full sm:w-auto" button-style="theme-solid">
                        {{ $t('Create Plan') }}
                    </ButtonBase>
                </router-link>
            </AppInputButton>
        </div>

        <!--Stripe method configuration-->
        <div v-if="allowedPayments" class="card shadow-card">
            <img :src="$getPaymentLogo('stripe')" alt="Stripe" class="mb-8 h-8" />

            <AppInputSwitch
                :title="$t('Allow Stripe Service')"
                :description="$t('Allow your users pay by their credit card')"
                :is-last="!stripe.allowedService"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'allowed_stripe', stripe.allowedService)"
                    v-model="stripe.allowedService"
                    :state="stripe.allowedService"
                />
            </AppInputSwitch>

            <!--Stripe credentials are set up-->
            <div v-if="stripe.allowedService">
                <div v-if="stripe.isConfigured">
                    <AppInputText
                        @input="$updateText('/admin/settings', 'stripe_payment_description', stripe.paymentDescription)"
                        :title="$t('Payment Description')"
                        :description="$t('The description showed below user payment method selection.')"
                    >
                        <textarea
                            rows="2"
                            @input="
                                $updateText(
                                    '/admin/settings',
                                    'stripe_payment_description',
                                    stripe.paymentDescription,
                                    true
                                )
                            "
                            v-model="stripe.paymentDescription"
                            :placeholder="
                                $t('Describe in short which methods user can pay with this payment method...')
                            "
                            type="text"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>

                    <AppInputText
                        :title="$t('Your Webhook URL')"
                        :description="$t('Please copy your url and paste it to the service webhook setup.')"
                    >
                        <CopyInput size="small" :str="getWebhookEndpoint('stripe')" />
                    </AppInputText>

                    <div
                        @click="stripe.isVisibleCredentialsForm = !stripe.isVisibleCredentialsForm"
                        class="flex cursor-pointer items-center"
                        :class="{ 'mb-4': stripe.isVisibleCredentialsForm }"
                    >
                        <edit2-icon size="14" class="vue-feather text-theme mr-2.5" />
                        <b class="text-sm">{{ $t('Update Your Credentials') }}</b>
                    </div>
                </div>

                <!--Set up Stripe credentials-->
                <ValidationObserver
                    v-if="!stripe.isConfigured || stripe.isVisibleCredentialsForm"
                    @submit.prevent="storeCredentials('stripe')"
                    ref="credentialsForm"
                    v-slot="{ invalid }"
                    tag="form"
                    class="rounded-xl p-5 shadow-lg"
                >
                    <FormLabel v-if="!stripe.isConfigured" icon="shield">
                        {{ $t('Configure Your Credentials') }}
                    </FormLabel>
                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        name="Publishable Key"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText :title="$t('admin_settings.payments.stripe_pub_key')" :error="errors[0]">
                            <input
                                v-model="stripe.credentials.key"
                                :placeholder="$t('admin_settings.payments.stripe_pub_key_plac')"
                                type="text"
                                :class="{ 'border-red': errors[0] }"
                                class="focus-border-theme input-dark"
                            />
                        </AppInputText>
                    </ValidationProvider>
                    <ValidationProvider tag="div" mode="passive" name="Secret Key" rules="required" v-slot="{ errors }">
                        <AppInputText :title="$t('admin_settings.payments.stripe_sec_key')" :error="errors[0]">
                            <input
                                v-model="stripe.credentials.secret"
                                :placeholder="$t('admin_settings.payments.stripe_sec_key_plac')"
                                type="text"
                                :class="{ 'border-red': errors[0] }"
                                class="focus-border-theme input-dark"
                            />
                        </AppInputText>
                    </ValidationProvider>
                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        name="Webhook Secret"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText :title="$t('Webhook Secret')" :error="errors[0]">
                            <input
                                v-model="stripe.credentials.webhook"
                                :placeholder="$t('Paste your webhook secret')"
                                type="text"
                                :class="{ 'border-red': errors[0] }"
                                class="focus-border-theme input-dark"
                            />
                        </AppInputText>
                    </ValidationProvider>

                    <ButtonBase
                        :disabled="isLoading"
                        :loading="isLoading"
                        button-style="theme"
                        type="submit"
                        class="w-full"
                    >
                        {{ $t('Store Credentials') }}
                    </ButtonBase>
                </ValidationObserver>
            </div>
        </div>

        <!--Paystack method configuration-->
        <div v-if="allowedPayments" class="card shadow-card">
            <img :src="$getPaymentLogo('paystack')" alt="Paystack" class="mb-8 h-7" />

            <AppInputSwitch
                :title="$t('Allow Paystack Service')"
                :description="$t('Allow your users pay by their credit card')"
                :is-last="!paystack.allowedService"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'allowed_paystack', paystack.allowedService)"
                    v-model="paystack.allowedService"
                    :state="paystack.allowedService"
                />
            </AppInputSwitch>

            <!--Paystack credentials are set up-->
            <div v-if="paystack.allowedService">
                <div v-if="paystack.isConfigured">
                    <AppInputText
                        @input="
                            $updateText('/admin/settings', 'paystack_payment_description', paystack.paymentDescription)
                        "
                        :title="$t('Payment Description')"
                        :description="$t('The description showed below user payment method selection.')"
                    >
                        <textarea
                            rows="2"
                            @input="
                                $updateText(
                                    '/admin/settings',
                                    'paystack_payment_description',
                                    paystack.paymentDescription,
                                    true
                                )
                            "
                            v-model="paystack.paymentDescription"
                            :placeholder="
                                $t('Describe in short which methods user can pay with this payment method...')
                            "
                            type="text"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>

                    <AppInputText
                        :title="$t('Your Webhook URL')"
                        :description="$t('Please copy your url and paste it to the service webhook setup.')"
                    >
                        <CopyInput size="small" :str="getWebhookEndpoint('paystack')" />
                    </AppInputText>

                    <div
                        @click="paystack.isVisibleCredentialsForm = !paystack.isVisibleCredentialsForm"
                        class="flex cursor-pointer items-center"
                        :class="{ 'mb-4': paystack.isVisibleCredentialsForm }"
                    >
                        <edit2-icon size="14" class="vue-feather text-theme mr-2.5" />
                        <b class="text-sm">{{ $t('Update Your Credentials') }}</b>
                    </div>
                </div>

                <!--Set up Paystack credentials-->
                <ValidationObserver
                    v-if="!paystack.isConfigured || paystack.isVisibleCredentialsForm"
                    @submit.prevent="storeCredentials('paystack')"
                    ref="credentialsForm"
                    v-slot="{ invalid }"
                    tag="form"
                    class="rounded-xl p-5 shadow-lg"
                >
                    <FormLabel v-if="!paystack.isConfigured" icon="shield">
                        {{ $t('Configure Your Credentials') }}
                    </FormLabel>
                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        name="Publishable Key"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText :title="$t('admin_settings.payments.stripe_pub_key')" :error="errors[0]">
                            <input
                                v-model="paystack.credentials.key"
                                :placeholder="$t('admin_settings.payments.stripe_pub_key_plac')"
                                type="text"
                                :class="{ 'border-red': errors[0] }"
                                class="focus-border-theme input-dark"
                            />
                        </AppInputText>
                    </ValidationProvider>
                    <ValidationProvider tag="div" mode="passive" name="Secret Key" rules="required" v-slot="{ errors }">
                        <AppInputText :title="$t('admin_settings.payments.stripe_sec_key')" :error="errors[0]">
                            <input
                                v-model="paystack.credentials.secret"
                                :placeholder="$t('admin_settings.payments.stripe_sec_key_plac')"
                                type="text"
                                :class="{ 'border-red': errors[0] }"
                                class="focus-border-theme input-dark"
                            />
                        </AppInputText>
                    </ValidationProvider>

                    <ButtonBase
                        :disabled="isLoading"
                        :loading="isLoading"
                        button-style="theme"
                        type="submit"
                        class="w-full"
                    >
                        {{ $t('Store Credentials') }}
                    </ButtonBase>
                </ValidationObserver>
            </div>
        </div>

        <!--PayPal method configuration-->
        <div v-if="allowedPayments" class="card shadow-card">
            <img :src="$getPaymentLogo('paypal')" alt="PayPal" class="mb-8 h-8" />

            <AppInputSwitch
                :title="$t('Allow PayPal Service')"
                :description="$t('Allow your users pay by their credit card')"
                :is-last="!paypal.allowedService"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'allowed_paypal', paypal.allowedService)"
                    v-model="paypal.allowedService"
                    :state="paypal.allowedService"
                />
            </AppInputSwitch>

            <!--Stripe credentials are set up-->
            <div v-if="paypal.allowedService">
                <div v-if="paypal.isConfigured">
                    <AppInputSwitch :title="$t('Live Mode')" :description="$t('Toggle amid live and sandbox mode')">
                        <SwitchInput
                            @input="$updateText('/admin/settings', 'paypal_live', config.isPayPalLive)"
                            v-model="config.isPayPalLive"
                            :state="config.isPayPalLive"
                        />
                    </AppInputSwitch>

                    <AppInputText
                        @input="$updateText('/admin/settings', 'paypal_payment_description', paypal.paymentDescription)"
                        :title="$t('Payment Description')"
                        :description="$t('The description showed below user payment method selection.')"
                    >
                        <textarea
                            rows="2"
                            @input="
                                $updateText(
                                    '/admin/settings',
                                    'paypal_payment_description',
                                    paypal.paymentDescription,
                                    true
                                )
                            "
                            v-model="paypal.paymentDescription"
                            :placeholder="
                                $t('Describe in short which methods user can pay with this payment method...')
                            "
                            type="text"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>

                    <AppInputText
                        :title="$t('Your Webhook URL')"
                        :description="$t('Please copy your url and paste it to the service webhook setup.')"
                    >
                        <CopyInput size="small" :str="getWebhookEndpoint('paypal')" />
                    </AppInputText>

                    <div
                        @click="paypal.isVisibleCredentialsForm = !paypal.isVisibleCredentialsForm"
                        class="flex cursor-pointer items-center"
                        :class="{ 'mb-4': paypal.isVisibleCredentialsForm }"
                    >
                        <edit2-icon size="14" class="vue-feather text-theme mr-2.5" />
                        <b class="text-sm">{{ $t('Update Your Credentials') }}</b>
                    </div>
                </div>

                <!--Set up Paypal credentials-->
                <ValidationObserver
                    v-if="!paypal.isConfigured || paypal.isVisibleCredentialsForm"
                    @submit.prevent="storeCredentials('paypal')"
                    ref="credentialsForm"
                    v-slot="{ invalid }"
                    tag="form"
                    class="rounded-xl p-5 shadow-lg"
                >
                    <FormLabel v-if="!paypal.isConfigured" icon="shield">
                        {{ $t('Configure Your Credentials') }}
                    </FormLabel>
                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        name="Publishable Key"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <AppInputText :title="$t('admin_settings.payments.stripe_pub_key')" :error="errors[0]">
                            <input
                                v-model="paypal.credentials.key"
                                :placeholder="$t('admin_settings.payments.stripe_pub_key_plac')"
                                type="text"
                                :class="{ 'border-red': errors[0] }"
                                class="focus-border-theme input-dark"
                            />
                        </AppInputText>
                    </ValidationProvider>
                    <ValidationProvider tag="div" mode="passive" name="Secret Key" rules="required" v-slot="{ errors }">
                        <AppInputText :title="$t('admin_settings.payments.stripe_sec_key')" :error="errors[0]">
                            <input
                                v-model="paypal.credentials.secret"
                                :placeholder="$t('admin_settings.payments.stripe_sec_key_plac')"
                                type="text"
                                :class="{ 'border-red': errors[0] }"
                                class="focus-border-theme input-dark"
                            />
                        </AppInputText>
                    </ValidationProvider>
                    <ValidationProvider tag="div" mode="passive" name="Webhook ID" rules="required" v-slot="{ errors }">
                        <AppInputText :title="$t('Webhook ID')" :error="errors[0]">
                            <input
                                v-model="paypal.credentials.webhook"
                                :placeholder="$t('Paste your webhook id')"
                                type="text"
                                :class="{ 'border-red': errors[0] }"
                                class="focus-border-theme input-dark"
                            />
                        </AppInputText>
                    </ValidationProvider>

                    <ButtonBase
                        :disabled="isLoading"
                        :loading="isLoading"
                        button-style="theme"
                        type="submit"
                        class="w-full"
                    >
                        {{ $t('Store Credentials') }}
                    </ButtonBase>
                </ValidationObserver>
            </div>
        </div>
    </PageTab>
</template>

<script>
import { Edit2Icon, Trash2Icon } from 'vue-feather-icons'
import AppInputButton from '../../../../components/Admin/AppInputButton'
import DatatableWrapper from '../../../../components/Others/Tables/DatatableWrapper'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PageTabGroup from '../../../../components/Others/Layout/PageTabGroup'
import SelectInput from '../../../../components/Others/Forms/SelectInput'
import SwitchInput from '../../../../components/Others/Forms/SwitchInput'
import ImageInput from '../../../../components/Others/Forms/ImageInput'
import AppInputSwitch from '../../../../components/Admin/AppInputSwitch'
import FormLabel from '../../../../components/Others/Forms/FormLabel'
import ButtonBase from '../../../../components/FilesView/ButtonBase'
import CopyInput from '../../../../components/Others/Forms/CopyInput'
import SetupBox from '../../../../components/Others/Forms/SetupBox'
import AppInputText from '../../../../components/Admin/AppInputText'
import PageTab from '../../../../components/Others/Layout/PageTab'
import InfoBox from '../../../../components/Others/Forms/InfoBox'
import { required } from 'vee-validate/dist/rules'
import { events } from '../../../../bus'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'AppPayments',
    components: {
        AppInputButton,
        DatatableWrapper,
        ValidationObserver,
        ValidationProvider,
        AppInputSwitch,
        AppInputText,
        PageTabGroup,
        SwitchInput,
        SelectInput,
        ImageInput,
        ButtonBase,
        CopyInput,
        FormLabel,
        Trash2Icon,
        Edit2Icon,
        SetupBox,
        required,
        PageTab,
        InfoBox,
    },
    computed: {
        ...mapGetters(['subscriptionTypes', 'config']),
        submitButtonText() {
            return this.isLoading
                ? this.$t('admin_settings.payments.button_testing')
                : this.$t('admin_settings.payments.button_submit')
        },
    },
    data() {
        return {
            allowedRegistrationBonus: true,
            registrationBonusAmount: undefined,

            allowedPayments: false,

            isLoading: false,
            isError: false,
            errorMessage: '',
            stripe: {
                allowedService: true,
                isConfigured: false,
                isVisibleCredentialsForm: false,
                paymentDescription: undefined,
                credentials: {
                    key: undefined,
                    secret: undefined,
                    webhook: undefined,
                },
            },
            paystack: {
                allowedService: true,
                isConfigured: false,
                isVisibleCredentialsForm: false,
                paymentDescription: undefined,
                credentials: {
                    key: undefined,
                    secret: undefined,
                },
            },
            paypal: {
                allowedService: true,
                isConfigured: false,
                isVisibleCredentialsForm: false,
                paymentDescription: undefined,
                credentials: {
                    key: undefined,
                    secret: undefined,
                    webhook: undefined,
                },
            },
            columns: [
                {
                    label: this.$t('Name'),
                    field: 'name',
                    sortable: true,
                },
                {
                    label: this.$t('Currency'),
                    field: 'currency',
                    sortable: true,
                },
                {
                    label: this.$t('Interval'),
                    field: 'interval',
                    sortable: true,
                },
                {
                    label: this.$t('admin_page_plans.table.subscribers'),
                    sortable: false,
                },
                {
                    label: this.$t('admin_page_user.table.action'),
                    sortable: false,
                },
            ],
        }
    },
    methods: {
        async storeCredentials(service) {
            // Validate fields
            const isValid = await this.$refs.credentialsForm.validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true

            // Send request to get verify account
            axios
                .post('/api/admin/settings/payment-service', {
                    service: service,
                    key: this[service].credentials.key,
                    secret: this[service].credentials.secret,
                    webhook: this[service].credentials.webhook || undefined,
                })
                .then(() => {
                    // Update Credentials
                    let commitKey = {
                        stripe: 'SET_STRIPE_CREDENTIALS',
                        paystack: 'SET_PAYSTACK_CREDENTIALS',
                        paypal: 'SET_PAYPAL_CREDENTIALS',
                    }[service]

                    // Commit credentials
                    this.$store.commit(commitKey, this[service].credentials)

                    this[service].allowedService = true
                    this[service].isConfigured = true
                    this[service].isVisibleCredentialsForm = false

                    // Show toaster
                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('toaster.credentials_set', {
                            service: service,
                        }),
                    })
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.isError = true
                        this.errorMessage = error.response.data.message
                    }
                })
                .finally(() => (this.isLoading = false))
        },
        subscriptionTypeChange(type) {
            events.$emit('confirm:open', {
                title: this.$t('Are you sure you want to change subscription type?'),
                message: this.$t(
                    'We strongly do not recommend change this value if there is any subscribed user to prevent any failures. You can operate only with one type of subscription and you can not change it on the fly!'
                ),
                action: {
                    type: type,
                    operation: 'change-subscription-type',
                },
            })
        },
        getWebhookEndpoint(service) {
            return `${this.config.host}/api/subscriptions/${service}/webhooks`
        },
    },
    created() {
        events.$on('action:confirmed', (data) => {
            if (data.operation === 'change-subscription-type')
                this.$updateText('/admin/settings', 'subscription_type', data.type)
        })

        // Set payment description
        this.stripe.paymentDescription = this.config.stripe_payment_description
        this.paystack.paymentDescription = this.config.paystack_payment_description
        this.paypal.paymentDescription = this.config.paypal_payment_description

        // Set if service is allowed
        this.stripe.allowedService = this.config.isStripe
        this.paystack.allowedService = this.config.isPaystack
        this.paypal.allowedService = this.config.isPayPal

        if (this.config.stripe_public_key) this.stripe.isConfigured = true

        if (this.config.paystack_public_key) this.paystack.isConfigured = true

        if (this.config.paypal_client_id) this.paypal.isConfigured = true

        this.allowedPayments = this.config.allowed_payments

        // Set metered
        this.allowedRegistrationBonus = this.config.allowed_registration_bonus
        this.registrationBonusAmount = this.config.registration_bonus_amount
    },
    destroyed() {
        events.$off('action:confirmed')
    },
}
</script>
