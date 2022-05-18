<template>
    <div class="h-screen lg:overflow-hidden lg:flex w-full">
        <!--On Top of App Components-->
        <FilePreview />
        <Spotlight />

		<!--Spotlight Addons-->
		<CreateUploadRequestPopup />
		<CreateTeamFolderPopup />
		<NotificationsPopup />
		<RemoteUploadPopup />

		<!--Mobile Navigation-->
        <MobileNavigation />

        <!--ConfirmPopup Popup-->
        <ConfirmPopup />
		<ProcessingPopup />

        <!-- Create language popup -->
        <CreateLanguage />

        <MobileNavigationToolbar />

        <ContentSidebar>
            <ContentGroup
                v-for="(menu, i) in nav"
                :key="i"
                :title="menu.groupTitle"
                :slug="menu.groupTitle"
                :can-collapse="false"
            >
                <router-link
                    v-for="(item, i) in menu.groupLinks"
                    :key="i"
                    :to="{ name: item.route }"
                    class="flex items-center py-2.5"
                    :class="{
                        'router-link-active':
                            item.linkActivation &&
                            item.linkActivation.includes($router.currentRoute.fullPath.split('/')[2]),
                    }"
                >
                    <box-icon v-if="item.icon === 'box'" size="17" class="vue-feather icon-active mr-2.5" />
                    <users-icon v-if="item.icon === 'users'" size="17" class="vue-feather icon-active mr-2.5" />
                    <settings-icon v-if="item.icon === 'settings'" size="17" class="vue-feather icon-active mr-2.5" />
                    <monitor-icon v-if="item.icon === 'monitor'" size="17" class="vue-feather icon-active mr-2.5" />
                    <globe-icon v-if="item.icon === 'globe'" size="17" class="vue-feather icon-active mr-2.5" />
                    <credit-card-icon v-if="item.icon === 'card'" size="17" class="vue-feather icon-active mr-2.5" />
                    <database-icon v-if="item.icon === 'database'" size="17" class="vue-feather icon-active mr-2.5" />
                    <dollar-sign-icon v-if="item.icon === 'dollar'" size="17" class="vue-feather icon-active mr-2.5" />
                    <file-text-icon v-if="item.icon === 'file-text'" size="17" class="vue-feather icon-active mr-2.5" />

                    <b class="text-active text-xs font-bold">
                        {{ item.title }}
                    </b>
                </router-link>
            </ContentGroup>
        </ContentSidebar>

        <router-view class="relative w-full overflow-x-hidden px-2.5 pb-12 md:px-6 lg:pt-6 lg:pb-0 z-[5]" />
    </div>
</template>

<script>
import FilePreview from '../components/FilePreview/FilePreview'
import CreateLanguage from '../components/Popups/CreateLanguagePopup'
import MobileNavigation from '../components/Mobile/MobileNavigation'
import ConfirmPopup from '../components/Popups/ConfirmPopup'
import ContentGroup from '../components/Sidebar/ContentGroup'
import ContentSidebar from '../components/Sidebar/ContentSidebar'
import Spotlight from '../components/Spotlight/Spotlight'
import MobileNavigationToolbar from '../components/Mobile/MobileNavigationToolbar'
import {
    BoxIcon,
    CreditCardIcon,
    DatabaseIcon,
    DollarSignIcon,
    FileTextIcon,
    GlobeIcon,
    HelpCircleIcon,
    MonitorIcon,
    RefreshCwIcon,
    SettingsIcon,
    UsersIcon,
} from 'vue-feather-icons'
import { mapGetters } from 'vuex'
import CreateUploadRequestPopup from "../components/UploadRequest/CreateUploadRequestPopup";
import CreateTeamFolderPopup from "../components/Teams/CreateTeamFolderPopup";
import NotificationsPopup from "../components/Notifications/NotificationsPopup";
import RemoteUploadPopup from "../components/RemoteUpload/RemoteUploadPopup";
import ProcessingPopup from "../components/Popups/ProcessingPopup";

export default {
    name: 'Admin',
    computed: {
        ...mapGetters(['isVisibleNavigationBars', 'config']),
        nav() {
            let subscriptionLinks = {
                metered: [
                    {
                        title: this.$t('payments'),
                        route: 'PaymentSettings',
                        icon: 'card',
                    },
                    {
                        title: this.$t('plans'),
                        route: 'Plans',
                        icon: 'database',
                        linkActivation: ['plans', 'plan'],
                    },
                    {
                        title: this.$t('transactions'),
                        route: 'Invoices',
                        icon: 'file-text',
                    },
                ],
                fixed: [
                    {
                        title: this.$t('payments'),
                        route: 'PaymentSettings',
                        icon: 'card',
                    },
                    {
                        title: this.$t('subscriptions'),
                        route: 'Subscriptions',
                        icon: 'dollar',
                    },
                    {
                        title: this.$t('plans'),
                        route: 'Plans',
                        icon: 'database',
                        linkActivation: ['plans', 'plan'],
                    },
                    {
                        title: this.$t('transactions'),
                        route: 'Invoices',
                        icon: 'file-text',
                    },
                ],
            }[this.config.subscriptionType]

            let sections = [
                {
                    groupCollapsable: false,
                    groupTitle: this.$t('admin'),
                    groupLinks: [
                        {
                            title: this.$t('dashboard'),
                            route: 'Dashboard',
                            icon: 'box',
                        },
                        {
                            title: this.$t('users'),
                            route: 'Users',
                            icon: 'users',
                            linkActivation: ['users', 'user'],
                        },
                        {
                            title: this.$t('settings'),
                            route: 'AppSettings',
                            icon: 'settings',
                        },
                    ],
                },
                {
                    groupCollapsable: false,
                    groupTitle: this.$t('content'),
                    groupLinks: [
                        {
                            title: this.$t('pages'),
                            route: 'Pages',
                            icon: 'monitor',
                        },
                        {
                            title: this.$t('languages'),
                            route: 'Language',
                            icon: 'globe',
                        },
                    ],
                },
            ]

            // Push subscription if there is metered or fixed type
            if (this.config.subscriptionType !== 'none') {
                sections.push({
                    groupCollapsable: false,
                    groupTitle: this.$t('subscription'),
                    groupLinks: subscriptionLinks,
                })
            }

            return sections
        },
    },
    components: {
		ProcessingPopup,
		RemoteUploadPopup,
		NotificationsPopup,
		CreateTeamFolderPopup,
		CreateUploadRequestPopup,
        MobileNavigationToolbar,
        FilePreview,
        Spotlight,
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
