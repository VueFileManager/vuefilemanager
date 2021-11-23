<template>
    <div class="sm:flex md:h-screen md:overflow-hidden" style="background: rgba(244, 245, 246, 0.6)">
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
                    <router-link :to="{name: 'Users'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <users-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.users') }}
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

            <!--SaaS-->
            <ContentGroup v-if="config.isSaaS" title="Subscription" class="navigator">
                <div class="menu-list-wrapper vertical">
                    <router-link :to="{name: 'Plans'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <database-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.plans') }}
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Invoices'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <file-text-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_menu.invoices') }}
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
    import { UsersIcon, SettingsIcon, FileTextIcon, CreditCardIcon, DatabaseIcon, BoxIcon, MonitorIcon, GlobeIcon } from 'vue-feather-icons'
    import SidebarNavigation from '/resources/js/components/Sidebar/SidebarNavigation'
    import MobileNavigation from '/resources/js/components/Others/MobileNavigation'
    import ContentSidebar from '/resources/js/components/Sidebar/ContentSidebar'
    import CreateLanguage from '/resources/js/components/Others/CreateLanguage'
    import ContentGroup from '/resources/js/components/Sidebar/ContentGroup'
    import ConfirmPopup from '/resources/js/components/Others/Popup/ConfirmPopup'
    import { mapGetters } from 'vuex'

    export default {
        name: 'Settings',
        computed: {
            ...mapGetters([
				'config'
			]),
        },
        components: {
			FilePreview,
			Spotlight,
            SidebarNavigation,
            MobileNavigation,
            CreateLanguage,
            ContentSidebar,
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

<style lang="scss" scoped>

    .user-headline {
        margin-bottom: 38px;
    }
</style>
