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
                    <router-link :to="{name: 'Dashboard'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <box-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.dashboard') }}
                        </div>
                    </router-link>
                    <router-link :to="{name: 'AppOthers'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <settings-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.settings') }}
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Pages'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <monitor-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.pages') }}
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Language'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <globe-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.languages') }}
                        </div>
                    </router-link>
                </div>
            </ContentGroup>

            <!-- Assets -->
            <ContentGroup :title="$t('Assets')" class="navigator">
                <div class="menu-list-wrapper vertical">
					<router-link v-for="(menu, i) in assetMenu" :key="i" :to="{name: menu.route}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <users-icon v-if="menu.icon === 'users'" size="17" />
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
				'config'
			]),
			assetMenu() {
				return {
					metered: [
						{
							title: this.$t('admin_menu.users'),
							route: 'Users',
							icon: 'users',
						},
						{
							title: this.$t('admin_menu.plans'),
							route: 'Plans',
							icon: 'database',
						},
						{
							title: this.$t('Transactions'),
							route: 'Invoices',
							icon: 'file-text',
						},
					],
					fixed: [
						{
							title: this.$t('admin_menu.users'),
							route: 'Users',
							icon: 'users',
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
						},
						{
							title: this.$t('Transactions'),
							route: 'Invoices',
							icon: 'file-text',
						},
					],
					none: [
						{
							title: this.$t('admin_menu.users'),
							route: 'Users',
							icon: 'users',
						},
					],
				}[this.config.subscriptionType]
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
