<template>
    <div id="desktop-toolbar">
        <div class="toolbar-wrapper">
            <!-- Go back-->
            <div class="toolbar-go-back" v-if="homeDirectory">
                <div @click="goBack" class="go-back-button">
                    <chevron-left-icon size="17" :class="{ 'is-active': browseHistory.length > 1 }" class="icon-back"></chevron-left-icon>

                    <span class="back-directory-title">
            {{ directoryName }}
          </span>

                    <span @click.stop="folderActions" v-if="
              browseHistory.length > 1 && $isThisLocation(['base', 'public'])
            " class="folder-options" id="folder-actions">
            <more-horizontal-icon size="14" class="icon-more"></more-horizontal-icon>
          </span>
                </div>
            </div>

            <!-- Tools-->
            <div class="toolbar-tools">
                <!--Search bar-->
                <div class="toolbar-button-wrapper">
                    <SearchBar/>
                </div>

                <!--Creating controls-->
                <div class="toolbar-button-wrapper" v-if="$checkPermission(['master', 'editor'])">
                    <ToolbarButtonUpload :class="{ 'is-inactive': canUploadInView || !hasCapacity }" :action="$t('actions.upload')"/>
                    <ToolbarButton :class="{ 'is-inactive': canCreateFolderInView }" @click.native="createFolder" source="folder-plus" :action="$t('actions.create_folder')"/>
                </div>

                <!--File Controls-->
                <div class="toolbar-button-wrapper" v-if="$checkPermission(['master', 'editor']) && ! $isMobile()">
                    <ToolbarButton source="move" :class="{ 'is-inactive': canMoveInView }" :action="$t('actions.move')" @click.native="moveItem"/>
                    <ToolbarButton v-if="!$isThisLocation(['public'])" source="share" :class="{ 'is-inactive': canShareInView }" :action="$t('actions.share')" @click.native="shareItem"/>
                    <ToolbarButton source="trash" :class="{ 'is-inactive': canDeleteInView }" :action="$t('actions.delete')" @click.native="deleteItem"/>
                </div>

                <!--View Controls-->
                <div class="toolbar-button-wrapper">
                    <ToolbarButton source="preview-sorting" class="preview-sorting" :action="$t('actions.sorting_view')" :class="{ active: sortingAndPreview }" @click.stop.native="sortingAndPreview = !sortingAndPreview"/>
                    <ToolbarButton :action="$t('actions.info_panel')" :class="{ active: fileInfoVisible }" @click.native="$store.dispatch('fileInfoToggle')" source="info"/>
                </div>
            </div>
        </div>
        <UploadProgress/>
    </div>
</template>

<script>
import ToolbarButtonUpload from '@/components/FilesView/ToolbarButtonUpload'
import { ChevronLeftIcon, MoreHorizontalIcon } from 'vue-feather-icons'
import UploadProgress from '@/components/FilesView/UploadProgress'
import ToolbarButton from '@/components/FilesView/ToolbarButton'
import SearchBar from '@/components/FilesView/SearchBar'
import { mapGetters } from 'vuex'
import { events } from '@/bus'
import { last } from 'lodash'

