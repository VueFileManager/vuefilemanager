<template>
    <div :style="{ top: positionY + 'px', left: positionX + 'px' }" @click="closeAndResetContextMenu" class="contextmenu" v-show="isVisible || showFromPreview" ref="contextmenu" :class="{ 'filePreviewFixed' : showFromPreview}">

        <!-- File Preview -->
        <div class="menu-options" id="menu-list" v-if="showFromPreview">
            <OptionGroup class="menu-option-group">
                <Option @click.native="renameItem" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="moveItem" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="shareItem" v-if="$checkPermission('master')" :title="item.shared
                            ? $t('context_menu.share_edit')
                            : $t('context_menu.share')" icon="share" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" class="menu-option" />
            </OptionGroup>

            <OptionGroup>
                <Option @click.native="downloadItem" v-if="!isFolder" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!-- Trash location-->
        <div v-if="$isThisLocation(['trash', 'trash-root']) && $checkPermission('master') && !showFromPreview" id="menu-list" class="menu-options">

            <!-- Single options -->
            <OptionGroup v-if="isMultiSelectContextMenu">
                <Option @click.native="restoreItem" v-if="item" :title="$t('context_menu.restore')" icon="restore" />
                <Option @click.native="deleteItem" v-if="item" :title="$t('context_menu.delete')" icon="trash" />
                <Option @click.native="emptyTrash" :title="$t('context_menu.empty_trash')" icon="empty-trash" />
            </OptionGroup>

            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="ItemDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" v-if="!isFolder" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>

            <!-- Multi options -->
            <OptionGroup v-if="!isMultiSelectContextMenu">
                <Option @click.native="restoreItem" v-if="item" :title="$t('context_menu.restore')" icon="restore" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" />
                <Option @click.native="emptyTrash" :title="$t('context_menu.empty_trash')" icon="empty-trash" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu && !hasFolder">
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!-- Shared location with MASTER permission-->
        <div v-if="$isThisLocation(['shared']) && $checkPermission('master') && !showFromPreview" id="menu-list" class="menu-options">

            <!-- Single options -->
            <OptionGroup class="menu-option-group" v-if="item && isFolder && isMultiSelectContextMenu">
                <Option @click.native="addToFavourites" :title="isInFavourites
                        ? $t('context_menu.remove_from_favourites')
                        : $t('context_menu.add_to_favourites')" icon="favourites" />
            </OptionGroup>

            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="renameItem" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="shareItem" :title=" item.shared ? $t('context_menu.share_edit'): $t('context_menu.share')" icon="share" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="ItemDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" v-if="!isFolder" :title="$t('context_menu.download')" icon="download" />
                <Option @click.native="downloadFolder" v-if="isFolder" :title="$t('context_menu.zip_folder')" icon="zip-folder" />
            </OptionGroup>

            <!-- Multi options -->
            <OptionGroup class="menu-option-group" v-if="item && !hasFile && !isMultiSelectContextMenu">
                <Option @click.native="addToFavourites" :title="isInFavourites
                                ? $t('context_menu.remove_from_favourites')
                                : $t('context_menu.add_to_favourites')" icon="favourites" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu">
                <Option @click.native="shareCancel" :title="$t('context_menu.share_cancel')" icon="share" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu && !hasFolder">
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!-- Base location with MASTER permission-->
        <div v-if="$isThisLocation(['base', 'participant_uploads', 'latest']) && $checkPermission('master') && !showFromPreview" id="menu-list" class="menu-options">
            
            <!-- No Files options -->
            <OptionGroup v-if="!$isThisLocation(['participant_uploads', 'latest']) && isMultiSelectContextMenu && !item">
                <Option @click.native="createFolder" :title="$t('context_menu.create_folder')" icon="create-folder" />
            </OptionGroup>

            <!-- Single options -->
            <OptionGroup v-if="!$isThisLocation(['participant_uploads', 'latest']) && item && isMultiSelectContextMenu && isFolder">
                <Option @click.native="addToFavourites" :title="isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites')" icon="favourites" />
            </OptionGroup>


            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="renameItem" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="moveItem" v-if="!$isThisLocation(['latest'])" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="shareItem" :title="item.shared
                                                            ? $t('context_menu.share_edit')
                                                            : $t('context_menu.share')" icon="share" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>


            <OptionGroup v-if="item && isMultiSelectContextMenu ">
                <Option @click.native="ItemDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" v-if="!isFolder" :title="$t('context_menu.download')" icon="download" />
                <Option @click.native="downloadFolder" v-if="isFolder" :title="$t('context_menu.zip_folder')" icon="zip-folder" />
            </OptionGroup>

            <!-- Multi options -->
            <OptionGroup v-if="!$isThisLocation(['participant_uploads', 'latest']) && !isMultiSelectContextMenu && item && !hasFile">
                <Option @click.native="addToFavourites" :title=" isInFavourites
                                                                                    ? $t('context_menu.remove_from_favourites')
                                                                                    : $t('context_menu.add_to_favourites')" icon="favourites" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu">
                <Option @click.native="moveItem" v-if="!$isThisLocation(['latest'])" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu && !hasFolder">
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!-- Base & Public location with EDITOR permission-->
        <div v-if="$isThisLocation(['base', 'public']) && $checkPermission('editor') && !showFromPreview " id="menu-list" class="menu-options">

            <!-- No Files options -->
            <OptionGroup v-if="isMultiSelectContextMenu && !item">
                <Option @click.native="createFolder" :title="$t('context_menu.create_folder')" icon="create-folder" />
            </OptionGroup>

            <!-- Single options -->

            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="renameItem" :title=" $t('context_menu.rename')" icon="rename" />
                <Option @click.native="moveItem" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="ItemDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" v-if="!isFolder" :title="$t('context_menu.download')" icon="download" />
                <Option @click.native="downloadFolder" v-if="isFolder" :title="$t('context_menu.zip_folder')" icon="zip-folder" />
            </OptionGroup>

            <!-- Multi options -->

            <OptionGroup v-if="item && !isMultiSelectContextMenu">
                <Option @click.native="moveItem" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="deleteItem" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu && !hasFolder">
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!-- Base & Public location with VISITOR permission-->
        <div v-if="$isThisLocation(['base', 'public']) && $checkPermission('visitor') && !showFromPreview" id="menu-list" class="menu-options">

            <!-- Single options -->
            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="ItemDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" v-if="!isFolder" :title="$t('context_menu.download')" icon="download" />
                <Option @click.native="downloadFolder" v-if="isFolder" :title="$t('context_menu.zip_folder')" icon="zip-folder" />
            </OptionGroup>

            <!-- Multi options -->
            <OptionGroup v-if="!isMultiSelectContextMenu && item ">
                <Option @click.native="downloadItem" v-if="!hasFolder" :title="$t('context_menu.download')" icon="download" />
                <Option v-if="hasFolder" :title="$t('context_menu.no_options')" icon="no-options" class="no-options" />
            </OptionGroup>

        </div>
    </div>
