<template>
	<div v-if="hasSubscription" class="card shadow-card">
		<FormLabel>
			{{ $t('Edit your Subscription') }}
		</FormLabel>

		<AppInputSwitch :title="$t('Cancel Subscription')" :description="$t('You can cancel your subscription now. You\'ll continue to have access to the features you\'ve paid for until the end of your billing cycle.')">
			<ButtonBase @click.native="cancelSubscriptionConfirmation" :loading="isCancelling" class="sm:w-auto w-full" button-style="secondary">
				{{ $t('Cancel Now') }}
			</ButtonBase>
		</AppInputSwitch>

		<AppInputSwitch :title="$t('Change Plan')" :description="$t('You can upgrade your plan at any time you want.')" :is-last="true">
			<ButtonBase @click.native="$openUpgradeOptions" class="sm:w-auto w-full" button-style="secondary">
				{{ $t('Change Now') }}
			</ButtonBase>
		</AppInputSwitch>
	</div>
</template>

<script>
	import AppInputSwitch from "../Admin/AppInputSwitch"
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import FormLabel from "../Others/Forms/FormLabel"
	import {events} from "../../bus";
	import axios from "axios";

	export default {
		name: 'UserEditSubscription',
		components: {
			AppInputSwitch,
			ButtonBase,
			FormLabel
		},
		computed: {
			subscription() {
				return this.$store.getters.user.data.relationships.subscription.data
			},
			hasSubscription() {
				return this.$store.getters.user.data.relationships.subscription
			},
		},
		data() {
			return {
				isCancelling: false,
			}
		},
		methods: {
			cancelSubscriptionConfirmation() {
				events.$emit('confirm:open', {
					title: this.$t('Are you sure you want to cancel subscription?'),
					message: this.$t("You'll continue to have access to the features you've paid for until the end of your billing cycle."),
					action: {
						operation: 'cancel-subscription',
					}
				})
			},
		},
		created() {

			events.$on('action:confirmed', data => {
				if (data.operation === 'cancel-subscription') {

					// Start deleting spinner button
					this.isCancelling = true

					// Send post request
					axios
						.post('/api/subscriptions/cancel')
						.then(() => {

							// Update user data
							this.$store.dispatch('getAppData').then(() => {
								this.fetchSubscriptionDetail()
							})

							events.$emit('toaster', {
								type: 'success',
								message: this.$t('popup_subscription_cancel.title'),
							})
						})
						.catch(() => {
							events.$emit('toaster', {
								type: 'danger',
								message: this.$t('popup_error.title'),
							})
						})
						.finally(() => {
							this.isCancelling = false
						})
				}

			})
		}
	}
</script>