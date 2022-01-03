<template>
    <PopupWrapper name="select-plan-subscription">

		<PopupHeader :title="$t('Upgrade Your Account')" icon="credit-card" />

		<!--Payment Options-->
		<div v-if="isPaymentOptionPage">
			<PopupContent class="px-4">

				<!--Stripe implementation-->
				<PaymentMethod
					v-if="config.isStripe"
					driver="stripe"
					:description="$t('Pay by your credit card or Apple Pay')"
				>
					<span v-if="! paypalMethodsLoaded" @click="payByStripe" class="text-sm text-theme font-bold cursor-pointer">
						{{ $t('Select') }}
					</span>
				</PaymentMethod>

				<!--PayPal implementation-->
				<div v-if="config.isPayPal" :class="{'dark:bg-2x-dark-foreground bg-light-background rounded-xl px-4 mb-2': paypalMethodsLoaded}">
					<PaymentMethod
						@click.native="pickedPaymentMethod('paypal')"
						driver="paypal"
						:description="$t('Available PayPal Credit, Debit or Credit Card.')"
					>
						<span v-if="! paypalMethodsLoaded" class="text-sm text-theme font-bold cursor-pointer">
							{{ $t('Select') }}
						</span>
					</PaymentMethod>

					<!--PayPal Buttons-->
					<div v-if="paypalMethodsLoaded">
						<div id="paypal-button-container"></div>
					</div>
				</div>

				<!--Paystack implementation-->
				<PaymentMethod
					v-if="config.isPaystack"
					driver="paystack"
					:description="$t('Available Bank Account, USSD, Mobile Money, Apple Pay')"
				>
					<paystack
						v-if="user && config"
						:channels="['bank', 'ussd', 'qr', 'mobile_money', 'bank_transfer']"
						class="font-bold"
						currency="ZAR"
						:plan="selectedPlan.data.meta.driver_plan_id.paystack"
						:amount="selectedPlan.data.attributes.amount * 100"
						:email="user.data.attributes.email"
						:paystackkey="config.paystack_public_key"
						:reference="reference"
						:callback="paymentSuccessful"
						:close="paystackClosed"
					>
						<span class="text-sm text-theme font-bold cursor-pointer">
							{{ $t('Select') }}
						</span>
					 </paystack>
				</PaymentMethod>
			</PopupContent>

			<PopupActions>
				<ButtonBase
					class="popup-button"
					@click.native="$closePopup()"
					button-style="secondary"
				>
					{{ $t('Cancel Payment') }}
				</ButtonBase>
			</PopupActions>
		</div>

		<!--Select Payment Plans-->
		<div v-if="! isPaymentOptionPage">
			<PopupContent>

				<!--Toggle amid monthly and yearly billing-->
				<div class="px-5 mb-2 text-right">
					<label :class="{'text-gray-400': !isYearlyPlans}" class="font-bold cursor-pointer text-xs">
						{{ $t('Billed Annually') }}
					</label>
					<div class="relative inline-block w-12 align-middle select-none">
						<SwitchInput class="transform scale-75" v-model="isYearlyPlans" :state="isYearlyPlans" />
					</div>
				</div>

				<!--Form to set team folder-->
				<div class="px-4" v-if="plans">
					<PlanDetail
						v-for="(plan, i) in plans"
						:plan="plan"
						:key="plan.data.id"
						v-if="plan.data.attributes.interval === intervalPlanType"
						:is-selected="selectedPlan && selectedPlan.data.id === plan.data.id"
						@click.native="selectPlan(plan)"
					/>
				</div>

			</PopupContent>

			<!--Actions-->
			<PopupActions>
				<ButtonBase
					class="popup-button"
					@click.native="$closePopup()"
					button-style="secondary"
				>{{ $t('popup_move_item.cancel') }}
				</ButtonBase>
				<ButtonBase
					class="popup-button"
					:button-style="buttonStyle"
					@click.native="isPaymentOptionPage = true"
				>{{ $t('Upgrade Account') }}
				</ButtonBase>
			</PopupActions>
		</div>
    </PopupWrapper>
</template>

<script>
	import PaymentMethod from "../Others/PaymentMethod";
	import { loadScript } from "@paypal/paypal-js";
    import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
	import PopupWrapper from '/resources/js/components/Others/Popup/PopupWrapper'
	import PopupActions from '/resources/js/components/Others/Popup/PopupActions'
	import PopupContent from '/resources/js/components/Others/Popup/PopupContent'
	import PopupHeader from '/resources/js/components/Others/Popup/PopupHeader'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import PlanDetail from "./PlanDetail";
	import paystack from 'vue-paystack';
	import {mapGetters} from "vuex";
	import {events} from "../../bus";
	import axios from "axios";

	export default {
		name: 'SelectPlanSubscriptionPopup',
		components: {
			PaymentMethod,
			paystack,
			PlanDetail,
			SwitchInput,
			PopupWrapper,
			PopupActions,
			PopupContent,
			PopupHeader,
			ButtonBase,
		},
		watch: {
			isYearlyPlans() {
				this.selectedPlan = undefined
			}
		},
		computed: {
			...mapGetters([
				'config',
				'user',
			]),
			reference() {
				let text = "";
				let possible =
					"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

				for (let i = 0; i < 10; i++)
					text += possible.charAt(Math.floor(Math.random() * possible.length));

				return text;
			},
			intervalPlanType() {
				return this.isYearlyPlans
					? 'year'
					: 'month'
			},
			buttonStyle() {
				return this.selectedPlan
					? 'theme'
					: 'secondary'
			}
		},
		data() {
			return {
				paypalMethodsLoaded: false,

				isPaymentOptionPage: false,
				isYearlyPlans: false,
				isLoading: false,
				selectedPlan: undefined,
				plans: undefined,
			}
		},
		methods: {
			pickedPaymentMethod(driver) {
				if (driver === 'paystack') {
					this.$closePopup()
				}
				if (driver === 'paypal' && !this.paypalMethodsLoaded) {
					this.PayPalInitialization()
				}
			},
			async PayPalInitialization() {
				this.paypalMethodsLoaded = true

				let paypal;

				try {
					paypal = await loadScript({
						'client-id': this.config.paypal_client_id,
						'vault': true,
					});
				} catch (error) {
					events.$emit('toaster', {
						type: 'danger',
						message: this.$t('Failed to load the PayPal service'),
					})
				}

				const planId = this.selectedPlan.data.meta.driver_plan_id.paypal
				const userId = this.user.data.id
				const app = this

				// Initialize paypal buttons for single charge
				await paypal.Buttons({
					createSubscription: function(data, actions) {
						return actions.subscription.create({
							plan_id: planId,
							custom_id: userId
						});
					},
					onApprove: function(data, actions) {
						app.paymentSuccessful()
					}
				}).render('#paypal-button-container');
			},
			payByStripe() {
				axios.post('/api/stripe/checkout', {
						planCode: this.selectedPlan.data.meta.driver_plan_id.stripe
					})
					.then(response => {
						window.location = response.data.url
					})
			},
			selectPlan(plan) {
				this.selectedPlan = plan
			},
			paymentSuccessful() {
				this.$closePopup()

				events.$emit('toaster', {
					type: 'success',
					message: this.$t('Your payment was successfully received.'),
				})
			},
			paystackClosed() {
				// ...
			}
		},
		created() {
			axios.get('/api/subscriptions/plans')
				.then(response => {
					this.plans = response.data.data
				})
		}
	}
</script>

