<template>
    <transition name="sidebar">
        <div id="sidebar" v-if="app && (isVisibleSidebar || ! isSmallAppSize)">

            <!--User Headline-->
            <UserHeadline/>

            <!--Content-->
            <div class="content-scroller">

                <!--Locations-->
                <div class="menu-list-wrapper">
                    <TextLabel>{{ $t('sidebar.locations') }}</TextLabel>
                    <ul class="menu-list">
                        <li class="menu-list-item" :class="{'is-active': isBaseLocation}" @click="goHome">
                            <FontAwesomeIcon class="icon" icon="hdd"/>
                            <span class="label">{{ $t('locations.home') }}</span>
                        </li>
                        <li class="menu-list-item" :class="{'is-active': isSharedLocation}" @click="getShared">
                            <FontAwesomeIcon class="icon" icon="share"/>
                            <span class="label">Shared</span>
                        </li>
                        <li class="menu-list-item" :class="{'is-active': isTrashLocation}" @click="getTrash">
                            <FontAwesomeIcon class="icon" icon="trash-alt"/>
                            <span class="label">{{ $t('locations.trash') }}</span>
                        </li>
                    </ul>
                </div>

                <!--Favourites-->
                <div class="menu-list-wrapper favourites"
                     @dragover.prevent="dragEnter"
                     @dragleave="dragLeave"
                     @drop="dragFinish($event)"
                     :class="{ 'is-dragenter': area }"
                >
                    <TextLabel>{{ $t('sidebar.favourites') }}</TextLabel>
                    <transition-group tag="ul" class="menu-list" name="folder-item">
                        <li class="empty-list" v-if="app.favourites.length == 0" :key="0">
                            {{ $t('sidebar.favourites_empty') }}
                        </li>

                        <li @click.stop="openFolder(folder)" class="menu-list-item" v-for="folder in app.favourites"
                            :key="folder.unique_id">
                            <div>
                                <FontAwesomeIcon class="icon" icon="folder"/>
                                <span class="label">{{ folder.name }}</span>
                            </div>
                            <FontAwesomeIcon @click.stop="removeFavourite(folder)" v-if="! $isMobile()" class="delete-icon" icon="times"/>
                        </li>
                    </transition-group>
                </div>

                <!--Last Uploads-->
                <div class="menu-list-wrapper">
                    <TextLabel>{{ $t('sidebar.latest') }}</TextLabel>

                    <p class="empty-list" v-if="app.latest_uploads.length == 0">{{ $t('sidebar.latest_empty') }}</p>

                    <FileListItemThumbnail @dblclick.native="downloadFile(item)" @click.native="showFileDetail(item)" :file="item" v-for="item in app.latest_uploads" :key="item.unique_id"/>
                </div>
            </div>

            <!--Storage Size Info-->
            <StorageSize v-if="config.storageLimit"/>

            <!--Mobile logout button-->
            <div v-if="isSmallAppSize" class="log-out-button">
                <ButtonBase @click.native="$store.dispatch('logOut')" button-style="danger">{{ $t('context_menu.log_out') }}</ButtonBase>
            </div>
        </div>
    </transition>
</template>

