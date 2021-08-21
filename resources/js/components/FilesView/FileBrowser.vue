<template>
    <div
		:class="{'is-offset': filesInQueueTotal > 0, 'is-dragging': isDragging }"
		class="file-content"
		id="file-content-id"
		@drop.stop.prevent="dropUpload($event)"
		@keydown.delete="deleteItems"
		@dragover="dragEnter"
		@dragleave="dragLeave"
		@dragover.prevent
		tabindex="-1"
	>
        <div
			:class="{'is-visible': isVisibleSidebar, 'mobile-multi-select': isMultiSelect}"
			@click.self="filesContainerClick"
			class="files-container"
			ref="fileContainer"
		>
            <MobileToolbar />

			<!--Mobile Actions-->
            <FileActionsMobile>
				<slot name="file-actions-mobile" />
			</FileActionsMobile>

			<!--Show empty page if no content-->
            <EmptyFilePage>
				<slot name="empty-file-page" />
			</EmptyFilePage>

			<!--Item previews list-->
            <div v-if="isList" class="file-list-wrapper">
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
						:key="item.id"
						class="file-item"
						:class="draggedItems.includes(item) ? 'dragged' : '' "
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
						:key="item.id"
						class="file-item"
						:class="draggedItems.includes(item) ? 'dragged' : '' "
					/>
                </transition-group>
            </div>
        </div>

		<!--File Info Panel-->
        <div :class="{'is-visible': isVisibleSidebar }" class="file-info-container">
            <InfoSidebar />
        </div>
    </div>
</template>

<script>
    import FileActionsMobile from '/resources/js/components/FilesView/FileActionsMobile'
	import MobileToolbar from '/resources/js/components/FilesView/MobileToolbar'
	import EmptyFilePage from '/resources/js/components/FilesView/EmptyFilePage'
	import EmptyMessage from '/resources/js/components/FilesView/EmptyMessage'
	import FileItemList from '/resources/js/components/FilesView/FileItemList'
	import FileItemGrid from '/resources/js/components/FilesView/FileItemGrid'
	import InfoSidebar from '/resources/js/components/FilesView/InfoSidebar'
	import {mapGetters} from 'vuex'
	import {events} from '/resources/js/bus'
	import {debounce} from "lodash";

	export default {
		name: 'FileBrowser',
		components: {
			FileActionsMobile,
			EmptyFilePage,
			MobileToolbar,
			FileItemList,
			FileItemGrid,
			EmptyMessage,
			InfoSidebar,
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
		watch: {
			query(val) {
				this.$searchFiles(val)
			}
		},
		data() {
			return {
				draggingId: undefined,
				isDragging: false,
				isMultiSelect: false,
				query: '',
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
				this.$uploadExternalFiles(event, this.currentFolder.id)

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

				if (event.dataTransfer.items.length == 0) {
					// Prevent to drop on file or image
					if (data.type !== 'folder' || this.draggingId === data) return

					//Prevent move selected folder to folder if in beteewn selected folders
					if (this.clipboard.find(item => item === data && this.clipboard.length > 1)) return

					// Move folder to new parent

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
					const id = data.type !== 'folder' ? this.currentFolder.id : data.id

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
			events.$on('mobileSelecting:start', () => {
				this.isMultiSelect = true
			})

			events.$on('mobileSelecting:stop', () => {
				this.isMultiSelect = false
			})

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
				var container = document.getElementsByClassName(
					'files-container'
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

	.mobile-multi-select {
		bottom: 50px !important;
		top: 0px;
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
		display: flex;

		&.is-dragging {
			@include transform(scale(0.99));
		}
	}

	.files-container {
		overflow-x: hidden;
		overflow-y: auto;
		flex: 0 0 100%;
		@include transition(150ms);
		position: relative;
		scroll-behavior: smooth;

		&.is-visible {
			flex: 0 1 100%;
		}

		.file-list {

			&.grid {
				display: grid;
				grid-template-columns: repeat(auto-fill, 180px);
				justify-content: space-evenly;
			}
		}
	}

	.file-info-container {
		flex: 0 0 300px;
		padding-left: 20px;
		overflow: auto;
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

	@media only screen and (min-width: 960px) {

		.file-content {
			position: absolute;
			top: 72px;
			left: 15px;
			right: 15px;
			bottom: 0;
			@include transition;
			overflow-y: auto;
			overflow-x: hidden;

			&.is-offset {
				margin-top: 50px;
			}
		}
	}

	@media only screen and (max-width: 960px) {

		.file-info-container {
			display: none;
		}

		.mobile-search {
			display: block;
		}
		.file-content {
			position: absolute;
			top: 0px;
			left: 15px;
			right: 15px;
			bottom: 0;
			@include transition;
			overflow-y: auto;
			overflow-x: hidden;

			&.is-offset {
				margin-top: 50px;
			}
		}
	}

	@media only screen and (max-width: 690px) {

		.files-container {
			padding-left: 15px;
			padding-right: 15px;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			position: fixed;
			overflow-y: auto;

			.file-list {

				&.grid {
					grid-template-columns: repeat(auto-fill, 120px);
				}
			}
		}

		.file-content {
			position: absolute;
			top: 0;
			left: 0px;
			right: 0px;
			bottom: 0;
			@include transition;

			&.is-offset {
				margin-top: 50px;
			}
		}

		.mobile-search {
			margin-bottom: 0;
		}

		.file-info-container {
			display: none;
		}
	}
</style>
