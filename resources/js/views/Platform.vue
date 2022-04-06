<template>
    <div class="lg:flex lg:h-screen lg:overflow-hidden w-full">
        <!--On Top of App Components-->
        <FilePreview />
        <Spotlight />

        <!--Popups-->
        <ProcessingPopup />
        <ConfirmPopup />

        <CreateTeamFolderPopup />
        <EditTeamFolderPopup />

        <ShareCreatePopup />
        <ShareEditPopup />

        <CreateUploadRequestPopup />
		<NotificationsPopup />
        <CreateFolderPopup />
        <RenameItemPopup />
        <MoveItemPopup />

        <!--Mobile components-->
        <FileSortingMobile />
        <FileFilterMobile />

        <!--Navigations-->
        <MobileNavigation />

        <!--Others-->
        <DragUI />

        <!--2 col Sidebars-->
        <PanelNavigationFiles />

        <div
            @contextmenu.prevent.capture="contextMenu($event, undefined)"
            class="transition-transform duration-200 lg:grid lg:flex-grow lg:content-start lg:px-3.5"
        >
            <DesktopToolbar />

            <MobileToolbar />

            <!--Google Adsense banner-->
            <div v-if="config.allowedAdsense && config.adsenseBanner01" v-html="config.adsenseBanner01" class="mb-5 min-h-[120px]"></div>

            <!--File list & info sidebar-->
            <div class="flex space-x-3 lg:overflow-hidden">
                <router-view id="file-view" class="relative w-full" :key="$route.fullPath" />

                <InfoSidebar v-if="isVisibleSidebar" />
            </div>
        </div>
    </div>
</template>

<script>
import FileSortingMobile from '../components/FilesView/FileSortingMobile'
import SidebarNavigation from '../components/Sidebar/SidebarNavigation'
import FileFilterMobile from '../components/FilesView/FileFilterMobile'
import CreateFolderPopup from '../components/Others/CreateFolderPopup'
import ProcessingPopup from '../components/FilesView/ProcessingPopup'
import MobileNavigation from '../components/Others/MobileNavigation'
import ShareCreatePopup from '../components/Others/ShareCreatePopup'
import DesktopToolbar from '../components/FilesView/DesktopToolbar'
import CreateTeamFolderPopup from '../components/Teams/CreateTeamFolderPopup'
import ConfirmPopup from '../components/Others/Popup/ConfirmPopup'
import RenameItemPopup from '../components/Others/RenameItemPopup'
import PanelNavigationFiles from './FileView/Components/PanelNavigationFiles'
import MobileToolbar from '../components/FilesView/MobileToolbar'
import ShareEditPopup from '../components/Others/ShareEditPopup'
import FilePreview from '../components/FilePreview/FilePreview'
import MoveItemPopup from '../components/Others/MoveItemPopup'
import EditTeamFolderPopup from '../components/Teams/EditTeamFolderPopup'
import Spotlight from '../components/Spotlight/Spotlight'
import DragUI from '../components/FilesView/DragUI'
import InfoSidebar from '../components/FilesView/InfoSidebar'
import { events } from '../bus'
import { mapGetters } from 'vuex'
import CreateUploadRequestPopup from "../components/Others/CreateUploadRequestPopup";
import NotificationsPopup from "../components/Others/NotificationsPopup";

export default {
    name: 'Platform',
    components: {
		NotificationsPopup,
		CreateUploadRequestPopup,
        CreateTeamFolderPopup,
        PanelNavigationFiles,
        EditTeamFolderPopup,
        CreateFolderPopup,
        FileSortingMobile,
        SidebarNavigation,
        FileFilterMobile,
        MobileNavigation,
        ShareCreatePopup,
        ProcessingPopup,
        RenameItemPopup,
        ShareEditPopup,
        DesktopToolbar,
        MoveItemPopup,
        MobileToolbar,
        ConfirmPopup,
        InfoSidebar,
        FilePreview,
        Spotlight,
        DragUI,
    },
    computed: {
        ...mapGetters(['isVisibleSidebar', 'isLimitedUser', 'config']),
    },
    data() {
        return {
            isScaledDown: false,
        }
    },
    methods: {
        contextMenu(event, item) {
            events.$emit('context-menu:show', event, item)
        },
    },
    mounted() {
        // TODO: new scaledown effect
        events.$on('mobile-menu:show', () => (this.isScaledDown = true))
        events.$on('mobile-menu:hide', () => (this.isScaledDown = false))
    },
}
</script>