<script>
    import FileListItemThumbnail from '@/components/VueFileManagerComponents/Sidebar/FileListItemThumbnail'
    import UserHeadline from '@/components/VueFileManagerComponents/Sidebar/UserHeadline'
    import ButtonBase from '@/components/VueFileManagerComponents/FilesView/ButtonBase'
    import StorageSize from '@/components/VueFileManagerComponents/Sidebar/StorageSize'
    import TextLabel from '@/components/VueFileManagerComponents/Others/TextLabel'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'Sidebar',
        components: {
            FileListItemThumbnail,
            UserHeadline,
            StorageSize,
            ButtonBase,
            TextLabel,
        },
        computed: {
            ...mapGetters(['homeDirectory', 'app', 'appSize', 'config', 'currentFolder']),
            isTrashLocation() {
                return this.currentFolder && this.currentFolder.location === 'trash-root' || this.currentFolder && this.currentFolder.location === 'trash'
            },
            isBaseLocation() {
                return this.currentFolder && this.currentFolder.location === 'base'
            },
            isSharedLocation() {
                return this.currentFolder && this.currentFolder.location === 'shared'
            },
            isSmallAppSize() {
                return this.appSize === 'small'
            }
        },
        data() {
            return {
                area: false,
                draggedItem: undefined,
                isVisibleSidebar: false,
            }
        },
        methods: {
            getShared() {
                this.$store.dispatch('getShared')
            },
            getTrash() {
                this.$store.dispatch('getTrash')
            },
            goHome() {
                this.$store.commit('FLUSH_BROWSER_HISTORY')
                this.$store.dispatch('getFolder', [this.homeDirectory, true])
            },
            openFolder(folder) {

                // Go to folder
                this.$store.dispatch('getFolder', [folder, false])
            },
            downloadFile(file) {

                this.$downloadFile(file.file_url, file.name + '.' + file.mimetype)
            },
            showFileDetail(file) {
                // Dispatch load file info detail
                this.$store.dispatch('getFileDetail', file)

                // Show panel if is not open
                this.$store.dispatch('fileInfoToggle', true)
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

                // Remove favourites folder
                this.$store.dispatch('removeFromFavourites', folder)
            }
        },
        mounted() {
            this.$store.dispatch('getAppData')

            // Listen for dragstart folder items
            events.$on('dragstart', (item) => this.draggedItem = item)

            // Listen for show sidebar
            events.$on('show:sidebar', () => {
                this.isVisibleSidebar = !this.isVisibleSidebar
            })

            // Listen for hide sidebar
            events.$on('show:content', () => {
                if (this.isVisibleSidebar) this.isVisibleSidebar = false
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    #sidebar {
        position: relative;
        flex: 0 0 265px;
        background: $light_background;
    }

    .content-scroller {
        bottom: 50px;
        position: absolute;
        top: 75px;
        left: 0;
        right: 0;
        overflow-x: auto;

        .menu-list-wrapper:first-of-type {
            margin-top: 20px;
        }

        .text-label {
            margin-left: 15px;
        }
    }

    .menu-list-wrapper {
        margin-bottom: 20px;

        .menu-list {

            .menu-list-item {
                display: block;
                padding: 8px 15px 8px 25px;
                @include transition(150ms);
                cursor: pointer;
                position: relative;
                white-space: nowrap;

                .icon {
                    @include font-size(13);
                    width: 15px;
                    margin-right: 9px;
                    vertical-align: middle;

                    path {
                        fill: $text;
                    }
                }

                .delete-icon {
                    display: none;
                    position: absolute;
                    right: 15px;
                    top: 50%;
                    @include transform(translateY(-50%));
                    @include font-size(12);

                    path {
                        fill: $text-muted;
                    }
                }

                .label {
                    @include font-size(14);
                    font-weight: 700;
                    vertical-align: middle;
                    white-space: nowrap;
                    max-width: 210px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    display: inline-block;
                }

                &:hover,
                &.is-active {
                    background: rgba($theme, .1);

                    .icon {
                        path {
                            fill: $theme;
                        }
                    }

                    .label {
                        color: $theme;
                    }

                    .delete-icon {
                        display: block;
                    }
                }
            }
        }

        &.favourites {

            &.is-dragenter {

                .menu-list {
                    border: 2px dashed $theme;
                    border-radius: 8px;
                }
            }

            .menu-list {
                border: 2px dashed transparent;

                .menu-list-item {
                    padding: 8px 23px;

                    .icon {
                        margin-right: 5px;
                        @include font-size(14);
                        width: 20px;

                        path {
                            fill: $theme;
                        }
                    }

                    &:hover {
                        background: rgba($theme, .1);

                        .icon {
                            path {
                                fill: $theme;
                            }
                        }
                    }
                }
            }
        }

        .empty-list {
            @include font-size(12);
            color: $text-muted;
            display: block;
            padding: 15px;
        }
    }

    .log-out-button {
        margin-top: 15px;
        padding: 15px;

        button {
            width: 100%;
        }
    }

    @media only screen and (max-width: 1024px) {

        #sidebar {
            flex: 0 0 265px;
        }

        .menu-list-wrapper .menu-list .menu-list-item .label {
            max-width: 190px;
        }
    }

    @media only screen and (max-width: 690px) {

        .content-scroller {
            bottom: initial;
            position: relative;
            top: initial;
            overflow-x: initial;
        }

        #sidebar {
            position: absolute;
            overflow-y: auto;
            top: 0;
            left: 0;
            right: 0;
            z-index: 99;
            bottom: 0;
            background: white;
            opacity: 1;
        }

        .menu-list-wrapper {

            &.favourites {

                .menu-list .menu-list-item {
                    padding: 10px 26px;
                }
            }

            .menu-list .menu-list-item {
                padding: 10px 26px;

                .label {
                    @include font-size(14);
                }
            }
        }

        .log-out-button {
            margin-top: 0;
        }
    }

    @media (prefers-color-scheme: dark) {

        #sidebar {
            background: $dark_mode_foreground;
        }

        .menu-list-wrapper {

            .menu-list .menu-list-item {

                .label {
                    color: $dark_mode_text_primary;
                }

                .icon {

                    path {
                        fill: lighten($dark_mode_foreground, 10%);
                    }
                }

                &:hover {
                    background: rgba($theme, .1);
                }
            }
        }
    }

    @media (prefers-color-scheme: dark) and (max-width: 690px) {
        #sidebar {
            background: $dark_mode_background;
        }
    }

    .sidebar-enter-active {
        transition: all 300ms ease;
    }

    .sidebar-enter /* .list-leave-active below version 2.1.8 */
    {
        opacity: 0;
        transform: translateX(-100%);
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
