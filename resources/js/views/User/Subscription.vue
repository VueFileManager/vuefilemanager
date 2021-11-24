<template>
    <PageTab :is-loading="isLoading">
		<div v-if="subscription" class="card shadow-card">
			<div class="md:flex md:space-x-10 mb-8">
				<div class="md:mb-0 mb-6">
					<b class="block leading-5 text-lg">
						{{ status }}
					</b>
					<small class="text-gray-500">
						{{ $t('We will send you a notification upon Subscription expiration') }}
					</small>
				</div>
				<div>
					<b class="block leading-5 text-lg">
						{{ price }}
					</b>
					<small class="text-gray-500">
						{{ subscription.relationships.plan.data.attributes.name }}
					</small>
				</div>
			</div>

			<div v-for="(limit, i) in limitations" :key="i" class="mb-6">
				<b class="mb-3 block text-sm text-gray-400">
					{{ limit.message }}
				</b>
				<ProgressLine :data="limit.distribution" />
			</div>

			<div class="flex space-x-4 mt-8">
				<ButtonBase @click.native="cancelSubscriptionConfirmation" :loading="isCancelling" class="popup-button" button-style="secondary">
					{{ $t('Cancel Subscription') }}
				</ButtonBase>
				<ButtonBase @click.native="$openUpgradeOptions" class="popup-button" button-style="theme">
					{{ $t('Upgrade Plan') }}
				</ButtonBase>
			</div>
		</div>

		<div v-if="! subscription && !isLoading" class="card shadow-card">
			<InfoBox style="margin-bottom: 0">
				<p>{{ $t("You don't have any subscription") }}</p>
			</InfoBox>
		</div>
    </PageTab>
</template>

<script>
	import ProgressLine from "../../components/Admin/ProgressLine";
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import {mapGetters} from 'vuex'
	import {events} from '/resources/js/bus'
	import axios from 'axios'

	export default {
		name: 'UserSubscription',
		components: {
			ProgressLine,
			ButtonBase,
			InfoBox,
			PageTab,
		},
		computed: {
			...mapGetters([
				'user',
			]),
			status() {
				return {
					'active': `Active until ${this.subscription.attributes.renews_at}`,
					'cancelled': `Active until ${this.subscription.attributes.ends_at}`,
				}[this.subscription.attributes.status]
			},
			price() {
				return {
					'month': `${this.subscription.relationships.plan.data.attributes.price} Per Month`,
					'year': `${this.subscription.relationships.plan.data.attributes.price} Per Year`,
				}[this.subscription.relationships.plan.data.attributes.interval]
			},
		},
		data() {
			return {
				subscription: undefined,
				limitations: [],
				isLoading: true,
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
			cancelSubscription() {

				// Start deleting spinner button
				this.isCancelling = true

				// Send post request
				axios
					.post('/api/subscription/cancel')
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
			},
			fetchSubscriptionDetail() {
				axios.get('/api/subscription/detail')
					.then(response => {

						this.subscription = response.data.data

						Object
							.entries(this.user.data.meta.limitations)
							.map(([key, item]) => {

								let payload = {
									color: {
										'max_storage_amount': 'warning',
										'max_team_members': 'purple',
									},
									message: {
										'max_storage_amount': `Total ${item.use} of ${item.total} Used`,
										'max_team_members': `Total ${item.use} of ${item.total} Members`,
									},
									title: {
										'max_storage_amount': `Storage`,
										'max_team_members': `Team Members`,
									}
								}

								this.limitations.push({
									message: payload.message[key],
									distribution: [
										{
											progress: item.percentage,
											color: payload.color[key],
											title: payload.title[key],
										}
									]
								})
							})

						this.isLoading = false
					})
					.finally(() => {
						this.isLoading = false
					})
			}
		},
		created() {
			this.fetchSubscriptionDetail()

			events.$on('action:confirmed', data => {
				if (data.operation === 'cancel-subscription')
					this.cancelSubscription()
			})
		}
	}
</script>
