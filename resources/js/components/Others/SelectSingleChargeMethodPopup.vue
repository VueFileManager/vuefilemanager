<template>
    <PopupWrapper name="select-payment-method">
        <PopupHeader :title="$t('Select Payment Method')" icon="credit-card" />

        <PopupContent style="padding: 0 20px">

			<!--PayPal implementation-->
			<div :class="{'dark:bg-2x-dark-foreground bg-light-background rounded-xl px-4 mb-2': paypalMethodsLoaded}">
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
				driver="paystack"
				:description="$t('Available Bank Account, USSD, Mobile Money, Apple Pay')"
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
					:reference="reference"
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
				class="popup-button"
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
	},
	data() {
		return {
			paypalMethodsLoaded: false,
		}
	},
	computed: {
		...mapGetters([
			'singleChargeAmount',
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

			const userId = this.user.data.id
			const amount = this.singleChargeAmount

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
		events.$on('popup:close', () => this.paypalMethodsLoaded = false)
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
