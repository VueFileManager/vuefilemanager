<template>
    <div id="application-wrapper">

        <!--File preview window-->
        <FilePreview />

		<Spotlight />

        <!--Popups-->
        <ProcessingPopup />
        <ConfirmPopup />

		<CreateTeamFolderPopup />

        <ShareCreatePopup />
        <ShareEditPopup />

        <CreateFolderPopup />
        <RenameItemPopup />

        <MoveItemPopup />

        <!--Mobile components-->
        <FileSortingMobile />
        <FileFilterMobile />
        <FileMenuMobile />

		<CreateListMobile />

        <MultiSelectToolbarMobile />

        <!--Navigations-->
        <MobileNavigation />
        <SidebarNavigation />

        <!--Others-->
        <DragUI />

        <router-view :class="{'is-scaled-down': isScaledDown}" />
    </div>
</template>

<script>
	import CreateTeamFolderPopup from "../components/Teams/CreateTeamFolderPopup"
    import Spotlight from '/resources/js/components/Spotlight/Spotlight'
    import MultiSelectToolbarMobile from '/resources/js/components/FilesView/MultiSelectToolbarMobile'
    import FileSortingMobile from '/resources/js/components/FilesView/FileSortingMobile'
    import SidebarNavigation from '/resources/js/components/Sidebar/SidebarNavigation'
    import FileFilterMobile from '/resources/js/components/FilesView/FileFilterMobile'
    import CreateListMobile from '/resources/js/components/FilesView/CreateListMobile'
    import CreateFolderPopup from '/resources/js/components/Others/CreateFolderPopup'
    import ProcessingPopup from '/resources/js/components/FilesView/ProcessingPopup'
    import MobileNavigation from '/resources/js/components/Others/MobileNavigation'
    import ShareCreatePopup from '/resources/js/components/Others/ShareCreatePopup'
    import FileMenuMobile from '/resources/js/components/FilesView/FileMenuMobile'
    import ConfirmPopup from '/resources/js/components/Others/Popup/ConfirmPopup'
    import RenameItemPopup from '/resources/js/components/Others/RenameItemPopup'
    import ShareEditPopup from '/resources/js/components/Others/ShareEditPopup'
    import MoveItemPopup from '/resources/js/components/Others/MoveItemPopup'
    import FilePreview from '/resources/js/components/FilePreview/FilePreview'
    import DragUI from '/resources/js/components/FilesView/DragUI'
    import {mapGetters} from 'vuex'
    import {events} from '/resources/js/bus'

    export default {
        name: 'Platform',
        components: {
            MultiSelectToolbarMobile,
			CreateTeamFolderPopup,
            CreateFolderPopup,
            FileSortingMobile,
            SidebarNavigation,
			CreateListMobile,
            FileFilterMobile,
            MobileNavigation,
            ShareCreatePopup,
            ProcessingPopup,
            RenameItemPopup,
            ShareEditPopup,
            FileMenuMobile,
            MoveItemPopup,
            ConfirmPopup,
            FilePreview,
			Spotlight,
            DragUI,
        },
        computed: {
            ...mapGetters([
                'config'
            ]),
        },
        data() {
            return {
                isScaledDown: false
            }
        },
		methods: {
        	spotlightListener(e) {
				if (e.key === 'k' && e.metaKey) {
					events.$emit('spotlight:show');
				}
			}
		},
        mounted() {
            events.$on('mobile-menu:show', () => this.isScaledDown = true)
            events.$on('fileItem:deselect', () => this.isScaledDown = false)
            events.$on('mobile-menu:hide', () => this.isScaledDown = false)

			window.addEventListener("keydown", this.spotlightListener);
        },
		destroyed() {
			window.removeEventListener("keydown", this.spotlightListener);
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