export default {
    name: 'ToolBar',
    components: {
        ToolbarButtonUpload,
        MoreHorizontalIcon,
        ChevronLeftIcon,
        UploadProgress,
        ToolbarButton,
        SearchBar
    },
    computed: {
        ...mapGetters([
            'FilePreviewType',
            'fileInfoVisible',
            'fileInfoDetail',
            'currentFolder',
            'browseHistory',
            'homeDirectory'
        ]),
        hasCapacity() {
            // Check if set storage limitation
            if (!this.$store.getters.config.storageLimit) return true

            // Check if is loaded user
            if (!this.$store.getters.user) return true

            // Check if user has storage
            return (
                this.$store.getters.user.relationships.storage.data.attributes.used <=
                100
            )
        },
        directoryName() {
            return this.currentFolder
                ? this.currentFolder.name
                : this.homeDirectory.name
        },
        preview() {
            return this.FilePreviewType === 'list' ? 'th' : 'th-list'
        },
        canCreateFolderInView() {
            return !this.$isThisLocation(['base', 'public'])
        },
        canDeleteInView() {
            let locations = [
                'trash',
                'trash-root',
                'base',
                'participant_uploads',
                'latest',
                'shared',
                'public'
            ]
            return !this.$isThisLocation(locations) || this.fileInfoDetail.length === 0
        },
        canUploadInView() {
            return !this.$isThisLocation(['base', 'public'])
        },
        canMoveInView() {
            let locations = [
                'base',
                'participant_uploads',
                'latest',
                'shared',
                'public'
            ]
            return !this.$isThisLocation(locations) || this.fileInfoDetail.length === 0

        },
        canShareInView() {
            let locations = [
                'base',
                'participant_uploads',
                'latest',
                'shared',
                'public'
            ]

            return !this.$isThisLocation(locations) || this.fileInfoDetail.length > 1 || this.fileInfoDetail.length === 0
        }
    },
    data() {
        return {
            sortingAndPreview: false
        }
    },
    watch: {
        sortingAndPreview() {
            if (this.sortingAndPreview) {
                events.$emit('sortingAndPreview', true)
            }

            if (!this.sortingAndPreview) {
                events.$emit('unClick')
            }
        }
    },
    methods: {
        goBack() {
            // Get previous folder
            let previousFolder = last(this.browseHistory)

            if (!previousFolder) return

            if (previousFolder.location === 'trash-root') {
                this.$store.dispatch('getTrash')
            } else if (previousFolder.location === 'shared') {
                this.$store.dispatch('getShared')
            } else {
                if (this.$isThisLocation('public')) {
                    this.$store.dispatch('browseShared', [
                        { folder: previousFolder, back: true, init: false }
                    ])
                } else {
                    this.$store.dispatch('getFolder', [
                        { folder: previousFolder, back: true, init: false }
                    ])
                }
            }
        },
        folderActions() {
            events.$emit('folder:actions', this.currentFolder)
        },
        deleteItem() {
            if (this.fileInfoDetail.length > 0)
                this.$store.dispatch('deleteItem')
        },
        createFolder() {
            this.$store.dispatch('createFolder', this.$t('popup_create_folder.folder_default_name'))
        },
        moveItem() {
            if (this.fileInfoDetail.length > 0)
                events.$emit('popup:open', { name: 'move', item: this.fileInfoDetail })
        },
        shareItem() {
            if (this.fileInfoDetail[0]) {
                //ADD BY M
                if (this.fileInfoDetail[0].shared) {
                    events.$emit('popup:open', {
                        name: 'share-edit',
                        item: this.fileInfoDetail[0]
                    })
                } else {
                    events.$emit('popup:open', {
                        name: 'share-create',
                        item: this.fileInfoDetail[0]
                    })
                }
            }
        }
    },
    mounted() {
        // events.$on('sortingAndPreview', (state) => {
        //     this.sortingAndPreview = state
        // })

        events.$on('unClick', () => {
            this.sortingAndPreview = false
        })
    }
}
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.preview-sorting {
    /deep/ .label {
        color: $text !important;
    }

    /deep/ .preview-sorting {
        path, line, polyline, rect, circle {
            stroke: $text !important;
        }
    }

    &:hover {
        /deep/ .preview-sorting {
            path, line, polyline, rect, circle {
                stroke: $theme !important;
            }
        }
    }
}

.toolbar-wrapper {
    padding-top: 10px;
    padding-bottom: 10px;
    display: flex;
    position: relative;
    z-index: 2;

    > div {
        flex-grow: 1;
        align-self: center;
        white-space: nowrap;
    }
}

.directory-name {
    vertical-align: middle;
    @include font-size(17);
    color: $text;
    font-weight: 700;
    max-width: 220px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
}

.icon-back {
    vertical-align: middle;
    cursor: pointer;
    margin-right: 6px;
    opacity: 0.15;
    pointer-events: none;
    @include transition(150ms);

    &.is-active {
        opacity: 1;
        pointer-events: initial;
    }
}

.toolbar-go-back {
    cursor: pointer;

    .folder-options {
        vertical-align: middle;
        margin-left: 6px;
        padding: 1px 4px;
        line-height: 0;
        border-radius: 3px;
        @include transition(150ms);

        svg circle {
            @include transition(150ms);
        }

        &:hover {
            background: $light_background;

            svg circle {
                stroke: $theme;
            }
        }

        .icon-more {
            vertical-align: middle;
        }
    }

    .back-directory-title {
        @include font-size(15);
        line-height: 1;
        font-weight: 700;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        vertical-align: middle;
        color: $text;
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
        margin-left: 28px;
        display: inline-block;
        vertical-align: middle;

        &:first-child {
            margin-left: 0 !important;
        }
    }

    .button {
        margin-left: 5px;

        &.active {
            /deep/ svg {
                line,
                circle,
                rect {
                    stroke: $theme;
                }
            }

            &.preview-sorting {
                background: $light_background;

                /deep/ .preview-sorting {
                    path, line, polyline, rect, circle {
                        stroke: $theme !important;
                    }
                }
            }
        }

        &.is-inactive {
            opacity: 0.25;
            pointer-events: none;
        }

        &:first-child {
            margin-left: 0;
        }
    }
}

@media only screen and (max-width: 1024px) {
    .toolbar-go-back .back-directory-title {
        max-width: 120px;
    }

    .toolbar-tools {
        .button {
            margin-left: 0;
            height: 40px;
            width: 40px;
        }

        .toolbar-button-wrapper {
            margin-left: 25px;
        }
    }
}

@media only screen and (max-width: 960px) {
    #desktop-toolbar {
        display: none;
    }
}

@media (prefers-color-scheme: dark) {
    .toolbar .directory-name {
        color: $dark_mode_text_primary;
    }

    .toolbar-go-back {
        .back-directory-title {
            color: $dark_mode_text_primary;
        }

        .folder-options {
            &:hover {
                background: $dark_mode_foreground;
            }
        }
    }

    .active {
        &.preview-sorting {
            background: $dark_mode_foreground !important;
        }
    }
    .preview-sorting {
        /deep/ .label {
            color: $text !important;
        }

        /deep/ .preview-sorting {
            path, line, polyline, rect, circle {
                stroke: $dark_mode_text_primary !important;
            }
        }
    }
}
</style>
