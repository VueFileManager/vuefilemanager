<template>
	<div v-if="canShowForMeteredBilling || canShowForFixedBilling" class="card shadow-card">
		<FormLabel icon="credit-card">
			{{ $t('Payment Method') }}
		</FormLabel>

		<!-- User has registered payment method -->
		<div v-if="hasPaymentMethod">
			<b v-if="config.subscriptionType === 'metered' && user.data.relationships.balance.data.attributes.balance > 0" class="mb-3 block text-sm mb-5">
				{{ $t('credit_to_auto_withdraw', {credit: user.data.relationships.balance.data.attributes.formatted}) }}
			</b>

			<!-- Card -->
			<PaymentCard
				v-for="card in user.data.relationships.creditCards.data"
				:key="card.data.id"
				:card="card"
			/>

			<small class="text-xs text-gray-500 pt-3 leading-none sm:block hidden">
				{{ $t('We are settling your payment automatically via your saved credit card.') }}
			</small>
		</div>

		<!-- User doesn't have registered payment method -->
		<div v-if="! hasPaymentMethod">

			<!-- Show credit card form -->
			<ButtonBase @click.native="showStoreCreditCardForm" v-if="! isCreditCardForm" :loading="stripe.storingStripePaymentMethod" type="submit" button-style="theme" class="w-full mt-4">
				{{ $t('Add Payment Method') }}
			</ButtonBase>

			<!-- Store credit card form -->
			<form v-if="isCreditCardForm" @submit.prevent="storeStripePaymentMethod" id="payment-form" class="mt-6">
				<div v-if="stripe.isInitialization" class="h-10 relative mb-6">
					<Spinner />
				</div>
				<div id="payment-element">
				<!-- Elements will create form elements here -->
				</div>
				<ButtonBase :loading="stripe.storingStripePaymentMethod" type="submit" button-style="theme" class="w-full mt-4">
					{{ $t('Store My Credit Card') }}
				</ButtonBase>
				<div id="error-message">
					<!-- Display error message to your customers here -->
				</div>
			</form>
		</div>
	</div>
</template>
<script>
	import ButtonBase from "../FilesView/ButtonBase";
	import FormLabel from "../Others/Forms/FormLabel"
	import PaymentCard from "./PaymentCard"
	import Spinner from "../FilesView/Spinner"
	import {mapGetters} from "vuex";
	import {events} from "../../bus";
	import {loadStripe} from "@stripe/stripe-js";
	import axios from "axios";

	// Define stripe variables
	let stripe, elements = undefined

	export default {
		name: 'UserStoredPaymentMethods',
		components: {
			ButtonBase,
			FormLabel,
			PaymentCard,
			Spinner
		},
		computed: {
			...mapGetters([
				'isDarkMode',
				'config',
				'user',
			]),
			canShowForMeteredBilling() {
				return this.config.isStripe && this.config.subscriptionType === 'metered'
			},
			canShowForFixedBilling() {
				return this.config.isStripe
					&& this.config.subscriptionType === 'fixed'
					&& this.$store.getters.user.data.relationships.subscription
					&& this.$store.getters.user.data.relationships.subscription.data.attributes.driver === 'stripe'
			},
			hasPaymentMethod() {
				return this.user.data.relationships.creditCards && this.user.data.relationships.creditCards.data.length > 0
			},
		},
		data() {
			return {
				isLoading: false,
				isCreditCardForm: false,
				stripe: {
					isInitialization: true,
					storingStripePaymentMethod: false
				}
			}
		},
		methods: {
			async storeStripePaymentMethod() {

				this.stripe.storingStripePaymentMethod = true

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

					// TODO: L9 - load credit card after was stored in database
				}

				this.stripe.storingStripePaymentMethod = false
			},
			async stripeInit() {

				// Init stripe js
				stripe = await loadStripe(this.config.stripe_public_key);

				await axios.get('/api/stripe/setup-intent')
					.then(response => {
						// Set up Stripe.js and Elements to use in checkout form, passing the client secret obtained in step 2
						elements = stripe.elements({
							clientSecret: response.data.client_secret,
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

				this.stripe.isInitialization = false
			},
			showStoreCreditCardForm() {
				this.isCreditCardForm = !this.isCreditCardForm
				this.stripeInit()
			}
		}
	}
</script>