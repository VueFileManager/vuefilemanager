<template>
    <PopupWrapper name="select-plan-subscription">

        <!--Title-->
		<b class="text-center block text-2xl font-extrabold mt-6">
			{{ $t('Upgrade Your Account') }}
		</b>

		<!--Payment Options-->
		<div v-if="isPaymentOptionPage">

			<PopupContent class="px-4">
				<b class="text-center block mb-3 mt-8">
					Stripe
				</b>
				<ButtonBase @click.native="payByStripe" class="block w-full mb-6" button-style="theme" type="button">
					<span class="text-theme">
						Pay With Stripe
					</span>
				</ButtonBase>

				<b class="text-center block mb-3 mt-8">
					PayStack
				</b>
				<ButtonBase class="block w-full mb-6" button-style="theme" type="button">
					<paystack
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
						<span class="text-theme">
							Pay With PayStack
						</span>
					 </paystack>
				</ButtonBase>

				<b class="text-center block mb-3">
					PayPal
				</b>
				<!--PayPal Button-->
				<div id="paypal-button-container"></div>
			</PopupContent>
		</div>

		<!--Select Payment Plans-->
		<div v-if="! isPaymentOptionPage">
			<PopupContent>

				<!--Toggle amid monthly and yearly billing-->
				<div class="text-center my-5">
					<label :class="{'text-gray-400': isYearlyPlans}" class="font-bold cursor-pointer text-xs">
						{{ $t('Billed Monthly') }}
					</label>
					<div class="relative inline-block w-14 mx-4 align-middle select-none">
						<SwitchInput class="transform scale-90" v-model="isYearlyPlans" />
					</div>
					<label :class="{'text-gray-400': !isYearlyPlans}" class="font-bold cursor-pointer text-xs">
						{{ $t('Billed Annually') }}
					</label>
				</div>

				<!--Form to set team folder-->
				<div class="px-4" v-if="plans">
					<PlanDetail
						v-for="plan in plans"
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
					@click.native="showPaymentOptions"
				>{{ $t('Upgrade Account') }}
				</ButtonBase>
			</PopupActions>
		</div>
    </PopupWrapper>
</template>

<script>
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
				isPaymentOptionPage: false,
				isYearlyPlans: false,
				isLoading: false,
				selectedPlan: undefined,
				plans: undefined,
			}
		},
		methods: {
			payByStripe() {
				axios.post('/api/subscriptions/stripe/checkout', {
						planCode: this.selectedPlan.data.meta.driver_plan_id.stripe
					})
					.then(response => {
						window.location = response.data.url
					})
			},
			async showPaymentOptions() {
				// Show payment buttons page
				this.isPaymentOptionPage = true

				// PayPal
				let paypal;

				try {
					paypal = await loadScript({
						'client-id': this.config.paypal_client_id,
						'vault': true,
					});
				} catch (error) {
					events.$emit('toaster', {
						type: 'error',
						message: this.$t('failed to load the PayPal components'),
					})
				}

				const planId = this.selectedPlan.data.meta.driver_plan_id.paypal
				const userId = this.user.data.id
				const app = this

				// Initialize paypal buttons
				await paypal.Buttons({
					createSubscription: function(data, actions) {
						return actions.subscription.create({
							'plan_id': planId,
							'custom_id': userId
						});
					},
					onApprove: function(data, actions) {
						app.paymentSuccessful()
					}
				}).render('#paypal-button-container');
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

