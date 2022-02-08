<template>
    <ContentSidebar v-if="(navigationTree && navigationTree.length >= 1) && isVisibleNavigationBars" class="relative lg:!grid">

		<!--Full screen button-->
        <div @click="$store.dispatch('toggleNavigationBars')" class="absolute top-2.5 right-0 inline-block cursor-pointer p-3 opacity-0 transition-all duration-200 hover:opacity-70">
            <chevrons-left-icon size="18" />
        </div>

		<div class="mb-auto">
			<!--Locations-->
			<ContentGroup :title="$t('Base')">
				<b @click="goHome" class="flex items-center py-2.5 cursor-pointer" :class="{'router-link-active': $route.params.id === sharedDetail.data.attributes.item_id}">
					<home-icon size="17" class="vue-feather icon-active mr-2.5" />
					<small class="text-active text-xs font-bold">
						{{ $t('Home') }}
					</small>
				</b>
			</ContentGroup>

			<!--Navigator-->
			<ContentGroup :title="$t('sidebar.navigator_title')" can-collapse="true">
				<TreeMenuNavigator class="folder-tree" :depth="0" :nodes="folder" v-for="folder in navigationTree" :key="folder.id" />
			</ContentGroup>
		</div>

		<ContentGroup class="mt-auto">
			<div @click="$store.dispatch('toggleThemeMode')" :title="$t('dark_mode_toggle')" class="flex items-center cursor-pointer group">
                <div class="button-icon inline-block cursor-pointer rounded-xl pr-3">
                    <sun-icon v-if="isDarkMode" size="14" class="vue-feather group-hover-text-theme" />
                    <moon-icon v-if="!isDarkMode" size="14" class="vue-feather group-hover-text-theme" />
                </div>
				<b class="text-xs group-hover-text-theme">
					Set {{ isDarkMode ? 'Light' : 'Dark' }} Mode
				</b>
            </div>
		</ContentGroup>

    </ContentSidebar>
</template>

<script>
import { SunIcon, MoonIcon, ChevronsLeftIcon, FolderIcon, HomeIcon, LinkIcon, Trash2Icon, UploadCloudIcon, UserCheckIcon, UsersIcon, XIcon } from 'vue-feather-icons'
import TreeMenuNavigator from '../../../components/Others/TreeMenuNavigator'
import ContentSidebar from '../../../components/Sidebar/ContentSidebar'
import ContentGroup from '../../../components/Sidebar/ContentGroup'
import { events } from '../../../bus'
import { mapGetters } from 'vuex'

export default {
    name: 'NavigationSharePanel',
    components: {
        TreeMenuNavigator,
        ContentSidebar,
        ContentGroup,
		SunIcon,
		MoonIcon,
        UploadCloudIcon,
		ChevronsLeftIcon,
        UserCheckIcon,
        FolderIcon,
        Trash2Icon,
        UsersIcon,
        HomeIcon,
        LinkIcon,
        XIcon,
    },
    computed: {
        ...mapGetters(['sharedDetail', 'navigation', 'clipboard', 'config', 'user', 'isVisibleNavigationBars', 'isDarkMode']),
        favourites() {
            return this.user.data.relationships.favourites.data.attributes.folders
        },
        storage() {
            return this.$store.getters.user.data.attributes.storage
        },
        tree() {
            return this.user.data.attributes.folders
        },
        navigationTree() {
            return this.navigation ? this.navigation[0].folders : undefined
        },
    },
    data() {
        return {
            draggedItem: undefined,
            area: false,
        }
    },
    methods: {
        goHome() {
            this.$router.replace({
                name: 'Public',
                params: {
                    token: this.sharedDetail.data.attributes.token,
                    id: this.sharedDetail.data.attributes.item_id,
                },
            })
        },
        dragLeave() {
            this.area = false
        },
        dragEnter() {
            if (this.draggedItem && this.draggedItem.type !== 'folder') return

            if (this.clipboard.length > 0 && this.clipboard.find((item) => item.type !== 'folder')) return

            this.area = true
        },
        dragFinish() {
            this.area = false

            events.$emit('drop')

            // Check if dragged item is folder
            if (this.draggedItem && this.draggedItem.type !== 'folder') return

            // Check if folder exist in favourites
            if (this.favourites.find((folder) => folder.id === this.draggedItem.id)) return

            // Prevent to move folders to self
            if (this.clipboard.length > 0 && this.clipboard.find((item) => item.type !== 'folder')) return

            //Add to favourites non selected folder
            if (!this.clipboard.includes(this.draggedItem)) {
                this.$store.dispatch('addToFavourites', this.draggedItem)
            }

            //Add to favourites selected folders
            if (this.clipboard.includes(this.draggedItem)) {
                this.$store.dispatch('addToFavourites', null)
            }
        },
    },
    created() {
        // Listen for dragstart folder items
        events.$on('dragstart', (item) => (this.draggedItem = item))
    },
}
</script>
