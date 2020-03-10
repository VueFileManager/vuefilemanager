<template>
    <div id="desktop-toolbar">
        <div class="toolbar-wrapper">
            <!-- Go back-->
            <div class="toolbar-go-back" v-if="homeDirectory">
                <div @click="goBack" class="go-back-button">
                    <FontAwesomeIcon
                            v-if="browseHistory.length > 0"
                            class="icon-back"
                            icon="chevron-left"
                    ></FontAwesomeIcon>
                    <span class="back-directory-title">{{
                            directoryName
                        }}</span>
                </div>
            </div>

            <!-- Tools-->
            <div class="toolbar-tools">
                <div class="toolbar-button-wrapper">
                    <SearchBar/>
                </div>
                <div class="toolbar-button-wrapper">
                    <ToolbarButtonUpload source="upload" action="Upload file"/>
                    <ToolbarButton
                            source="trash-alt"
                            action="Delete"
                            @click.native="deleteItems"
                    />
                    <ToolbarButton
                            @click.native="createFolder"
                            source="folder-plus"
                            action="Create folder"
                    />
                </div>
                <div class="toolbar-button-wrapper">
                    <ToolbarButton
                            :source="preview"
                            action=""
                            @click.native="$store.dispatch('changePreviewType')"
                    />
                    <ToolbarButton
                            :class="{ active: fileInfoVisible }"
                            @click.native="$store.dispatch('fileInfoToggle')"
                            source="info"
                    />
                </div>
            </div>
        </div>
        <UploadProgress />
    </div>
</template>

<script>
    import ToolbarButtonUpload from '@/components/VueFileManagerComponents/FilesView/ToolbarButtonUpload'
    import UploadProgress from '@/components/VueFileManagerComponents/FilesView/UploadProgress'
    import ToolbarButton from '@/components/VueFileManagerComponents/FilesView/ToolbarButton'
    import SearchBar from '@/components/VueFileManagerComponents/FilesView/SearchBar'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'ToolBar',
        components: {
            ToolbarButtonUpload,
            UploadProgress,
            ToolbarButton,
            SearchBar
        },
        computed: {
            ...mapGetters([
                'fileInfoVisible',
                'fileInfoDetail',
                'currentFolder',
                'browseHistory',
                'homeDirectory',
                'preview_type',
            ]),
            directoryName() {
                return this.currentFolder ? this.currentFolder.name : this.homeDirectory.name
            },
            previousFolder() {
                const length = this.browseHistory.length - 2

                return this.browseHistory[length] ? this.browseHistory[length] : this.homeDirectory
            },
            preview() {
                return this.preview_type === 'list' ? 'th' : 'th-list'
            }
        },
        data() {
            return {
                isSidebarMenu: false,
            }
        },
        methods: {
            showSidebarMenu() {
                this.isSidebarMenu = ! this.isSidebarMenu
                events.$emit('show:sidebar')
            },
            goBack() {

                if (this.previousFolder.location === 'trash-root') {
                    this.$store.dispatch('getTrash')
                    this.$store.commit('FLUSH_BROWSER_HISTORY')

                } else {
                    this.$store.dispatch('goToFolder', [this.previousFolder, true])
                }
            },
            deleteItems() {
                events.$emit('items:delete')
            },
            createFolder() {
                this.$createFolder()
            }
        },
        created() {
            // Listen for hide sidebar
            events.$on('show:content', () => {
                if (this.isSidebarMenu) this.isSidebarMenu = false
            })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";


    .toolbar-wrapper {
        padding-top: 15px;
        padding-bottom: 15px;
        display: flex;
        position: relative;
        z-index: 2;

        > div {
            width: 100%;
            flex-grow: 1;
            align-self: center;
            white-space: nowrap;
        }
    }

    .directory-name {
        vertical-align: middle;
        @include font-size(17);
        color: $text;
        font-weight: 600;
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
    }

    .icon-back {
        vertical-align: middle;
        cursor: pointer;
        margin-right: 12px;
    }

    .toolbar-go-back {
        cursor: pointer;

        .back-directory-title {
            line-height: 1;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            vertical-align: middle;
        }
    }

    .toolbar-position {
        text-align: center;

        span {
            @include font-size(17);
            font-weight: 600;
        }
    }

    .toolbar-tools {
        text-align: right;

        .toolbar-button-wrapper {
            margin-left: 75px;
            display: inline-block;
            vertical-align: middle;

            &:first-child {
                margin-left: 0 !important;
            }
        }

        button {
            margin-left: 20px;

            &:first-child {
                margin-left: 0;
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .toolbar .directory-name {
            color: $dark_mode_text_primary;
        }
    }
</style>
