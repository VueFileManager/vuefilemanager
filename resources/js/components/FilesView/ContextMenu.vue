<template>
    <div
            :style="{ top: positionY + 'px', left: positionX + 'px' }"
            @click="closeAndResetContextMenu"
            class="contextmenu"
            v-show="isVisible"
            ref="contextmenu"
    >
        <!--ContextMenu for trash location-->
        <div v-if="$isThisLocation(['trash', 'trash-root']) && $checkPermission('master')" id="menu-list" class="menu-options">
            <ul class="menu-option-group">
                <li class="menu-option" @click="$store.dispatch('restoreItem', item)" v-if="item">
                    <div class="icon">
                        <life-buoy-icon size="17"></life-buoy-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.restore') }}
                    </div>
                </li>
                <li class="menu-option" @click="deleteItem" v-if="item">
                    <div class="icon">
                        <trash-2-icon size="17"></trash-2-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.delete') }}
                    </div>
                </li>
                <li class="menu-option" @click="$store.dispatch('emptyTrash')">
                    <div class="icon">
                        <trash-icon size="17"></trash-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.empty_trash') }}
                    </div>
                </li>
            </ul>
            <ul class="menu-option-group" v-if="item">
                <li class="menu-option" @click="ItemDetail">
                    <div class="icon">
                        <eye-icon size="17"></eye-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.detail') }}
                    </div>
                </li>
                <li class="menu-option" @click="downloadItem" v-if="! isFolder">
                    <div class="icon">
                        <download-cloud-icon size="17"></download-cloud-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.download') }}
                    </div>
                </li>
            </ul>
        </div>

        <!--ContextMenu for Base location with MASTER permission-->
        <div v-if="$isThisLocation(['shared']) && $checkPermission('master')" id="menu-list" class="menu-options">
            <ul class="menu-option-group" v-if="item && isFolder">
                <li class="menu-option" @click="addToFavourites">
                    <div class="icon">
                        <star-icon size="17"></star-icon>
                    </div>
                    <div class="text-label">
                        {{ isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites') }}
                    </div>
                </li>
            </ul>
            <ul class="menu-option-group" v-if="item">
                <li class="menu-option" @click="shareItem">
                    <div class="icon">
                        <link-icon size="17"></link-icon>
                    </div>
                    <div class="text-label">
                        {{ item.shared ? $t('context_menu.share_edit') : $t('context_menu.share') }}
                    </div>
                </li>
                <li class="menu-option" @click="deleteItem">
                    <div class="icon">
                        <trash-2-icon size="17"></trash-2-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.delete') }}
                    </div>
                </li>
            </ul>
            <ul class="menu-option-group" v-if="item">
                <li class="menu-option" @click="ItemDetail" v-if="item">
                    <div class="icon">
                        <eye-icon size="17"></eye-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.detail') }}
                    </div>
                </li>
                <li class="menu-option" @click="downloadItem" v-if="! isFolder">
                    <div class="icon">
                        <download-cloud-icon size="17"></download-cloud-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.download') }}
                    </div>
                </li>
            </ul>
        </div>

        <!--ContextMenu for Base location with MASTER permission-->
        <div v-if="$isThisLocation(['base', 'participant_uploads', 'latest']) && $checkPermission('master')" id="menu-list" class="menu-options">

            <ul class="menu-option-group" v-if="! $isThisLocation(['participant_uploads', 'latest'])">
                <li class="menu-option" @click="addToFavourites" v-if="item && isFolder">
                    <div class="icon">
                        <star-icon size="17"></star-icon>
                    </div>
                    <div class="text-label">
                        {{ isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites') }}
                    </div>
                </li>
                <li class="menu-option" @click="createFolder">
                    <div class="icon">
                        <folder-plus-icon size="17"></folder-plus-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.create_folder') }}
                    </div>
                </li>
            </ul>
            <ul class="menu-option-group" v-if="item">
                <li class="menu-option" @click="moveItem">
                    <div class="icon">
                        <corner-down-right-icon size="17"></corner-down-right-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.move') }}
                    </div>
                </li>
                <li class="menu-option" @click="shareItem">
                    <div class="icon">
                        <link-icon size="17"></link-icon>
                    </div>
                    <div class="text-label">
                        {{ item.shared ? $t('context_menu.share_edit') : $t('context_menu.share') }}
                    </div>
                </li>
                <li class="menu-option" @click="deleteItem">
                    <div class="icon">
                        <trash-2-icon size="17"></trash-2-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.delete') }}
                    </div>
                </li>
            </ul>
            <ul class="menu-option-group" v-if="item">
                <li class="menu-option" @click="ItemDetail">
                    <div class="icon">
                        <eye-icon size="17"></eye-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.detail') }}
                    </div>
                </li>
                <li class="menu-option" @click="downloadItem" v-if="! isFolder">
                    <div class="icon">
                        <download-cloud-icon size="17"></download-cloud-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.download') }}
                    </div>
                </li>
            </ul>
        </div>

        <!--ContextMenu for Base location with EDITOR permission-->
        <div v-if="$isThisLocation(['base', 'public']) && $checkPermission('editor')" id="menu-list" class="menu-options">
            <ul class="menu-option-group">
                <li class="menu-option" @click="createFolder">
                    <div class="icon">
                        <folder-plus-icon size="17"></folder-plus-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.create_folder') }}
                    </div>
                </li>
            </ul>
            <ul class="menu-option-group" v-if="item">
                <li class="menu-option" @click="moveItem">
                    <div class="icon">
                        <corner-down-right-icon size="17"></corner-down-right-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.move') }}
                    </div>
                </li>
                <li class="menu-option" @click="deleteItem">
                    <div class="icon">
                        <trash-2-icon size="17"></trash-2-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.delete') }}
                    </div>
                </li>
            </ul>
            <ul class="menu-option-group" v-if="item">
                <li class="menu-option" @click="ItemDetail">
                    <div class="icon">
                        <eye-icon size="17"></eye-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.detail') }}
                    </div>
                </li>
                <li class="menu-option" @click="downloadItem" v-if="! isFolder">
                    <div class="icon">
                        <download-cloud-icon size="17"></download-cloud-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.download') }}
                    </div>
                </li>
            </ul>
        </div>

        <!--ContextMenu for Base location with VISITOR permission-->
        <div v-if="$isThisLocation(['base', 'public']) && $checkPermission('visitor')" id="menu-list" class="menu-options">
            <ul class="menu-option-group" v-if="item">
                <li class="menu-option" @click="ItemDetail">
                    <div class="icon">
                        <eye-icon size="17"></eye-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.detail') }}
                    </div>
                </li>
                <li class="menu-option" @click="downloadItem" v-if="! isFolder">
                    <div class="icon">
                        <download-cloud-icon size="17"></download-cloud-icon>
                    </div>
                    <div class="text-label">
                        {{ $t('context_menu.download') }}
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import {
        CornerDownRightIcon,
        DownloadCloudIcon,
        FolderPlusIcon,
        LifeBuoyIcon,
        Trash2Icon,
        TrashIcon,
        StarIcon,
        LinkIcon,
        EyeIcon,
    } from 'vue-feather-icons'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'ContextMenu',
        components: {
            CornerDownRightIcon,
            DownloadCloudIcon,
            FolderPlusIcon,
            LifeBuoyIcon,
            Trash2Icon,
            TrashIcon,
            LinkIcon,
            StarIcon,
            EyeIcon,
        },
        computed: {
            ...mapGetters(['app']),
            isFolder() {
                return this.item && this.item.type === 'folder'
            },
            isFile() {
                return (this.item && this.item.type !== 'folder') && (this.item && this.item.type !== 'image')
            },
            isImage() {
                return this.item && this.item.type === 'image'
            },
            isInFavourites() {
                return this.app.favourites.find(el => el.unique_id == this.item.unique_id)
            },
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
            moveItem() {
                // Open move item popup
                events.$emit('popup:open', {name: 'move', item: this.item})
            },
            shareItem() {
                if (this.item.shared) {
                    // Open share item popup
                    events.$emit('popup:open', {name: 'share-edit', item: this.item})
                } else {
                    // Open share item popup
                    events.$emit('popup:open', {name: 'share-create', item: this.item})
                }
            },
            addToFavourites() {
                // Check if folder is in favourites and then add/remove from favourites
                if (this.app.favourites && !this.app.favourites.find(el => el.unique_id == this.item.unique_id)) {
                    this.$store.dispatch('addToFavourites', this.item)
                } else {
                    this.$store.dispatch('removeFromFavourites', this.item)
                }
            },
            downloadItem() {
                // Download file
                this.$downloadFile(
                    this.item.file_url,
                    this.item.name + '.' + this.item.mimetype
                )
            },
            ItemDetail() {

                // Dispatch load file info detail
                this.$store.commit('GET_FILEINFO_DETAIL', this.item)

                // Show panel if is not open
                this.$store.dispatch('fileInfoToggle', true)
            },
            deleteItem() {
                // Dispatch remove item
                this.$store.dispatch('deleteItem', this.item)
            },
            createFolder() {
                // Create folder
                this.$createFolder(this.$t('popup_create_folder.folder_default_name'))
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
                let nodesSameClass = parent.getElementsByClassName("menu-option")

                let VerticalOffsetArea = nodesSameClass.length * 50
                let HorizontalOffsetArea = 190

                let container = document.getElementById('files-view')

                var offset = container.getClientRects()[0];

                let x = event.clientX - offset.left
                let y = event.clientY - offset.top

                // Set position Y
                if ((container.offsetHeight - y) < VerticalOffsetArea) {
                    this.positionY = y - VerticalOffsetArea
                } else {
                    this.positionY = y
                }

                // Set position X
                if ((container.offsetWidth - x) < HorizontalOffsetArea) {
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

            events.$on('contextMenu:hide', () => (this.closeAndResetContextMenu()))

            events.$on('folder:actions', folder => {

                // Store item
                this.item = folder

                if (this.isVisible)
                    this.isVisible = false
                else
                    this.showFolderActionsMenu()

            })
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .menu-option {
        display: flex;
        align-items: center;

        .icon {
            margin-right: 20px;
            line-height: 0;
        }

        .text-label {
            @include font-size(16);
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

        .menu-option-group {
            padding: 5px 0;
            border-bottom: 1px solid $light_mode_border;

            &:first-child {
                padding-top: 0;
            }

            &:last-child {
                padding-bottom: 0;
                border-bottom: none;
            }
        }

        .menu-option {
            white-space: nowrap;
            font-weight: 700;
            @include font-size(14);
            padding: 15px 20px;
            cursor: pointer;
            width: 100%;
            color: $text;

            &:hover {
                background: $light_background;

                .text-label {
                    color: $theme;
                }

                path, line, polyline, rect, circle, polygon {
                    stroke: $theme;
                }
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .contextmenu {
            background: $dark_mode_foreground;

            .menu-options {

                .menu-option-group {
                    border-color: $dark_mode_border_color;
                }

                .menu-option {
                    color: $dark_mode_text_primary;

                    &:hover {
                        background: rgba($theme, 0.1);
                    }
                }
            }
        }
    }
</style>
