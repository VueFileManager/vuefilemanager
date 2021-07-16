<template>
    <div id="application-wrapper">

        <!--File preview window-->
        <FilePreview />

        <!--Popups-->
        <ProcessingPopup />
        <ConfirmPopup />

        <ShareCreatePopup />
        <ShareEditPopup />

        <CreateFolderPopup />
        <RenameItemPopup />

		<TwoFactorSetupPopup />

        <MoveItemPopup />

        <!--Mobile components-->
        <FileSortingMobile />
        <FileFilterMobile />
        <FileMenuMobile />

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
    import TwoFactorSetupPopup from '@/components/Others/TwoFactorSetupPopup'
    import MultiSelectToolbarMobile from '@/components/FilesView/MultiSelectToolbarMobile'
    import FileSortingMobile from '@/components/FilesView/FileSortingMobile'
    import SidebarNavigation from '@/components/Sidebar/SidebarNavigation'
    import FileFilterMobile from '@/components/FilesView/FileFilterMobile'
    import CreateFolderPopup from '@/components/Others/CreateFolderPopup'
    import ProcessingPopup from '@/components/FilesView/ProcessingPopup'
    import MobileNavigation from '@/components/Others/MobileNavigation'
    import ShareCreatePopup from '@/components/Others/ShareCreatePopup'
    import FileMenuMobile from '@/components/FilesView/FileMenuMobile'
    import ConfirmPopup from '@/components/Others/Popup/ConfirmPopup'
    import RenameItemPopup from '@/components/Others/RenameItemPopup'
    import ShareEditPopup from '@/components/Others/ShareEditPopup'
    import MoveItemPopup from '@/components/Others/MoveItemPopup'
    import FilePreview from '@/components/FilesView/FilePreview'
    import DragUI from '@/components/FilesView/DragUI'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'Platform',
        components: {
            TwoFactorSetupPopup,
            MultiSelectToolbarMobile,
            CreateFolderPopup,
            FileSortingMobile,
            SidebarNavigation,
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
        mounted() {
            events.$on('mobile-menu:show', () => this.isScaledDown = true)

            events.$on('fileItem:deselect', () => this.isScaledDown = false)
            events.$on('mobile-menu:hide', () => this.isScaledDown = false)
        }
    }
</script>

<style lang="scss">
    @import '@assets/vuefilemanager/_mixins';

    @media only screen and (max-width: 690px) {

        .is-scaled-down {
            @include transform(scale(0.95));
        }
    }
</style>
