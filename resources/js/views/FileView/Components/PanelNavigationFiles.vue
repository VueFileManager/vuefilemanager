<template>
    <ContentSidebar v-if="isVisibleNavigationBars" class="relative">
        <!--Full screen button-->
        <div
            @click="$store.dispatch('toggleNavigationBars')"
            class="absolute top-[11px] right-1 inline-block cursor-pointer p-3 opacity-20 transition-all duration-200 hover:opacity-70"
        >
            <chevrons-left-icon size="18" />
        </div>

        <!--Locations-->
        <ContentGroup
            v-for="(menu, i) in nav"
            :key="i"
            :title="menu.groupTitle"
            :slug="menu.groupTitle"
            :can-collapse="menu.groupCollapsable"
        >
            <router-link
                v-for="(item, i) in menu.groupLinks"
                :key="i"
                @click.native="resetData"
                :to="{ name: item.route }"
                class="flex items-center py-2.5"
            >
                <home-icon v-if="item.icon === 'home'" size="17" class="vue-feather icon-active mr-2.5" />
                <upload-cloud-icon
                    v-if="item.icon === 'upload-cloud'"
                    size="17"
                    class="vue-feather icon-active mr-2.5"
                />
                <link-icon v-if="item.icon === 'link'" size="17" class="vue-feather icon-active mr-2.5" />
                <trash2-icon v-if="item.icon === 'trash'" size="17" class="vue-feather icon-active mr-2.5" />
                <users-icon size="17" v-if="item.icon === 'users'" class="vue-feather icon-active mr-2.5" />
                <user-check-icon size="17" v-if="item.icon === 'user-check'" class="vue-feather icon-active mr-2.5" />

                <b class="text-active text-xs font-bold">
                    {{ item.title }}
                </b>
            </router-link>
        </ContentGroup>

        <!--Navigator-->
        <ContentGroup v-if="navigation" :title="$t('navigator')" slug="navigator" :can-collapse="true">
            <small v-if="tree.length === 0" class="text-xs text-gray-500 dark:text-gray-500">
                {{ $t("not_any_folder") }}
            </small>
            <TreeMenuNavigator :depth="0" :nodes="folder" v-for="folder in tree" :key="folder.id" />
        </ContentGroup>

        <!--Favourites-->
        <ContentGroup v-if="user" :title="$t('favourites')" slug="favourites" :can-collapse="true">
            <div
                @dragover.prevent="dragEnter"
                @dragleave="dragLeave"
                @drop="dragFinish($event)"
                :class="{ 'border-theme': area }"
                class="-ml-5 rounded-lg border-2 border-dashed border-transparent pl-5"
            >
                <!--Empty message-->
                <small v-if="favourites.length === 0" class="text-xs text-gray-500 dark:text-gray-500" :key="0">
                    {{ $t('sidebar.favourites_empty') }}
                </small>

                <!--Folder item-->
                <div
                    @click="goToFolder(folder)"
                    v-for="folder in favourites"
                    :key="folder.data.id"
                    class="group flex cursor-pointer items-center justify-between py-2.5"
                >
                    <div class="flex items-center">
                        <folder-icon
                            size="17"
                            class="vue-feather mr-2.5"
                            :class="{
                                'text-theme': $route.params.id === folder.data.id,
                            }"
                        />
                        <span
                            class="max-w-1 overflow-hidden text-ellipsis whitespace-nowrap text-xs font-bold"
                            :class="{
                                'text-theme': $route.params.id === folder.data.id,
                            }"
                        >
                            {{ folder.data.attributes.name }}
                        </span>
                    </div>
                    <div @click.stop="$removeFavourite(folder)" class="-m-2 p-2">
                        <x-icon size="12" class="mr-5 opacity-0 group-hover:opacity-100" />
                    </div>
                </div>
            </div>
        </ContentGroup>
    </ContentSidebar>
</template>

