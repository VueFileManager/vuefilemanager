<template>
    <div id="shared-content">
        <div id="single-file">
            <div v-if="false" class="single-file-wrapper">
                <FileItemGrid :data="item"/>

                <ButtonBase @click.native="download" class="download-button" button-style="theme">
                    Download File
                </ButtonBase>
            </div>
        </div>
        <div
                @contextmenu.prevent.capture="contextMenu($event, undefined)"
                :class="filesViewWidth"
                @click="fileViewClick"
                id="files-view"
                v-if="true"
        >
            <!--Move item window-->
            <MoveItem/>

            <!--Mobile Menu-->
            <MobileMenu/>

            <!--Background vignette-->
            <Vignette/>

            <!--Context menu-->
            <ContextMenu/>

            <!--Desktop Toolbar-->
            <DesktopToolbar/>

            <!--File browser-->
            <FileBrowser/>
        </div>
    </div>
</template>

<script>
    import DesktopToolbar from '@/components/VueFileManagerComponents/FilesView/DesktopToolbar'
    import FileItemGrid from '@/components/VueFileManagerComponents/FilesView/FileItemGrid'
    import FileBrowser from '@/components/VueFileManagerComponents/FilesView/FileBrowser'
    import ContextMenu from '@/components/VueFileManagerComponents/FilesView/ContextMenu'
    import ButtonBase from '@/components/VueFileManagerComponents/FilesView/ButtonBase'
    import MobileMenu from '@/components/VueFileManagerComponents/FilesView/MobileMenu'
    import Vignette from '@/components/VueFileManagerComponents/Others/Vignette'
    import MoveItem from '@/components/VueFileManagerComponents/Others/MoveItem'
    import {ResizeSensor} from 'css-element-queries'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'SharedContent',
        components: {
            DesktopToolbar,
            FileItemGrid,
            FileBrowser,
            ContextMenu,
            MobileMenu,
            ButtonBase,
            Vignette,
            MoveItem,
        },
        computed: {
            ...mapGetters(['config', 'filesViewWidth']),
        },
        data() {
            return {
                item: {
                    "id": 339,
                    "user_id": 3,
                    "unique_id": 421,
                    "folder_id": 0,
                    "thumbnail": null,
                    "name": "VueFileManager-0.0.1-mac",
                    "basename": "gF5GiO16GNgmkr7K-VueFileManager-0.0.1-mac.zip",
                    "mimetype": "zip",
                    "filesize": "95.78MB",
                    "type": "file",
                    "deleted_at": null,
                    "created_at": "11. April. 2020 at 17:11",
                    "updated_at": "2020-04-11 17:11:49",
                    "file_url": "https://vuefilemanager.hi5ve.digital/api/file/gF5GiO16GNgmkr7K-VueFileManager-0.0.1-mac.zip",
                    "parent": null
                }
            }
        },
        methods: {
            download() {
                console.log('penis');
                this.$downloadFile(
                    this.item.file_url,
                    this.item.name + '.' + this.item.mimetype
                )
            },
            fileViewClick() {
                events.$emit('contextMenu:hide')
            },
            contextMenu(event, item) {
                events.$emit('contextMenu:show', event, item)
            },
            handleContentResize() {
                let filesView = document.getElementById('files-view').clientWidth

                if (filesView >= 0 && filesView <= 690)
                    this.$store.commit('SET_FILES_VIEW_SIZE', 'minimal-scale')

                else if (filesView >= 690 && filesView <= 960)
                    this.$store.commit('SET_FILES_VIEW_SIZE', 'compact-scale')

                else if (filesView >= 960)
                    this.$store.commit('SET_FILES_VIEW_SIZE', 'full-scale')
            },
        },
        mounted() {

            var filesView = document.getElementById('files-view');
            new ResizeSensor(filesView, this.handleContentResize);

            let homeDirectory = {
                unique_id: 0,
                name: 'Home',
                location: 'base',
            }

            // Set start directory
            this.$store.commit('SET_START_DIRECTORY', homeDirectory)

            // Load folder
            this.$store.dispatch('goToFolder', [homeDirectory, false, true])
        }
    }
</script>

<style lang="scss">
    @import "@assets/app.scss";

    #shared-content {
        width: 100%;
        height: 100%;
    }

    #single-file {
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        top: 0;
        display: grid;

        .single-file-wrapper {
            margin: auto;
            text-align: center;

            .download-button {
                margin-top: 15px;
            }
        }

        /deep/ .file-wrapper {

            .file-item {
                width: 290px;

                &:hover, &.is-clicked {
                    background: transparent;
                }

                .item-shared {
                    display: none;
                }
            }
        }
    }

    #files-view {
        font-family: 'Nunito', sans-serif;
        font-size: 16px;
        //overflow: hidden;
        width: 100%;
        height: 100%;
        position: relative;
        min-width: 320px;
        overflow-x: hidden;

        &.minimal-scale {
            padding: 0;

            .mobile-toolbar {
                padding: 10px 0 5px;
            }

            .popup-wrapper {
                left: 15px;
                right: 15px;
                padding: 25px 15px;
            }

            .toolbar {
                display: block;
                position: sticky;
                top: 0;
            }

            .toolbar-go-back {
                padding-top: 15px;
            }

            .toolbar-tools {
                text-align: left;
                display: flex;

                .toolbar-button-wrapper {
                    width: 100%;

                    &:last-child {
                        text-align: right;
                    }
                }
            }

            .files-container {
                padding-left: 15px;
                padding-right: 15px;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                position: absolute;
                overflow-y: auto;

                .file-list {
                    //height: 100%;

                    &.grid {
                        grid-template-columns: repeat(auto-fill, 120px);

                        .file-wrapper {

                            .file-item {
                                width: 120px;
                            }

                            .icon-item {
                                margin-bottom: 10px;
                                height: 90px;

                                .file-icon {
                                    @include font-size(75);
                                }

                                .file-icon-text {
                                    @include font-size(12);
                                }

                                .folder-icon {
                                    @include font-size(75);
                                    margin-top: 0;
                                    margin-bottom: 0;
                                }

                                .image {
                                    width: 90px;
                                    height: 90px;
                                }
                            }

                            .item-name .name {
                                @include font-size(13);
                                line-height: 1.2;
                                max-height: 30px;
                            }
                        }
                    }
                }
            }

            .file-wrapper {
                .item-name .name {
                    max-width: 220px;
                }
            }

            .search-bar {

                input {
                    min-width: initial;
                    width: 100%;
                }
            }

            .item-shared {
                .label {
                    display: none;
                }
            }
        }

        &.compact-scale {
            padding-left: 15px;
            padding-right: 15px;

            .file-content {
                position: absolute;
                top: 72px;
                left: 15px;
                right: 15px;
                bottom: 0;
                @include transition;

                &.is-offset {
                    margin-top: 50px;
                }
            }

            .toolbar-tools {

                .toolbar-button-wrapper {
                    margin-left: 35px;
                }
            }

            .search-bar input {
                min-width: 190px;
            }

            .toolbar-go-back span {
                max-width: 120px;
            }

            .grid .file-wrapper {

                .icon-item {
                    margin-bottom: 15px;
                }
            }
        }

        &.full-scale {
            padding-left: 15px;
            padding-right: 15px;

            .file-content {
                position: absolute;
                top: 72px;
                left: 15px;
                right: 15px;
                bottom: 0;
                @include transition;

                &.is-offset {
                    margin-top: 50px;
                }
            }
        }
    }

</style>
