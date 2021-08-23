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
        <MobileContextMenu />

        <MultiSelectToolbar />

        <!--Others-->
        <Vignette />
        <DragUI />
        <Alert />

		<div @contextmenu.prevent.capture="contextMenu($event, undefined)" id="file-view">
			<DesktopToolbar/>
			<router-view :key="$route.fullPath" />
		</div>
    </div>
</template>

<script>
    import MultiSelectToolbar from '/resources/js/components/FilesView/MultiSelectToolbar'
    import MobileContextMenu from '/resources/js/components/FilesView/MobileContextMenu'
    import FileSortingMobile from '/resources/js/components/FilesView/FileSortingMobile'
    import CreateFolderPopup from '/resources/js/components/Others/CreateFolderPopup'
    import ProcessingPopup from '/resources/js/components/FilesView/ProcessingPopup'
    import RenameItemPopup from '/resources/js/components/Others/RenameItemPopup'
    import FilePreview from '/resources/js/components/FilePreview/FilePreview'
    import MoveItemPopup from '/resources/js/components/Others/MoveItemPopup'
	import DesktopToolbar from "../components/FilesView/DesktopToolbar"
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import Vignette from '/resources/js/components/Others/Vignette'
    import DragUI from '/resources/js/components/FilesView/DragUI'
    import Alert from '/resources/js/components/FilesView/Alert'
    import {events} from '/resources/js/bus'
    import {mapGetters} from 'vuex'

    export default {
        name: 'Platform',
        components: {
            MultiSelectToolbar,
            CreateFolderPopup,
            FileSortingMobile,
            MobileContextMenu,
            ProcessingPopup,
            RenameItemPopup,
			DesktopToolbar,
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
		methods: {
			spotlightListener(e) {
				if (e.key === 'k' && e.metaKey) {
					events.$emit('spotlight:show');
				}
			},
			contextMenu(event, item) {
				events.$emit('context-menu:show', event, item)
			},
		},
        mounted() {
            events.$on('mobile-menu:show', () => this.isScaledDown = true)
            events.$on('fileItem:deselect', () => this.isScaledDown = false)

            this.$store.dispatch('getShareDetail', this.$route.params.token)
                .then(response => {
                    this.isLoading = false

					let type = response.data.data.attributes.type
					let routeName = this.$router.currentRoute.name
					let isProtected = response.data.data.attributes.is_protected

                    // Show public file browser
                    if (type === 'folder' && !isProtected && routeName !== 'Public') {
                        this.$router.replace({name: 'Public', params: {token: this.$route.params.token, id: response.data.data.attributes.item_id}})
                    }

                    // Show public single file
                    if (type !== 'folder' && !isProtected && routeName !== 'SharedSingleFile') {
                        this.$router.push({name: 'SharedSingleFile'})
                    }

                    // Show authentication page
                    if (isProtected && routeName !== 'SharedAuthentication') {
                        this.$router.push({name: 'SharedAuthentication'})
                    }
                })
        }
    }
</script>

<style lang="scss">
    @import '/resources/sass/vuefilemanager/_mixins';

	#file-view {
		font-family: 'Nunito', sans-serif;
		font-size: 16px;
		width: 100%;
		height: 100%;
		position: relative;
		min-width: 320px;
		overflow-x: hidden;
		padding-left: 15px;
		padding-right: 15px;
		overflow-y: hidden;
		@include transition(120ms);
	}

    @media only screen and (max-width: 690px) {

        .is-scaled-down {
            @include transform(scale(0.95));
        }
    }
</style>
