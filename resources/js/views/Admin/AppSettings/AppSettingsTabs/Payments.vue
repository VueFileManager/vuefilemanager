<template>
    <PageTab>
		<div class="card shadow-card">
			<FormLabel icon="dollar">
				{{ $t('Subscription Payments') }}
			</FormLabel>

			<AppInputSwitch :title="$t('Allow Subscription Payments')" :description="$t('User can subscribe to fixed or metered plan')" :is-last="! allowedPayments">
				<SwitchInput @input="$updateText('/admin/settings', 'allowedPayments', allowedPayments)" v-model="allowedPayments" :state="allowedPayments" />
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

			<AppInputSwitch :title="$t('Allow Stripe Service')" :description="$t('Allow your users pay by their credit card')" :is-last="! stripe.allowStripe">
				<SwitchInput @input="$updateText('/admin/settings', 'payments_active', stripe.allowStripe)" v-model="stripe.allowStripe" :state="stripe.allowStripe" />
			</AppInputSwitch>

			<!--Stripe credentials are set up-->
			<div v-if="stripe.allowStripe">
				<div v-if="stripe.isConfigured">
					<AppInputText :title="$t('Payment Description')" :description="$t('The description showed below user payment method selection.')">
						<textarea rows="2" @input="$updateText('/admin/settings', 'stripe_payment_description', stripe.paymentDescription, true)" v-model="stripe.paymentDescription" :placeholder="$t('Describe in short which methods user can pay with this payment method...')" type="text" class="focus-border-theme input-dark" />
					</AppInputText>

					<AppInputText :title="$t('Your Stripe Webhook URL')" :description="$t('Please copy your url and paste it to the Stripe webhook setup.')">
						<CopyInput size="small" :str="stripeWebhookEndpoint" />
					</AppInputText>

					<div @click="stripe.isVisibleCredentialsForm = !stripe.isVisibleCredentialsForm" class="flex items-center cursor-pointer" :class="{'mb-4': stripe.isVisibleCredentialsForm}">
						<edit2-icon size="14" class="vue-feather text-theme mr-2.5" />
						<b class="text-sm">{{ $t('Update Stripe Credentials') }}</b>
					</div>
				</div>

				<!--Set up Stripe credentials-->
				<ValidationObserver v-if="! stripe.isConfigured || stripe.isVisibleCredentialsForm" @submit.prevent="stripeCredentialsSubmit" ref="stripeCredentials" v-slot="{ invalid }" tag="form" class="p-5 border rounded-xl">
					<FormLabel icon="shield">
						{{ $t('Configure Your Stripe Credentials') }}
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
						{{ $t('Store Stripe Credentials') }}
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
			stripeWebhookEndpoint() {
				return `${this.config.host}/api/subscriptions/stripe/webhook`
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
				allowedPayments: true,
				stripe: {
					allowStripe: true,
					isConfigured: true,
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
			async stripeCredentialsSubmit() {

				// Validate fields
				const isValid = await this.$refs.stripeCredentials.validate();

				if (!isValid) return;

				// Start loading
				this.isLoading = true

				// Send request to get verify account
				axios
					.post('/api/admin/settings/stripe', this.stripeCredentials)
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
			axios.get('/api/admin/settings', {
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
