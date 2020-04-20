<template>
    <div
            :style="{ top: positionY + 'px', left: positionX + 'px' }"
            @click="closeAndResetContextMenu"
            class="contextmenu"
            v-show="isVisible"
            ref="contextmenu"
    >
        <!--ContextMenu for trash location-->
        <ul v-if="$isTrashLocation() && $checkPermission('master')" class="menu-options" ref="list">
            <li class="menu-option" @click="removeItem" v-if="item">
                {{ $t('context_menu.delete') }}
            </li>
            <li class="menu-option" @click="$store.dispatch('restoreItem', item)" v-if="item">
                {{ $t('context_menu.restore') }}
            </li>
            <li class="menu-option" @click="$store.dispatch('emptyTrash')">
                {{ $t('context_menu.empty_trash') }}
            </li>
            <li class="menu-option" @click="ItemDetail" v-if="item">
                {{ $t('context_menu.detail') }}
            </li>
            <li class="menu-option" @click="downloadItem" v-if="! isFolder && item">
                {{ $t('context_menu.download') }}
            </li>
        </ul>

        <!--ContextMenu for Base location with MASTER permission-->
        <ul v-if="$isBaseLocation() && $checkPermission('master')" class="menu-options" ref="list">
            <li class="menu-option" @click="addToFavourites" v-if="item && isFolder">
                {{ isInFavourites ? $t('context_menu.remove_from_favourites') : $t('context_menu.add_to_favourites') }}
            </li>
            <li class="menu-option" @click="createFolder">
                {{ $t('context_menu.create_folder') }}
            </li>
            <li class="menu-option" @click="removeItem" v-if="item">
                {{ $t('context_menu.delete') }}
            </li>
            <li class="menu-option" @click="moveItem" v-if="item">
                {{ $t('context_menu.move') }}
            </li>
            <li class="menu-option" @click="shareItem" v-if="item">
                {{ $t('context_menu.share') }}
            </li>
            <li class="menu-option" @click="ItemDetail" v-if="item">
                {{ $t('context_menu.detail') }}
            </li>
            <li class="menu-option" @click="downloadItem" v-if="! isFolder && item">
                {{ $t('context_menu.download') }}
            </li>
        </ul>

        <!--ContextMenu for Base location with EDITOR permission-->
        <ul v-if="$isBaseLocation() && $checkPermission('editor')" class="menu-options" ref="list">
            <li class="menu-option" @click="createFolder">
                {{ $t('context_menu.create_folder') }}
            </li>
            <li class="menu-option" @click="removeItem" v-if="item">
                {{ $t('context_menu.delete') }}
            </li>
            <li class="menu-option" @click="moveItem" v-if="item">
                {{ $t('context_menu.move') }}
            </li>
            <li class="menu-option" @click="ItemDetail" v-if="item">
                {{ $t('context_menu.detail') }}
            </li>
            <li class="menu-option" @click="downloadItem" v-if="! isFolder && item">
                {{ $t('context_menu.download') }}
            </li>
        </ul>

        <!--ContextMenu for Base location with VISITOR permission-->
        <ul v-if="$isBaseLocation() && $checkPermission('visitor')" class="menu-options" ref="list">
            <li class="menu-option" @click="ItemDetail" v-if="item">
                {{ $t('context_menu.detail') }}
            </li>
            <li class="menu-option" @click="downloadItem" v-if="! isFolder && item">
                {{ $t('context_menu.download') }}
            </li>
        </ul>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'ContextMenu',
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
                // Open share item popup
                events.$emit('popup:open', {name: 'share-create', item: this.item})
            },
            addToFavourites() {
                // Check if folder is in favourites and then add/remove from favourites
                if (this.app.favourites && ! this.app.favourites.find(el => el.unique_id == this.item.unique_id)) {
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
                this.$store.dispatch('loadFileInfoDetail', this.item)

                // Show panel if is not open
                this.$store.dispatch('fileInfoToggle', true)
            },
            removeItem() {
                // Dispatch remove item
                this.$store.dispatch('removeItem', this.item)
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
            showContextMenu(event, item) {
                let VerticalOffsetArea = item ? this.$refs.list.children.length * 50 : 50
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
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .contextmenu {
        min-width: 190px;
        position: absolute;
        z-index: 99;
        box-shadow: $shadow;
        background: white;
        border-radius: 8px;
        overflow: hidden;

        &.showed {
            display: block;
        }

        .menu-options {
            list-style: none;
            width: 100%;
            margin: 0;
            padding: 0;

            .menu-option {
                white-space: nowrap;
                font-weight: 700;
                @include font-size(15);
                padding: 15px 30px;
                cursor: pointer;
                width: 100%;
                color: $text;

                &:hover {
                    background: $light_background;
                    color: $theme;
                }
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .contextmenu {
            background: $dark_mode_foreground;

            .menu-options .menu-option {
                color: $dark_mode_text_primary;

                &:hover {
                    background: $dark_mode_background;
                }
            }
        }
    }
</style>
