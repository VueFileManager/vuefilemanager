<template>
    <PopupWrapper name="select-payment-method">
        <PopupHeader :title="$t('select_payment_method')" icon="credit-card" />

        <PopupContent style="padding: 0 20px">
			<InfoBox v-if="!config.isPayPal && !config.isPaystack" class="!mb-0">
				<p>{{ $t("not_any_payment_method") }}</p>
			</InfoBox>

            <!--PayPal implementation-->
            <div
                v-if="config.isPayPal"
                :class="{
                    'mb-2 rounded-xl bg-light-background px-4 dark:bg-2x-dark-foreground': paypal.isMethodsLoaded,
                }"
            >
                <PaymentMethod
                    @click.native.once="payByPayPal"
                    driver="paypal"
                    :description="config.paypal_payment_description"
                >
                    <div v-if="paypal.isMethodLoading" class="translate-y-3 scale-50 transform">
                        <Spinner />
                    </div>
                    <span
                        v-if="!paypal.isMethodsLoaded"
                        :class="{ 'opacity-0': paypal.isMethodLoading }"
                        class="text-theme cursor-pointer text-sm font-bold"
                    >
                        {{ $t('select') }}
                    </span>
                </PaymentMethod>

                <!--PayPal Buttons-->
                <div id="paypal-button-container"></div>
            </div>

			<!--Paystack implementation-->
			<PaymentMethod
				v-if="config.isPaystack"
				driver="paystack"
				:description="$t(config.paystack_payment_description)"
			>
				<div v-if="paystack.isGettingCheckoutLink" class="translate-y-3 scale-50 transform">
					<Spinner />
				</div>
				<span
					@click="payByPaystack()"
					:class="{ 'opacity-0': paystack.isGettingCheckoutLink }"
					class="text-theme cursor-pointer text-sm font-bold"
				>
					{{ $t('select') }}
				</span>
			</PaymentMethod>
        </PopupContent>

        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary">
                {{ $t('cancel_payment') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import PopupWrapper from '../../Popups/Components/PopupWrapper'
import PopupActions from '../../Popups/Components/PopupActions'
import PopupContent from '../../Popups/Components/PopupContent'
import PopupHeader from '../../Popups/Components/PopupHeader'
import ButtonBase from '../../UI/Buttons/ButtonBase'
import { loadScript } from '@paypal/paypal-js'
import PaymentMethod from '../PaymentMethod'
import Spinner from '../../UI/Others/Spinner'
import InfoBox from "../../UI/Others/InfoBox"
import { events } from '../../../bus'
import { mapGetters } from 'vuex'
import axios from "axios";

export default {
    name: 'ChargePaymentPopup',
    components: {
        PaymentMethod,
        PopupWrapper,
        PopupActions,
        PopupContent,
        PopupHeader,
        ButtonBase,
        Spinner,
		InfoBox,
    },
    data() {
        return {
            paypal: {
                isMethodsLoaded: false,
                isMethodLoading: false,
            },
			paystack: {
				isGettingCheckoutLink: false,
			},
        }
    },
    computed: {
        ...mapGetters(['singleChargeAmount', 'config', 'user']),
    },
    methods: {
		payByPaystack() {
			this.paystack.isGettingCheckoutLink = true

			axios
				.post('/api/paystack/checkout', {
					amount: this.singleChargeAmount * 100,
				})
				.then((response) => {
					window.location = response.data.data.authorization_url
				})
		},
        async payByPayPal() {
			if (this.paypal.isMethodLoading) {
				return
			}

            this.paypal.isMethodLoading = true

            let paypal

            try {
                paypal = await loadScript({
                    'client-id': this.config.paypal_client_id,
                    vault: true,
                })
            } catch (error) {
                events.$emit('toaster', {
                    type: 'danger',
                    message: this.$t('failed_to_load_paypal'),
                })
            }

            const userId = this.user.data.id
            const amount = this.singleChargeAmount

            this.paypal.isMethodsLoaded = true
            this.paypal.isMethodLoading = false
			const app = this

			// Initialize paypal buttons for single charge
            await paypal
                .Buttons({
                    createOrder: function (data, actions) {
                        return actions.order.create({
                            purchase_units: [
                                {
                                    amount: {
                                        value: amount,
                                    },
                                    custom_id: userId,
                                },
                            ],
                        })
                    },
					onApprove: function () {
						app.paymentSuccessful()
					},
                })
                .render('#paypal-button-container')
        },
        paymentSuccessful() {
            this.$closePopup()

            events.$emit('toaster', {
                type: 'success',
                message: this.$t('payment_was_successfully_received'),
            })

			// todo: temporary reload function
			setTimeout(() => document.location.reload(), 500)
        },
    },
    created() {
        events.$on('popup:close', () => (this.paypal.isMethodsLoaded = false))
    },
}
</script>
