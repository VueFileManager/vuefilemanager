<template>
	<div v-if="hasSubscription" class="card shadow-card">
		<FormLabel>
			{{ $t('Subscription') }}
		</FormLabel>

		<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
			{{ status }}
		</b>

		<b class="mb-3 block text-sm text-gray-400 mb-8">
			{{ subscription.data.relationships.plan.data.attributes.name }} / {{ price }}
		</b>

		<div v-for="(limit, i) in limitations" :key="i" :class="{'mb-6': (Object.keys(limitations).length - 1) !== i}">
			<b class="mb-3 block text-sm text-gray-400">
				{{ limit.message }}
			</b>
			<ProgressLine :data="limit.distribution" />
		</div>
	</div>
</template>
<script>
	import FormLabel from "../Others/Forms/FormLabel"
	import ProgressLine from "../Admin/ProgressLine"
	import {mapGetters} from "vuex";

	export default {
		name: 'UserFixedSubscriptionDetail',
		components: {
			ProgressLine,
			FormLabel,
		},
		computed: {
			...mapGetters([
				'user',
			]),
			subscription() {
				return this.$store.getters.user.data.relationships.subscription
			},
			hasSubscription() {
				return this.$store.getters.user.data.relationships.subscription
			},
			limitations() {
				let limitations = []

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

						limitations.push({
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

				return limitations
			},
			status() {
				return {
					'active': `Active until ${this.subscription.data.attributes.renews_at}`,
					'cancelled': `Ends at ${this.subscription.data.attributes.ends_at}`,
				}[this.subscription.data.attributes.status]
			},
			price() {
				return {
					'month': `${this.subscription.data.relationships.plan.data.attributes.price} Per Month`,
					'year': `${this.subscription.data.relationships.plan.data.attributes.price} Per Year`,
				}[this.subscription.data.relationships.plan.data.attributes.interval]
			},
		}
	}
</script>