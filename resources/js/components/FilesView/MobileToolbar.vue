<template>
    <div class="sticky top-0 dark:bg-dark-background bg-white flex text-center py-5 px-4 w-full justify-between items-center z-10 lg:hidden block">

        <!-- Go back-->
		<div @click="goBack" class="go-back-button flex text-left items-center">
            <chevron-left-icon size="17" :class="{'opacity-0 -translate-x-3': ! isLoadedFolder, 'opacity-100 translate-x-0': isLoadedFolder }" class="transform align-middle cursor-pointer mr-2 -ml-1 transition-all duration-200" />

			<!--Folder Title-->
			<div :class="{'-translate-x-4': ! isLoadedFolder}" class="transform lg:text-base text-sm align-middle font-bold overflow-hidden overflow-ellipsis inline-block whitespace-nowrap transition-all duration-200" style="max-width: 200px;">
				{{ $getCurrentLocationName() }}
			</div>

			<span @click.stop="showItemActions" :class="{'-translate-x-4 opacity-0': ! currentFolder, 'translate-x-0 opacity-100': currentFolder}" class="transform py-0.5 px-1.5 ml-3 rounded-md dark:bg-dark-foreground bg-light-background transition-all duration-200">
				<more-horizontal-icon size="14" />
			</span>
        </div>

		<div class="flex items-center relative">
			<TeamMembersButton
				v-if="$isThisRoute($route, ['TeamFolders', 'SharedWithMe'])"
				size="28"
				@click.stop.native="$showMobileMenu('team-menu')"
			   	class="absolute right-9"
			/>

			<!--More Actions-->
			<div class="relative">
				<div v-if="$checkPermission('master')" @click="showMobileNavigation" class="tap-area absolute right-0 p-4 -mr-2 transform -translate-y-2/4">
					<menu-icon size="17" />
				</div>
			</div>
		</div>
    </div>
</template>

<script>
	import TeamMembersPreview from "../Teams/Components/TeamMembersPreview";
	import TeamMembersButton from "../Teams/Components/TeamMembersButton";
    import ToolbarButton from '/resources/js/components/FilesView/ToolbarButton'
    import SearchBar from '/resources/js/components/FilesView/SearchBar'
    import {MenuIcon, ChevronLeftIcon, MoreHorizontalIcon } from 'vue-feather-icons'
    import {mapGetters} from 'vuex'
    import {events} from '/resources/js/bus'

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
            ...mapGetters([
                'currentTeamFolder',
                'isVisibleSidebar',
                'currentFolder',
                'itemViewType',
                'clipboard',
            ]),
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
        }
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .dark {

		.directory-name {
			color: $dark_mode_text_primary;
		}

		.tap-area {

			path, line, polyline, rect, circle {
				stroke: $dark_mode_text_primary;
			}
		}
    }
</style>
