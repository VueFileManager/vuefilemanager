<template>
    <div class="lg:flex h-screen lg:overflow-hidden w-full">
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
		<RemoteUploadPopup />
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
            class="lg:flex lg:flex-col lg:w-full lg:px-3.5 min-w-0"
        >
            <DesktopToolbar />

            <MobileToolbar />

            <!--Google Adsense banner-->
            <div v-if="config.allowedAdsense && config.adsenseBanner01" v-html="config.adsenseBanner01" class="mb-5 min-h-[120px]"></div>

            <!--File list & info sidebar-->
            <div class="flex space-x-3 lg:overflow-hidden grow" @drop.stop.prevent="uploadDroppedItems($event)" @dragenter.prevent @dragover.prevent>
                <router-view id="file-view" class="relative w-full min-w-0" :key="$route.fullPath" />

                <InfoSidebar v-if="isVisibleSidebar" />
            </div>
        </div>
    </div>
</template>

<script>
import { getFilesFromDataTransferItems } from 'datatransfer-files-promise'
import FileSortingMobile from '../components/Menus/FileSortingMobile.vue'
import SidebarNavigation from '../components/Sidebar/SidebarNavigation.vue'
import FileFilterMobile from '../components/Menus/FileFilterMobile.vue'
import CreateFolderPopup from '../components/Popups/CreateFolderPopup.vue'
import ProcessingPopup from '../components/Popups/ProcessingPopup.vue'
import MobileNavigation from '../components/Mobile/MobileNavigation.vue'
import ShareCreatePopup from '../components/Popups/ShareCreatePopup.vue'
import DesktopToolbar from '../components/Layout/Toolbars/DesktopToolbar.vue'
import CreateTeamFolderPopup from '../components/Teams/CreateTeamFolderPopup.vue'
import ConfirmPopup from '../components/Popups/ConfirmPopup.vue'
import RenameItemPopup from '../components/Popups/RenameItemPopup.vue'
import PanelNavigationFiles from '../components/EntriesView/PanelNavigationFiles.vue'
import MobileToolbar from '../components/Layout/Toolbars/MobileToolbar.vue'
import ShareEditPopup from '../components/Popups/ShareEditPopup.vue'
import FilePreview from '../components/FilePreview/FilePreview.vue'
import MoveItemPopup from '../components/Popups/MoveItemPopup.vue'
import EditTeamFolderPopup from '../components/Teams/EditTeamFolderPopup.vue'
import Spotlight from '../components/Spotlight/Spotlight.vue'
import DragUI from '../components/UI/Others/DragUI.vue'
import InfoSidebar from '../components/Layout/Sidebars/InfoSidebar.vue'
import { events } from '../bus'
import { mapGetters } from 'vuex'
import CreateUploadRequestPopup from "../components/UploadRequest/CreateUploadRequestPopup.vue";
import NotificationsPopup from "../components/Notifications/NotificationsPopup.vue";
import RemoteUploadPopup from "../components/RemoteUpload/RemoteUploadPopup.vue";

export default {
    name: 'Platform',
    components: {
		RemoteUploadPopup,
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
        ...mapGetters(['isVisibleSidebar', 'config', 'currentFolder']),
    },
    data() {
        return {
            isScaledDown: false,
        }
    },
    methods: {
		async uploadDroppedItems(event) {
			// Check if user dropped folder with files
			let files = await getFilesFromDataTransferItems(event.dataTransfer.items)

			if (files.length !== 0) {
				// Upload folder with files
				this.$uploadDraggedFolderOrFile(files, this.currentFolder?.data.id)
			}
		},
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
