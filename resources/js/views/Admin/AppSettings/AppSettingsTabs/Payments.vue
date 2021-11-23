<template>
    <PageTab :is-loading="isLoading">

		<!--Stripe Information-->
		<PageTabGroup v-if="config.stripe_public_key && payments">
			<div class="card shadow-card">
				<FormLabel>
					{{ $t('admin_settings.payments.section_payments') }}
				</FormLabel>
				<InfoBox>
					<p v-html="$t('admin_settings.payments.credentials_disclaimer')"></p>
				</InfoBox>
				<AppInputSwitch :title="$t('admin_settings.payments.allow_payments')">
					<SwitchInput @input="$updateText('/admin/settings', 'payments_active', payments.status)" v-model="payments.status" class="switch" :state="payments.status" />
				</AppInputSwitch>
				<AppInputText :title="$t('admin_settings.payments.webhook_url')">
					<input :value="stripeWebhookEndpoint" type="text" class="focus-border-theme input-dark" disabled />
				</AppInputText>
			</div>
		</PageTabGroup>

		<!--Stripe Set up-->
		<PageTabGroup v-if="! config.stripe_public_key">
			<ValidationObserver @submit.prevent="stripeCredentialsSubmit" ref="stripeCredentials" v-slot="{ invalid }" tag="form" class="card shadow-card">
				<FormLabel>
					{{ $t('admin_settings.payments.stripe_setup') }}
				</FormLabel>
				<InfoBox>
					<p v-html="$t('admin_settings.payments.stripe_create_acc')"></p>
				</InfoBox>
				<ValidationProvider tag="div" mode="passive" name="Currency" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('admin_settings.payments.stripe_currency')" :error="errors[0]">
						<SelectInput v-model="stripeCredentials.currency" :options="currencyList" :placeholder="$t('admin_settings.payments.stripe_currency_plac')" :isError="errors[0]" />
					</AppInputText>
				</ValidationProvider>
				<ValidationProvider tag="div" mode="passive" name="Publishable Key" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('admin_settings.payments.stripe_pub_key')" :error="errors[0]">
						<input v-model="stripeCredentials.key" :placeholder="$t('admin_settings.payments.stripe_pub_key_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme input-dark" />
					</AppInputText>
				</ValidationProvider>
				<ValidationProvider tag="div" mode="passive" name="Secret Key" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('admin_settings.payments.stripe_sec_key')" :error="errors[0]">
						<input v-model="stripeCredentials.secret" :placeholder="$t('admin_settings.payments.stripe_sec_key_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme input-dark" />
					</AppInputText>
				</ValidationProvider>
				<ValidationProvider tag="div" mode="passive" name="Webhook URL" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('Webhook URL')" :error="errors[0]">
						<InfoBox>
							<p v-html="$t('admin_settings.payments.stripe_create_webhook')"></p>
						</InfoBox>
						<input :value="stripeWebhookEndpoint" type="text" class="focus-border-theme input-dark" disabled />
					</AppInputText>
				</ValidationProvider>
				<ValidationProvider tag="div" mode="passive" name="Webhook Secret" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('Webhook Secret')" :error="errors[0]">
						<input v-model="stripeCredentials.webhookSecret" :placeholder="$t('admin_settings.payments.stripe_webhook_key_plac')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme input-dark" />
					</AppInputText>
				</ValidationProvider>
				<InfoBox v-if="isError" type="error">
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
	import AppInputText from "../../../../components/Admin/AppInputText";
	import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import StorageItemDetail from '/resources/js/components/Others/StorageItemDetail'
	import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
	import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
	import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
	import ImageInput from '/resources/js/components/Others/Forms/ImageInput'
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import {required} from 'vee-validate/dist/rules'
	import {mapGetters} from 'vuex'
	import {events} from '/resources/js/bus'
	import axios from 'axios'
	import AppInputSwitch from "../../../../components/Admin/AppInputSwitch";

	export default {
		name: 'AppPayments',
		components: {
			AppInputSwitch,
			AppInputText,
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
