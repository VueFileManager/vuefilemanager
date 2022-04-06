<template>
    <div class="lg:flex lg:h-screen lg:overflow-hidden w-full">
        <!--File preview window-->
        <FilePreview />
        <Spotlight />

        <!--Popups-->
        <ProcessingPopup />

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
            class="transition-transform duration-200 lg:grid lg:flex-grow lg:content-start lg:px-3.5"
        >
            <DesktopSharepageToolbar />

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
import MobileMultiSelectToolbar from '../components/FilesView/MobileMultiSelectToolbar'
import NavigationSharePanel from './FileView/Components/NavigationSharePanel'
import FileSortingMobile from '../components/FilesView/FileSortingMobile'
import CreateFolderPopup from '../components/Others/CreateFolderPopup'
import ProcessingPopup from '../components/FilesView/ProcessingPopup'
import DesktopToolbar from '../components/FilesView/DesktopToolbar'
import RenameItemPopup from '../components/Others/RenameItemPopup'
import MobileToolbar from '../components/FilesView/MobileToolbar'
import FilePreview from '../components/FilePreview/FilePreview'
import MoveItemPopup from '../components/Others/MoveItemPopup'
import InfoSidebar from '../components/FilesView/InfoSidebar'
import Spotlight from '../components/Spotlight/Spotlight'
import Vignette from '../components/Others/Vignette'
import DragUI from '../components/FilesView/DragUI'
import Alert from '../components/FilesView/Alert'
import { mapGetters } from 'vuex'
import { events } from '../bus'
import router from '../router'
import DesktopSharepageToolbar from '../components/FilesView/DesktopSharepageToolbar'

export default {
    name: 'Shared',
    components: {
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
        ...mapGetters(['isVisibleSidebar', 'sharedDetail', 'config']),
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

        this.$store
            .dispatch('getShareDetail', this.$router.currentRoute.params.token)
            .then(() => this.$store.dispatch('getFolderTree'))
    },
}
</script>
