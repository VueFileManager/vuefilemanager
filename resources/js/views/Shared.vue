<template>
    <div class="lg:flex h-screen lg:overflow-hidden w-full">
        <!--File preview window-->
        <FilePreview />
        <Spotlight />

        <!--Popups-->
        <ProcessingPopup />

		<RemoteUploadPopup />
        <CreateFolderPopup />
        <RenameItemPopup />
        <MoveItemPopup />

        <!-- Mobile components -->
        <FileSortingMobile />
        <MobileMultiSelectToolbar />

        <!--Others-->
        <Vignette />
        <DragUI />
        <Alert />

        <NavigationSharePanel />

        <div
            @contextmenu.prevent.capture="contextMenu($event, undefined)"
            class="lg:flex lg:flex-col lg:w-full lg:px-3.5 min-w-0"
        >
            <DesktopSharepageToolbar />

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
import MobileMultiSelectToolbar from '../components/Layout/Toolbars/MobileMultiSelectToolbar.vue'
import NavigationSharePanel from '../components/EntriesView/NavigationSharePanel.vue'
import FileSortingMobile from '../components/Menus/FileSortingMobile.vue'
import CreateFolderPopup from '../components/Popups/CreateFolderPopup.vue'
import ProcessingPopup from '../components/Popups/ProcessingPopup.vue'
import DesktopToolbar from '../components/Layout/Toolbars/DesktopToolbar.vue'
import RenameItemPopup from '../components/Popups/RenameItemPopup.vue'
import MobileToolbar from '../components/Layout/Toolbars/MobileToolbar.vue'
import FilePreview from '../components/FilePreview/FilePreview.vue'
import MoveItemPopup from '../components/Popups/MoveItemPopup.vue'
import InfoSidebar from '../components/Layout/Sidebars/InfoSidebar.vue'
import Spotlight from '../components/Spotlight/Spotlight.vue'
import Vignette from '../components/UI/Others/Vignette.vue'
import DragUI from '../components/UI/Others/DragUI.vue'
import Alert from '../components/Popups/Alert.vue'
import { mapGetters } from 'vuex'
import { events } from '../bus'
import DesktopSharepageToolbar from '../components/Layout/Toolbars/DesktopSharepageToolbar.vue'
import RemoteUploadPopup from "../components/RemoteUpload/RemoteUploadPopup.vue";
import {getFilesFromDataTransferItems} from "datatransfer-files-promise";

export default {
    name: 'Shared',
    components: {
		RemoteUploadPopup,
        DesktopSharepageToolbar,
        MobileToolbar,
        InfoSidebar,
        NavigationSharePanel,
        MobileMultiSelectToolbar,
        CreateFolderPopup,
        FileSortingMobile,
        ProcessingPopup,
        RenameItemPopup,
        DesktopToolbar,
        MoveItemPopup,
        FilePreview,
        Spotlight,
        Vignette,
        DragUI,
        Alert,
    },
    computed: {
        ...mapGetters(['isVisibleSidebar', 'sharedDetail', 'config', 'currentFolder']),
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

        this.$store
            .dispatch('getShareDetail', this.$route.params.token)
            .then(() => this.$store.dispatch('getFolderTree'))
    },
}
</script>
