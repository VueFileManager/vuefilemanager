<template>
    <div class="sticky top-0 z-20 block flex w-full items-center justify-between bg-white py-5 px-4 text-center dark:bg-dark-background lg:hidden">
        <!-- Go back-->
        <div @click="goBack" class="go-back-button flex items-center text-left">
            <chevron-left-icon
                size="17"
                :class="{
                    '-translate-x-3 opacity-0': !isLoadedFolder,
                    'translate-x-0 opacity-100': isLoadedFolder,
                }"
                class="mr-2 -ml-1 transform cursor-pointer align-middle transition-all duration-200"
            />

            <!--Folder Title-->
            <div
                :class="{ '-translate-x-4': !isLoadedFolder }"
                class="inline-block transform overflow-hidden text-ellipsis whitespace-nowrap align-middle text-sm font-bold transition-all duration-200 lg:text-base"
                style="max-width: 200px"
            >
                {{ $getCurrentLocationName() }}
            </div>

            <span
                @click.stop="showItemActions"
                :class="{
                    '-translate-x-4 opacity-0': !currentFolder,
                    'translate-x-0 opacity-100': currentFolder,
                }"
                class="ml-3 transform rounded-md bg-light-background py-0.5 px-1.5 transition-all duration-200 dark:bg-dark-foreground"
            >
                <more-horizontal-icon size="14" />
            </span>
        </div>

        <div class="relative flex items-center">
            <TeamMembersButton v-if="$isThisRoute($route, ['TeamFolders', 'SharedWithMe'])" size="28" @click.stop.native="$showMobileMenu('team-menu')" class="absolute right-9" />

            <!--More Actions-->
            <div class="relative">
                <div v-if="$checkPermission('master')" @click="showMobileNavigation" class="absolute right-0 -mr-2 -translate-y-2/4 transform p-4">
                    <menu-icon size="17" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TeamMembersPreview from '../Teams/Components/TeamMembersPreview'
import TeamMembersButton from '../Teams/Components/TeamMembersButton'
import ToolbarButton from './ToolbarButton'
import SearchBar from './SearchBar'
import { MenuIcon, ChevronLeftIcon, MoreHorizontalIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'
import { events } from '../../bus'

export default {
    name: 'MobileToolBar',
    components: {
        TeamMembersPreview,
        MoreHorizontalIcon,
        TeamMembersButton,
        ChevronLeftIcon,
        ToolbarButton,
        SearchBar,
        MenuIcon,
    },
    computed: {
        ...mapGetters(['currentTeamFolder', 'isVisibleSidebar', 'currentFolder', 'itemViewType', 'clipboard']),
        isLoadedFolder() {
            return this.$route.params.id
        },
    },
    methods: {
        showItemActions() {
            this.$store.commit('CLIPBOARD_CLEAR')
            this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.currentFolder)

            this.$showMobileMenu('file-menu')
            events.$emit('mobile-context-menu:show', this.currentFolder)
        },
        showMobileNavigation() {
            this.$showMobileMenu('user-navigation')
            this.$store.commit('DISABLE_MULTISELECT_MODE')
        },
        goBack() {
            if (this.isLoadedFolder) this.$router.back()
        },
    },
    created() {
        events.$on('show:content', () => {
            if (this.isSidebarMenu) this.isSidebarMenu = false
        })
    },
}
</script>
