<template>
    <section id="viewport" v-if="user">

        <ContentSidebar>

            <!--Empty storage warning-->
            <ContentGroup v-if="config.storageLimit && storage.used > 95">
                <UpgradeSidebarBanner/>
            </ContentGroup>

            <!--Locations-->
            <ContentGroup :title="$t('sidebar.locations_title')">
                <div class="menu-list-wrapper vertical">
                    <a class="menu-list-item link" :class="{'is-active': $isThisLocation(['base'])}" @click="goHome">
                        <div class="icon text-theme">
                            <home-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('sidebar.home') }}
                        </div>
                    </a>
                    <a class="menu-list-item link" :class="{'is-active': $isThisLocation(['latest'])}" @click="getLatest">
                        <div class="icon text-theme">
                            <upload-cloud-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('sidebar.latest') }}
                        </div>
                    </a>
					<a class="menu-list-item link" :class="{'is-active': $isThisLocation(['shared'])}" @click="getShared">
                        <div class="icon text-theme">
                            <link-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('sidebar.my_shared') }}
                        </div>
                    </a>
                    <a class="menu-list-item link" :class="{'is-active': $isThisLocation(['trash', 'trash-root'])}" @click="getTrash">
                        <div class="icon text-theme">
                            <trash2-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('locations.trash') }}
                        </div>
                    </a>
                </div>
            </ContentGroup>

            <!--Locations-->
            <ContentGroup :title="$t('Collaboration')" :can-collapse="true">
                <div class="menu-list-wrapper vertical">
                    <a class="menu-list-item link" :class="{'is-active': $isThisLocation(['base'])}" @click="goHome">
                        <div class="icon text-theme">
                            <users-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('Team Folders') }}
                        </div>
                    </a>
                    <a class="menu-list-item link" :class="{'is-active': $isThisLocation(['latest'])}" @click="getLatest">
                        <div class="icon text-theme">
                            <user-check-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('Shared with Me') }}
                        </div>
                    </a>
                </div>
            </ContentGroup>

            <!--Navigator-->
            <ContentGroup :title="$t('sidebar.navigator_title')" slug="navigator" :can-collapse="true" class="navigator">
                <span class="empty-note navigator" v-if="tree.length === 0">
                    {{ $t('sidebar.folders_empty') }}
                </span>
                <TreeMenuNavigator class="folder-tree" :depth="0" :nodes="folder" v-for="folder in tree" :key="folder.id"/>
            </ContentGroup>

            <!--Favourites-->
            <ContentGroup :title="$t('sidebar.favourites')" slug="favourites" :can-collapse="true">

                <div class="menu-list-wrapper vertical favourites" :class="{ 'is-dragenter': area }" @dragover.prevent="dragEnter" @dragleave="dragLeave" @drop="dragFinish($event)">
                    <transition-group tag="div" class="menu-list" name="folder-item">
                        <span class="empty-note favourites" v-if="favourites.length == 0" :key="0">
                            {{ $t('sidebar.favourites_empty') }}
                        </span>

                        <a @click.stop="openFolder(folder)" class="menu-list-item" :class="{'is-current': (folder && currentFolder) && (currentFolder.id === folder.id)}" v-for="folder in favourites" :key="folder.id">
                            <div class="text-theme">
                                <folder-icon size="17" class="folder-icon text-theme"></folder-icon>
                                <span class="label text-theme">{{ folder.name }}</span>
                            </div>
                            <x-icon size="17" @click.stop="removeFavourite(folder)" class="delete-icon"></x-icon>
                        </a>
                    </transition-group>
                </div>
            </ContentGroup>
        </ContentSidebar>

        <ContentFileView/>
    </section>
</template>

