<template>
    <div id="application-wrapper">

        <!--Loading Spinner-->
        <Spinner v-if="isLoading" />

        <!--File preview window-->
        <FilePreview />

        <!--Popups-->
        <ProcessingPopup />

        <CreateFolderPopup />
        <RenameItemPopup />

        <MoveItemPopup />

        <!-- Mobile components -->
        <FileSortingMobile />
        <FileMenuMobile />

        <MultiSelectToolbarMobile />

        <!--Others-->
        <Vignette />
        <DragUI />
        <Alert />

        <router-view :class="{'is-scaled-down': isScaledDown}" />
    </div>
</template>

<script>
    import MultiSelectToolbarMobile from '/resources/js/components/FilesView/MultiSelectToolbarMobile'
    import FileSortingMobile from '/resources/js/components/FilesView/FileSortingMobile'
    import CreateFolderPopup from '/resources/js/components/Others/CreateFolderPopup'
    import ProcessingPopup from '/resources/js/components/FilesView/ProcessingPopup'
    import FileMenuMobile from '/resources/js/components/FilesView/FileMenuMobile'
    import RenameItemPopup from '/resources/js/components/Others/RenameItemPopup'
    import MoveItemPopup from '/resources/js/components/Others/MoveItemPopup'
    import FilePreview from '/resources/js/components/FilePreview/FilePreview'
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import Vignette from '/resources/js/components/Others/Vignette'
    import DragUI from '/resources/js/components/FilesView/DragUI'
    import Alert from '/resources/js/components/FilesView/Alert'
    import {events} from '/resources/js/bus'
    import {mapGetters} from 'vuex'

    export default {
        name: 'Platform',
        components: {
            MultiSelectToolbarMobile,
            CreateFolderPopup,
            FileSortingMobile,
            ProcessingPopup,
            RenameItemPopup,
            FileMenuMobile,
            MoveItemPopup,
            FilePreview,
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
                isScaledDown: false
            }
        },
        mounted() {
            events.$on('mobile-menu:show', () => this.isScaledDown = true)
            events.$on('fileItem:deselect', () => this.isScaledDown = false)

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

<style lang="scss">
    @import '/resources/sass/vuefilemanager/_mixins';

    @media only screen and (max-width: 690px) {

        .is-scaled-down {
            @include transform(scale(0.95));
        }
    }
</style>
