<template>
    <div
		:class="{
			'user-dropping-file': isDragging,
			'grid-view': itemViewType === 'grid' && ! isVisibleSidebar,
			'grid-view-sidebar': itemViewType === 'grid' && isVisibleSidebar
		}"
		class="md:w-full md:overflow-y-auto md:h-full md:px-0 px-4"
		@drop.stop.prevent="uploadDroppedItems($event)"
		@keydown.delete="deleteItems"
		@dragover="dragEnter"
		@dragleave="dragLeave"
		@dragover.prevent
		tabindex="-1"
		@click.self="deselect"
	>
		<ItemHandler
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
	import ItemHandler from '/resources/js/components/FilesView/ItemHandler'
	import {events} from '/resources/js/bus'
	import {mapGetters} from 'vuex'

	export default {
		name: 'FileBrowser',
		components: {
			ItemHandler,
		},
		computed: {
			...mapGetters([
				'isVisibleSidebar',
				'currentFolder',
				'itemViewType',
				'clipboard',
				'entries'
			]),
			draggedItems() {
				// Set opacity for dragged items
				if (!this.clipboard.includes(this.draggingId)) {
					return [this.draggingId]
				}

				if (this.clipboard.includes(this.draggingId)) {
					return this.clipboard
				}
			}
		},
		data() {
			return {
				draggingId: undefined,
				isDragging: false,
			}
		},
		methods: {
			deleteItems() {
				if (this.clipboard.length > 0 && this.$checkPermission('master') || this.$checkPermission('editor')) {
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
				img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
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
					if (this.clipboard.find(item => item === data && this.clipboard.length > 1)) return

					// Move item if is not included in selected items
					if (!this.clipboard.includes(this.draggingId)) {
						this.$store.dispatch('moveItem', {to_item: data, noSelectedItem: this.draggingId})
					}

					// Move selected items to folder
					if (this.clipboard.length > 0 && this.clipboard.includes(this.draggingId)) {
						this.$store.dispatch('moveItem', {to_item: data, noSelectedItem: null})
					}

				} else {

					// Get id from current folder
					const id = data.data.type !== 'folder' ? this.currentFolder.data.id : data.data.id

					// Upload external file
					this.$uploadDraggedFiles(event, id)
				}

				this.isDragging = false
			},
			contextMenu(event, item) {
				events.$emit('context-menu:show', event, item)
			},
			deselect() {
				this.$store.commit('CLIPBOARD_CLEAR')
			}
		},
		created() {
			events.$on('drop', () => {
				this.isDragging = false

				setTimeout(() => {
					this.draggingId = undefined
				}, 10)
			})
		}
	}
</script>

<style>
	.grid-view {
		@apply grid content-start xl:grid-cols-6 xl:gap-4 lg:grid-cols-4 lg:gap-2 grid-cols-3
	}

	.grid-view-sidebar {
		@apply grid content-start 2xl:grid-cols-5 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-3 xl:gap-4 lg:gap-2
	}
</style>
