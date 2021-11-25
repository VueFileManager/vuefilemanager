<template>
    <div>
		<div class="card shadow-card pb-0 sticky top-0 z-10">

			<div class="mb-2">
				<h1 class="font-bold text-xl">
					{{ plan.attributes.name }}
				</h1>
				<small class="text-sm font-bold text-gray-500">
					{{ plan.attributes.price }} / {{ $t(`interval.${plan.attributes.interval}`) }}
				</small>
			</div>

			<CardNavigation :pages="pages" class="-mx-3.5" />
		</div>

		<router-view v-if="! isLoading" :plan="plan" />

        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
	import CardNavigation from "../../../components/Admin/CardNavigation"
	import Spinner from '/resources/js/components/FilesView/Spinner'
	import axios from 'axios'

	export default {
		name: 'Plan',
		components: {
			CardNavigation,
			Spinner,
		},
		data() {
			return {
				isLoading: true,
				plan: undefined,
				pages: [
					{
						title: this.$t('admin_page_plans.tabs.settings'),
						route: 'PlanSettings',
					},
					{
						title: this.$t('admin_page_plans.tabs.subscribers'),
						route: 'PlanSubscribers',
					},
					{
						title: this.$t('admin_page_plans.tabs.delete'),
						route: 'PlanDelete',
					},
				]
			}
		},
		created() {
			axios.get('/api/subscription/plans/' + this.$route.params.id)
				.then(response => {
					this.plan = response.data.data
				})
				.finally(() => {
					this.isLoading = false
				})
		}
	}
</script>
