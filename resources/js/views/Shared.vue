<template>
    <div id="application-wrapper">

        <!--Full File Preview-->
        <FileFullPreview />

        <!--Loading Spinner-->
        <Spinner v-if="isLoading" />

        <!--Move item popup-->
        <MoveItem />

        <!-- Processing popup for zip -->
        <ProcessingPopup />

        <!-- Mobile Menu for Multi selected items -->
        <MobileMultiSelectMenu />

        <!--Rename folder/file item-->
        <RenameItem />

        <!--Create folder mobile UI-->
        <CreateFolder />

        <!--Drag UI-->
        <DragUI />

        <!--Mobile Menu-->
        <MobileMenu />

        <!--Mobile menu for selecting view and sorting-->
        <MobileSortingAndPreview />

        <!--System alerts-->
        <Alert />

        <!--Background vignette-->
        <Vignette />

        <!--Pages-->
        <router-view />
    </div>
</template>

<script>
import MobileSortingAndPreview from '@/components/FilesView/MobileSortingAndPreview'
import MobileMultiSelectMenu from '@/components/FilesView/MobileMultiSelectMenu'
import ProcessingPopup from '@/components/FilesView/ProcessingPopup'
import FileFullPreview from '@/components/FilesView/FileFullPreview'
import CreateFolder from '@/components/Others/CreateFolder'
import MobileMenu from '@/components/FilesView/MobileMenu'
import RenameItem from '@/components/Others/RenameItem'
import Spinner from '@/components/FilesView/Spinner'
import MoveItem from '@/components/Others/MoveItem'
import Vignette from '@/components/Others/Vignette'
import DragUI from '@/components/FilesView/DragUI'
import Alert from '@/components/FilesView/Alert'
import {mapGetters} from 'vuex'

export default {
    name: 'Platform',
    components: {
        MobileSortingAndPreview,
        MobileMultiSelectMenu,
        FileFullPreview,
        ProcessingPopup,
        CreateFolder,
        MobileMenu,
        RenameItem,
        MoveItem,
        Vignette,
        Spinner,
        DragUI,
        Alert,
    },
    computed: {
        ...mapGetters([
            'config'
        ]),
    },
    data() {
        return {
            isLoading: true,
        }
    },
    mounted() {
        this.$store.dispatch('getShareDetail', this.$route.params.token)
            .then(response => {
                this.isLoading = false

                // Show public file browser
                if (response.data.data.attributes.type === 'folder' && !response.data.data.attributes.is_protected && this.$router.currentRoute.name !== 'SharedFileBrowser') {
                    this.$router.push({name: 'SharedFileBrowser'})
                }

                // Show public single file
                if (response.data.data.attributes.type !== 'folder' && !response.data.data.attributes.is_protected && this.$router.currentRoute.name !== 'SharedSingleFile') {
                    this.$router.push({name: 'SharedSingleFile'})
                }

                // Show authentication page
                if (response.data.data.attributes.is_protected && this.$router.currentRoute.name !== 'SharedAuthentication') {
                    this.$router.push({name: 'SharedAuthentication'})
                }
            })
    }
}
</script>
