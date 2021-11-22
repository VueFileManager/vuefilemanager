<template>
    <div class="pr-6 w-full overflow-x-hidden relative" style="background: rgba(244, 245, 246, 0.6)">
		<!--Page Tab links-->
		<div class="card shadow-card py-0 lg:mt-6 mt-4 sticky top-0 z-10">
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

		<!--Router Content-->
		<router-view class="" />
    </div>
</template>

<script>
    import {UsersIcon, SettingsIcon, Trash2Icon, EyeIcon, FileTextIcon, CodeIcon, MailIcon, CreditCardIcon, HomeIcon} from 'vue-feather-icons'
	import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
	import SectionTitle from '/resources/js/components/Others/SectionTitle'
	import PageHeader from '/resources/js/components/Others/PageHeader'
	import {mapGetters} from 'vuex'

	export default {
		name: 'AppSettings',
		components: {
			HomeIcon,
			CreditCardIcon,
			CodeIcon,
			MailIcon,
			FileTextIcon,
			EyeIcon,
			UsersIcon,
			Trash2Icon,
			SettingsIcon,
			SectionTitle,
			MobileHeader,
			PageHeader,
		},
		computed: {
			...mapGetters(['config']),
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
						title: 'Homepage',
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

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
	@import '/resources/sass/vuefilemanager/_mixins';
</style>
