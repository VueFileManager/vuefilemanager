<template>
    <PageTab>
		<!--Global payment settings-->
		<div v-if="false" class="card shadow-card">
			<FormLabel icon="dollar">
				{{ $t('Subscription Payments') }}
			</FormLabel>

			<AppInputSwitch :title="$t('Allow Subscription Payments')" :description="$t('User can subscribe to fixed or metered plan')" :is-last="! allowedPayments">
				<SwitchInput @input="$updateText('/admin/settings', 'allowed_payments', allowedPayments)" v-model="allowedPayments" :state="allowedPayments" />
			</AppInputSwitch>

			<AppInputText v-if="allowedPayments" :title="$t('Subscription Type')" :is-last="true">
				<SelectInput :default="config.subscriptionType" :options="subscriptionTypes" :placeholder="$t('Select your subscription type')"/>
			</AppInputText>
		</div>

		<!--Stripe method configuration-->
		<div v-if="allowedPayments" class="card shadow-card">
			<FormLabel icon="credit-card">
				{{ $t('Stripe') }}
			</FormLabel>

			<AppInputSwitch :title="$t('Allow Stripe Service')" :description="$t('Allow your users pay by their credit card')" :is-last="! stripe.allowedService">
				<SwitchInput @input="$updateText('/admin/settings', 'allowed_stripe', stripe.allowedService)" v-model="stripe.allowedService" :state="stripe.allowedService" />
			</AppInputSwitch>

			<!--Stripe credentials are set up-->
			<div v-if="stripe.allowedService">
				<div v-if="stripe.isConfigured">
					<AppInputText @input="$updateText('/admin/settings', 'stripe_payment_description', stripe.paymentDescription)" :title="$t('Payment Description')" :description="$t('The description showed below user payment method selection.')">
						<textarea rows="2" @input="$updateText('/admin/settings', 'stripe_payment_description', stripe.paymentDescription, true)" v-model="stripe.paymentDescription" :placeholder="$t('Describe in short which methods user can pay with this payment method...')" type="text" class="focus-border-theme input-dark" />
					</AppInputText>

					<AppInputText :title="$t('Your Webhook URL')" :description="$t('Please copy your url and paste it to the service webhook setup.')">
						<CopyInput size="small" :str="getWebhookEndpoint('stripe')" />
					</AppInputText>

					<div @click="stripe.isVisibleCredentialsForm = !stripe.isVisibleCredentialsForm" class="flex items-center cursor-pointer" :class="{'mb-4': stripe.isVisibleCredentialsForm}">
						<edit2-icon size="14" class="vue-feather text-theme mr-2.5" />
						<b class="text-sm">{{ $t('Update Your Credentials') }}</b>
					</div>
				</div>

				<!--Set up Stripe credentials-->
				<ValidationObserver
					v-if="! stripe.isConfigured || stripe.isVisibleCredentialsForm"
					@submit.prevent="storeCredentials('stripe')"
					ref="credentialsForm"
					v-slot="{ invalid }"
					tag="form"
					class="p-5 border rounded-xl"
				>
					<FormLabel icon="shield">
						{{ $t('Configure Your Credentials') }}
					</FormLabel>
					<ValidationProvider tag="div" mode="passive" name="Publishable Key" rules="required" v-slot="{ errors }">
						<AppInputText :title="$t('admin_settings.payments.stripe_pub_key')" :error="errors[0]">
							<input v-model="stripe.credentials.key" :placeholder="$t('admin_settings.payments.stripe_pub_key_plac')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
						</AppInputText>
					</ValidationProvider>
					<ValidationProvider tag="div" mode="passive" name="Secret Key" rules="required" v-slot="{ errors }">
						<AppInputText :title="$t('admin_settings.payments.stripe_sec_key')" :error="errors[0]">
							<input v-model="stripe.credentials.secret" :placeholder="$t('admin_settings.payments.stripe_sec_key_plac')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
						</AppInputText>
					</ValidationProvider>

					<ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit" class="w-full">
						{{ $t('Store Credentials') }}
					</ButtonBase>
				</ValidationObserver>
			</div>
		</div>

		<!--Paystack method configuration-->
		<div v-if="allowedPayments" class="card shadow-card">
			<FormLabel icon="credit-card">
				{{ $t('Paystack') }}
			</FormLabel>

			<AppInputSwitch :title="$t('Allow Paystack Service')" :description="$t('Allow your users pay by their credit card')" :is-last="! paystack.allowedService">
				<SwitchInput @input="$updateText('/admin/settings', 'allowed_paystack', paystack.allowedService)" v-model="paystack.allowedService" :state="paystack.allowedService" />
			</AppInputSwitch>

			<!--Stripe credentials are set up-->
			<div v-if="paystack.allowedService">
				<div v-if="paystack.isConfigured">
					<AppInputText @input="$updateText('/admin/settings', 'paystack_payment_description', paystack.paymentDescription)" :title="$t('Payment Description')" :description="$t('The description showed below user payment method selection.')">
						<textarea rows="2" @input="$updateText('/admin/settings', 'paystack_payment_description', paystack.paymentDescription, true)" v-model="paystack.paymentDescription" :placeholder="$t('Describe in short which methods user can pay with this payment method...')" type="text" class="focus-border-theme input-dark" />
					</AppInputText>

					<AppInputText :title="$t('Your Webhook URL')" :description="$t('Please copy your url and paste it to the service webhook setup.')">
						<CopyInput size="small" :str="getWebhookEndpoint('paystack')" />
					</AppInputText>

					<div @click="paystack.isVisibleCredentialsForm = !paystack.isVisibleCredentialsForm" class="flex items-center cursor-pointer" :class="{'mb-4': paystack.isVisibleCredentialsForm}">
						<edit2-icon size="14" class="vue-feather text-theme mr-2.5" />
						<b class="text-sm">{{ $t('Update Your Credentials') }}</b>
					</div>
				</div>

				<!--Set up Paystack credentials-->
				<ValidationObserver
					v-if="! paystack.isConfigured || paystack.isVisibleCredentialsForm"
					@submit.prevent="storeCredentials('paystack')"
					ref="credentialsForm"
					v-slot="{ invalid }"
					tag="form"
					class="p-5 border rounded-xl"
				>
					<FormLabel icon="shield">
						{{ $t('Configure Your Credentials') }}
					</FormLabel>
					<ValidationProvider tag="div" mode="passive" name="Publishable Key" rules="required" v-slot="{ errors }">
						<AppInputText :title="$t('admin_settings.payments.stripe_pub_key')" :error="errors[0]">
							<input v-model="paystack.credentials.key" :placeholder="$t('admin_settings.payments.stripe_pub_key_plac')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
						</AppInputText>
					</ValidationProvider>
					<ValidationProvider tag="div" mode="passive" name="Secret Key" rules="required" v-slot="{ errors }">
						<AppInputText :title="$t('admin_settings.payments.stripe_sec_key')" :error="errors[0]">
							<input v-model="paystack.credentials.secret" :placeholder="$t('admin_settings.payments.stripe_sec_key_plac')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
						</AppInputText>
					</ValidationProvider>

					<ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit" class="w-full">
						{{ $t('Store Credentials') }}
					</ButtonBase>
				</ValidationObserver>
			</div>
		</div>

		<!--PayPal method configuration-->
		<div v-if="allowedPayments" class="card shadow-card">
			<FormLabel icon="credit-card">
				{{ $t('PayPal') }}
			</FormLabel>

			<AppInputSwitch :title="$t('Allow PayPal Service')" :description="$t('Allow your users pay by their credit card')" :is-last="! paypal.allowedService">
				<SwitchInput @input="$updateText('/admin/settings', 'allowed_paypal', paypal.allowedService)" v-model="paypal.allowedService" :state="paypal.allowedService" />
			</AppInputSwitch>

			<!--Stripe credentials are set up-->
			<div v-if="paypal.allowedService">
				<div v-if="paypal.isConfigured">
					<AppInputText @input="$updateText('/admin/settings', 'paypal_payment_description', paypal.paymentDescription)" :title="$t('Payment Description')" :description="$t('The description showed below user payment method selection.')">
						<textarea rows="2" @input="$updateText('/admin/settings', 'paypal_payment_description', paypal.paymentDescription, true)" v-model="paypal.paymentDescription" :placeholder="$t('Describe in short which methods user can pay with this payment method...')" type="text" class="focus-border-theme input-dark" />
					</AppInputText>

					<AppInputText :title="$t('Your Webhook URL')" :description="$t('Please copy your url and paste it to the service webhook setup.')">
						<CopyInput size="small" :str="getWebhookEndpoint('paypal')" />
					</AppInputText>

					<div @click="paypal.isVisibleCredentialsForm = !paypal.isVisibleCredentialsForm" class="flex items-center cursor-pointer" :class="{'mb-4': paypal.isVisibleCredentialsForm}">
						<edit2-icon size="14" class="vue-feather text-theme mr-2.5" />
						<b class="text-sm">{{ $t('Update Your Credentials') }}</b>
					</div>
				</div>

				<!--Set up Paypal credentials-->
				<ValidationObserver
					v-if="! paypal.isConfigured || paypal.isVisibleCredentialsForm"
					@submit.prevent="storeCredentials('paypal')"
					ref="credentialsForm"
					v-slot="{ invalid }"
					tag="form"
					class="p-5 border rounded-xl"
				>
					<FormLabel icon="shield">
						{{ $t('Configure Your Credentials') }}
					</FormLabel>
					<ValidationProvider tag="div" mode="passive" name="Publishable Key" rules="required" v-slot="{ errors }">
						<AppInputText :title="$t('admin_settings.payments.stripe_pub_key')" :error="errors[0]">
							<input v-model="paypal.credentials.key" :placeholder="$t('admin_settings.payments.stripe_pub_key_plac')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
						</AppInputText>
					</ValidationProvider>
					<ValidationProvider tag="div" mode="passive" name="Secret Key" rules="required" v-slot="{ errors }">
						<AppInputText :title="$t('admin_settings.payments.stripe_sec_key')" :error="errors[0]">
							<input v-model="paypal.credentials.secret" :placeholder="$t('admin_settings.payments.stripe_sec_key_plac')" type="text" :class="{'border-red': errors[0]}" class="focus-border-theme input-dark" />
						</AppInputText>
					</ValidationProvider>

					<ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit" class="w-full">
						{{ $t('Store Credentials') }}
					</ButtonBase>
				</ValidationObserver>
			</div>
		</div>
	</PageTab>