<script>
import UpgradeSidebarBanner from '/resources/js/components/Others/UpgradeSidebarBanner'
import TreeMenuNavigator from '/resources/js/components/Others/TreeMenuNavigator'
import ContentFileView from '/resources/js/components/Others/ContentFileView'
import ContentSidebar from '/resources/js/components/Sidebar/ContentSidebar'
import TitlePreview from '/resources/js/components/FilesView/TitlePreview'
import ContentGroup from '/resources/js/components/Sidebar/ContentGroup'
import { mapGetters } from 'vuex'
import { events } from '/resources/js/bus'
import {
    UploadCloudIcon,
	UserCheckIcon,
    FolderIcon,
    Trash2Icon,
	UsersIcon,
    HomeIcon,
	LinkIcon,
    XIcon,
} from 'vue-feather-icons'

export default {
    name: 'FilesView',
    components: {
        UpgradeSidebarBanner,
        TreeMenuNavigator,
        ContentFileView,
        UploadCloudIcon,
        ContentSidebar,
		UserCheckIcon,
        TitlePreview,
        ContentGroup,
        FolderIcon,
        Trash2Icon,
		UsersIcon,
        HomeIcon,
		LinkIcon,
        XIcon,
    },
    computed: {
        ...mapGetters([
			'homeDirectory',
			'currentFolder',
			'clipboard',
			'config',
        	'user',
		]),
        favourites() {
            return this.user.data.relationships.favourites.data.attributes.folders
        },
        tree() {
            return this.user.data.attributes.folders
        },
        storage() {
            return this.$store.getters.user.data.attributes.storage
        }
    },
    data() {
        return {
            area: false,
            draggedItem: undefined
        }
    },
    methods: {
		getShared() {
			this.$store.dispatch('getShared', [{back: false, init: false}])
		},
        getTrash() {
            this.$store.dispatch('getTrash')
        },
        getLatest() {
            this.$store.dispatch('getLatest')
        },
        goHome() {
            this.$store.dispatch('getFolder', [{ folder: this.homeDirectory, back: false, init: true }])
        },
        openFolder(folder) {
            this.$store.dispatch('getFolder', [{ folder: folder, back: false, init: false }])
        },
        dragEnter() {
            if (this.draggedItem && this.draggedItem.type !== 'folder') return

            if (this.clipboard.length > 0 && this.clipboard.find(item => item.type !== 'folder')) return

            this.area = true
        },
        dragLeave() {
            this.area = false
        },
        dragFinish() {
            this.area = false

            events.$emit('drop')

            // Check if dragged item is folder
            if (this.draggedItem && this.draggedItem.type !== 'folder') return

            // Check if folder exist in favourites
            if (this.favourites.find(folder => folder.id == this.draggedItem.id)) return

            // Prevent to move folders to self
            if (this.clipboard.length > 0 && this.clipboard.find(item => item.type !== 'folder')) return

            // Store favourites folder

            //Add to favourites non selected folder
            if (!this.clipboard.includes(this.draggedItem)) {
                this.$store.dispatch('addToFavourites', this.draggedItem)
            }

            //Add to favourites selected folders
            if (this.clipboard.includes(this.draggedItem)) {
                this.$store.dispatch('addToFavourites', null)
            }
        },
        removeFavourite(folder) {
            this.$store.dispatch('removeFromFavourites', folder)
        }
    },
    created() {
        this.goHome()

        // Listen for dragstart folder items
        events.$on('dragstart', (item) => {
            this.draggedItem = item
        })
    }
}
</script>

<style lang="scss" scoped>

.empty-note {

    &.navigator {
        padding: 5px 25px 10px;
    }

    &.favourites {
        padding: 5px 23px 10px;
    }
}

.navigator {
    width: 100%;
    overflow-x: auto;
}

@media only screen and (max-width: 1024px) {

    .empty-note {

        &.navigator {
            padding: 5px 20px 10px;
        }

        &.favourites {
            padding: 5px 18px 10px;
        }
    }
}

// Transition
.folder-item-move {
    transition: transform 300s ease;
}

.folder-item-enter-active {
    transition: all 300ms ease;
}

.folder-item-leave-active {
    transition: all 300ms;
}

.folder-item-enter, .folder-item-leave-to /* .list-leave-active below version 2.1.8 */
{
    opacity: 0;
    transform: translateX(30px);
}

.folder-item-leave-active {
    position: absolute;
}

</style>
