<template>
    <div
        :class="{
            'opacity-75': isDragging,
            'grid-view': itemViewType === 'grid' && !isVisibleSidebar,
            'grid-view-sidebar': itemViewType === 'grid' && isVisibleSidebar,
        }"
        class="px-4 lg:h-full lg:w-full lg:overflow-y-auto lg:px-0"
        @drop.stop.prevent="uploadDroppedItems($event)"
        @keydown.delete="deleteItems"
        @dragover="dragEnter"
        @dragleave="dragLeave"
        @dragover.prevent
        tabindex="-1"
        @click.self="deselect"
    >
        <ItemHandler
            @click.native="hideContextMenu"
            @dragstart="dragStart(item)"
            @drop.stop.native.prevent="dragFinish(item, $event)"
            @contextmenu.native.prevent="contextMenu($event, item)"
            :class="draggedItems.includes(item) ? 'opacity-60' : ''"
            v-for="item in entries"
            :key="item.data.id"
            :item="item"
        />
    </div>
</template>

<script>
import ItemHandler from './ItemHandler'
import { events } from '../../bus'
import { mapGetters } from 'vuex'

export default {
    name: 'FileBrowser',
    components: {
        ItemHandler,
    },
    computed: {
        ...mapGetters(['isVisibleSidebar', 'currentFolder', 'itemViewType', 'clipboard', 'entries', 'config']),
        draggedItems() {
            // Set opacity for dragged items
            if (!this.clipboard.includes(this.draggingId)) {
                return [this.draggingId]
            }

            if (this.clipboard.includes(this.draggingId)) {
                return this.clipboard
            }
        },
    },
    data() {
        return {
            draggingId: undefined,
            isDragging: false,
        }
    },
    methods: {
        deleteItems() {
            if ((this.clipboard.length > 0 && this.$checkPermission('master')) || this.$checkPermission('editor')) {
                this.$store.dispatch('deleteItem')
            }
        },
        uploadDroppedItems(event) {
            this.$uploadDraggedFiles(event, this.currentFolder.data.id)

            this.isDragging = false
        },
        dragEnter() {
            this.isDragging = true
        },
        dragLeave() {
            this.isDragging = false
        },
        dragStart(data) {
            let img = document.createElement('img')
            img.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'
            event.dataTransfer.setDragImage(img, 0, 0)

            events.$emit('dragstart', data)

            // Store dragged folder
            this.draggingId = data

            // TODO: founded issue on firefox
        },
        dragFinish(data, event) {
            if (event.dataTransfer.items.length === 0) {
                // Prevent to drop on file or image
                if (data.data.type !== 'folder' || this.draggingId === data) return

                // Prevent move selected folder to folder if in between selected folders
                if (this.clipboard.find((item) => item === data && this.clipboard.length > 1)) return

                // Move item if is not included in selected items
                if (!this.clipboard.includes(this.draggingId)) {
                    this.$store.dispatch('moveItem', {
                        to_item: data,
                        item: this.draggingId,
                    })
                }

                // Move selected items to folder
                if (this.clipboard.length > 0 && this.clipboard.includes(this.draggingId)) {
                    this.$store.dispatch('moveItem', {
                        to_item: data,
                        item: null,
                    })
                }
            } else {
                // Get id from current folder
                const id = data.data.type !== 'folder' ? this.currentFolder?.data.id : data.data.id

                // Upload external file
                this.$uploadDraggedFiles(event, id)
            }

            this.isDragging = false
        },
        contextMenu(event, item) {
            events.$emit('context-menu:show', event, item)
        },
        hideContextMenu() {
            events.$emit('context-menu:hide')
        },
        deselect() {
            // Hide context menu
            events.$emit('context-menu:hide')

            // Clear clipboard
            this.$store.commit('CLIPBOARD_CLEAR')
        },
    },
    created() {
        events.$on('drop', () => {
            this.isDragging = false

            setTimeout(() => {
                this.draggingId = undefined
            }, 10)
        })
    },
}
</script>
