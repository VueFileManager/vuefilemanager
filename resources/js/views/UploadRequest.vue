<template>
    <div class="lg:flex h-screen lg:overflow-hidden w-full">
        <!--On Top of App Components-->
        <FilePreview />
        <Spotlight />

		<ConfirmPopup />

        <!--Popups-->
        <CreateFolderPopup />
		<RemoteUploadPopup />
        <RenameItemPopup />
        <MoveItemPopup />

        <!--Mobile components-->
        <FileSortingMobile />
        <FileFilterMobile />

        <!--Others-->
        <DragUI />

        <div
            @contextmenu.prevent.capture="contextMenu($event, undefined)"
            class="lg:flex lg:flex-col lg:w-full lg:px-3.5 min-w-0"
        >
            <DesktopUploadRequestToolbar v-if="canShowUI" />
            <MobileUploadRequestToolBar v-if="canShowUI" />

            <!--Google Adsense banner-->
            <div v-if="config.allowedAdsense && config.adsenseBanner01" v-html="config.adsenseBanner01" class="mb-5 min-h-[120px]"></div>

            <!--File list & info sidebar-->
            <div class="flex space-x-3 lg:overflow-hidden grow" @drop.stop.prevent="uploadDroppedItems($event)" @dragenter.prevent @dragover.prevent>
                <router-view id="file-view" class="relative w-full min-w-0" :key="$route.fullPath" />

                <InfoSidebarUploadRequest v-if="canShowUI && isVisibleSidebar" />
            </div>
        </div>
    </div>
</template>

<script>
import DesktopUploadRequestToolbar from '../components/Layout/Toolbars/DesktopUploadRequestToolbar.vue'
import MobileUploadRequestToolBar from "../components/Layout/Toolbars/MobileUploadRequestToolbar.vue"
import InfoSidebarUploadRequest from "../components/Layout/Sidebars/InfoSidebarUploadRequest.vue"
import FileSortingMobile from '../components/Menus/FileSortingMobile.vue'
import FileFilterMobile from '../components/Menus/FileFilterMobile.vue'
import CreateFolderPopup from '../components/Popups/CreateFolderPopup.vue'
import DesktopToolbar from '../components/Layout/Toolbars/DesktopToolbar.vue'
import ConfirmPopup from "../components/Popups/ConfirmPopup.vue"
import RenameItemPopup from '../components/Popups/RenameItemPopup.vue'
import FilePreview from '../components/FilePreview/FilePreview.vue'
import MoveItemPopup from '../components/Popups/MoveItemPopup.vue'
import Spotlight from '../components/Spotlight/Spotlight.vue'
import DragUI from '../components/UI/Others/DragUI.vue'
import { mapGetters } from 'vuex'
import { events } from '../bus'
import RemoteUploadPopup from "../components/RemoteUpload/RemoteUploadPopup.vue";
import {getFilesFromDataTransferItems} from "datatransfer-files-promise";

export default {
    name: 'UploadRequest',
    components: {
		RemoteUploadPopup,
        DesktopUploadRequestToolbar,
		MobileUploadRequestToolBar,
		InfoSidebarUploadRequest,
        CreateFolderPopup,
        FileSortingMobile,
        FileFilterMobile,
        RenameItemPopup,
        DesktopToolbar,
        MoveItemPopup,
		ConfirmPopup,
        FilePreview,
        Spotlight,
        DragUI,
    },
    computed: {
        ...mapGetters(['isVisibleSidebar', 'config', 'uploadRequest', 'fileQueue', 'currentFolder']),
		canShowUI() {
			return (this.uploadRequest && this.uploadRequest.data.attributes.status === 'filling') || this.fileQueue.length > 0
		}
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

		events.$on('action:confirmed', (data) => {
			if (data.operation === 'close-upload-request')
				this.$store.dispatch('closeUploadRequest')
		})

		this.$store.dispatch('getUploadRequestDetail')
			.then((response) => {
				if (! this.$route.params.id && response.data.data.attributes.status === 'filling') {
					this.$store.dispatch('getUploadRequestFolder')
				}
			})
	},
}
</script>
