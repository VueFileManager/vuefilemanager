<template>
    <section id="viewport" v-if="app">

        <ContentSidebar>

            <!--Locations-->
            <ContentGroup :title="$t('sidebar.locations_title')">
                <div class="menu-list-wrapper vertical">
                    <a class="menu-list-item link" :class="{'is-active': $isThisLocation(['base'])}" @click="goHome">
                        <div class="icon">
                            <home-icon size="17"></home-icon>
                        </div>
                        <div class="label">
                            {{ $t('sidebar.home') }}
                        </div>
                    </a>
                    <a class="menu-list-item link" :class="{'is-active': $isThisLocation(['latest'])}"
                       @click="getLatest">
                        <div class="icon">
                            <upload-cloud-icon size="17"></upload-cloud-icon>
                        </div>
                        <div class="label">
                            {{ $t('sidebar.latest') }}
                        </div>
                    </a>
                </div>
            </ContentGroup>

            <!--Navigator-->
            <ContentGroup :title="$t('sidebar.navigator_title')" class="navigator">
                <span class="empty-note navigator" v-if="app.tree.length == 0">
                    {{ $t('sidebar.folders_empty') }}
                </span>
                <TreeMenuNavigator class="folder-tree" :depth="0" :nodes="items" v-for="items in app.tree"
                                   :key="items.unique_id"/>
            </ContentGroup>

            <!--Favourites-->
            <ContentGroup :title="$t('sidebar.favourites')">

                <div class="menu-list-wrapper vertical favourites"
                     :class="{ 'is-dragenter': area }"
                     @dragover.prevent="dragEnter"
                     @dragleave="dragLeave"
                     @drop="dragFinish($event)"
                >
                    <transition-group tag="div" class="menu-list" name="folder-item">
                        <span class="empty-note favourites" v-if="app.favourites.length == 0" :key="0">
                            {{ $t('sidebar.favourites_empty') }}
                        </span>

                        <a @click.stop="openFolder(folder)"
                           class="menu-list-item"
                           :class="{'is-current': (folder && currentFolder) && (currentFolder.unique_id === folder.unique_id)}"
                           v-for="folder in app.favourites"
                           :key="folder.unique_id">
                            <div>
                                <folder-icon size="17" class="folder-icon"></folder-icon>
                                <span class="label">{{ folder.name }}</span>
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
    import TreeMenuNavigator from '@/components/Others/TreeMenuNavigator'
    import ContentFileView from '@/components/Others/ContentFileView'
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'
    import {
        UploadCloudIcon,
        FolderIcon,
        HomeIcon,
        XIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'FilesView',
        components: {
            TreeMenuNavigator,
            ContentFileView,
            ContentSidebar,
            UploadCloudIcon,
            ContentGroup,
            FolderIcon,
            HomeIcon,
            XIcon,
        },
        computed: {
            ...mapGetters(['app', 'homeDirectory', 'currentFolder']),
        },
        data() {
            return {
                area: false,
                draggedItem: undefined,
            }
        },
        methods: {
            getLatest() {
                this.$store.dispatch('getLatest')
            },
            goHome() {
                this.$store.dispatch('getFolder', [{folder: this.homeDirectory, back: false, init: true}])
            },
            openFolder(folder) {
                this.$store.dispatch('getFolder', [{folder: folder, back: false, init: false}])
            },
            dragEnter() {
                if (this.draggedItem && this.draggedItem.type !== 'folder') return

                this.area = true
            },
            dragLeave() {
                this.area = false
            },
            dragFinish() {
                this.area = false

                // Check if draged item is folder
                if (this.draggedItem && this.draggedItem.type !== 'folder') return

                // Check if folder exist in favourites
                if (this.app.favourites.find(folder => folder.unique_id == this.draggedItem.unique_id)) return

                // Store favourites folder
                this.$store.dispatch('addToFavourites', this.draggedItem)

            },
            removeFavourite(folder) {
                this.$store.dispatch('removeFromFavourites', folder)
            }
        },
        created() {
            this.goHome()

            // Listen for dragstart folder items
            events.$on('dragstart', (item) => this.draggedItem = item)
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
