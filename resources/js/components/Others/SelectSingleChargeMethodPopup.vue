<template>
    <PopupWrapper name="select-payment-method">
        <PopupHeader :title="$t('Select Payment Method')" icon="credit-card" />

        <PopupContent style="padding: 0 20px">

			<!--PayPal implementation-->
			<div
				v-if="config.isPayPal"
				:class="{'dark:bg-2x-dark-foreground bg-light-background rounded-xl px-4 mb-2': paypal.isMethodsLoaded}"
			>
				<PaymentMethod
					@click.native="pickedPaymentMethod('paypal')"
					driver="paypal"
					:description="config.paypal_payment_description"
				>
					<div v-if="paypal.isMethodLoading" class="transform scale-50 translate-y-3">
						<Spinner />
					</div>
					<span v-if="! paypal.isMethodsLoaded" :class="{'opacity-0': paypal.isMethodLoading}" class="text-sm text-theme font-bold cursor-pointer">
						{{ $t('Select') }}
					</span>
				</PaymentMethod>

				<!--PayPal Buttons-->
				<div id="paypal-button-container"></div>
			</div>

			<!--Paystack implementation-->
			<PaymentMethod
				v-if="config.isPaystack"
				driver="paystack"
				:description="config.paystack_payment_description"
			>
				<paystack
					@click.native="pickedPaymentMethod('paystack')"
					v-if="user && config"
					:channels="['card', 'bank', 'ussd', 'qr', 'mobile_money', 'bank_transfer']"
					class="font-bold"
					currency="ZAR"
					:amount="singleChargeAmount * 100"
					:email="user.data.attributes.email"
					:paystackkey="config.paystack_public_key"
					:reference="$generatePaystackReference()"
					:callback="paystackPaymentSuccessful"
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
				class="w-full"
				@click.native="$closePopup()"
				button-style="secondary"
			>
                {{ $t('Cancel Payment') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import PopupWrapper from '/resources/js/components/Others/Popup/PopupWrapper'
import PopupActions from '/resources/js/components/Others/Popup/PopupActions'
import PopupContent from '/resources/js/components/Others/Popup/PopupContent'
import PopupHeader from '/resources/js/components/Others/Popup/PopupHeader'
import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
import { loadScript } from "@paypal/paypal-js"
import PaymentMethod from "./PaymentMethod"
import Spinner from "../FilesView/Spinner"
import {events} from '/resources/js/bus'
import paystack from 'vue-paystack'
import {mapGetters} from "vuex"

export default {
	name: "SelectSingleChargeMethodPopup",
	components: {
		PaymentMethod,
		PopupWrapper,
		PopupActions,
		PopupContent,
		PopupHeader,
		ButtonBase,
		paystack,
		Spinner,
	},
	data() {
		return {
			paypal: {
				isMethodsLoaded: false,
				isMethodLoading: false,
			}
		}
	},
	computed: {
		...mapGetters([
			'singleChargeAmount',
			'config',
			'user',
		]),
	},
	methods: {
		pickedPaymentMethod(driver) {
			if (driver === 'paystack') {
				this.$closePopup()
			}
			if (driver === 'paypal' && !this.paypal.isMethodsLoaded) {
				this.PayPalInitialization()
			}
		},
		async PayPalInitialization() {
			this.paypal.isMethodLoading = true

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

			const userId = this.user.data.id
			const amount = this.singleChargeAmount

			this.paypal.isMethodsLoaded = true
			this.paypal.isMethodLoading = false

			// Initialize paypal buttons for single charge
			await paypal.Buttons({
				createOrder: function(data, actions) {
					return actions.order.create({
						purchase_units: [{
							amount: {
								value: amount,
							},
							custom_id: userId
						}]
					});
				}
			}).render('#paypal-button-container');
		},
		paystackPaymentSuccessful() {
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
		events.$on('popup:close', () => this.paypal.isMethodsLoaded = false)
	}
}
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
	@import '/resources/sass/vuefilemanager/_mixins';

	.mobile-actions {
		white-space: nowrap;
		overflow-x: auto;
		margin: 0 -20px;
		padding: 10px 0 10px 20px;
	}
</style>
