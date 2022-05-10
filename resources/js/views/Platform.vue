<template>
    <div class="lg:flex h-screen lg:overflow-hidden w-full">
        <!--On Top of App Components-->
        <FilePreview />
        <Spotlight />

        <!--Popups-->
        <ProcessingPopup />
        <ConfirmPopup />

        <ShareCreatePopup />
        <ShareEditPopup />

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
            class="lg:flex lg:flex-col lg:w-full lg:px-3.5"
        >
            <DesktopToolbar />

            <MobileToolbar />

            <!--File list & info sidebar-->
            <div class="flex space-x-3 lg:overflow-hidden grow" @drop.stop.prevent="uploadDroppedItems($event)" @dragenter.prevent @dragover.prevent>
                <router-view id="file-view" class="relative w-full" :key="$route.fullPath" />

                <InfoSidebar v-if="isVisibleSidebar" />
            </div>
        </div>
    </div>
</template>

<script>
import FileSortingMobile from '../components/Menus/FileSortingMobile'
import SidebarNavigation from '../components/Sidebar/SidebarNavigation'
import FileFilterMobile from '../components/Menus/FileFilterMobile'
import CreateFolderPopup from '../components/Popups/CreateFolderPopup'
import ProcessingPopup from '../components/Popups/ProcessingPopup'
import MobileNavigation from '../components/Mobile/MobileNavigation'
import ShareCreatePopup from '../components/Popups/ShareCreatePopup'
import DesktopToolbar from '../components/Layout/Toolbars/DesktopToolbar'
import ConfirmPopup from '../components/Popups/ConfirmPopup'
import RenameItemPopup from '../components/Popups/RenameItemPopup'
import PanelNavigationFiles from '../components/EntriesView/PanelNavigationFiles'
import MobileToolbar from '../components/Layout/Toolbars/MobileToolbar'
import ShareEditPopup from '../components/Popups/ShareEditPopup'
import FilePreview from '../components/FilePreview/FilePreview'
import MoveItemPopup from '../components/Popups/MoveItemPopup'
import Spotlight from '../components/Spotlight/Spotlight'
import DragUI from '../components/UI/Others/DragUI'
import InfoSidebar from '../components/Layout/Sidebars/InfoSidebar'
import { events } from '../bus'
import { mapGetters } from 'vuex'

export default {
    name: 'Platform',
    components: {
        PanelNavigationFiles,
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
		uploadDroppedItems(event) {
			this.$uploadDraggedFiles(event, this.currentFolder?.data.id)
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
