<template>
    <div class="sm:flex md:h-screen md:overflow-hidden">

        <!--Loading Spinner-->
        <Spinner v-if="isLoading" />

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

		<NavigationSharePanel v-if="sharedDetail && $router.currentRoute.name === 'Public'"/>

		<div
			@contextmenu.prevent.capture="contextMenu($event, undefined)"
			class="md:grid md:content-start sm:flex-grow sm:px-3.5 transition-transform duration-300"
			:class="{'transform scale-97 origin-center': isScaledDown}"
		>
			<DesktopToolbar />

			<MobileToolbar />

			<!--File list & info sidebar-->
			<div class="flex space-x-6 md:overflow-hidden md:h-full">

				<router-view
					id="file-view"
					:class="{'2xl:w-5/6 md:w-4/6 w-full': isVisibleSidebar, 'w-full': ! isVisibleSidebar}"
					class="relative"
					:key="$route.fullPath"
				/>

				<InfoSidebar
					v-if="isVisibleSidebar"
					class="2xl:w-72 w-2/6 overflow-y-auto overflow-x-hidden h-screen md:block hidden"
				/>
			</div>
		</div>
    </div>
</template>

<script>
	import MobileToolbar from '/resources/js/components/FilesView/MobileToolbar'
	import InfoSidebar from "../components/FilesView/InfoSidebar";
    import MobileMultiSelectToolbar from '/resources/js/components/FilesView/MobileMultiSelectToolbar'
    import FileSortingMobile from '/resources/js/components/FilesView/FileSortingMobile'
    import CreateFolderPopup from '/resources/js/components/Others/CreateFolderPopup'
    import ProcessingPopup from '/resources/js/components/FilesView/ProcessingPopup'
	import NavigationSharePanel from "./FileView/Components/NavigationSharePanel"
    import RenameItemPopup from '/resources/js/components/Others/RenameItemPopup'
    import FilePreview from '/resources/js/components/FilePreview/FilePreview'
    import MoveItemPopup from '/resources/js/components/Others/MoveItemPopup'
	import DesktopToolbar from "../components/FilesView/DesktopToolbar"
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import Vignette from '/resources/js/components/Others/Vignette'
    import DragUI from '/resources/js/components/FilesView/DragUI'
    import Alert from '/resources/js/components/FilesView/Alert'
	import Spotlight from "../components/Spotlight/Spotlight"
    import {events} from '/resources/js/bus'
    import {mapGetters} from 'vuex'

    export default {
        name: 'Shared',
        components: {
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
            Spinner,
            DragUI,
            Alert,
        },
        computed: {
            ...mapGetters([
                'isVisibleSidebar',
                'sharedDetail',
                'config',
            ])
        },
        data() {
            return {
                isLoading: true,
                isScaledDown: false
            }
        },
		methods: {
			spotlightListener(e) {
				if (e.key === 'k' && e.metaKey || e.key === 'k' && e.ctrlKey) {
                    e.preventDefault()
					events.$emit('spotlight:show');
				}
			},
			contextMenu(event, item) {
				events.$emit('context-menu:show', event, item)
			},
		},
        mounted() {
            events.$on('mobile-menu:show', () => this.isScaledDown = true)

            this.$store.dispatch('getShareDetail', this.$route.params.token)
                .then(response => {
                    this.isLoading = false

					let type = response.data.data.attributes.type
					let routeName = this.$router.currentRoute.name
					let isProtected = response.data.data.attributes.protected

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
