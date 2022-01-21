<template>
    <div class="sm:flex md:h-screen md:overflow-hidden dark:bg-dark-background bg-light-background">
		<!--On Top of App Components-->
        <FilePreview />
		<Spotlight />

        <!--Mobile Navigation-->
        <MobileNavigation />

        <!--ConfirmPopup Popup-->
        <ConfirmPopup />

        <!-- Create language popup -->
        <CreateLanguage/>

        <!--Navigation Sidebar-->
        <SidebarNavigation/>

        <ContentSidebar>
			<ContentGroup v-for="(menu, i) in nav" :title="menu.groupTitle" :slug="menu.groupTitle" :can-collapse="false">
				<router-link v-for="(item, i) in menu.groupLinks" :key="i" :to="{name: item.route}" class="flex items-center py-2.5" :class="{'router-link-active': item.linkActivation && item.linkActivation.includes($router.currentRoute.fullPath.split('/')[2])}">
					<box-icon v-if="item.icon === 'box'" size="17" class="mr-2.5 vue-feather icon-active" />
					<users-icon v-if="item.icon === 'users'" size="17" class="mr-2.5 vue-feather icon-active" />
					<settings-icon v-if="item.icon === 'settings'" size="17" class="mr-2.5 vue-feather icon-active" />
					<monitor-icon v-if="item.icon === 'monitor'" size="17" class="mr-2.5 vue-feather icon-active" />
					<globe-icon v-if="item.icon === 'globe'" size="17" class="mr-2.5 vue-feather icon-active" />
					<credit-card-icon v-if="item.icon === 'card'" size="17" class="mr-2.5 vue-feather icon-active" />
					<database-icon v-if="item.icon === 'database'" size="17" class="mr-2.5 vue-feather icon-active" />
					<dollar-sign-icon v-if="item.icon === 'dollar'" size="17" class="mr-2.5 vue-feather icon-active" />
					<file-text-icon v-if="item.icon === 'file-text'" size="17" class="mr-2.5 vue-feather icon-active" />

					<b class="font-bold text-xs text-active">
						{{ item.title }}
					</b>
				</router-link>
            </ContentGroup>
        </ContentSidebar>

        <router-view class="lg:pl-0 pl-6 pr-6 w-full overflow-x-hidden relative lg:pt-6 pt-4" />
    </div>
</template>

<script>
    import FilePreview from '/resources/js/components/FilePreview/FilePreview'
	import Spotlight from '/resources/js/components/Spotlight/Spotlight'
    import { DollarSignIcon, HelpCircleIcon, RefreshCwIcon, UsersIcon, SettingsIcon, FileTextIcon, CreditCardIcon, DatabaseIcon, BoxIcon, MonitorIcon, GlobeIcon } from 'vue-feather-icons'
    import SidebarNavigation from '/resources/js/components/Sidebar/SidebarNavigation'
    import MobileNavigation from '/resources/js/components/Others/MobileNavigation'
    import ContentSidebar from '/resources/js/components/Sidebar/ContentSidebar'
    import CreateLanguage from '/resources/js/components/Others/CreateLanguage'
    import ContentGroup from '/resources/js/components/Sidebar/ContentGroup'
    import ConfirmPopup from '/resources/js/components/Others/Popup/ConfirmPopup'
    import { mapGetters } from 'vuex'

    export default {
        name: 'Admin',
        computed: {
            ...mapGetters([
				'isVisibleNavigationBars',
				'config',
			]),
			nav() {
				let subscriptionLinks = {
					metered: [
						{
							title: this.$t('Payments'),
							route: 'PaymentSettings',
							icon: 'card',
						},
						{
							title: this.$t('admin_menu.plans'),
							route: 'Plans',
							icon: 'database',
							linkActivation: [
								'plans', 'plan'
							],
						},
						{
							title: this.$t('Transactions'),
							route: 'Invoices',
							icon: 'file-text',
						},
					],
					fixed: [
						{
							title: this.$t('Payments'),
							route: 'PaymentSettings',
							icon: 'card',
						},
						{
							title: this.$t('Subscriptions'),
							route: 'Subscriptions',
							icon: 'dollar',
						},
						{
							title: this.$t('admin_menu.plans'),
							route: 'Plans',
							icon: 'database',
							linkActivation: [
								'plans', 'plan'
							],
						},
						{
							title: this.$t('Transactions'),
							route: 'Invoices',
							icon: 'file-text',
						},
					],
				}[this.config.subscriptionType]

				return [
					{
						groupCollapsable: false,
						groupTitle: this.$t('global.admin'),
						groupLinks: [
							{
								title: this.$t('admin_menu.dashboard'),
								route: 'Dashboard',
								icon: 'box',
							},
							{
								title: this.$t('admin_menu.users'),
								route: 'Users',
								icon: 'users',
								linkActivation: [
									'users', 'user'
								],
							},
							{
								title: this.$t('admin_menu.settings'),
								route: 'AppSettings',
								icon: 'settings',
							},
						],
					},
					{
						groupCollapsable: false,
						groupTitle: this.$t('Content'),
						groupLinks: [
							{
								title: this.$t('admin_menu.pages'),
								route: 'Pages',
								icon: 'monitor',
							},
							{
								title: this.$t('admin_menu.languages'),
								route: 'Language',
								icon: 'globe',
							},
						],
					},
					{
						groupCollapsable: false,
						groupTitle: this.$t('Subscription'),
						groupLinks: subscriptionLinks,
					},
				]
			}
        },
        components: {
			FilePreview,
			Spotlight,
            SidebarNavigation,
            MobileNavigation,
            CreateLanguage,
            ContentSidebar,
			DollarSignIcon,
			HelpCircleIcon,
			RefreshCwIcon,
            CreditCardIcon,
            FileTextIcon,
            ContentGroup,
            DatabaseIcon,
            SettingsIcon,
            MonitorIcon,
            UsersIcon,
            GlobeIcon,
            ConfirmPopup,
            BoxIcon,
        },
	}
</script>
