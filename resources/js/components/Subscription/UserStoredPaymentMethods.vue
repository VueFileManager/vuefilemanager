<template>
    <div v-if="canShowForMeteredBilling || canShowForFixedBilling" class="card shadow-card">
        <FormLabel icon="credit-card">
            {{ $t('payment_method') }}
        </FormLabel>

        <!-- User has registered payment method -->
        <div v-if="hasPaymentMethod">
            <b
                v-if="
                    config.subscriptionType === 'metered' && user.data.relationships.balance.data.attributes.balance > 0
                "
                class="mb-3 mb-5 block text-sm"
            >
                {{
                    $t('credit_to_auto_withdraw', {
                        credit: user.data.relationships.balance.data.attributes.formatted,
                    })
                }}
            </b>

            <!-- Card -->
            <PaymentCard v-for="card in user.data.relationships.creditCards.data" :key="card.data.id" :card="card" />

            <small class="hidden pt-3 text-xs leading-none dark:text-gray-500 text-gray-500 sm:block">
                {{ $t('auto_settled_credit_card') }}
            </small>
        </div>

        <!-- User doesn't have registered payment method -->
        <div v-if="!hasPaymentMethod">
            <!-- Show credit card form -->
            <ButtonBase
                @click.native="showStoreCreditCardForm"
                v-if="!isCreditCardForm"
                :loading="stripe.storingStripePaymentMethod"
                type="submit"
                button-style="theme"
                class="mt-4 w-full"
            >
                {{ $t('add_payment_method') }}
            </ButtonBase>

            <!-- Store credit card form -->
            <form v-if="isCreditCardForm" @submit.prevent="storeStripePaymentMethod" id="payment-form" class="mt-6">
                <div v-if="stripe.isInitialization" class="relative mb-6 h-10">
                    <Spinner />
                </div>
				<InfoBox v-if="config.isDemo && !stripe.isInitialization">
					<p>For adding test credit card please use <b class="text-theme">4242 4242 4242 4242</b> as a card number, <b class="text-theme">11/22</b>
						as the expiration date and <b class="text-theme">123</b> as CVC number and ZIP <b class="text-theme">12345</b> if required.</p>
				</InfoBox>
                <div id="payment-element">
                    <!-- Elements will create form elements here -->
                </div>
                <ButtonBase
                    :loading="stripe.storingStripePaymentMethod"
                    type="submit"
                    button-style="theme"
                    class="mt-4 w-full"
                >
                    {{ $t('store_my_credit_card') }}
                </ButtonBase>
                <div id="error-message" class="pt-2 text-xs text-rose-600">
                    <!-- Display error message to your customers here -->
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import ButtonBase from '../UI/Buttons/ButtonBase'
import FormLabel from '../UI/Labels/FormLabel'
import PaymentCard from './PaymentCard'
import Spinner from '../UI/Others/Spinner'
import { mapGetters } from 'vuex'
import { events } from '../../bus'
import { loadStripe } from '@stripe/stripe-js'
import axios from 'axios'
import InfoBox from "../UI/Others/InfoBox";

// Define stripe variables
let stripe,
    elements = undefined

export default {
    name: 'UserStoredPaymentMethods',
    components: {
		InfoBox,
        ButtonBase,
        FormLabel,
        PaymentCard,
        Spinner,
    },
    computed: {
        ...mapGetters(['isDarkMode', 'config', 'user']),
        canShowForMeteredBilling() {
            return this.config.isStripe && this.config.subscriptionType === 'metered'
        },
        canShowForFixedBilling() {
            return (
                this.config.isStripe &&
                this.config.subscriptionType === 'fixed' &&
                this.$store.getters.user.data.relationships.subscription &&
                this.$store.getters.user.data.relationships.subscription.data.attributes.driver === 'stripe'
            )
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
                storingStripePaymentMethod: false,
            },
        }
    },
    methods: {
        async storeStripePaymentMethod() {
			if (this.config.isDemo && this.user.data.attributes.email === 'ho**@hi5ve.digital') {
				events.$emit('toaster', {
					type: 'success',
					message: this.$t('credit_card_stored'),
				})

				return
			}

            this.stripe.storingStripePaymentMethod = true

            const { error } = await stripe.confirmSetup({
                //`Elements` instance that was used to create the Payment Element
                elements,
                redirect: 'if_required',
                confirmParams: {
                    return_url: window.location.href,
                },
            })

            if (error) {
                // This point will only be reached if there is an immediate error when
                // confirming the payment. Show error to your customer (e.g., payment
                // details incomplete)
                const messageContainer = document.querySelector('#error-message')
                messageContainer.textContent = error.message
            } else {
                // Your customer will be redirected to your `return_url`. For some payment
                // methods like iDEAL, your customer will be redirected to an intermediate
                // site first to authorize the payment, then redirected to the `return_url`.
                events.$emit('toaster', {
                    type: 'success',
                    message: this.$t('credit_card_stored'),
                })

                // TODO: L9 - load credit card after was stored in database
				setTimeout(() => document.location.reload(), 500)
            }

            this.stripe.storingStripePaymentMethod = false
        },
        async stripeInit() {
            // Init stripe js
            stripe = await loadStripe(this.config.stripe_public_key)

            await axios
                .get('/api/stripe/setup-intent')
                .then((response) => {
                    // Set up Stripe.js and Elements to use in checkout form, passing the client secret obtained in step 2
                    elements = stripe.elements({
                        clientSecret: response.data.data.client_secret,
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
                            },
                        },
                    })

                    // Create and mount the Payment Element
                    const paymentElement = elements.create('payment')

                    paymentElement.mount('#payment-element')
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
        },
    }
}
</script>
