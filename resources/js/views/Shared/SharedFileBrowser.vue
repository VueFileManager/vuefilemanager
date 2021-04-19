<template>
    <div @contextmenu.prevent.capture="contextMenu($event, undefined)" id="viewport">
        <ContentSidebar v-if="navigationTree && navigationTree.length >= 1">

            <!--Locations-->
            <ContentGroup :title="$t('sidebar.locations_title')">
                <div class="menu-list-wrapper vertical">
                    <a class="menu-list-item link" @click="goHome">
                        <div class="icon">
                            <home-icon size="17"/>
                        </div>
                        <div class="label">
                            {{ $t('sidebar.home') }}
                        </div>
                    </a>
                </div>
            </ContentGroup>

            <!--Navigator-->
            <ContentGroup :title="$t('sidebar.navigator_title')" class="navigator">
                <TreeMenuNavigator class="folder-tree" :depth="0" :nodes="folder" v-for="folder in navigationTree" :key="folder.id" />
            </ContentGroup>
        </ContentSidebar>

        <div id="files-view">
            <ContextMenu />

            <DesktopToolbar />

            <FileBrowser />

            <DesktopSortingAndPreview />
        </div>
    </div>
</template>

<script>
    import DesktopSortingAndPreview from '@/components/FilesView/DesktopSortingAndPreview'
    import TreeMenuNavigator from '@/components/Others/TreeMenuNavigator'
    import DesktopToolbar from '@/components/FilesView/DesktopToolbar'
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
    import ContextMenu from '@/components/FilesView/ContextMenu'
    import FileBrowser from '@/components/FilesView/FileBrowser'
    import {HomeIcon} from 'vue-feather-icons'
    import {mapGetters} from "vuex"
    import {events} from '@/bus'

    export default {
        name: 'SharedFileBrowser',
        components: {
            DesktopSortingAndPreview,
            TreeMenuNavigator,
            ContentSidebar,
            DesktopToolbar,
            ContentGroup,
            ContextMenu,
            FileBrowser,
            HomeIcon,
        },
        computed: {
            ...mapGetters([
                'sharedDetail',
                'navigation',
                'config',
            ]),
            navigationTree() {
                return this.navigation ? this.navigation[0].folders : undefined
            },
        },
        data() {
            return {
                homeDirectory: undefined,
            }
        },
        methods: {
            goHome() {
                this.$store.dispatch('browseShared', [{folder: this.homeDirectory, back: false, init: true}])
            },
            contextMenu(event, item) {
                events.$emit('contextMenu:show', event, item)
            },
            initFileBrowser() {
                this.homeDirectory = {
                    id: this.sharedDetail.item_id,
                    name: this.$t('locations.home'),
                    location: 'public',
                }

                // Get folder tree
                this.$store.dispatch('getFolderTree')

                // Load folder
                this.goHome()
            },
        },
        created() {
            if (!this.sharedDetail) {
                this.$store.dispatch('getShareDetail', this.$route.params.token).then(() => {
                    this.initFileBrowser()
                })
            } else {
                this.initFileBrowser()
            }
        }
    }
</script>

<style lang="scss">

     #files-view {
         font-family: 'Nunito', sans-serif;
         font-size: 16px;
         width: 100%;
         height: 100%;
         position: relative;
         min-width: 320px;
         overflow-x: hidden;
         padding-left: 15px;
         padding-right: 15px;
         overflow-y: hidden;
     }
</style>