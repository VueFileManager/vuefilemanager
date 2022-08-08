<template>
    <div
        :class="{
            'opacity-75': isDragging,
            'grid-view': itemViewType === 'grid' && !isVisibleSidebar,
            'grid-view-sidebar': itemViewType === 'grid' && isVisibleSidebar,
        }"
        class="px-4 lg:h-full lg:w-full lg:overflow-y-auto lg:px-0"
        @drop.prevent="dragStop($event)"
        @keydown.delete="deleteItems"
        @dragover="dragEnter"
        @dragleave="dragLeave"
        @dragover.prevent
        @scroll="infiniteScroll"
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

        <!-- Infinite Loader Element -->
        <div
			v-show="showInfiniteLoadSpinner"
            class="relative h-8 md:mt-0 md:mb-4 my-4 col-span-full scale-50"
			ref="infinityLoader"
		>
            <Spinner />
        </div>
    </div>
</template>

<script>
import { getFilesFromDataTransferItems } from 'datatransfer-files-promise'
import Spinner from '../UI/Others/Spinner'
import ItemHandler from './ItemHandler'
import { events } from '../../bus'
import { mapGetters } from 'vuex'
import { debounce } from 'lodash'

export default {
    name: 'FileBrowser',
    components: {
        ItemHandler,
        Spinner
    },
    computed: {
        ...mapGetters(['isVisibleSidebar', 'currentFolder', 'itemViewType', 'clipboard', 'entries', 'config', 'paginate']),
        draggedItems() {
            // Set opacity for dragged items
            if (!this.clipboard.includes(this.draggingId)) {
                return [this.draggingId]
            }

            if (this.clipboard.includes(this.draggingId)) {
                return this.clipboard
            }
        },
        canLoadMoreEntries() {
			return this.paginate?.currentPage !== this.paginate?.lastPage
        },
        showInfiniteLoadSpinner() {
            return this.canLoadMoreEntries && this.entries.length !== 0 && this.paginate.perPage <= this.entries.length
        },
    },
    data() {
        return {
            draggingId: undefined,
            isDragging: false,
            isLoadingNewEntries: false,
        }
    },
    methods: {
		infiniteScroll: debounce(function () {
			if (this.isInfinityLoaderAtBottomPage() && this.canLoadMoreEntries && !this.isLoadingNewEntries) {
				this.isLoadingNewEntries = true

				this.$getDataByLocation(this.paginate.currentPage + 1)
					.then(() => this.isLoadingNewEntries = false)
			}
		}, 150),
        isInfinityLoaderAtBottomPage() {
            let rect = this.$refs.infinityLoader.getBoundingClientRect()

            return (
                rect.bottom > 0 &&
                rect.right > 0 &&
                rect.left < (window.innerWidth || document.documentElement.clientWidth) &&
                rect.top < (window.innerHeight || document.documentElement.clientHeight)
            );
        },
        deleteItems() {
            if ((this.clipboard.length > 0 && this.$checkPermission('master')) || this.$checkPermission('editor')) {
                this.$store.dispatch('deleteItem')
            }
        },
        dragStop() {
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

            // TODO: found issue on firefox
        },
        async dragFinish(data, event) {

			if (event.dataTransfer.files.length) {
				// Check if user dropped folder with files
				let files = await getFilesFromDataTransferItems(event.dataTransfer.items)

				if (files.length !== 0 && event.dataTransfer.items.length === 0) {
					const id = data.data.type !== 'folder' ? this.currentFolder?.data.id : data.data.id

					// Upload folder with files
					this.$uploadDraggedFolderOrFile(files, id)
				}
			} else {
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
		// Track document scrolling to load new entries if needed
		if (window.innerWidth <= 1024) {
			document.addEventListener('scroll', this.infiniteScroll)
		}

        events.$on('drop', () => {
            this.isDragging = false

            setTimeout(() => {
                this.draggingId = undefined
            }, 10)
        })
    },
}
</script>
