<template>
    <PopupWrapper name="select-payment-method">
        <PopupHeader :title="$t('Select Payment Method')" icon="credit-card" />

        <PopupContent style="padding: 0 20px">
            <!--PayPal implementation-->
            <div
                v-if="config.isPayPal"
                :class="{
                    'mb-2 rounded-xl bg-light-background px-4 dark:bg-2x-dark-foreground': paypal.isMethodsLoaded,
                }"
            >
                <PaymentMethod
                    @click.native="pickedPaymentMethod('paypal')"
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
                    <span class="text-theme cursor-pointer text-sm font-bold">
                        {{ $t('Select') }}
                    </span>
                </paystack>
            </PaymentMethod>
        </PopupContent>

        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary">
                {{ $t('Cancel Payment') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import PopupWrapper from './Popup/PopupWrapper'
import PopupActions from './Popup/PopupActions'
import PopupContent from './Popup/PopupContent'
import PopupHeader from './Popup/PopupHeader'
import ButtonBase from '../FilesView/ButtonBase'
import { loadScript } from '@paypal/paypal-js'
import PaymentMethod from './PaymentMethod'
import Spinner from '../FilesView/Spinner'
import { events } from '../../bus'
import paystack from 'vue-paystack'
import { mapGetters } from 'vuex'

export default {
    name: 'SelectSingleChargeMethodPopup',
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
            },
        }
    },
    computed: {
        ...mapGetters(['singleChargeAmount', 'config', 'user']),
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

            let paypal

            try {
                paypal = await loadScript({
                    'client-id': this.config.paypal_client_id,
                    vault: true,
                })
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
                })
                .render('#paypal-button-container')
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
        },
    },
    created() {
        events.$on('popup:close', () => (this.paypal.isMethodsLoaded = false))
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.mobile-actions {
    white-space: nowrap;
    overflow-x: auto;
    margin: 0 -20px;
    padding: 10px 0 10px 20px;
}
</style>
