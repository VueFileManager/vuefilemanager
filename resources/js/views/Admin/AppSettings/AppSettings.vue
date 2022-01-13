<template>
    <div>
		<!--Page Tab links-->
		<div class="card shadow-card sticky top-0 z-10" style="padding-bottom: 0; padding-top: 0;">
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
						title: this.$t('Homepage'),
						route: 'AppIndex',
					},
					{
						title: this.$t('admin_settings.tabs.email'),
						route: 'AppEmail',
					},
				]
			}
		},
		mounted() {
			this.$router.push({name: 'AppOthers'})
		}
	}
</script>
