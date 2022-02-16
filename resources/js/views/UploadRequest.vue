<template>
    <div class="lg:flex lg:h-screen lg:overflow-hidden">
        <!--On Top of App Components-->
        <FilePreview />
        <Spotlight />

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
            <DesktopUploadRequestToolbar v-if="entries.length" />
            <!--<MobileToolbar />-->

            <!--Google Adsense banner-->
            <div v-if="config.allowedAdsense" v-html="config.adsenseBanner01" class="mb-5 min-h-[120px]"></div>

            <!--File list & info sidebar-->
            <div class="flex space-x-3 lg:overflow-hidden">
                <router-view id="file-view" class="relative w-full" :key="$route.fullPath" />

                <!--<InfoSidebar v-if="isVisibleSidebar" />-->
            </div>
        </div>
    </div>
</template>

<script>
import DesktopUploadRequestToolbar from '../components/FilesView/DesktopUploadRequestToolbar'
import FileSortingMobile from '../components/FilesView/FileSortingMobile'
import FileFilterMobile from '../components/FilesView/FileFilterMobile'
import CreateFolderPopup from '../components/Others/CreateFolderPopup'
import DesktopToolbar from '../components/FilesView/DesktopToolbar'
import RenameItemPopup from '../components/Others/RenameItemPopup'
import MobileToolbar from '../components/FilesView/MobileToolbar'
import FilePreview from '../components/FilePreview/FilePreview'
import MoveItemPopup from '../components/Others/MoveItemPopup'
import InfoSidebar from '../components/FilesView/InfoSidebar'
import Spotlight from '../components/Spotlight/Spotlight'
import DragUI from '../components/FilesView/DragUI'
import { mapGetters } from 'vuex'
import { events } from '../bus'

export default {
    name: 'UploadRequest',
    components: {
        DesktopUploadRequestToolbar,
        CreateFolderPopup,
        FileSortingMobile,
        FileFilterMobile,
        RenameItemPopup,
        DesktopToolbar,
        MoveItemPopup,
        MobileToolbar,
        InfoSidebar,
        FilePreview,
        Spotlight,
        DragUI,
    },
    computed: {
        ...mapGetters(['isVisibleSidebar', 'config', 'entries']),
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
