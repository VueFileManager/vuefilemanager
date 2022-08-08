<template>
    <PopupWrapper name="select-plan-subscription">
        <PopupHeader :title="$t('upgrade_your_account')" icon="credit-card" />

        <!--Payment Options-->
        <div v-if="isPaymentOptionPage">
            <PopupContent>
                <!--Stripe implementation-->
                <PaymentMethod
                    v-if="config.isStripe"
                    driver="stripe"
                    :description="$t(config.stripe_payment_description)"
                >
                    <div v-if="stripe.isGettingCheckoutLink" class="translate-y-3 scale-50 transform">
                        <Spinner />
                    </div>
                    <span
                        @click="payByStripe"
                        :class="{ 'opacity-0': stripe.isGettingCheckoutLink }"
                        class="text-theme cursor-pointer text-sm font-bold"
                    >
                        {{ $t('select') }}
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
                        @click.native.once="payByPayPal"
                        driver="paypal"
                        :description="$t(config.paypal_payment_description)"
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
        </div>

        <!--Select Payment Plans-->
        <div v-if="!isPaymentOptionPage">
            <PopupContent v-if="plans">
                <InfoBox v-if="plans.data.length === 0" class="!mb-0">
                    <p>There isn't any plan yet.</p>
                </InfoBox>

                <PlanPeriodSwitcher v-if="yearlyPlans.length > 0" v-model="isSelectedYearlyPlans" />

                <!--List available plans-->
                <div>
                    <PlanDetail
                        v-for="(plan, i) in plans.data"
                        :plan="plan"
                        :key="plan.data.id"
                        v-if="plan.data.attributes.interval === intervalPlanType"
                        :class="{ 'pointer-events-none opacity-50': userSubscribedPlanId === plan.data.id }"
                        :is-selected="selectedPlan && selectedPlan.data.id === plan.data.id"
                        @click.native="selectPlan(plan)"
                    />
                </div>
            </PopupContent>

            <!--Actions-->
            <PopupActions>
                <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary"
                    >{{ $t('cancel') }}
                </ButtonBase>
                <ButtonBase
                    class="w-full"
                    v-if="plans && plans.data.length !== 0"
                    :button-style="buttonStyle"
                    @click.native="isPaymentOptionPage = true"
                    >{{ $t('upgrade_account') }}
                </ButtonBase>
            </PopupActions>
        </div>
    </PopupWrapper>
</template>

<script>
import PaymentMethod from '../PaymentMethod'
import { loadScript } from '@paypal/paypal-js'
import SwitchInput from '../../Inputs/SwitchInput'
import PopupWrapper from '../../Popups/Components/PopupWrapper'
import PopupActions from '../../Popups/Components/PopupActions'
import PopupContent from '../../Popups/Components/PopupContent'
import PopupHeader from '../../Popups/Components/PopupHeader'
import ButtonBase from '../../UI/Buttons/ButtonBase'
import PlanDetail from '../PlanDetail'
import { mapGetters } from 'vuex'
import { events } from '../../../bus'
import axios from 'axios'
import Spinner from '../../UI/Others/Spinner'
import InfoBox from '../../UI/Others/InfoBox'
import PlanPeriodSwitcher from '../PlanPeriodSwitcher'


export default {
    name: 'SubscribeAccountPopup',
    components: {
        PlanPeriodSwitcher,
        InfoBox,
        Spinner,
        PaymentMethod,
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
        yearlyPlans() {
            return this.plans.data.filter((plan) => plan.data.attributes.interval === 'year')
        },
    },
    data() {
        return {
            paystack: {
                isGettingCheckoutLink: false,
            },
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
        payByPaystack() {
            this.paystack.isGettingCheckoutLink = true

            axios
                .post('/api/paystack/checkout', {
                    planCode: this.selectedPlan.data.meta.driver_plan_id.paystack,
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
                    onApprove: function () {
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
				.catch((error) => {
					this.$closePopup()

					setTimeout(() => {
						events.$emit('alert:open', {
							title: error.response.data.title || this.$t('popup_error.title'),
							message: error.response.data.message || this.$t('popup_error.message'),
						})
					}, 100)
				})
				.finally(() => {
					this.stripe.isGettingCheckoutLink = false
				})
        },
        selectPlan(plan) {
            this.selectedPlan = plan
        },
        paymentSuccessful() {
            this.$closePopup()

            events.$emit('toaster', {
                type: 'success',
                message: this.$t('payment_was_successfully_received'),
            })

            // todo: temporary reload function
            setTimeout(() => document.location.reload(), 1000)
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
