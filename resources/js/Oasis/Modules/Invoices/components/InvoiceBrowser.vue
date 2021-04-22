<template>
    <div
		class="file-content"
		id="file-content-id"
		tabindex="-1"
	>
        <div
			:class="{'is-visible': isVisibleSidebar, 'mobile-multi-select': isMultiSelect}"
			@click.self="filesContainerClick"
			class="files-container"
			ref="fileContainer"
		>
            <MobileToolbar />

            <SearchBar v-model="query" @reset-query="query = ''" class="mobile-search" :placeholder="$t('inputs.placeholder_search_files')" />

			<!--Mobile Actions-->
            <InvoiceActionsMobile />

			<!--Item previews list-->
            <div class="file-list-wrapper">
                <transition-group
					name="file"
					tag="section"
					class="file-list"
				>
                    <InvoiceItem
						@contextmenu.native.prevent="contextMenu($event, item)"
						:item="item"
						v-if="item.type === 'invoice'"
						v-for="item in entries"
						:key="item.id"
						class="file-item"
					/>
                    <ClientItem
						@contextmenu.native.prevent="contextMenu($event, item)"
						:item="item"
						v-if="item.type === 'client'"
						v-for="item in entries"
						:key="item.id"
						class="file-item"
					/>
                </transition-group>
            </div>

			<!--Show empty page if folder is empty-->
            <EmptyFilePage v-if="! isSearching" />

			<!--Show empty page if no search results-->
            <EmptyMessage
				v-if="isSearching && isEmpty"
				:message="$t('messages.nothing_was_found')"
				icon="eye-slash"
			/>
        </div>

		<!--File Info Panel-->
        <div :class="{'is-visible': isVisibleSidebar }" class="file-info-container">
            <InvoiceInfoSidebar />
        </div>
    </div>
</template>

<script>
    import InvoiceActionsMobile from '@/Oasis/Modules/Invoices/components/InvoiceActionsMobile'
	import InvoiceInfoSidebar from '@/Oasis/Modules/Invoices/components/InvoiceInfoSidebar'
	import InvoiceItem from '@/Oasis/Modules/Invoices/components/InvoiceItem'
	import ClientItem from '@/Oasis/Modules/Invoices/components/ClientItem'
	import EmptyFilePage from '@/components/FilesView/EmptyFilePage'
	import MobileToolbar from '@/components/FilesView/MobileToolbar'
	import EmptyMessage from '@/components/FilesView/EmptyMessage'
	import SearchBar from '@/components/FilesView/SearchBar'
	import {mapGetters} from 'vuex'
	import {events} from '@/bus'

	export default {
		name: 'FilesContainer',
		components: {
			InvoiceActionsMobile,
			InvoiceInfoSidebar,
			EmptyFilePage,
			MobileToolbar,
			EmptyMessage,
			InvoiceItem,
			ClientItem,
			SearchBar,
		},
		computed: {
			...mapGetters([
				'filesInQueueTotal',
				'isVisibleSidebar',
				'FilePreviewType',
				'currentFolder',
				'isSearching',
				'clipboard',
				'isLoading',
				'entries',
			]),
			isEmpty() {
				return this.entries.length == 0
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
				this.$searchInvoices(val)
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
			contextMenu(event, item) {
				events.$emit('contextMenu:show', event, item)
			},
			filesContainerClick() {
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

			events.$on('fileItem:deselect', () => {
				this.$store.commit('CLIPBOARD_CLEAR')
			})

			this.$store.commit('STORE_CURRENT_FOLDER', {
				name: 'Invoices',
				id: undefined,
				location: 'regular-invoice',
			})
		}
	}
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
	@import '@assets/vuefilemanager/_mixins';

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