<script>
import {
    ChevronsLeftIcon,
    FolderIcon,
    HomeIcon,
    LinkIcon,
    Trash2Icon,
    UploadCloudIcon,
    UserCheckIcon,
    UsersIcon,
    XIcon,
} from 'vue-feather-icons'
import TreeMenuNavigator from '../../../components/Others/TreeMenuNavigator'
import ContentSidebar from '../../../components/Sidebar/ContentSidebar'
import ContentGroup from '../../../components/Sidebar/ContentGroup'
import { events } from '../../../bus'
import { mapGetters } from 'vuex'

export default {
    name: 'PanelNavigationFiles',
    components: {
        TreeMenuNavigator,
        ContentSidebar,
        ContentGroup,
        ChevronsLeftIcon,
        UploadCloudIcon,
        UserCheckIcon,
        FolderIcon,
        Trash2Icon,
        UsersIcon,
        HomeIcon,
        LinkIcon,
        XIcon,
    },
    computed: {
        ...mapGetters(['isVisibleNavigationBars', 'navigation', 'clipboard', 'config', 'user']),
        favourites() {
            return this.user.data.relationships.favourites.data
        },
        storage() {
            return this.$store.getters.user.data.attributes.storage
        },
        tree() {
            return {
                RecentUploads: this.navigation[0].folders,
                MySharedItems: this.navigation[0].folders,
                Trash: this.navigation[0].folders,
                Public: this.navigation[0].folders,
                Files: this.navigation[0].folders,
                TeamFolders: this.navigation[1].folders,
                SharedWithMe: this.navigation[2].folders,
            }[this.$route.name]
        },
    },
    data() {
        return {
            draggedItem: undefined,
            area: false,
            nav: [
                {
                    groupCollapsable: false,
                    groupTitle: this.$t('sidebar.locations_title'),
                    groupLinks: [
                        {
                            icon: 'home',
                            route: 'Files',
                            title: this.$t('sidebar.home'),
                        },
                        {
                            icon: 'upload-cloud',
                            route: 'RecentUploads',
                            title: this.$t('sidebar.latest'),
                        },
                        {
                            icon: 'link',
                            route: 'MySharedItems',
                            title: this.$t('publicly_shared'),
                        },
                        {
                            icon: 'trash',
                            route: 'Trash',
                            title: this.$t('locations.trash'),
                        },
                    ],
                },
                {
                    groupCollapsable: true,
                    groupTitle: this.$t('collaboration'),
                    groupLinks: [
                        {
                            icon: 'users',
                            route: 'TeamFolders',
                            title: this.$t('team_folders'),
                        },
                        {
                            icon: 'user-check',
                            route: 'SharedWithMe',
                            title: this.$t('shared_with_me'),
                        },
                    ],
                },
            ],
        }
    },
    methods: {
        resetData() {
            this.$store.commit('SET_CURRENT_TEAM_FOLDER', null)
            this.$store.commit('CLIPBOARD_CLEAR')
        },
        goToFolder(folder) {
            this.$router.push({ name: 'Files', params: { id: folder.data.id } })
        },
        dragLeave() {
            this.area = false
        },
        dragEnter() {
            if (this.draggedItem && this.draggedItem.data.type !== 'folder') return

            if (this.clipboard.length > 0 && this.clipboard.find((item) => item.data.type !== 'folder')) return

            this.area = true
        },
        dragFinish() {
            this.area = false

            events.$emit('drop')

            // Check if dragged item is folder
            if (this.draggedItem && this.draggedItem.data.type !== 'folder') return

            // Check if folder exist in favourites
            if (this.favourites.find((folder) => folder.data.id === this.draggedItem.data.id)) return

            // Prevent to move folders to self
            if (this.clipboard.length > 0 && this.clipboard.find((item) => item.data.type !== 'folder')) return

            // Add to favourites non selected folder
            if (!this.clipboard.includes(this.draggedItem)) {
                this.$store.dispatch('addToFavourites', this.draggedItem)
            }

            // Add to favourites selected folders
            if (this.clipboard.includes(this.draggedItem)) {
                this.$store.dispatch('addToFavourites', null)
            }
        },
    },
    created() {
        // Listen for dragstart folder items
        events.$on('dragstart', (item) => (this.draggedItem = item))

        this.$store.dispatch('getFolderTree')
    },
}
</script>
