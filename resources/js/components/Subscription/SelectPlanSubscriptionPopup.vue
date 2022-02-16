<template>
    <PopupWrapper name="select-plan-subscription">
        <PopupHeader :title="$t('Upgrade Your Account')" icon="credit-card" />

        <!--Payment Options-->
        <div v-if="isPaymentOptionPage">
            <PopupContent>
                <!--Stripe implementation-->
                <PaymentMethod
                    v-if="config.isStripe"
                    driver="stripe"
                    :description="$t('Pay by your credit card or Apple Pay')"
                >
                    <div v-if="stripe.isGettingCheckoutLink" class="translate-y-3 scale-50 transform">
                        <Spinner />
                    </div>
                    <span
                        @click="payByStripe"
                        :class="{ 'opacity-0': stripe.isGettingCheckoutLink }"
                        class="text-theme cursor-pointer text-sm font-bold"
                    >
                        {{ $t('Select') }}
                    </span>
                </PaymentMethod>

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
                        :description="$t('Available PayPal Credit, Debit or Credit Card.')"
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
                        :reference="$generatePaystackReference()"
                        :callback="paymentSuccessful"
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
        </div>

        <!--Select Payment Plans-->
        <div v-if="!isPaymentOptionPage">
            <PopupContent v-if="plans">
                <InfoBox v-if="plans.data.length === 0" class="!mb-0">
                    <p>There isn't any plan yet.</p>
                </InfoBox>

                <!--Toggle yearly billing-->
                <div v-if="hasYearlyPlans.length > 0" class="mb-2 px-5 text-right">
                    <label
                        :class="{ 'text-gray-400': !isSelectedYearlyPlans }"
                        class="cursor-pointer text-xs font-bold"
                    >
                        {{ $t('Billed Annually') }}
                    </label>
                    <div class="relative inline-block w-12 select-none align-middle">
                        <SwitchInput
                            class="scale-75 transform"
                            v-model="isSelectedYearlyPlans"
                            :state="isSelectedYearlyPlans"
                        />
                    </div>
                </div>

                <!--List available plans-->
                <div>
                    <PlanDetail
                        v-for="(plan, i) in plans.data"
                        :plan="plan"
                        :key="plan.data.id"
                        v-if="
                            plan.data.attributes.interval === intervalPlanType && userSubscribedPlanId !== plan.data.id
                        "
                        :is-selected="selectedPlan && selectedPlan.data.id === plan.data.id"
                        @click.native="selectPlan(plan)"
                    />
                </div>
            </PopupContent>

            <!--Actions-->
            <PopupActions>
                <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary"
                    >{{ $t('popup_move_item.cancel') }}
                </ButtonBase>
                <ButtonBase
                    class="w-full"
                    v-if="plans.data.length !== 0"
                    :button-style="buttonStyle"
                    @click.native="isPaymentOptionPage = true"
                    >{{ $t('Upgrade Account') }}
                </ButtonBase>
            </PopupActions>
        </div>
    </PopupWrapper>
</template>

<script>
import PaymentMethod from '../Others/PaymentMethod'
import { loadScript } from '@paypal/paypal-js'
import SwitchInput from '../Others/Forms/SwitchInput'
import PopupWrapper from '../Others/Popup/PopupWrapper'
import PopupActions from '../Others/Popup/PopupActions'
import PopupContent from '../Others/Popup/PopupContent'
import PopupHeader from '../Others/Popup/PopupHeader'
import ButtonBase from '../FilesView/ButtonBase'
import PlanDetail from './PlanDetail'
import paystack from 'vue-paystack'
import { mapGetters } from 'vuex'
import { events } from '../../bus'
import axios from 'axios'
import Spinner from '../FilesView/Spinner'
import InfoBox from '../Others/Forms/InfoBox'

export default {
    name: 'SelectPlanSubscriptionPopup',
    components: {
        InfoBox,
        Spinner,
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
        isSelectedYearlyPlans() {
            this.selectedPlan = undefined
        },
    },
    computed: {
        ...mapGetters(['config', 'user']),
        intervalPlanType() {
            return this.isSelectedYearlyPlans ? 'year' : 'month'
        },
        buttonStyle() {
            return this.selectedPlan ? 'theme' : 'secondary'
        },
        userSubscribedPlanId() {
            return (
                this.user &&
                this.user.data.relationships.subscription &&
                this.user.data.relationships.subscription.data.relationships.plan.data.id
            )
        },
        hasYearlyPlans() {
            return this.plans.data.filter((plan) => plan.data.attributes.interval === 'year')
        },
    },
    data() {
        return {
            stripe: {
                isGettingCheckoutLink: false,
            },
            paypal: {
                isMethodsLoaded: false,
                isMethodLoading: false,
            },
            isPaymentOptionPage: false,
            isSelectedYearlyPlans: false,
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

            const planId = this.selectedPlan.data.meta.driver_plan_id.paypal
            const userId = this.user.data.id
            const app = this

            this.paypal.isMethodsLoaded = true
            this.paypal.isMethodLoading = false

            // Initialize paypal buttons for single charge
            await paypal
                .Buttons({
                    createSubscription: function (data, actions) {
                        return actions.subscription.create({
                            plan_id: planId,
                            custom_id: userId,
                        })
                    },
                    onApprove: function (data, actions) {
                        app.paymentSuccessful()
                    },
                })
                .render('#paypal-button-container')
        },
        payByStripe() {
            this.stripe.isGettingCheckoutLink = true

            axios
                .post('/api/stripe/checkout', {
                    planCode: this.selectedPlan.data.meta.driver_plan_id.stripe,
                })
                .then((response) => {
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
        },
    },
    created() {
        // Load available plans
        axios.get('/api/subscriptions/plans').then((response) => {
            this.plans = response.data
        })

        // Reset states on popup close
        events.$on('popup:close', () => {
            this.isSelectedYearlyPlans = false
            this.isPaymentOptionPage = false
            this.selectedPlan = undefined
            this.paypal.isMethodsLoaded = false
        })
    },
}
</script>
