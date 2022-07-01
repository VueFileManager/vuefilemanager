<template>
    <PageTab>
        <!--Global payment settings-->
        <div class="card shadow-card">
            <FormLabel icon="dollar">
                {{ $t('subscription_payments') }}
            </FormLabel>

            <AppInputSwitch
                :title="$t('allow_subscription_payments')"
                :description="$t('allow_subscription_payments_description')"
                :is-last="true"
            >
                <SwitchInput
                    @input="updateAllowedPayments"
                    v-model="allowedPayments"
                    :state="allowedPayments"
                />
            </AppInputSwitch>
        </div>

        <!--Metered settings-->
        <div v-if="config.subscriptionType === 'metered' && allowedPayments" class="card shadow-card">
            <FormLabel icon="bar-chart">
                {{ $t('metered_billing_settings') }}
            </FormLabel>

            <AppInputSwitch
                :title="$t('allow_registration_bonus')"
                :description="$t('allow_registration_bonus_description')"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'allowed_registration_bonus', allowedRegistrationBonus)"
                    v-model="allowedRegistrationBonus"
                    :state="allowedRegistrationBonus"
                />
            </AppInputSwitch>

            <AppInputText
                v-if="allowedRegistrationBonus"
                :title="$t('registration_bonus_amount')"
                :description="$t('registration_bonus_amount_description')"
            >
                <input
                    @input="$updateText('/admin/settings', 'registration_bonus_amount', registrationBonusAmount)"
                    v-model="registrationBonusAmount"
                    :placeholder="$t('registration_bonus_amount_')"
                    type="number"
                    class="focus-border-theme input-dark"
                />
            </AppInputText>

            <AppInputButton
                :title="$t('metered_plan')"
                :description="$t('metered_plan_description')"
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
                        {{ $t('plan_details') }}
                    </ButtonBase>
                </router-link>

                <router-link v-if="!config.isCreatedMeteredPlan" :to="{ name: 'CreateMeteredPlan' }">
                    <ButtonBase class="w-full sm:w-auto" button-style="theme-solid">
                        {{ $t('create_plan') }}
                    </ButtonBase>
                </router-link>
            </AppInputButton>
        </div>

		<!--Fraud Prevention Mechanism Rules-->
        <div v-if="config.subscriptionType === 'metered' && allowedPayments" class="card shadow-card">
            <FormLabel icon="shield">
                {{ $t('Usage Restriction Rules for User Accounts') }}
            </FormLabel>

            <AppInputSwitch
                :title="$t('allow_limit_usage_in_new_accounts')"
                :description="$t('limit_usage_description_for_restrictions')"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'limit_usage_in_new_accounts', settings.limitUsageInNewAccounts)"
                    v-model="settings.limitUsageInNewAccounts"
                    :state="settings.limitUsageInNewAccounts"
                />
            </AppInputSwitch>

			<AppInputText
				v-if="settings.limitUsageInNewAccounts"
				class="-mt-3"
			>
                <input
					@input="$updateText('/admin/settings', 'limit_usage_in_new_accounts_amount', settings.limitUsageInNewAccountsAmount)"
					v-model="settings.limitUsageInNewAccountsAmount"
					:placeholder="$t('Max Usage Amount...')"
					type="number"
					class="focus-border-theme input-dark"
				/>
            </AppInputText>

            <AppInputSwitch
                :title="$t('allow_limit_usage_bigger_than_balance')"
                :description="$t('limit_usage_description_for_restrictions')"
				:is-last="true"
            >
                <SwitchInput
                    @input="$updateText('/admin/settings', 'usage_bigger_than_balance', settings.usageBiggerThanBalance)"
                    v-model="settings.usageBiggerThanBalance"
                    :state="settings.usageBiggerThanBalance"
                />
            </AppInputSwitch>
        </div>

        <!--Stripe method configuration-->
        <div v-if="allowedPayments" class="card shadow-card">
            <img :src="$getPaymentLogo('stripe')" alt="Stripe" class="mb-8 h-8" />

            <AppInputSwitch
                :title="$t('Allow Stripe Service')"
                :description="$t('allow_pay_by_card')"
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
				<AppInputText
					:title="$t('webhook_url')"
					:description="$t('copy_webhook_note')"
				>
					<CopyInput size="small" :str="getWebhookEndpoint('stripe')" />
				</AppInputText>

                <div v-if="stripe.isConfigured">
                    <AppInputText
                        @input="$updateText('/admin/settings', 'stripe_payment_description', stripe.paymentDescription)"
                        :title="$t('payment_description')"
                        :description="$t('payment_description_note')"
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
                                $t('additional_info_about_payment_method_')
                            "
                            type="text"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>

                    <div
                        @click="stripe.isVisibleCredentialsForm = !stripe.isVisibleCredentialsForm"
                        class="flex cursor-pointer items-center"
                        :class="{ 'mb-4': stripe.isVisibleCredentialsForm }"
                    >
                        <edit2-icon size="14" class="vue-feather text-theme mr-2.5" />
                        <b class="text-sm">{{ $t('update_your_credentials') }}</b>
                    </div>
                </div>

                <!--Set up Stripe credentials-->
                <ValidationObserver
                    v-if="!stripe.isConfigured || stripe.isVisibleCredentialsForm"
                    @submit.prevent="storeCredentials('stripe')"
                    ref="stripe"
                    v-slot="{ invalid }"
                    tag="form"
                    class="rounded-xl p-5 shadow-lg"
                >
                    <FormLabel v-if="!stripe.isConfigured" icon="shield">
                        {{ $t('configure_your_credentials') }}
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
                                :class="{ '!border-rose-600': errors[0] }"
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
                                :class="{ '!border-rose-600': errors[0] }"
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
                                :placeholder="$t('paste_webhook_secret')"
                                type="text"
                                :class="{ '!border-rose-600': errors[0] }"
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
                        {{ $t('store_credentials') }}
                    </ButtonBase>
                </ValidationObserver>
            </div>
        </div>

        <!--Paystack method configuration-->
        <div v-if="allowedPayments" class="card shadow-card">
            <img :src="$getPaymentLogo('paystack')" alt="Paystack" class="mb-8 h-7" />

            <AppInputSwitch
                :title="$t('Allow Paystack Service')"
                :description="$t('allow_pay_by_card')"
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
				<AppInputText
					:title="$t('webhook_url')"
					:description="$t('copy_webhook_note')"
				>
					<CopyInput size="small" :str="getWebhookEndpoint('paystack')" />
				</AppInputText>

                <div v-if="paystack.isConfigured">
                    <AppInputText
                        @input="
                            $updateText('/admin/settings', 'paystack_payment_description', paystack.paymentDescription)
                        "
                        :title="$t('payment_description')"
                        :description="$t('payment_description_note')"
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
                                $t('additional_info_about_payment_method_')
                            "
                            type="text"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>

                    <div
                        @click="paystack.isVisibleCredentialsForm = !paystack.isVisibleCredentialsForm"
                        class="flex cursor-pointer items-center"
                        :class="{ 'mb-4': paystack.isVisibleCredentialsForm }"
                    >
                        <edit2-icon size="14" class="vue-feather text-theme mr-2.5" />
                        <b class="text-sm">{{ $t('update_your_credentials') }}</b>
                    </div>
                </div>

                <!--Set up Paystack credentials-->
                <ValidationObserver
                    v-if="!paystack.isConfigured || paystack.isVisibleCredentialsForm"
                    @submit.prevent="storeCredentials('paystack')"
                    ref="paystack"
                    v-slot="{ invalid }"
                    tag="form"
                    class="rounded-xl p-5 shadow-lg"
                >
                    <FormLabel v-if="!paystack.isConfigured" icon="shield">
                        {{ $t('configure_your_credentials') }}
                    </FormLabel>
                    <ValidationProvider tag="div" mode="passive" name="Secret Key" rules="required" v-slot="{ errors }">
                        <AppInputText :title="$t('admin_settings.payments.stripe_sec_key')" :error="errors[0]">
                            <input
                                v-model="paystack.credentials.secret"
                                :placeholder="$t('admin_settings.payments.stripe_sec_key_plac')"
                                type="text"
                                :class="{ '!border-rose-600': errors[0] }"
                                class="focus-border-theme input-dark"
                            />
                        </AppInputText>
                    </ValidationProvider>
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
                                :class="{ '!border-rose-600': errors[0] }"
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
                        {{ $t('store_credentials') }}
                    </ButtonBase>
                </ValidationObserver>
            </div>
        </div>

        <!--PayPal method configuration-->
        <div v-if="allowedPayments" class="card shadow-card">
            <img :src="$getPaymentLogo('paypal')" alt="PayPal" class="mb-8 h-8" />

            <AppInputSwitch
                :title="$t('Allow PayPal Service')"
                :description="$t('allow_pay_by_card')"
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
				<AppInputText
					:title="$t('webhook_url')"
					:description="$t('copy_webhook_note')"
				>
					<CopyInput size="small" :str="getWebhookEndpoint('paypal')" />
				</AppInputText>

                <div v-if="paypal.isConfigured">
                    <AppInputSwitch :title="$t('Live Mode')" :description="$t('Toggle between sandbox and live mode')">
                        <SwitchInput
                            @input="$updateText('/admin/settings', 'paypal_live', config.isPayPalLive)"
                            v-model="config.isPayPalLive"
                            :state="config.isPayPalLive"
                        />
                    </AppInputSwitch>

                    <AppInputText
                        @input="$updateText('/admin/settings', 'paypal_payment_description', paypal.paymentDescription)"
                        :title="$t('payment_description')"
                        :description="$t('payment_description_note')"
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
                                $t('additional_info_about_payment_method_')
                            "
                            type="text"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>

                    <div
                        @click="paypal.isVisibleCredentialsForm = !paypal.isVisibleCredentialsForm"
                        class="flex cursor-pointer items-center"
                        :class="{ 'mb-4': paypal.isVisibleCredentialsForm }"
                    >
                        <edit2-icon size="14" class="vue-feather text-theme mr-2.5" />
                        <b class="text-sm">{{ $t('update_your_credentials') }}</b>
                    </div>
                </div>

                <!--Set up Paypal credentials-->
                <ValidationObserver
                    v-if="!paypal.isConfigured || paypal.isVisibleCredentialsForm"
                    @submit.prevent="storeCredentials('paypal')"
                    ref="paypal"
                    v-slot="{ invalid }"
                    tag="form"
                    class="rounded-xl p-5 shadow-lg"
                >
                    <FormLabel v-if="!paypal.isConfigured" icon="shield">
                        {{ $t('configure_your_credentials') }}
                    </FormLabel>

					<ValidationProvider>
						<AppInputSwitch :title="$t('Live Mode')" :description="$t('Toggle between sandbox and live mode')">
							<SwitchInput v-model="paypal.credentials.live" :state="paypal.credentials.live" />
						</AppInputSwitch>
					</ValidationProvider>

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
                                :class="{ '!border-rose-600': errors[0] }"
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
                                :class="{ '!border-rose-600': errors[0] }"
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
                                :class="{ '!border-rose-600': errors[0] }"
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
                        {{ $t('store_credentials') }}
                    </ButtonBase>
                </ValidationObserver>
            </div>
        </div>
    </PageTab>
