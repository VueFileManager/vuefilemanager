<template>
    <div
            ref="contextmenu"
            class="contextmenu"
            :style="{ top: positionY + 'px', left: positionX + 'px' }"
            v-show="isVisible"
    >
        <ul class="menu-options" id="menu-options-list" ref="list" @click="closeAndResetContextMenu">
            <li class="menu-option" @click="addToFavourites" v-if="! $isTrashLocation() && item && item.type === 'folder'">{{ isInFavourites ? 'Remove Favourite' : 'Add To Favourites' }}</li>
            <li class="menu-option" @click="$store.dispatch('restoreItem', item)" v-if="item && $isTrashLocation()">Restore</li>
            <li class="menu-option" @click="createFolder" v-if="! $isTrashLocation()">Create Folder</li>
            <li class="menu-option" @click="removeItem" v-if="! $isTrashLocation()">Delete</li>
            <li class="menu-option" @click="$store.dispatch('emptyTrash')" v-if="$isTrashLocation()">Empty Trash</li>
            <li class="menu-option" @click="ItemDetail" v-if="item">Detail</li>
            <li class="menu-option" @click="downloadItem" v-if="isFile || isImage">Download</li>
        </ul>
    </div>
</template>

<script>
    import {events} from '@/bus'
    import {mapGetters} from 'vuex'

    export default {
        name: 'ContextMenu',
        computed: {
            ...mapGetters(['app']),
            isFile() {
                return this.item && this.item.type === 'file' ? true : false
            },
            isImage() {
                return this.item && this.item.type === 'image' ? true : false
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
            addToFavourites() {
                if (this.app.favourites && ! this.app.favourites.find(el => el.unique_id == this.item.unique_id)) {
                    this.$store.dispatch('addToFavourites', this.item.unique_id)
                } else {
                    this.$store.dispatch('removeFromFavourites', this.item.unique_id)
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
                this.$createFolder('New Folder')
            },
            closeAndResetContextMenu() {
                // Close context menu
                this.isVisible = false

                // Reset item container
                this.item = undefined
            },
            showContextMenu(event, item) {
                let VerticalOffsetArea = item ? this.$refs.list.children.length * 50 : 50
                let HorizontalOffsetArea = 150

                let container = document.getElementById('files-view')

                let x = event.pageX - container.getBoundingClientRect().x
                let y = event.pageY - container.getBoundingClientRect().y

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
        max-width: 190px;
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
                font-weight: 600;
                @include font-size(15);
                padding: 15px 30px;
                cursor: pointer;
                width: 100%;

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
                &:hover {
                    background: $dark_mode_background;
                }
            }
        }
    }
</style>
