<template>
    <div>
		<div v-if="plan" class="card shadow-card sticky top-0 z-10" style="padding-bottom: 0;">

			<div class="mb-2">
				<h1 class="font-bold text-xl">
					{{ plan.attributes.name }}
				</h1>
				<small class="text-sm font-bold text-gray-500">
					{{ $t('30 Days intervals') }}
				</small>
			</div>

			<CardNavigation :pages="pages" class="-mx-1.5" />
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
	import {mapGetters} from "vuex";

	export default {
		name: 'MeteredPlan',
		components: {
			CardNavigation,
			Spinner,
		},
		computed: {
			...mapGetters([
				'config'
			]),
			pages() {
				let pages = [
					{
						title: this.$t('admin_page_plans.tabs.settings'),
						route: 'PlanMeteredSettings',
					},
					{
						title: this.$t('admin_page_plans.tabs.subscribers'),
						route: 'PlanMeteredSubscribers',
					},
				]

				if (this.plan && this.plan.attributes.status === 'active') {
					pages.push({
						title: this.$t('admin_page_plans.tabs.delete'),
						route: 'PlanMeteredDelete',
					})
				}

				return pages
			}
		},
		data() {
			return {
				isLoading: true,
				plan: undefined,
			}
		},
		created() {
			axios.get('/api/subscriptions/admin/plans/' + this.$route.params.id)
				.then(response => {
					this.plan = response.data.data
				})
				.finally(() => {
					this.isLoading = false
				})
		}
	}
</script>
