<template>
    <div id="application-wrapper">

        <!--Full File Preview-->
        <FileFullPreview />

        <!--Mobile Navigation-->
        <MobileNavigation />

        <!--Processing popup-->
        <ProcessingPopup />

        <!--Confirm Popup-->
        <Confirm />

        <!--Share Item popup-->
        <ShareCreate />
        <ShareEdit />

        <!--Rename folder/file item-->
        <RenameItem />

        <!--Create folder mobile UI-->
        <CreateFolder />

        <!--Move item popup-->
        <MoveItem />

        <!-- Mobile Menu for Multi selected items -->
        <MobileMultiSelectMenu />

        <!--Drag UI-->
        <DragUI />

        <!--Mobile menu for selecting view and sorting-->
        <MobileSortingAndPreview />

        <!--Mobile Menu-->
        <MobileMenu />

        <!--Navigation Sidebar-->
        <MenuBar />

        <!--File page-->
        <keep-alive :include="['Admin', 'Users']">
            <router-view :class="{'is-scaled-down': isScaledDown}" />
        </keep-alive>
    </div>
</template>

<script>
import MobileSortingAndPreview from '@/components/FilesView/MobileSortingAndPreview'
import MobileMultiSelectMenu from '@/components/FilesView/MobileMultiSelectMenu'
import ProcessingPopup from '@/components/FilesView/ProcessingPopup'
import FileFullPreview from '@/components/FilesView/FileFullPreview'
import MobileNavigation from '@/components/Others/MobileNavigation'
import CreateFolder from '@/components/Others/CreateFolder'
import MobileMenu from '@/components/FilesView/MobileMenu'
import ShareCreate from '@/components/Others/ShareCreate'
import Confirm from '@/components/Others/Popup/Confirm'
import RenameItem from '@/components/Others/RenameItem'
import ShareEdit from '@/components/Others/ShareEdit'
import MoveItem from '@/components/Others/MoveItem'
import DragUI from '@/components/FilesView/DragUI'
import MenuBar from '@/components/Sidebar/MenuBar'
import {includes} from 'lodash'
import {mapGetters} from 'vuex'
import {events} from '@/bus'

export default {
    name: 'Platform',
    components: {
        MobileSortingAndPreview,
        MobileMultiSelectMenu,
        MobileNavigation,
        FileFullPreview,
        ProcessingPopup,
        CreateFolder,
        ShareCreate,
        MobileMenu,
        RenameItem,
        ShareEdit,
        MoveItem,
        Confirm,
        MenuBar,
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
        events.$on('show:mobile-navigation', () => this.isScaledDown = true)
        events.$on('hide:mobile-navigation', () => this.isScaledDown = false)
        events.$on('mobileMenu:show', () => this.isScaledDown = true)
        events.$on('fileItem:deselect', () => this.isScaledDown = false)
        events.$on('mobileSortingAndPreview', state => this.isScaledDown = state)
    }
}
</script>

<style lang="scss">
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';

@media only screen and (max-width: 690px) {

    .is-scaled-down {
        @include transform(scale(0.95));
    }
}
</style>
