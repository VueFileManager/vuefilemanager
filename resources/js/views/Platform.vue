<template>
    <div class="sm:flex md:h-screen md:overflow-hidden">

        <!--On Top of App Components-->
        <FilePreview />
		<Spotlight />

		<!--Popups-->
        <ProcessingPopup />
        <ConfirmPopup />

		<CreateTeamFolderPopup />
		<EditTeamFolderPopup />

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
        <SidebarNavigation />
		<PanelNavigationFiles />

		<div
			@contextmenu.prevent.capture="contextMenu($event, undefined)"
			class="md:grid md:content-start sm:flex-grow sm:px-3.5 transition-transform duration-200"
		>
			<DesktopToolbar />

			<MobileToolbar />

			<!--File list & info sidebar-->
			<div class="flex space-x-6 md:overflow-hidden md:h-screen">

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
    import FileSortingMobile from '/resources/js/components/FilesView/FileSortingMobile'
	import SidebarNavigation from '/resources/js/components/Sidebar/SidebarNavigation'
	import FileFilterMobile from '/resources/js/components/FilesView/FileFilterMobile'
	import CreateFolderPopup from '/resources/js/components/Others/CreateFolderPopup'
	import ProcessingPopup from '/resources/js/components/FilesView/ProcessingPopup'
	import MobileNavigation from '/resources/js/components/Others/MobileNavigation'
	import ShareCreatePopup from '/resources/js/components/Others/ShareCreatePopup'
	import DesktopToolbar from '/resources/js/components/FilesView/DesktopToolbar'
	import CreateTeamFolderPopup from "../components/Teams/CreateTeamFolderPopup"
	import ConfirmPopup from '/resources/js/components/Others/Popup/ConfirmPopup'
	import RenameItemPopup from '/resources/js/components/Others/RenameItemPopup'
	import PanelNavigationFiles from "./FileView/Components/PanelNavigationFiles"
	import MobileToolbar from '/resources/js/components/FilesView/MobileToolbar'
	import ShareEditPopup from '/resources/js/components/Others/ShareEditPopup'
	import FilePreview from '/resources/js/components/FilePreview/FilePreview'
	import MoveItemPopup from '/resources/js/components/Others/MoveItemPopup'
	import EditTeamFolderPopup from "../components/Teams/EditTeamFolderPopup"
	import Spotlight from '/resources/js/components/Spotlight/Spotlight'
	import DragUI from '/resources/js/components/FilesView/DragUI'
	import InfoSidebar from "../components/FilesView/InfoSidebar"
	import {events} from '/resources/js/bus'
	import {mapGetters} from "vuex"

	export default {
		name: 'Platform',
		components: {
			CreateTeamFolderPopup,
			PanelNavigationFiles,
			EditTeamFolderPopup,
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
			...mapGetters([
				'isVisibleSidebar'
			])
		},
		data() {
			return {
				isScaledDown: false
			}
		},
		methods: {
			contextMenu(event, item) {
				events.$emit('context-menu:show', event, item)
			},
		},
		mounted() {
			// TODO: new scaledown effect
			events.$on('mobile-menu:show', () => this.isScaledDown = true)
			events.$on('mobile-menu:hide', () => this.isScaledDown = false)
		}
	}
</script>
