<template>
	<div class="card shadow-card">
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
	</div>
</template>
<script>
	import ProgressLine from "../../../../components/Admin/ProgressLine"
	import {mapGetters} from "vuex";

	export default {
		name: 'UserFixedSubscription',
		props: [
			'subscription',
			'user',
		],
		components: {
			ProgressLine
		},
		computed: {
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
				limitations: []
			}
		},
		created() {
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
		}

	}
</script>