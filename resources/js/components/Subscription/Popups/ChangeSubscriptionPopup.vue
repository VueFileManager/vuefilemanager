<template>
    <PopupWrapper name="change-plan-subscription">
        <PopupHeader :title="$t('change_your_plan')" icon="credit-card" />

        <!--Select Payment Plans-->
		<PopupContent v-if="plans">
			<InfoBox v-if="plans.data.length === 0" class="!mb-0">
				<p>{{ $t("not_any_plan") }}</p>
			</InfoBox>

			<!--Toggle yearly billing-->
			<PlanPeriodSwitcher v-if="yearlyPlans.length > 0" v-model="isSelectedYearlyPlans" />

			<!--List available plans-->
			<div>
				<PlanDetail
					v-for="(plan, i) in plans.data"
					:plan="plan"
					:key="plan.data.id"
					v-if="plan.data.attributes.interval === intervalPlanType"
					:class="{'opacity-50 pointer-events-none': userSubscribedPlanId === plan.data.id}"
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
				:loading="isLoading"
				@click.native="proceedToPayment"
				>{{ $t('change_plan') }}
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
import PlanDetail from '../PlanDetail'
import {mapGetters} from 'vuex'
import {events} from '../../../bus'
import axios from 'axios'
import Spinner from '../../UI/Others/Spinner'
import InfoBox from '../../UI/Others/InfoBox'
import PlanPeriodSwitcher from "../PlanPeriodSwitcher";

export default {
    name: 'ChangeSubscriptionPopup',
    components: {
		PlanPeriodSwitcher,
        InfoBox,
        Spinner,
        PlanDetail,
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
		subscriptionDriver() {
			return this.user.data.relationships.subscription.data.attributes.driver
		},
		subscription() {
			return this.user.data.relationships.subscription
		}
    },
    data() {
        return {
            isSelectedYearlyPlans: false,
            isLoading: false,
            selectedPlan: undefined,
            plans: undefined,
        }
    },
    methods: {
		proceedToPayment() {
			// Start button spinner
			this.isLoading = true

			if (this.subscriptionDriver === 'stripe') {
				this.payByStripe()
			}

			if (this.subscriptionDriver === 'paypal') {
				this.payByPayPal()
			}

			if (this.subscriptionDriver === 'paystack') {
				this.payByPaystack()
			}
		},
		payByPayPal() {
			axios.post(`/api/subscriptions/swap/${this.selectedPlan.data.id}`)
				.then((response) => {
					window.location = response.data.links[0].href
				})
		},
		payByStripe() {
			// Subscribe to the new plan
			if (['inactive', 'cancelled', 'completed'].includes(this.subscription.data.attributes.status)) {
				axios
					.post('/api/stripe/checkout', {
						planCode: this.selectedPlan.data.meta.driver_plan_id.stripe,
					})
					.then((response) => {
						window.location = response.data.url
					})
					.catch((error) => {
						events.$emit('alert:open', {
							title: error.response.data.title || this.$t('popup_error.title'),
							message: error.response.data.message || this.$t('popup_error.message'),
						})
					})
			}

			// Change active subscription
			if (this.subscription.data.attributes.status === 'active') {
				axios
					.post(`/api/subscriptions/swap/${this.selectedPlan.data.id}`)
					.then(() => {
						this.$closePopup()

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('subscription_changed'),
						})
					})
			}
		},
		payByPaystack() {
			axios
				.post('/api/paystack/checkout', {
					planCode: this.selectedPlan.data.meta.driver_plan_id.paystack,
				})
				.then((response) => {
					window.location = response.data.data.authorization_url
				})
		},
        selectPlan(plan) {
            this.selectedPlan = plan
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
            this.selectedPlan = undefined
        })
    },
}
</script>
