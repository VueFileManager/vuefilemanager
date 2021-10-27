<template>
    <div
		:class="{'is-offset': filesInQueueTotal > 0, 'is-dragging': isDragging }"
		class="file-content md:w-full md:overflow-y-auto md:h-full"
		id="file-content-id"
		@drop.stop.prevent="dropUpload($event)"
		@keydown.delete="deleteItems"
		@dragover="dragEnter"
		@dragleave="dragLeave"
		@dragover.prevent
		tabindex="-1"
		@click.self="filesContainerClick"
	>

		<!--Item previews list-->
		<div v-if="isList" class="file-list-wrapper md:px-0 px-4">
			<transition-group
				name="file"
				tag="section"
				class="file-list"
				:class="FilePreviewType"
			>
				<FileItemList
					@dragstart="dragStart(item)"
					@drop.stop.native.prevent="dragFinish(item, $event)"
					@contextmenu.native.prevent="contextMenu($event, item)"
					:item="item"
					v-for="item in entries"
					:key="item.data.id"
					class="file-item"
					:class="draggedItems.includes(item) ? 'dragged' : ''"
				/>
			</transition-group>
		</div>

		<!--Item previews grid-->
		<div v-if="isGrid" class="file-grid-wrapper">
			<transition-group
				name="file"
				tag="section"
				class="file-list"
				:class="FilePreviewType"
			>
				<FileItemGrid
					@dragstart="dragStart(item)"
					@drop.native.prevent="dragFinish(item, $event)"
					@contextmenu.native.prevent="contextMenu($event, item)"
					:item="item"
					v-for="item in entries"
					:key="item.data.id"
					class="file-item"
					:class="draggedItems.includes(item) ? 'dragged' : '' "
				/>
			</transition-group>
		</div>
    </div>
</template>

<script>
	import FileItemList from '/resources/js/components/FilesView/FileItemList'
	import FileItemGrid from '/resources/js/components/FilesView/FileItemGrid'
	import {mapGetters} from 'vuex'
	import {events} from '/resources/js/bus'

	export default {
		name: 'FileBrowser',
		components: {
			FileItemList,
			FileItemGrid,
		},
		computed: {
			...mapGetters([
				'filesInQueueTotal',
				'isVisibleSidebar',
				'FilePreviewType',
				'currentFolder',
				'clipboard',
				'isLoading',
				'entries'
			]),
			isGrid() {
				return this.FilePreviewType === 'grid'
			},
			isList() {
				return this.FilePreviewType === 'list'
			},
			isEmpty() {
				return this.entries.length === 0
			},
			draggedItems() {
				//Set opacity for dragged items
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
			dropUpload(event) {
				// Upload external file
				this.$uploadExternalFiles(event, this.currentFolder.data.id)

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
			},
			dragFinish(data, event) {

				if (event.dataTransfer.items.length === 0) {
					// Prevent to drop on file or image
					if (data.data.type !== 'folder' || this.draggingId === data) return

					//Prevent move selected folder to folder if in beteewn selected folders
					if (this.clipboard.find(item => item === data && this.clipboard.length > 1)) return

					//Move item if is not included in selected items
					if (!this.clipboard.includes(this.draggingId)) {
						this.$store.dispatch('moveItem', {to_item: data, noSelectedItem: this.draggingId})
					}

					//Move selected items to folder
					if (this.clipboard.length > 0 && this.clipboard.includes(this.draggingId)) {
						this.$store.dispatch('moveItem', {to_item: data, noSelectedItem: null})
					}

				} else {

					// Get id of current folder
					const id = data.data.type !== 'folder' ? this.currentFolder.data.id : data.data.id

					// Upload external file
					this.$uploadExternalFiles(event, id)
				}

				this.isDragging = false
			},
			contextMenu(event, item) {
				events.$emit('context-menu:show', event, item)
			},
			filesContainerClick() {

				// Deselect items clicked by outside
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

			events.$on('fileItem:deselect', () => {
				this.$store.commit('CLIPBOARD_CLEAR')
			})

			events.$on('scrollTop', () => {

				// Scroll top
				let container = document.getElementsByClassName(
					'file-content'
				)[0]

				if (container)
					container.scrollTop = 0
			})
		}
	}
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
	@import '/resources/sass/vuefilemanager/_mixins';

	.file-list {
		.dragged {
			/deep/ .is-dragenter {
				border: 2px solid transparent;
			}
		}
	}

	.dragged {
		opacity: 0.5;
	}

	#multi-selected {
		position: fixed;
		pointer-events: none;
		z-index: 100;

	}

	.button-upload {
		display: block;
		text-align: center;
		margin: 20px 0;
	}

	.mobile-search {
		display: none;
		margin-bottom: 10px;
		margin-top: 10px;
	}

	.file-content {

		&.is-dragging {
			@include transform(scale(0.99));
		}
	}

	.files-container {

		.file-list {

			&.grid {
				display: grid;
				grid-template-columns: repeat(auto-fill, 180px);
				justify-content: space-evenly;
			}
		}
	}

	// Transition
	.file-move {
		transition: transform 0.6s;
	}

	.file-enter-active {
		transition: all 300ms ease;
	}

	.file-leave-active {
		transition: all 0ms;
	}

	.file-enter, .file-leave-to /* .list-leave-active below version 2.1.8 */
	{
		opacity: 0;
		transform: translateX(-20px);
	}

	@media only screen and (max-width: 960px) {

		.mobile-search {
			display: block;
		}
	}

	@media only screen and (max-width: 690px) {

		.files-container {

			.file-list {

				&.grid {
					grid-template-columns: repeat(auto-fill, 120px);
				}
			}
		}

		.mobile-search {
			margin-bottom: 0;
		}
	}
</style>
