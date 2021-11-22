<template>
    <div>
		<!--Page Tab links-->
		<div class="card shadow-card py-0 sticky top-0 z-10">
			<router-link
				class="inline-block text-sm font-bold px-4 py-5 border-b-2 border-transparent border-bottom-theme"
				:class="{'text-theme': $router.currentRoute.name === page.route, 'text-gray-600': $router.currentRoute.name !== page.route}"
				v-for="(page, i) in pages"
				:to="{name: page.route}"
				:key="i"
				replace
			>
				{{ page.title }}
			</router-link>
		</div>

		<!--Page Content-->
		<router-view />
    </div>
</template>

<script>
	import {mapGetters} from 'vuex'

	export default {
		name: 'AppSettings',
		components: {

		},
		computed: {
			...mapGetters([
				'config'
			]),
		},
		data() {
			return {
				pages: [
					{
						title: this.$t('admin_settings.tabs.others'),
						route: 'AppOthers',
					},
					{
						title: this.$t('admin_settings.tabs.appearance'),
						route: 'AppAppearance',
					},
					{
						title: this.$t('admin_settings.tabs.billings'),
						route: 'AppBillings',
					},
					{
						title: this.$t('admin_settings.tabs.payments'),
						route: 'AppPayments',
					},
					{
						title: this.$t('Homepage'),
						route: 'AppIndex',
					},
					{
						title: this.$t('admin_settings.tabs.email'),
						route: 'AppEmail',
					},
				]
			}
		}
	}
</script>