</template>

<script>
import { Edit2Icon, Trash2Icon } from 'vue-feather-icons'
import AppInputButton from '../../../../components/Forms/Layouts/AppInputButton'
import DatatableWrapper from '../../../../components/UI/Table/DatatableWrapper'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PageTabGroup from '../../../../components/Layout/PageTabGroup'
import SelectInput from '../../../../components/Inputs/SelectInput'
import SwitchInput from '../../../../components/Inputs/SwitchInput'
import ImageInput from '../../../../components/Inputs/ImageInput'
import AppInputSwitch from '../../../../components/Forms/Layouts/AppInputSwitch'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import CopyInput from '../../../../components/Inputs/CopyInput'
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import PageTab from '../../../../components/Layout/PageTab'
import InfoBox from '../../../../components/UI/Others/InfoBox'
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
        required,
        PageTab,
        InfoBox,
    },
	watch: {
		'paypal.credentials.live': function (val) {
			this.$updateText('/admin/settings', 'paypal_live', val)

			this.$store.commit('REPLACE_CONFIG_VALUE', {
				key: 'isPayPalLive',
				value: val,
			})
		}
	},
    computed: {
        ...mapGetters([
			'config'
		]),
    },
    data() {
        return {
			settings: undefined,
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
					live: false,
                },
            },
            columns: [
                {
                    label: this.$t('name'),
                    field: 'name',
                    sortable: true,
                },
                {
                    label: this.$t('currency'),
                    field: 'currency',
                    sortable: true,
                },
                {
                    label: this.$t('interval'),
                    field: 'interval',
                    sortable: true,
                },
                {
                    label: this.$t('subscribers'),
                    sortable: false,
                },
                {
                    label: this.$t('action'),
                    sortable: false,
                },
            ],
        }
    },
    methods: {
        async storeCredentials(service) {
            // Validate fields
            const isValid = await this.$refs[service].validate()

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
                    live: this[service].credentials.live,
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
					if ([401, 500].includes(error.response.status)) {
						events.$emit('alert:open', {
							title: error.response.data.title,
							message: error.response.data.message,
						})
					} else {
						events.$emit('toaster', {
							type: 'danger',
							message: this.$t('popup_error.title'),
						})
					}
                })
                .finally(() => (this.isLoading = false))
        },
        getWebhookEndpoint(service) {
            return `${this.config.host}/api/subscriptions/${service}/webhooks`
        },
		updateAllowedPayments(val) {
			// Update value
			this.$updateText('/admin/settings', 'allowed_payments', val)

			// Update config value
			this.$store.commit('REPLACE_CONFIG_VALUE', {
				key: 'allowed_payments',
				value: val,
			})
		}
    },
	mounted() {
		axios
			.get('/api/admin/settings', {
				params: {
					column: 'limit_usage_in_new_accounts|limit_usage_in_new_accounts_amount|usage_bigger_than_balance',
				},
			})
			.then((response) => {
				this.isLoading = false

				this.settings = {
					limitUsageInNewAccounts: parseInt(response.data.limit_usage_in_new_accounts),
					limitUsageInNewAccountsAmount: parseInt(response.data.limit_usage_in_new_accounts_amount),
					usageBiggerThanBalance: parseInt(response.data.usage_bigger_than_balance),
				}
			})
	},
    created() {
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
