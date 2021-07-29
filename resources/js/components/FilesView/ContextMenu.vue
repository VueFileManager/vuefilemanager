<template>
    <div v-show="isVisible" @click="closeAndResetContextMenu" :style="{top: positionY + 'px', left: positionX + 'px'}" class="contextmenu" ref="contextmenu">

        <!-- Trash location-->
        <div v-if="$isThisLocation(['trash', 'trash-root']) && $checkPermission('master')" id="menu-list" class="menu-options">

            <!-- Single options -->
            <OptionGroup v-if="isMultiSelectContextMenu">
                <Option @click.native="$restoreFileOrFolder(item)" v-if="item" :title="$t('context_menu.restore')" icon="restore" />
                <Option @click.native="$deleteFileOrFolder(item)" v-if="item" :title="$t('context_menu.delete')" icon="trash" />
                <Option @click.native="emptyTrash" :title="$t('context_menu.empty_trash')" icon="empty-trash" />
            </OptionGroup>

            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="ItemDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" v-if="!isFolder" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>

            <!-- Multi options -->
            <OptionGroup v-if="!isMultiSelectContextMenu">
                <Option @click.native="$restoreFileOrFolder(item)" v-if="item" :title="$t('context_menu.restore')" icon="restore" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
                <Option @click.native="emptyTrash" :title="$t('context_menu.empty_trash')" icon="empty-trash" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu && !hasFolder">
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!-- Shared location with MASTER permission-->
        <div v-if="$isThisLocation(['shared']) && $checkPermission('master')" id="menu-list" class="menu-options">

            <!-- Single options -->
            <OptionGroup class="menu-option-group" v-if="item && isFolder && isMultiSelectContextMenu">
                <Option @click.native="addToFavourites" :title="isInFavourites
                        ? $t('context_menu.remove_from_favourites')
                        : $t('context_menu.add_to_favourites')" icon="favourites" />
            </OptionGroup>

            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="$shareFileOrFolder(item)" :title=" item.shared ? $t('context_menu.share_edit'): $t('context_menu.share')" icon="share" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="ItemDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>

            <!-- Multi options -->
            <OptionGroup class="menu-option-group" v-if="item && !hasFile && !isMultiSelectContextMenu">
                <Option @click.native="addToFavourites" :title="isInFavourites
                                ? $t('context_menu.remove_from_favourites')
                                : $t('context_menu.add_to_favourites')" icon="favourites" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu">
                <Option @click.native="shareCancel" :title="$t('context_menu.share_cancel')" icon="share" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu && !hasFolder">
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!-- Base location with MASTER permission-->
        <div v-if="$isThisLocation(['base', 'participant_uploads', 'latest']) && $checkPermission('master')" id="menu-list" class="menu-options">
            
            <!-- No Files options -->
            <OptionGroup v-if="!$isThisLocation(['participant_uploads', 'latest']) && isMultiSelectContextMenu && !item">
                <Option @click.native="createFolder" :title="$t('context_menu.create_folder')" icon="create-folder" />
            </OptionGroup>

            <!-- Single options -->
            <OptionGroup v-if="!$isThisLocation(['participant_uploads', 'latest']) && item && isMultiSelectContextMenu && isFolder">
                <Option @click.native="addToFavourites" :title="isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites')" icon="favourites" />
            </OptionGroup>

            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="$renameFileOrFolder(item)" :title="$t('context_menu.rename')" icon="rename" />
                <Option @click.native="$moveFileOrFolder(item)" v-if="!$isThisLocation(['latest'])" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="$shareFileOrFolder(item)" :title="item.shared ? $t('context_menu.share_edit') : $t('context_menu.share')" icon="share" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && isMultiSelectContextMenu ">
                <Option @click.native="ItemDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>

            <!-- Multi options -->
            <OptionGroup v-if="!$isThisLocation(['participant_uploads', 'latest']) && !isMultiSelectContextMenu && item && !hasFile">
                <Option @click.native="addToFavourites" :title="isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites')" icon="favourites" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu">
                <Option @click.native="$moveFileOrFolder(item)" v-if="!$isThisLocation(['latest'])" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu">
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!-- Base & Public location with EDITOR permission-->
        <div v-if="$isThisLocation(['base', 'public']) && $checkPermission('editor')" id="menu-list" class="menu-options">

            <!-- No Files options -->
            <OptionGroup v-if="isMultiSelectContextMenu && !item">
                <Option @click.native="createFolder" :title="$t('context_menu.create_folder')" icon="create-folder" />
            </OptionGroup>

            <!-- Single options -->
            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="$renameFileOrFolder(item)" :title=" $t('context_menu.rename')" icon="rename" />
                <Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="ItemDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>

            <!-- Multi options -->
            <OptionGroup v-if="item && !isMultiSelectContextMenu">
                <Option @click.native="$moveFileOrFolder(item)" :title="$t('context_menu.move')" icon="move-item" />
                <Option @click.native="$deleteFileOrFolder(item)" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup v-if="item && !isMultiSelectContextMenu && !hasFolder">
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </div>

        <!-- Base & Public location with VISITOR permission-->
        <div v-if="$isThisLocation(['base', 'public']) && $checkPermission('visitor')" id="menu-list" class="menu-options">

            <!-- Single options -->
            <OptionGroup v-if="item && isMultiSelectContextMenu">
                <Option @click.native="ItemDetail" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
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
        ...mapGetters(['user', 'clipboard']),
        hasFolder() {
            return this.clipboard.find(item => item.type === 'folder')
        },
        hasFile() {
            return this.clipboard.find(item => item.type !== 'folder')
        },
        isMultiSelectContextMenu() {

            // If is context Menu open on multi selected items open just options for the multi selected items
            if (this.clipboard.length > 1 && this.clipboard.includes(this.item))
                return false

            // If is context Menu open for the non selected item open options for the single item
            if (this.clipboard.length < 2 || !this.clipboard.includes(this.item))
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
            item: undefined,
            isVisible: false,
            positionX: 0,
            positionY: 0
        }
    },
    methods: {
        emptyTrash() {
            this.$store.dispatch('emptyTrash')
        },
        shareCancel() {
            this.$store.dispatch('shareCancel')
        },
        addToFavourites() {
            // Check if folder is in favourites and then add/remove from favourites
            if (
                this.favourites &&
                !this.favourites.find(el => el.id === this.item.id)
            ) {
                // Add to favourite folder that is not selected
                if (!this.clipboard.includes(this.item)) {
                    this.$store.dispatch('addToFavourites', this.item)
                }

                // Add to favourites all selected folders
                if (this.clipboard.includes(this.item)) {
                    this.$store.dispatch('addToFavourites', null)
                }
            } else {
                this.$store.dispatch('removeFromFavourites', this.item)
            }
        },
        downloadItem() {
            if (this.clipboard.length > 1 || (this.clipboard.length === 1 && this.clipboard[0].type === 'folder'))
                this.$store.dispatch('downloadZip')
            else {
                this.$downloadFile(this.item.file_url, this.item.name + '.' + this.item.mimetype)
            }
        },
        ItemDetail() {
            // Dispatch load file info detail
            this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)

            // Show panel if is not open
            this.$store.dispatch('fileInfoToggle', true)
        },
        createFolder() {
            this.$store.dispatch('createFolder', {
                name: this.$t('popup_create_folder.folder_default_name')
            })
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
        }
    },
    created() {
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

.dark-mode {
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
