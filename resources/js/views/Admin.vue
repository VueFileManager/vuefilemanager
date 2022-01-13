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

            <!--Admin-->
			<ContentGroup :title="$t('global.admin')" class="navigator">
                <div class="menu-list-wrapper vertical">
					<router-link v-for="(menu, i) in admin" :key="i" :to="{name: menu.route}" class="menu-list-item link" :class="{'router-link-active': menu.linkActivation && menu.linkActivation.includes($router.currentRoute.fullPath.split('/')[2])}">
                        <div class="icon text-theme">
                            <box-icon v-if="menu.icon === 'box'" size="17" />
                            <users-icon v-if="menu.icon === 'users'" size="17" />
                            <settings-icon v-if="menu.icon === 'settings'" size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ menu.title }}
                        </div>
                    </router-link>
                </div>
            </ContentGroup>

            <!--Content-->
            <ContentGroup :title="$t('Content')" class="navigator">
                <div class="menu-list-wrapper vertical">
					<router-link v-for="(menu, i) in content" :key="i" :to="{name: menu.route}" class="menu-list-item link">
                        <div class="icon text-theme">
							<monitor-icon v-if="menu.icon === 'monitor'" size="17" />
                            <globe-icon v-if="menu.icon === 'globe'" size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ menu.title }}
                        </div>
                    </router-link>
                </div>
            </ContentGroup>

            <!-- Assets -->
            <ContentGroup :title="$t('Subscription')" v-if="config.isSaaS" class="navigator">
                <div class="menu-list-wrapper vertical">
					<router-link v-for="(menu, i) in assetMenu" :key="i" :to="{name: menu.route}" class="menu-list-item link" :class="{'router-link-active': menu.linkActivation && menu.linkActivation.includes($router.currentRoute.fullPath.split('/')[2])}">
                        <div class="icon text-theme">
                            <credit-card-icon v-if="menu.icon === 'card'" size="17" />
                            <database-icon v-if="menu.icon === 'database'" size="17" />
                            <dollar-sign-icon v-if="menu.icon === 'dollar'" size="17" />
                            <file-text-icon v-if="menu.icon === 'file-text'" size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ menu.title }}
                        </div>
                    </router-link>
                </div>
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
			assetMenu() {
				return {
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
			}
        },
		data() {
			return {
				admin: [
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
				content: [
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