</template>

<script>
    import {
		Edit2Icon,
	} from 'vue-feather-icons'
	import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
	import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
	import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
	import ImageInput from '/resources/js/components/Others/Forms/ImageInput'
	import AppInputSwitch from "../../../../components/Admin/AppInputSwitch"
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import CopyInput from "../../../../components/Others/Forms/CopyInput"
	import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
	import AppInputText from "../../../../components/Admin/AppInputText"
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import {required} from 'vee-validate/dist/rules'
	import {events} from '/resources/js/bus'
	import {mapGetters} from 'vuex'
	import axios from 'axios'

	export default {
		name: 'AppPayments',
		components: {
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
			Edit2Icon,
			SetupBox,
			required,
			PageTab,
			InfoBox,
		},
		computed: {
			...mapGetters([
				'subscriptionTypes',
				'config',
			]),
			submitButtonText() {
				return this.isLoading ? this.$t('admin_settings.payments.button_testing') : this.$t('admin_settings.payments.button_submit')
			}
		},
		data() {
			return {
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
						key: 'test',
						secret: 'test',
					}
				},
				paystack: {
					allowedService: true,
					isConfigured: false,
					isVisibleCredentialsForm: false,
					paymentDescription: undefined,
					credentials: {
						key: undefined,
						secret: undefined,
					}
				},
				paypal: {
					allowedService: true,
					isConfigured: false,
					isVisibleCredentialsForm: false,
					paymentDescription: undefined,
					credentials: {
						key: undefined,
						secret: undefined,
					}
				},
			}
		},
		methods: {
			getWebhookEndpoint(service) {
				return `${this.config.host}/api/subscriptions/${service}/webhook`
			},
			async storeCredentials(service) {

				// Validate fields
				const isValid = await this.$refs.credentialsForm.validate();

				if (!isValid) return;

				// Start loading
				this.isLoading = true

				let credentials = {
					stripe: {
						service: 'stripe',
						key: this.stripe.credentials.key,
						secret: this.stripe.credentials.secret,
					},
					paystack: {
						service: 'paystack',
						key: this.paystack.credentials.key,
						secret: this.paystack.credentials.secret,
					},
					paypal: {
						service: 'paypal',
						key: this.paypal.credentials.key,
						secret: this.paypal.credentials.secret,
					},
				}

				// Send request to get verify account
				axios
					.post('/api/admin/settings/payment-service', credentials[service])
					.then(() => {

						// Update Credentials
						let commitKey = {
							stripe: 'SET_STRIPE_CREDENTIALS',
							paystack: 'SET_PAYSTACK_CREDENTIALS',
							paypal: 'SET_PAYPAL_CREDENTIALS',
						}[service]

						// Commit credentials
						this.$store.commit(commitKey, credentials[service])

						this[service].allowedService = true
						this[service].isConfigured = true
						this[service].isVisibleCredentialsForm = false

						// Show toaster
						events.$emit('toaster', {
							type: 'success',
							message: this.$t('toaster.credentials_set', {service: service}),
						})
					})
					.catch(error => {

						if (error.response.status === 500) {
							this.isError = true
							this.errorMessage = error.response.data.message
						}
					})
					.finally(() => this.isLoading = false)
			},
		},
		mounted() {
			// Set payment description
			this.stripe.paymentDescription = this.config.stripe_payment_description
			this.paystack.paymentDescription = this.config.paystack_payment_description
			this.paypal.paymentDescription = this.config.paypal_payment_description

			// Set if service is allowed
			this.stripe.allowedService = this.config.isStripe
			this.paystack.allowedService = this.config.isPaystack
			this.paypal.allowedService = this.config.isPayPal

			if (this.config.stripe_public_key)
				this.stripe.isConfigured = true

			if (this.config.paystack_public_key)
				this.paystack.isConfigured = true

			if (this.config.paypal_client_id)
				this.paypal.isConfigured = true

			this.allowedPayments = this.config.allowed_payments
		}
	}
</script>
