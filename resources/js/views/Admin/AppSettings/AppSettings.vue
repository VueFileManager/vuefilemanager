<template>
    <div>
		<!--Page Tab links-->
		<div class="card shadow-card py-0 sticky top-0 z-10">
			<CardNavigation :pages="pages" class="-mx-1" />
		</div>

		<!--Page Content-->
		<router-view />
    </div>
</template>

<script>
	import CardNavigation from "../../../components/Admin/CardNavigation";
	import {mapGetters} from 'vuex'

	export default {
		name: 'AppSettings',
		components: {
			CardNavigation,
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
						title: this.$t('admin_settings.tabs.payments'),
						route: 'AppPayments',
					},
					{
						title: this.$t('admin_settings.tabs.billings'),
						route: 'AppBillings',
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
