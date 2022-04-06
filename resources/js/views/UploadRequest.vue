<template>
    <div class="lg:flex lg:h-screen lg:overflow-hidden w-full">
        <!--On Top of App Components-->
        <FilePreview />
        <Spotlight />

		<ConfirmPopup />

        <!--Popups-->
        <CreateFolderPopup />
        <RenameItemPopup />
        <MoveItemPopup />

        <!--Mobile components-->
        <FileSortingMobile />
        <FileFilterMobile />

        <!--Others-->
        <DragUI />

        <div
            @contextmenu.prevent.capture="contextMenu($event, undefined)"
            class="transition-transform duration-200 lg:grid lg:flex-grow lg:content-start lg:px-3.5"
        >
            <DesktopUploadRequestToolbar v-if="canShowUI" />
            <MobileUploadRequestToolBar v-if="canShowUI" />

            <!--Google Adsense banner-->
            <div v-if="config.allowedAdsense && config.adsenseBanner01" v-html="config.adsenseBanner01" class="mb-5 min-h-[120px]"></div>

            <!--File list & info sidebar-->
            <div class="flex space-x-3 lg:overflow-hidden">
                <router-view id="file-view" class="relative w-full" :key="$route.fullPath" />

                <InfoSidebarUploadRequest v-if="canShowUI && isVisibleSidebar" />
            </div>
        </div>
    </div>
</template>

<script>
import DesktopUploadRequestToolbar from '../components/FilesView/DesktopUploadRequestToolbar'
import MobileUploadRequestToolBar from "../components/FilesView/MobileUploadRequestToolbar"
import InfoSidebarUploadRequest from "../components/FilesView/InfoSidebarUploadRequest"
import FileSortingMobile from '../components/FilesView/FileSortingMobile'
import FileFilterMobile from '../components/FilesView/FileFilterMobile'
import CreateFolderPopup from '../components/Others/CreateFolderPopup'
import DesktopToolbar from '../components/FilesView/DesktopToolbar'
import ConfirmPopup from "../components/Others/Popup/ConfirmPopup"
import RenameItemPopup from '../components/Others/RenameItemPopup'
import FilePreview from '../components/FilePreview/FilePreview'
import MoveItemPopup from '../components/Others/MoveItemPopup'
import Spotlight from '../components/Spotlight/Spotlight'
import DragUI from '../components/FilesView/DragUI'
import { mapGetters } from 'vuex'
import { events } from '../bus'

export default {
    name: 'UploadRequest',
    components: {
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
        ...mapGetters(['isVisibleSidebar', 'config', 'uploadRequest', 'fileQueue']),
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