</template>

<script>
import OptionGroup from '@/components/FilesView/OptionGroup'
import Option from '@/components/FilesView/Option'
import {mapGetters} from 'vuex'
import {events} from '@/bus'

export default {
    name: 'ContextMenu',
    components: {
        OptionGroup,
        Option
    },
    computed: {
        ...mapGetters(['user', 'fileInfoDetail']),
        hasFolder() {
            return this.fileInfoDetail.find(item => item.type === 'folder')
        },
        hasFile() {
            return this.fileInfoDetail.find(item => item.type !== 'folder')
        },
        isMultiSelectContextMenu() {

            // If is context Menu open on multi selected items open just options for the multi selected items
            if (this.fileInfoDetail.length > 1 && this.fileInfoDetail.includes(this.item))
                return false

            // If is context Menu open for the non selected item open options for the single item
            if (this.fileInfoDetail.length < 2 || !this.fileInfoDetail.includes(this.item))
                return true
        },
        favourites() {
            return this.user.data.relationships.favourites.data.attributes.folders
        },
        isFolder() {
            return this.item && this.item.type === 'folder'
        },
        isFile() {
            return (
                this.item &&
                this.item.type !== 'folder' &&
                this.item &&
                this.item.type !== 'image'
            )
        },
        isImage() {
            return this.item && this.item.type === 'image'
        },
        isInFavourites() {
            return this.favourites.find((el) => el.id === this.item.id)
        }
    },
    data() {
        return {
            showFromPreview: false,
            item: undefined,
            isVisible: false,
            positionX: 0,
            positionY: 0
        }
    },
    methods: {
        downloadFolder() {
            this.$store.dispatch('downloadFolder', this.item)
        },
        emptyTrash() {
            this.$store.dispatch('emptyTrash')
        },
        restoreItem() {

            // If is item not in selected items restore just this single item
            if (!this.fileInfoDetail.includes(this.item))
                this.$store.dispatch('restoreItem', this.item)

            // If is item in selected items restore all items from fileInfoDetail
            if (this.fileInfoDetail.includes(this.item))
                this.$store.dispatch('restoreItem', null)
        },
        shareCancel() {
            this.$store.dispatch('shareCancel')
        },
        renameItem() {
            events.$emit('popup:open', {name: 'rename-item', item: this.item})
        },
        moveItem() {
            events.$emit('popup:open', {name: 'move', item: [this.item]})
        },
        shareItem() {
            if (this.item.shared) {
                // Open edit share popup
                events.$emit('popup:open', {name: 'share-edit', item: this.item})
            } else {
                // Open create share popup
                events.$emit('popup:open', {name: 'share-create', item: this.item})
            }
        },
        addToFavourites() {
            // Check if folder is in favourites and then add/remove from favourites
            if (
                this.favourites &&
                !this.favourites.find(el => el.id === this.item.id)
            ) {
                // Add to favourite folder that is not selected
                if (!this.fileInfoDetail.includes(this.item)) {
                    this.$store.dispatch('addToFavourites', this.item)
                }

                // Add to favourites all selected folders
                if (this.fileInfoDetail.includes(this.item)) {
                    this.$store.dispatch('addToFavourites', null)
                }
            } else {
                this.$store.dispatch('removeFromFavourites', this.item)
            }
        },
        downloadItem() {
            if (this.fileInfoDetail.length > 1)
                this.$store.dispatch('downloadFiles')
            else {
                this.$downloadFile(this.item.file_url, this.item.name + '.' + this.item.mimetype)
            }
        },
        ItemDetail() {
            // Dispatch load file info detail
            this.$store.commit('GET_FILEINFO_DETAIL', this.item)

            // Show panel if is not open
            this.$store.dispatch('fileInfoToggle', true)
        },
        deleteItem() {
            // If is context menu open on non selected item delete this single item
            if (!this.fileInfoDetail.includes(this.item)) {
                this.$store.dispatch('deleteItem', this.item)
            }
            // If is context menu open to multi selected items dele this selected items
            if (this.fileInfoDetail.includes(this.item)) {
                this.$store.dispatch('deleteItem')
            }
        },
        createFolder() {
            this.$store.dispatch('createFolder', this.$t('popup_create_folder.folder_default_name'))
        },
        closeAndResetContextMenu() {
            // Close context menu
            this.isVisible = false

            // Reset item container
            this.item = undefined
        },
        showFolderActionsMenu() {
            let container = document.getElementById('folder-actions')

            this.positionX = container.offsetLeft + 16
            this.positionY = container.offsetTop + 30

            // Show context menu
            this.isVisible = true
        },
        showContextMenu(event) {
            let parent = document.getElementById('menu-list')
            let nodesSameClass = parent.getElementsByClassName('menu-option')

            let VerticalOffsetArea = nodesSameClass.length * 50
            let HorizontalOffsetArea = 190

            let container = document.getElementById('files-view')

            var offset = container.getClientRects()[0]

            let x = event.clientX - offset.left
            let y = event.clientY - offset.top

            // Set position Y
            if (container.offsetHeight - y < VerticalOffsetArea) {
                this.positionY = y - VerticalOffsetArea
            } else {
                this.positionY = y
            }

            // Set position X
            if (container.offsetWidth - x < HorizontalOffsetArea) {
                this.positionX = x - HorizontalOffsetArea
            } else {
                this.positionX = x
            }

            // Show context menu
            this.isVisible = true
        },
        showFilePreviewMenu() {
            let container = document.getElementById('fast-preview-menu')
            if (container) {
                this.positionX = container.offsetLeft + 16
                this.positionY = container.offsetTop + 51
            }
        }
    },
    watch: {
        item(newValue, oldValue) {
            if (oldValue != undefined && this.showFromPreview) {
                this.showFromPreview = false
            }
        }
    },

    mounted() {
        events.$on('actualShowingImage:ContextMenu', (item) => {
            this.item = item
        })
    },
    created() {
        events.$on('showContextMenuPreview:show', (item) => {
            if (!this.showFromPreview) {
                this.item = item
                this.showFromPreview = true
                this.showFilePreviewMenu()
            } else if (this.showFromPreview) {
                this.showFromPreview = false
                this.item = undefined
            }
        })

        events.$on('showContextMenuPreview:hide', () => {
            this.isVisible = false
            this.showFromPreview = false
            this.item = undefined
        })

        events.$on('contextMenu:show', (event, item) => {
            // Store item
            this.item = item

            // Show context menu
            setTimeout(() => this.showContextMenu(event, item), 10)
        })

        events.$on('unClick', () => this.closeAndResetContextMenu())

        events.$on('folder:actions', (folder) => {
            // Store item
            this.item = folder

            if (this.isVisible) this.isVisible = false
            else this.showFolderActionsMenu()
        })
    }
}
</script>

<style scoped lang="scss">
@import "@assets/vuefilemanager/_variables";
@import "@assets/vuefilemanager/_mixins";

.no-options {
    /deep/ .text-label {
        color: $text-muted !important;
    }

    /deep/ &:hover {
        background: transparent;
    }

    /deep/ path,
    /deep/ line,
    /deep/ circle {
        stroke: $text-muted !important;
    }
}

.filePreviewFixed {
    position: fixed !important;
    display: flex;
}

.contextmenu {
    min-width: 250px;
    position: absolute;
    z-index: 99;
    box-shadow: $shadow;
    background: white;
    border-radius: 8px;
    overflow: hidden;

    &.showed {
        display: block;
    }
}

.menu-options {
    list-style: none;
    width: 100%;
    margin: 0;
    padding: 0;
}

@media (prefers-color-scheme: dark) {
    .contextmenu {
        background: $dark_mode_foreground;
    }
    .no-options {
        /deep/ .text-label {
            color: $dark_mode_text_secondary !important;
        }

        /deep/ &:hover {
            background: transparent;
        }

        /deep/ path,
        /deep/ line,
        /deep/ circle {
            stroke: $dark_mode_text_secondary !important;
        }
    }
}
</style>
