<template>
    <div @click="fileViewClick" @contextmenu.prevent.capture="contextMenu($event, undefined)" id="files-view" :class="filesViewWidth">
        <ContextMenu />
        <DesktopToolbar />
        <FileBrowser />
    </div>
</template>

<script>
    import DesktopToolbar from '@/components/FilesView/DesktopToolbar'
    import FileBrowser from '@/components/FilesView/FileBrowser'
    import ContextMenu from '@/components/FilesView/ContextMenu'
    import {ResizeSensor} from 'css-element-queries'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'FilesView',
        components: {
            DesktopToolbar,
            FileBrowser,
            ContextMenu,
        },
        computed: {
            ...mapGetters(['config', 'filesViewWidth']),
        },
        methods: {
            fileViewClick() {
                events.$emit('contextMenu:hide')
            },
            contextMenu(event, item) {
                events.$emit('contextMenu:show', event, item)
            },
            handleContentResize() {
                let filesView = document.getElementById('files-view')
                    .clientWidth

                if (filesView >= 0 && filesView <= 690)
                    this.$store.commit('SET_FILES_VIEW_SIZE', 'minimal-scale')

                else if (filesView >= 690 && filesView <= 960)
                    this.$store.commit('SET_FILES_VIEW_SIZE', 'compact-scale')

                else if (filesView >= 960)
                    this.$store.commit('SET_FILES_VIEW_SIZE', 'full-scale')
            },
        },
        mounted() {

            // Set default directory
            if (this.config.directory) {
                // Set start directory
                this.$store.commit('SET_START_DIRECTORY', this.config.directory)

                // Load folder
                this.$store.dispatch('getFolder', [
                    {
                        unique_id: this.config.directory.unique_id,
                        name: this.config.directory.name,
                        location: 'base',
                    },
                    false,
                    true
                ])
            } else {

                let homeDirectory = {
                    unique_id: 0,
                    name: 'Home',
                    location: 'base',
                }

                // Set start directory
                this.$store.commit('SET_START_DIRECTORY', homeDirectory)

                // Load folder
                this.$store.dispatch('getFolder', [homeDirectory, false, true])
            }

            var filesView = document.getElementById('files-view');
            new ResizeSensor(filesView, this.handleContentResize);
        }
    }
</script>

<style lang="scss">
    @import "@assets/app.scss";

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
