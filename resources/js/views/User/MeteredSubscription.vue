<template>
    <PageTab :is-loading="isLoading">

		<!-- Balance -->
		<div class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Balance') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ user.data.relationships.balance.data.attributes.formatted }}
			</b>

			<div v-if="! isCreditCardForm">

				<!-- Make payment form -->
				<ValidationObserver ref="fundAccount" @submit.prevent="makePayment" v-slot="{ invalid }" tag="form" class="mt-6">
					<ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Capacity" rules="required">
						<AppInputText :description="$t('The amount will be increased as soon as we register your charge from payment gateway.')" :error="errors[0]" :is-last="true">
							<div class="sm:flex sm:space-x-4 sm:space-y-0 space-y-4">
								<input v-model="chargeAmount"
									   :placeholder="$t('Fund Your Account Balance...')"
									   type="number"
									   min="1"
									   max="999999999"
									   class="focus-border-theme input-dark"
									   :class="{'border-red-700': errors[0]}"
								/>
								<ButtonBase type="submit" button-style="theme" class="sm:w-auto w-full">
									{{ $t('Make a Payment') }}
								</ButtonBase>
							</div>
						</AppInputText>
					</ValidationProvider>
				</ValidationObserver>

				<!-- Show credit card form -->
				<div @click="showStoreCreditCardForm" class="flex items-center md:justify-start justify-center border-t mt-5 pt-5 dark:border-opacity-5 border-light border-dashed">
					<credit-card-icon size="14" class="vue-feather text-theme" />
					<b class="cursor-pointer text-xs font-bold ml-2">
						{{ $t('Store credit card and charge automatically.') }}
					</b>
				</div>
			</div>

			<!-- Store credit card form-->
			<form v-if="isCreditCardForm" @submit.prevent="storeStripePaymentMethod" id="payment-form" class="mt-6">
				<div v-if="stripeData.isInitialization" class="h-10 relative mb-6">
					<Spinner />
				</div>
				<div id="payment-element">
				<!-- Elements will create form elements here -->
				</div>
				<ButtonBase :loading="stripeData.storingStripePaymentMethod" type="submit" button-style="theme" class="w-full mt-4">
					{{ $t('Store Credit Card') }}
				</ButtonBase>
				<div id="error-message">
					<!-- Display error message to your customers here -->
				</div>
			</form>
		</div>

		<!--Usage Estimates-->
		<div class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Usage Estimates') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ user.data.meta.usages.costEstimate }}
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				{{ user.data.relationships.subscription.data.attributes.updated_at }} {{ $t('till now') }}
			</b>

			<div>
				<div class="flex items-center justify-between py-2 border-b dark:border-opacity-5 border-light border-dashed" v-for="(usage, i) in user.data.meta.usages.featureEstimates" :key="i">
					<div class="w-2/4 leading-none">
						<b class="text-sm font-bold leading-none">
							{{ $t(usage.feature) }}
						</b>
						<small class="text-xs text-gray-500 pt-2 leading-none sm:block hidden">
							{{ $t(`feature_usage_desc_${usage.feature}`) }}
						</small>
					</div>
					<div class="text-left w-1/4">
						<span class="text-sm font-bold text-gray-560">
							{{ usage.usage }}
						</span>
					</div>
					<div class="text-right w-1/4">
						<span class="text-sm font-bold text-theme">
							{{ usage.cost }}
						</span>
					</div>
				</div>
			</div>
		</div>

		<!--Billing Alert-->
		<div class="card shadow-card">
			<FormLabel icon="bell">
                {{ $t('Billing Alert') }}
            </FormLabel>

			<div v-if="user.data.relationships.alert">
				<b class="text-3xl font-extrabold -mt-3 block mb-0.5 flex items-center">
					{{ user.data.relationships.alert.data.attributes.formatted }}
					<edit2-icon v-if="! showUpdateBillingAlertForm" @click="showUpdateBillingAlertForm = ! showUpdateBillingAlertForm" size="12" class="vue-feather cursor-pointer ml-2 transform -translate-y-0.5" />
					<trash2-icon v-if="showUpdateBillingAlertForm" @click="deleteBillingAlert" size="12" class="vue-feather cursor-pointer ml-2 transform -translate-y-0.5" />
				</b>

				<b class="block text-sm text-gray-400">
					{{ $t('Alert will be triggered after you reach the value above.') }}
				</b>
			</div>

			<ValidationObserver v-if="showUpdateBillingAlertForm" ref="updatebillingAlertForm" @submit.prevent="updateBillingAlert" v-slot="{ invalid }" tag="form" class="mt-6">
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Billing Alert" rules="required">
					<AppInputText :description="$t('You will receive an email whenever your monthly balance reaches the specified amount above.')" :error="errors[0]" :is-last="true">
						<div class="sm:flex sm:space-x-4 sm:space-y-0 space-y-4">
							<input v-model="billingAlertAmount"
								   :placeholder="$t('Alert Amount...')"
								   type="number"
								   min="1"
								   max="999999999"
								   class="focus-border-theme input-dark"
								   :class="{'border-red-700': errors[0]}"
							/>
							<ButtonBase :loadint="isSendingBillingAlert" :disabled="isSendingBillingAlert" type="submit" button-style="theme" class="sm:w-auto w-full">
								{{ $t('Update Alert') }}
							</ButtonBase>
						</div>
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>

			<ValidationObserver v-if="! user.data.relationships.alert" ref="billingAlertForm" @submit.prevent="setBillingAlert" v-slot="{ invalid }" tag="form" class="mt-6">
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Billing Alert" rules="required">
					<AppInputText :description="$t('You will receive an email whenever your monthly balance reaches the specified amount above.')" :error="errors[0]" :is-last="true">
						<div class="flex space-x-4">
							<input v-model="billingAlertAmount"
								   :placeholder="$t('Alert Amount...')"
								   type="number"
								   min="1"
								   max="999999999"
								   class="focus-border-theme input-dark"
								   :class="{'border-red-700': errors[0]}"
							/>
							<ButtonBase :loadint="isSendingBillingAlert" :disabled="isSendingBillingAlert" type="submit" button-style="theme" class="submit-button">
								{{ $t('Set Alert') }}
							</ButtonBase>
						</div>
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>
		</div>

		<!--Transactions-->
		<div class="card shadow-card">
			<FormLabel icon="file-text">
                {{ $t('Transactions') }}
            </FormLabel>

			<DatatableWrapper
				@init="isLoading = false"
				api="/api/subscriptions/transactions"
				:paginator="true"
				:columns="columns"
			>
                <template slot-scope="{ row }">
                    <tr class="border-b dark:border-opacity-5 border-light border-dashed">
                        <td class="py-4">
                            <span class="text-sm font-bold">
								{{ row.data.attributes.note }}
							</span>
                        </td>
                        <td>
							<ColorLabel v-if="config.subscriptionType === 'fixed'" :color="$getTransactionStatusColor(row.data.attributes.status)">
                                {{ row.data.attributes.status }}
							</ColorLabel>
							<ColorLabel v-if="config.subscriptionType === 'metered'" :color="$getTransactionTypeColor(row.data.attributes.type)">
                                {{ row.data.attributes.type }}
							</ColorLabel>
                        </td>
                        <td>
                            <span class="text-sm font-bold" :class="$getTransactionTypeTextColor(row.data.attributes.type)">
                                {{ $getTransactionMark(row.data.attributes.type) + row.data.attributes.price }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm font-bold">
                                {{ row.data.attributes.created_at }}
                            </span>
                        </td>
                        <td class="text-right">
                            <img class="inline-block max-h-5" :src="$getPaymentLogo(row.data.attributes.driver)" :alt="row.data.attributes.driver">
                        </td>
                    </tr>
                </template>

				<!--Empty page-->
                <template v-slot:empty-page>
                    <InfoBox>
                        <p>{{ $t('admin_page_user.invoices.empty') }}</p>
                    </InfoBox>
                </template>
            </DatatableWrapper>
		</div>
    </PageTab>
</template>

<script>
    import {
		CreditCardIcon,
		XIcon,
		Trash2Icon,
		Edit2Icon,
	} from "vue-feather-icons";
	import Spinner from "../../components/FilesView/Spinner";
	import ColorLabel from "../../components/Others/ColorLabel";
	import DatatableWrapper from "../../components/Others/Tables/DatatableWrapper";
	import AppInputText from "../../components/Admin/AppInputText";
	import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import AppInputSwitch from "../../components/Admin/AppInputSwitch"
	import FormLabel from "../../components/Others/Forms/FormLabel"
	import ProgressLine from "../../components/Admin/ProgressLine"
	import {mapGetters} from 'vuex'
	import axios from 'axios'
	import {events} from "../../bus"
	import {loadStripe} from '@stripe/stripe-js'

	// Define stripe variables
	let stripe, elements = undefined

	export default {
		name: 'MeteredSubscription',
		components: {
			CreditCardIcon,
			DatatableWrapper,
			ValidationProvider,
			ValidationObserver,
			AppInputSwitch,
			AppInputText,
			ProgressLine,
			ButtonBase,
			ColorLabel,
			Trash2Icon,
			Edit2Icon,
			XIcon,
			FormLabel,
			InfoBox,
			PageTab,
			Spinner,
		},
		computed: {
			...mapGetters([
				'isDarkMode',
				'config',
				'user',
			]),
		},
		data() {
			return {
				isLoading: false,

				isSendingBillingAlert: false,
				billingAlertAmount: undefined,
				showUpdateBillingAlertForm: false,

				columns: [
					{
						label: this.$t('Note'),
						field: 'note',
						sortable: true
					},
					{
						label: this.$t('Status'),
						field: 'status',
						sortable: true
					},
					{
						label: this.$t('admin_page_invoices.table.total'),
						field: 'amount',
						sortable: true
					},
					{
						label: this.$t('Payed At'),
						field: 'created_at',
						sortable: true
					},
					{
						label: this.$t('Service'),
						field: 'driver',
						sortable: true
					},
				],

				chargeAmount: undefined,
				isCreditCardForm: false,

				stripeData: {
					isInitialization: true,
					storingStripePaymentMethod: false
				}
			}
		},
		methods: {
			async updateBillingAlert() {

				// Validate fields
				const isValid = await this.$refs.updatebillingAlertForm.validate();

				if (!isValid) return;

				this.isSendingBillingAlert = true

				axios
					.patch(`/api/subscriptions/billing-alerts/${this.user.data.relationships.alert.data.id}`, {
						amount: this.billingAlertAmount
					})
					.then(() => {
						this.$store.dispatch('getAppData')

						this.showUpdateBillingAlertForm = false

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('Your billing alert was updated successfully'),
						})
					})
					.catch(() => {
						events.$emit('toaster', {
							type: 'danger',
							message: this.$t('popup_error.title'),
						})
					})
					.finally(() => {
						this.isSendingBillingAlert = false
					})
			},
			async setBillingAlert() {

				// Validate fields
				const isValid = await this.$refs.billingAlertForm.validate();

				if (!isValid) return;

				this.isSendingBillingAlert = true

				axios
					.post('/api/subscriptions/billing-alerts', {
						amount: this.billingAlertAmount
					})
					.then(() => {
						this.$store.dispatch('getAppData')

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('Your billing alert was set successfully'),
						})
					})
					.catch(() => {
						events.$emit('toaster', {
							type: 'danger',
							message: this.$t('popup_error.title'),
						})
					})
					.finally(() => {
						this.isSendingBillingAlert = false
					})
			},
			deleteBillingAlert() {
				events.$emit('confirm:open', {
					title: this.$t('Are you sure you want to delete your alert?'),
					message: this.$t('You will no longer receive any notifications that your billing limit has been exceeded.'),
					action: {
						id: this.user.data.relationships.alert.data.id,
						operation: 'delete-billing-alert',
					}
				})
			},
			async makePayment() {
				// Validate fields
				const isValid = await this.$refs.fundAccount.validate();

				if (!isValid) return;

				// Show payment methods popup
				this.$store.dispatch('callSingleChargeProcess', this.chargeAmount)
			},
			async storeStripePaymentMethod() {

				this.stripeData.storingStripePaymentMethod = true

				const {error} = await stripe.confirmSetup({
					//`Elements` instance that was used to create the Payment Element
					elements,
					redirect: 'if_required',
					confirmParams: {
						return_url: window.location.href,
					}
				});

				if (error) {
					// This point will only be reached if there is an immediate error when
					// confirming the payment. Show error to your customer (e.g., payment
					// details incomplete)
					const messageContainer = document.querySelector('#error-message');
					messageContainer.textContent = error.message;
				} else {
					// Your customer will be redirected to your `return_url`. For some payment
					// methods like iDEAL, your customer will be redirected to an intermediate
					// site first to authorize the payment, then redirected to the `return_url`.
					events.$emit('toaster', {
						type: 'success',
						message: this.$t('Your credit card was stored successfully'),
					})
				}

				this.stripeData.storingStripePaymentMethod = false
			},
			async stripeInit() {

				// Init stripe js
				stripe = await loadStripe(this.config.stripe_public_key);

				await axios.get('/api/stripe/setup-intent')
					.then(response => {
						// Set up Stripe.js and Elements to use in checkout form, passing the client secret obtained in step 2
						elements = stripe.elements({
							clientSecret: response.data,
							appearance: {
								theme: 'stripe',
								variables: {
									colorPrimary: this.config.app_color,
									fontFamily: 'Nunito',
									borderRadius: '8px',
									colorText: this.isDarkMode ? '#bec6cf' : '#1B2539',
									colorBackground: this.isDarkMode ? '#191b1e' : '#fff',
									fontWeightNormal: '700',
									fontSizeSm: '0.875rem',
									colorSuccessText: '#0ABB87',
									colorSuccess: '#0ABB87',
									colorWarning: '#fd397a',
									colorWarningText: '#fd397a',
									colorDangerText: '#fd397a',
									colorTextSecondary: '#6b7280',
									spacingGridRow: '20px',
								}
							},
						});

						// Create and mount the Payment Element
						const paymentElement = elements.create('payment');

						paymentElement.mount('#payment-element');
					})
					.catch(() => {
						events.$emit('toaster', {
							type: 'danger',
							message: this.$t('popup_error.title'),
						})
					})

				this.stripeData.isInitialization = false
			},
			showStoreCreditCardForm() {
				this.isCreditCardForm = !this.isCreditCardForm
				this.stripeInit()
			}
		},
		created() {
			events.$on('action:confirmed', data => {

				if (data.operation === 'delete-billing-alert')
					axios.delete(`/api/subscriptions/billing-alerts/${this.user.data.relationships.alert.data.id}`)
						.then(() => {
							this.$store.dispatch('getAppData')

							this.showUpdateBillingAlertForm = false
							this.billingAlertAmount = undefined

							events.$emit('toaster', {
								type: 'success',
								message: this.$t('Your billing alert was deleted.'),
							})
						})
						.catch(() => this.$isSomethingWrong())
			})
		}
	}
</script>
