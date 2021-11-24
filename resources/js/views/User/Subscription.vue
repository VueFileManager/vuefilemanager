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
				<ButtonBase class="popup-button" button-style="secondary">
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
				isConfirmedCancel: false,
				isConfirmedResume: false,
				isDeleting: false,
				isResuming: false,
				isLoading: true,
			}
		},
		methods: {
			cancelSubscription() {

				// Set confirm button
				if (!this.isConfirmedCancel) {
					this.isConfirmedCancel = true
					return
				}

				// Start deleting spinner button
				this.isDeleting = true
				this.isLoading = true

				// Send delete request
				axios
					.post('/api/user/subscription/cancel')
					.then(() => {

						// Update user data
						this.$store.dispatch('getAppData').then(() => {
							this.fetchSubscriptionDetail()
						})

						events.$emit('alert:open', {
							emoji: '👍',
							title: this.$t('popup_subscription_cancel.title'),
							message: this.$t('popup_subscription_cancel.message'),
							buttonStyle: 'theme',
							button: this.$t('popup_subscription_cancel.button')
						})
					})
					.catch(() => {
						events.$emit('alert:open', {
							title: this.$t('popup_error.title'),
							message: this.$t('popup_error.message'),
						})
					})
					.finally(() => {

						// End deleting spinner button
						this.isDeleting = false
						this.isLoading = false
						this.isConfirmedCancel = false
					})
			},
			resumeSubscription() {

				// Set confirm button
				if (!this.isConfirmedResume) {
					this.isConfirmedResume = true
					return
				}

				// Start deleting spinner button
				this.isResuming = true
				this.isLoading = true

				// Send delete request
				axios
					.post('/api/user/subscription/resume')
					.then(() => {

						// Update user data
						this.$store.dispatch('getAppData').then(() => {
							this.fetchSubscriptionDetail()
						})

						events.$emit('alert:open', {
							emoji: '👍',
							title: this.$t('popup_subscription_resumed.title'),
							message: this.$t('popup_subscription_resumed.message'),
							buttonStyle: 'theme',
							button: this.$t('popup_subscription_resumed.button')
						})
					})
					.catch(() => {
						events.$emit('alert:open', {
							title: this.$t('popup_error.title'),
							message: this.$t('popup_error.message'),
						})
					})
					.finally(() => {

						// End deleting spinner button
						this.isResuming = false
						this.isLoading = false
						this.isConfirmedResume = false
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
		}
	}
</script>
